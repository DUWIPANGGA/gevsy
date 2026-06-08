<?php

use App\Models\Meeting;
use App\Models\MeetingParticipant;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('meeting.{meetingId}', function ($user, int $meetingId) {
    $isActiveParticipant = MeetingParticipant::query()
        ->where('meeting_id', $meetingId)
        ->where('user_id', $user->id)
        ->whereNull('left_at')
        ->exists();

    if ($isActiveParticipant) {
        return true;
    }

    $meeting = Meeting::query()->find($meetingId);
    if (! $meeting) {
        return false;
    }

    $hasJoinedBefore = $meeting->participants()
        ->where('user_id', $user->id)
        ->exists();

    if (! $hasJoinedBefore && $meeting->activeParticipants()->count() >= 5) {
        return false;
    }

    MeetingParticipant::query()->updateOrCreate(
        [
            'meeting_id' => $meetingId,
            'user_id' => $user->id,
        ],
        [
            'joined_at' => now(),
            'left_at' => null,
        ]
    );

    return true;
});
