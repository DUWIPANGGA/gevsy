import os
import logging
import tempfile
import struct
import asyncio
from typing import Optional

from fastapi import FastAPI, WebSocket, WebSocketDisconnect, UploadFile, File, HTTPException
from fastapi.middleware.cors import CORSMiddleware
import uvicorn
from faster_whisper import WhisperModel
from text_cleaner import clean

# =========================
# LOGGING
# =========================
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s - %(levelname)s - %(message)s"
)

# =========================
# ENV LOADER SEDERHANA
# =========================
if os.path.exists(".env"):
    with open(".env", "r") as f:
        for line in f:
            line = line.strip()
            if line and "=" in line and not line.startswith("#"):
                k, v = line.split("=", 1)
                os.environ[k.strip()] = v.strip()

# =========================
# KONFIGURASI
# =========================
WHISPER_LANGUAGE = os.getenv("WHISPER_LANGUAGE", "id")
WHISPER_MODEL_SIZE = os.getenv("WHISPER_MODEL_SIZE", "base")
DEVICE = os.getenv("WHISPER_DEVICE", "cpu")
COMPUTE_TYPE = os.getenv("WHISPER_COMPUTE_TYPE", "int8")
HOST = os.getenv("HOST", "127.0.0.1")
PORT = int(os.getenv("PORT", "8001"))

FFMPEG_PATH = os.getenv("FFMPEG_PATH", os.path.join(
    os.path.dirname(os.path.abspath(__file__)),
    "..", "ffmpeg", "bin", "ffmpeg.exe"
))

LOCAL_MODEL_DIR = os.path.join(
    os.path.dirname(os.path.abspath(__file__)),
    "models",
    WHISPER_MODEL_SIZE
)

# =========================
# LOAD WHISPER MODEL
# =========================
logging.info("Loading Whisper model...")
model: Optional[WhisperModel] = None

try:
    # Optimalkan thread CPU untuk pemrosesan paralel yang lebih cepat
    cpu_threads = min(4, os.cpu_count() or 2)
    logging.info(f"Loading Whisper model (threads={cpu_threads})...")

    if os.path.exists(LOCAL_MODEL_DIR) and len(os.listdir(LOCAL_MODEL_DIR)) > 0:
        model = WhisperModel(
            LOCAL_MODEL_DIR,
            device=DEVICE,
            compute_type=COMPUTE_TYPE,
            cpu_threads=cpu_threads,
            local_files_only=True
        )
    else:
        from faster_whisper.utils import download_model
        os.makedirs(LOCAL_MODEL_DIR, exist_ok=True)
        download_model(WHISPER_MODEL_SIZE, output_dir=LOCAL_MODEL_DIR)
        model = WhisperModel(
            LOCAL_MODEL_DIR,
            device=DEVICE,
            compute_type=COMPUTE_TYPE,
            cpu_threads=cpu_threads,
            local_files_only=True
        )
    logging.info("Model loaded successfully")
except Exception as e:
    logging.error(f"Model load failed: {e}")
    model = None

# =========================
# FASTAPI APP
# =========================
app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# =========================
# FUNGSI BANTUAN: BUAT FILE WAV DARI RAW PCM
# =========================
def write_wav_file(sample_rate: int, pcm_data: bytes) -> str:
    """
    Menerima raw PCM 16-bit little-endian (mono)
    Menulis file WAV sementara dan mengembalikan path-nya.
    """
    temp = tempfile.NamedTemporaryFile(suffix=".wav", delete=False)
    temp.close()
    output_path = temp.name

    # Parameter WAV
    num_channels = 1
    bits_per_sample = 16
    byte_rate = sample_rate * num_channels * bits_per_sample // 8
    block_align = num_channels * bits_per_sample // 8
    data_size = len(pcm_data)

    with open(output_path, "wb") as f:
        # RIFF header
        f.write(b'RIFF')
        f.write(struct.pack('<I', 36 + data_size))  # file size - 8
        f.write(b'WAVE')
        # fmt subchunk
        f.write(b'fmt ')
        f.write(struct.pack('<I', 16))  # chunk size
        f.write(struct.pack('<H', 1))   # audio format (PCM)
        f.write(struct.pack('<H', num_channels))
        f.write(struct.pack('<I', sample_rate))
        f.write(struct.pack('<I', byte_rate))
        f.write(struct.pack('<H', block_align))
        f.write(struct.pack('<H', bits_per_sample))
        # data subchunk
        f.write(b'data')
        f.write(struct.pack('<I', data_size))
        f.write(pcm_data)

    return output_path

# =========================
# ROOT ENDPOINT
# =========================
@app.get("/")
def root():
    return {
        "status": "online",
        "model": WHISPER_MODEL_SIZE,
        "device": DEVICE,
        "mode": "raw_pcm_16k"
    }

# =========================
# ENDPOINT UPLOAD FILE (untuk debug, tetap support webm)
# =========================
@app.post("/transcribe")
async def transcribe(file: UploadFile = File(...)):
    suffix = os.path.splitext(file.filename)[1] or ".webm"
    with tempfile.NamedTemporaryFile(suffix=suffix, delete=False) as f:
        f.write(await file.read())
        input_path = f.name

    # Jika file bukan PCM, kita konversi dengan ffmpeg (tetap support)
    if suffix not in [".pcm", ".raw"]:
        output_path = input_path + ".wav"
        import subprocess
        ffmpeg_cmd = [
            FFMPEG_PATH, "-y",
            "-i", input_path,
            "-ar", "16000",
            "-ac", "1",
            "-c:a", "pcm_s16le",
            output_path
        ]
        subprocess.run(ffmpeg_cmd, check=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        target = output_path
    else:
        # Raw PCM – baca dan buat WAV
        with open(input_path, "rb") as rf:
            pcm_data = rf.read()
        target = write_wav_file(16000, pcm_data)

    try:
        segments, info = model.transcribe(
            target,
            language=WHISPER_LANGUAGE,
            vad_filter=True,
            beam_size=1,
            temperature=0,
            condition_on_previous_text=False,
            no_speech_threshold=0.6
        )
        text = " ".join([s.text for s in segments]).strip()
        logging.info(f"[HTTP] Raw whisper text: {text!r}")
        text = clean(text)
        logging.info(f"[HTTP] After clean: {text!r}")
        return {"text": text}
    except Exception as e:
        raise HTTPException(500, str(e))
    finally:
        for p in [input_path, target]:
            try:
                if os.path.exists(p):
                    os.remove(p)
            except:
                pass

# =========================
# WEBSOCKET STREAMING (PCM)
# =========================
@app.websocket("/ws/transcribe")
async def ws_transcribe(ws: WebSocket):
    await ws.accept()
    logging.info("WebSocket connected (raw PCM mode)")

    try:
        while True:
            # Menerima binary frame (Int16Array dari frontend)
            data = await ws.receive_bytes()
            if not data or len(data) == 0:
                continue

            # Setiap data frame yang diterima adalah satu kalimat/frasa utuh dari VAD frontend
            logging.info(f"Received audio block: {len(data)} bytes ({len(data)/32000:.2f}s)")
            
            # Buat file WAV dari data pcm yang diterima
            wav_path = write_wav_file(16000, data)

            def do_transcribe():
                segments, info = model.transcribe(
                    wav_path,
                    language=WHISPER_LANGUAGE,
                    vad_filter=True, # Gunakan Silero VAD bawaan untuk memfilter noise/hening
                    beam_size=1,
                    temperature=0,
                    condition_on_previous_text=False,
                    no_speech_threshold=0.6
                )
                return " ".join([s.text for s in segments]).strip()

            try:
                text = await asyncio.get_running_loop().run_in_executor(None, do_transcribe)
                logging.info(f"[WS] Raw whisper text: {text!r}")
                text = clean(text)
                logging.info(f"[WS] After clean: {text!r}")

                if text:
                    logging.info(f"Transcribed: {text}")
                    await ws.send_json({
                        "status": "success",
                        "text": text
                    })
                else:
                    await ws.send_json({
                        "status": "empty",
                        "text": ""
                    })
            except Exception as e:
                logging.error(f"Whisper error: {e}")
                await ws.send_json({
                    "status": "error",
                    "text": f"Whisper failed: {str(e)}"
                })
            finally:
                try:
                    if os.path.exists(wav_path):
                        os.remove(wav_path)
                except:
                    pass

    except WebSocketDisconnect:
        logging.info("WebSocket disconnected")
    except Exception as e:
        logging.error(f"WebSocket error: {e}")

# =========================
# RUN SERVER
# =========================
if __name__ == "__main__":
    uvicorn.run(app, host=HOST, port=PORT)