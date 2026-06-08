<?php

declare(strict_types=1);

namespace App\Enums;

enum MeetingPipelineStatus: string
{
    case Idle = 'idle';
    case AwaitingRecording = 'awaiting_recording';
    case Recording = 'recording';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';
}
