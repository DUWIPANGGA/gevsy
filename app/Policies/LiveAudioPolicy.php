<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LiveAudio;
use Illuminate\Auth\Access\HandlesAuthorization;

class LiveAudioPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LiveAudio');
    }

    public function view(AuthUser $authUser, LiveAudio $liveAudio): bool
    {
        return $authUser->can('View:LiveAudio');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LiveAudio');
    }

    public function update(AuthUser $authUser, LiveAudio $liveAudio): bool
    {
        return $authUser->can('Update:LiveAudio');
    }

    public function delete(AuthUser $authUser, LiveAudio $liveAudio): bool
    {
        return $authUser->can('Delete:LiveAudio');
    }

    public function restore(AuthUser $authUser, LiveAudio $liveAudio): bool
    {
        return $authUser->can('Restore:LiveAudio');
    }

    public function forceDelete(AuthUser $authUser, LiveAudio $liveAudio): bool
    {
        return $authUser->can('ForceDelete:LiveAudio');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LiveAudio');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LiveAudio');
    }

    public function replicate(AuthUser $authUser, LiveAudio $liveAudio): bool
    {
        return $authUser->can('Replicate:LiveAudio');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LiveAudio');
    }

}