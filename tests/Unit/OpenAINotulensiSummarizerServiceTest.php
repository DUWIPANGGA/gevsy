<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\OpenAINotulensiSummarizerService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenAINotulensiSummarizerServiceTest extends TestCase
{
    public function test_summarize_returns_structured_payload(): void
    {
        Http::fake([
            'https://api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'ringkasan' => 'Ringkasan uji.',
                                'topik_dibahas' => ['A'],
                                'keputusan' => ['B'],
                                'action_items' => [
                                    ['task' => 'T1', 'pic' => 'P1', 'deadline' => 'D1'],
                                ],
                                'risiko_catatan' => ['R1'],
                            ], JSON_UNESCAPED_UNICODE),
                        ],
                    ],
                ],
                'usage' => [
                    'prompt_tokens' => 3,
                    'completion_tokens' => 4,
                    'total_tokens' => 7,
                ],
            ], 200),
        ]);

        config(['openai.api_key' => 'test-key']);

        $svc = app(OpenAINotulensiSummarizerService::class);
        $out = $svc->summarize('transkrip percobaan');

        $this->assertSame('Ringkasan uji.', $out['ringkasan']);
        $this->assertArrayHasKey('structured', $out);
        $this->assertSame('R1', $out['structured']['risiko_catatan'][0]);
    }
}
