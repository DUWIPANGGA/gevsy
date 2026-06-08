<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhisperCppService
{
    public function transcribeChunk(string $absolutePath): string
    {
        $whisperUrl = env('WHISPER_URL', 'http://127.0.0.1:8000/transcribe');
        $filename = basename($absolutePath);

        if (!is_file($absolutePath)) {
            throw new \InvalidArgumentException("File audio tidak ditemukan di path: {$absolutePath}");
        }

        $response = Http::timeout(30)
            ->attach('file', fopen($absolutePath, 'r'), $filename)
            ->post($whisperUrl);

        if (!$response->successful()) {
            $json = $response->json();
            $detail = is_array($json) && isset($json['detail']) 
                ? $json['detail'] 
                : 'Gagal melakukan transkripsi menggunakan Whisper.cpp. Pastikan server Python whisper_server berjalan.';
            
            Log::error('Whisper.cpp transcription request failed', [
                'status' => $response->status(),
                'detail' => $detail,
            ]);
            throw new \RuntimeException($detail);
        }

        /** @var array<string, mixed> $json */
        $json = $response->json();

        return trim((string) ($json['text'] ?? ''));
    }
}
