<?php

declare(strict_types=1);

return [
    'api_key' => env('OPENAI_API_KEY'),
    'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
    'transcription_model' => env('OPENAI_TRANSCRIPTION_MODEL', 'whisper-1'),
    'summary_model' => env('OPENAI_SUMMARY_MODEL', 'gpt-4o-mini'),
    'request_timeout' => (int) env('OPENAI_REQUEST_TIMEOUT', 600),
    'max_retries' => (int) env('OPENAI_MAX_RETRIES', 2),
];
