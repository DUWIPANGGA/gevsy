<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Arsip;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArsipPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Arsip');
    }

    public function view(AuthUser $authUser, Arsip $arsip): bool
    {
        return $authUser->can('View:Arsip');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Arsip');
    }

    public function update(AuthUser $authUser, Arsip $arsip): bool
    {
        return $authUser->can('Update:Arsip');
    }

    public function delete(AuthUser $authUser, Arsip $arsip): bool
    {
        return $authUser->can('Delete:Arsip');
    }

    public function restore(AuthUser $authUser, Arsip $arsip): bool
    {
        return $authUser->can('Restore:Arsip');
    }

    public function forceDelete(AuthUser $authUser, Arsip $arsip): bool
    {
        return $authUser->can('ForceDelete:Arsip');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Arsip');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Arsip');
    }

    public function replicate(AuthUser $authUser, Arsip $arsip): bool
    {
        return $authUser->can('Replicate:Arsip');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Arsip');
    }

}