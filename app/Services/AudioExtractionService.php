<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class AudioExtractionService
{
    public function extractToMp3(string $absoluteInputPath, string $absoluteOutputPath): bool
    {
        $ffmpeg = (new ExecutableFinder)->find('ffmpeg');
        if ($ffmpeg === null) {
            Log::info('ffmpeg not found; skipping audio extraction', [
                'input' => $absoluteInputPath,
            ]);

            return false;
        }

        @unlink($absoluteOutputPath);

        $process = new Process([
            $ffmpeg,
            '-y',
            '-i',
            $absoluteInputPath,
            '-vn',
            '-acodec',
            'libmp3lame',
            '-q:a',
            '4',
            $absoluteOutputPath,
        ]);
        $process->setTimeout(3600);
        $process->run();

        if (! $process->isSuccessful()) {
            Log::warning('ffmpeg extraction failed', [
                'error' => $process->getErrorOutput(),
            ]);

            return false;
        }

        return is_file($absoluteOutputPath) && filesize($absoluteOutputPath) > 0;
    }
}
