<?php

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows two authenticated users to join the same meeting and send signaling payloads', function () {
    $host = User::factory()->create();
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $meeting = Meeting::query()->create([
        'nama_rapat' => 'Room Test',
        'deskripsi_rapat' => 'Testing room',
        'tanggal' => now()->toDateString(),
        'waktu' => now()->format('H:i:s'),
        'tipe_rapat' => 'online',
        'link_meeting' => 'https://example.com/room-test',
        'password_rapat' => null,
        'dibuat_oleh' => $host->id,
        'status_rapat' => 'ongoing',
    ]);

    $this->actingAs($userA)
        ->post(route('meeting.join.submit'), [
            'meeting_id' => $meeting->id,
        ])
        ->assertRedirect(route('meeting.room', $meeting->id));

    $this->actingAs($userB)
        ->post(route('meeting.join.submit'), [
            'meeting_id' => $meeting->id,
        ])
        ->assertRedirect(route('meeting.room', $meeting->id));

    $this->actingAs($userA)
        ->get(route('meeting.room', $meeting->id))
        ->assertOk();

    $this->actingAs($userB)
        ->get(route('meeting.room', $meeting->id))
        ->assertOk();

    $this->actingAs($userA)
        ->postJson(route('meeting.signal', $meeting->id), [
            'type' => 'join',
        ])
        ->assertOk()
        ->assertJson(['status' => 'sent']);

    $this->actingAs($userB)
        ->postJson(route('meeting.signal', $meeting->id), [
            'type' => 'candidate',
            'candidate' => [
                'candidate' => 'candidate:1 1 udp 1234 127.0.0.1 9999 typ host',
                'sdpMid' => '0',
                'sdpMLineIndex' => 0,
            ],
        ])
        ->assertOk()
        ->assertJson(['status' => 'sent']);
});

it('rejects the 6th active participant from joining a meeting', function () {
    $host = User::factory()->create();
    $meeting = Meeting::query()->create([
        'nama_rapat' => 'Limited Room',
        'deskripsi_rapat' => 'Max 5 users',
        'tanggal' => now()->toDateString(),
        'waktu' => now()->format('H:i:s'),
        'tipe_rapat' => 'online',
        'link_meeting' => 'https://example.com/limited-room',
        'password_rapat' => null,
        'dibuat_oleh' => $host->id,
        'status_rapat' => 'ongoing',
    ]);

    $participants = User::factory()->count(6)->create();

    foreach ($participants->take(5) as $participant) {
        $this->actingAs($participant)
            ->post(route('meeting.join.submit'), [
                'meeting_id' => $meeting->id,
            ])
            ->assertRedirect(route('meeting.room', $meeting->id));
    }

    $this->actingAs($participants[5])
        ->from(route('meeting.join.form'))
        ->post(route('meeting.join.submit'), [
            'meeting_id' => $meeting->id,
        ])
        ->assertRedirect(route('meeting.join.form'))
        ->assertSessionHasErrors(['meeting_id']);
});
