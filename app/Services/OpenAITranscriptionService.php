<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAITranscriptionService
{
    public function transcribeFile(string $absolutePath, ?string $mimeType = null): array
    {
        $apiKey = config('openai.api_key');
        if (blank($apiKey)) {
            throw new \RuntimeException('OPENAI_API_KEY belum diset.');
        }

        $model = (string) config('openai.transcription_model');
        $base = rtrim((string) config('openai.base_url'), '/');
        $timeout = (int) config('openai.request_timeout', 600);
        $retries = (int) config('openai.max_retries', 2);

        $filename = basename($absolutePath);

        $response = Http::withToken($apiKey)
            ->timeout($timeout)
            ->retry($retries, 2000, throw: false)
            ->asMultipart()
            ->post("{$base}/audio/transcriptions", [
                [
                    'name' => 'file',
                    'contents' => fopen($absolutePath, 'r'),
                    'filename' => $filename,
                ],
                [
                    'name' => 'model',
                    'contents' => $model,
                ],
                [
                    'name' => 'response_format',
                    'contents' => 'verbose_json',
                ],
            ]);

        if (! $response->successful()) {
            Log::error('OpenAI transcription failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            $response->throw();
        }

        /** @var array<string, mixed> $json */
        $json = $response->json();

        $text = (string) ($json['text'] ?? '');
        $usage = is_array($json['usage'] ?? null) ? $json['usage'] : [];

        return [
            'text' => $text,
            'model' => $model,
            'usage' => $usage,
        ];
    }
}
