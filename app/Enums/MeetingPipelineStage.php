<?php

declare(strict_types=1);

namespace App\Enums;

enum MeetingPipelineStage: string
{
    case ExtractAudio = 'extract_audio';
    case Transcribe = 'transcribe';
    case Summarize = 'summarize';
    case GeneratePdf = 'generate_pdf';
}
