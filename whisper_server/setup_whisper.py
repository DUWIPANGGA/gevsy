import os
import urllib.request
import zipfile
import tempfile
import shutil

PROJECT_DIR = os.path.dirname(os.path.abspath(__file__))
DEST_DIR = os.path.join(PROJECT_DIR, "whisper-cpp")
ZIP_URL = "https://github.com/ggml-org/whisper.cpp/releases/download/v1.8.5/whisper-bin-x64.zip"
MODEL_URL = "https://huggingface.co/ggerganov/whisper.cpp/resolve/main/ggml-base.bin"

def download_file(url, filepath):
    print(f"Downloading {url} to {filepath}...")
    # Add a browser user-agent to prevent forbidden (403) errors from some CDNs
    req = urllib.request.Request(
        url, 
        headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'}
    )
    with urllib.request.urlopen(req) as response, open(filepath, 'wb') as out_file:
        shutil.copyfileobj(response, out_file)
    print("Download completed.")

def main():
    if not os.path.exists(DEST_DIR):
        os.makedirs(DEST_DIR)
        
    models_dir = os.path.join(DEST_DIR, "models")
    if not os.path.exists(models_dir):
        os.makedirs(models_dir)

    # 1. Download and Extract Whisper.cpp binaries
    temp_zip = os.path.join(tempfile.gettempdir(), "whisper-bin.zip")
    try:
        download_file(ZIP_URL, temp_zip)
        print("Extracting whisper.cpp zip...")
        with zipfile.ZipFile(temp_zip, 'r') as zip_ref:
            zip_ref.extractall(DEST_DIR)
        print("Extraction complete.")
    except Exception as e:
        print(f"Error downloading/extracting binaries: {e}")
        return
    finally:
        if os.path.exists(temp_zip):
            try:
                os.remove(temp_zip)
            except Exception:
                pass

    # 2. Download ggml-base.bin model
    model_path = os.path.join(models_dir, "ggml-base.bin")
    if not os.path.exists(model_path):
        try:
            download_file(MODEL_URL, model_path)
        except Exception as e:
            print(f"Error downloading model: {e}")
            return
    else:
        print("Model file already exists.")

    # 3. Detect executable name (newer releases use whisper-cli.exe, older use main.exe)
    binary_name = "whisper-cli.exe"
    if not os.path.exists(os.path.join(DEST_DIR, binary_name)):
        if os.path.exists(os.path.join(DEST_DIR, "main.exe")):
            binary_name = "main.exe"
        else:
            files = os.listdir(DEST_DIR)
            print(f"Files in directory: {files}")
            # Look for any .exe containing whisper or main
            for f in files:
                if f.endswith(".exe") and ("whisper" in f or "main" in f or "cli" in f):
                    binary_name = f
                    break
    
    binary_path = os.path.join(DEST_DIR, binary_name).replace("\\", "/")
    final_model_path = model_path.replace("\\", "/")
    
    print(f"Detected Whisper binary at: {binary_path}")

    # 4. Update whisper_server/.env with new paths
    env_path = os.path.join(PROJECT_DIR, ".env")
    env_content = []
    
    if os.path.exists(env_path):
        with open(env_path, "r") as f:
            for line in f:
                if line.strip().startswith("WHISPER_CPP_PATH="):
                    env_content.append(f"WHISPER_CPP_PATH={binary_path}\n")
                elif line.strip().startswith("WHISPER_MODEL_PATH="):
                    env_content.append(f"WHISPER_MODEL_PATH={final_model_path}\n")
                else:
                    env_content.append(line)
                    
        # Verify if keys were updated
        has_cpp = any(line.strip().startswith("WHISPER_CPP_PATH=") for line in env_content)
        has_model = any(line.strip().startswith("WHISPER_MODEL_PATH=") for line in env_content)
        
        if not has_cpp:
            env_content.append(f"WHISPER_CPP_PATH={binary_path}\n")
        if not has_model:
            env_content.append(f"WHISPER_MODEL_PATH={final_model_path}\n")
            
        with open(env_path, "w") as f:
            f.writelines(env_content)
        print("Updated .env file successfully!")
    else:
        print(".env file not found, creating new one...")
        with open(env_path, "w") as f:
            f.write(f"WHISPER_CPP_PATH={binary_path}\n")
            f.write(f"WHISPER_MODEL_PATH={final_model_path}\n")
            f.write("FFMPEG_PATH=C:/Users/REDMI/AppData/Local/Microsoft/WinGet/Links/ffmpeg.exe\n")
            f.write("WHISPER_LANGUAGE=id\n")
            f.write("HOST=127.0.0.1\n")
            f.write("PORT=8001\n")
        print("Created .env file successfully!")

    print("\n==============================================")
    print("Setup completed successfully!")
    print("Please restart uvicorn by running: python main.py")
    print("==============================================")

if __name__ == "__main__":
    main()
