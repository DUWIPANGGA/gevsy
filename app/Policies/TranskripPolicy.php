<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Transkrip;
use Illuminate\Auth\Access\HandlesAuthorization;

class TranskripPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Transkrip');
    }

    public function view(AuthUser $authUser, Transkrip $transkrip): bool
    {
        return $authUser->can('View:Transkrip');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Transkrip');
    }

    public function update(AuthUser $authUser, Transkrip $transkrip): bool
    {
        return $authUser->can('Update:Transkrip');
    }

    public function delete(AuthUser $authUser, Transkrip $transkrip): bool
    {
        return $authUser->can('Delete:Transkrip');
    }

    public function restore(AuthUser $authUser, Transkrip $transkrip): bool
    {
        return $authUser->can('Restore:Transkrip');
    }

    public function forceDelete(AuthUser $authUser, Transkrip $transkrip): bool
    {
        return $authUser->can('ForceDelete:Transkrip');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Transkrip');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Transkrip');
    }

    public function replicate(AuthUser $authUser, Transkrip $transkrip): bool
    {
        return $authUser->can('Replicate:Transkrip');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Transkrip');
    }

}