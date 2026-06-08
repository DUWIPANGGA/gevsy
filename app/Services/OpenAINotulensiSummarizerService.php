<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAINotulensiSummarizerService
{
    public const PROMPT_VERSION = 'notulensi-v1';

    public function summarize(string $transcript): array
    {
        $apiKey = config('openai.api_key');
        if (blank($apiKey)) {
            throw new \RuntimeException('OPENAI_API_KEY belum diset.');
        }

        $model = (string) config('openai.summary_model');
        $base = rtrim((string) config('openai.base_url'), '/');
        $timeout = (int) config('openai.request_timeout', 600);
        $retries = (int) config('openai.max_retries', 2);

        $system = <<<'PROMPT'
Anda adalah notulis rapat profesional. Diberikan transkrip rapat dalam bahasa Indonesia.
Keluarkan HANYA JSON valid (tanpa markdown) dengan struktur:
{
  "ringkasan": "string ringkasan eksekutif 5-10 kalimat",
  "topik_dibahas": ["..."],
  "keputusan": ["..."],
  "action_items": [{"task":"...","pic":"...","deadline":"..."}],
  "risiko_catatan": ["..."]
}
PROMPT;

        $response = Http::withToken($apiKey)
            ->timeout($timeout)
            ->retry($retries, 2000, throw: false)
            ->post("{$base}/chat/completions", [
                'model' => $model,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $transcript],
                ],
            ]);

        if (! $response->successful()) {
            Log::error('OpenAI summarization failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            $response->throw();
        }

        /** @var array<string, mixed> $json */
        $json = $response->json();
        $content = (string) data_get($json, 'choices.0.message.content', '');
        $usage = is_array($json['usage'] ?? null) ? $json['usage'] : [];

        $decoded = json_decode($content, true);
        if (! is_array($decoded)) {
            throw new \RuntimeException('Respons ringkasan AI bukan JSON valid.');
        }

        return [
            'structured' => $decoded,
            'ringkasan' => (string) ($decoded['ringkasan'] ?? ''),
            'model' => $model,
            'usage' => $usage,
        ];
    }
}
