@extends('admin.layouts.app')
@section('title', 'Roles & Permissions')

@section('content')
<div class="page-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1>Roles & Permissions</h1>
        <p>Kelola role dan hak akses pengguna dalam sistem</p>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="btn-primary shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Role
    </a>
</div>

<div class="grid gap-4">
    @forelse($roles as $role)
    <div class="card transition-all hover:shadow-lg">
        <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-bold text-sm shrink-0 shadow-sm" style="background:{{ $role->name === 'super_admin' ? 'linear-gradient(135deg,#ef4444,#dc2626)' : 'linear-gradient(135deg,#7c3aed,#4f46e5)' }}">
                    {{ strtoupper(substr($role->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="font-semibold" style="color:var(--text-primary)">{{ ucfirst($role->name) }}</h4>
                        @if($role->name === 'super_admin')
                            <span class="badge text-xs" style="background:rgba(239,68,68,0.1);color:#ef4444">Built-in</span>
                        @endif
                    </div>
                    <p class="text-xs mt-0.5" style="color:var(--text-muted)">{{ $role->permissions->count() }} permission{{ $role->permissions->count() !== 1 ? 's' : '' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                @if($role->name !== 'super_admin')
                <a href="{{ route('admin.roles.edit', $role) }}" class="btn-secondary text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Hapus role {{ $role->name }}?')" class="inline">
                    @csrf @method('DELETE')
                    <button class="btn-danger text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
                @endif
            </div>
        </div>
        @if($role->permissions->count())
        <div class="px-5 pb-5 flex flex-wrap gap-1.5 border-t pt-4" style="border-color:var(--divider)">
            @foreach($role->permissions as $perm)
                <span class="badge text-xs" style="background:rgba(99,102,241,0.08);color:#6366f1">{{ $perm->name }}</span>
            @endforeach
        </div>
        @endif
    </div>
    @empty
    <div class="card">
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <p>Belum ada role yang tersedia.</p>
        </div>
    </div>
    @endforelse
</div>
@endsection