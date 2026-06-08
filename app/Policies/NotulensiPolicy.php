<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Notulensi;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotulensiPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Notulensi');
    }

    public function view(AuthUser $authUser, Notulensi $notulensi): bool
    {
        return $authUser->can('View:Notulensi');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Notulensi');
    }

    public function update(AuthUser $authUser, Notulensi $notulensi): bool
    {
        return $authUser->can('Update:Notulensi');
    }

    public function delete(AuthUser $authUser, Notulensi $notulensi): bool
    {
        return $authUser->can('Delete:Notulensi');
    }

    public function restore(AuthUser $authUser, Notulensi $notulensi): bool
    {
        return $authUser->can('Restore:Notulensi');
    }

    public function forceDelete(AuthUser $authUser, Notulensi $notulensi): bool
    {
        return $authUser->can('ForceDelete:Notulensi');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Notulensi');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Notulensi');
    }

    public function replicate(AuthUser $authUser, Notulensi $notulensi): bool
    {
        return $authUser->can('Replicate:Notulensi');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Notulensi');
    }

}