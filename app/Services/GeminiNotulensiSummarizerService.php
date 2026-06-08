<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeminiNotulensiSummarizerService
{
    public const PROMPT_VERSION = 'gemini-notulensi-v1';

    public function summarize(string $transcript): array
    {
        $cookie = Str::random(16);
        $url = 'https://api.siputzx.my.id/api/ai/gemini';
        $timeout = 600;

        $promptSystem = <<<'PROMPT'
Kamu adalah sekretaris dan pembuat notulensi rapat profesional. Tugasmu adalah menganalisis transkrip percakapan rapat yang diberikan dalam bahasa Indonesia dan menyusun notulensi yang sangat rapi.
Keluarkan hasil analisis dalam format JSON murni tanpa membungkusnya dengan tag markdown seperti ```json atau tanda kutip tambahan lainnya. Respons kamu harus berupa string JSON valid yang dapat langsung diparse dengan json_decode di PHP.

Struktur JSON yang WAJIB kamu ikuti adalah:
{
  "ringkasan": "Ringkasan eksekutif jalannya rapat secara ringkas namun padat dan jelas (5-10 kalimat).",
  "topik_dibahas": [
    "Topik ke-1 yang dibahas...",
    "Topik ke-2..."
  ],
  "keputusan": [
    "Keputusan rapat ke-1...",
    "Keputusan rapat ke-2..."
  ],
  "action_items": [
    {
      "task": "Detail tugas yang harus dikerjakan",
      "pic": "Nama orang atau tim yang bertanggung jawab (isi '-' jika tidak disebutkan)",
      "deadline": "Batas waktu pengerjaan tugas (isi '-' jika tidak disebutkan)"
    }
  ],
  "risiko_catatan": [
    "Risiko, kendala, atau catatan penting tambahan ke-1...",
    "Risiko, kendala, atau catatan penting tambahan ke-2..."
  ]
}
PROMPT;

        Log::info('Gemini summarization request initiated', [
            'url' => $url,
            'cookie' => $cookie,
            'transcript_length' => strlen($transcript),
        ]);

        $response = Http::timeout($timeout)
            ->get($url, [
                'text' => $transcript,
                'cookie' => $cookie,
                'promptSystem' => $promptSystem,
            ]);

        if (! $response->successful()) {
            Log::error('Gemini summarization failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            $response->throw();
        }

        $json = $response->json();
        $responseText = (string) data_get($json, 'data.response', '');

        Log::info('Gemini summarization response text received', [
            'response_text' => $responseText,
        ]);

        // Clean any markdown wrapper (e.g. ```json ... ```)
        $responseText = trim($responseText);
        if (preg_match('/^```(?:json)?\s*(.*?)\s*```$/s', $responseText, $matches)) {
            $responseText = trim($matches[1]);
        }

        $decoded = json_decode($responseText, true);
        if (! is_array($decoded)) {
            Log::error('Gemini summarization response is not valid JSON', [
                'raw_response' => $responseText,
            ]);
            throw new \RuntimeException('Respons ringkasan AI bukan JSON valid: '.substr($responseText, 0, 500));
        }

        return [
            'structured' => $decoded,
            'ringkasan' => (string) ($decoded['ringkasan'] ?? ''),
            'model' => 'Gemini (public API)',
            'usage' => [],
        ];
    }
}
