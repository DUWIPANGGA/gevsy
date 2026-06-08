<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RekamanAudio;
use Illuminate\Auth\Access\HandlesAuthorization;

class RekamanAudioPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RekamanAudio');
    }

    public function view(AuthUser $authUser, RekamanAudio $rekamanAudio): bool
    {
        return $authUser->can('View:RekamanAudio');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RekamanAudio');
    }

    public function update(AuthUser $authUser, RekamanAudio $rekamanAudio): bool
    {
        return $authUser->can('Update:RekamanAudio');
    }

    public function delete(AuthUser $authUser, RekamanAudio $rekamanAudio): bool
    {
        return $authUser->can('Delete:RekamanAudio');
    }

    public function restore(AuthUser $authUser, RekamanAudio $rekamanAudio): bool
    {
        return $authUser->can('Restore:RekamanAudio');
    }

    public function forceDelete(AuthUser $authUser, RekamanAudio $rekamanAudio): bool
    {
        return $authUser->can('ForceDelete:RekamanAudio');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RekamanAudio');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RekamanAudio');
    }

    public function replicate(AuthUser $authUser, RekamanAudio $rekamanAudio): bool
    {
        return $authUser->can('Replicate:RekamanAudio');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RekamanAudio');
    }

}