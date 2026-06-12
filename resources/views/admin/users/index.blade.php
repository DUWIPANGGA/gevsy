@extends('admin.layouts.app')
@section('title', 'Pengguna')

@section('content')
<div class="page-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1>Pengguna</h1>
        <p>Total {{ $users->total() }} pengguna terdaftar dalam sistem</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-primary shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Pengguna
    </a>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Bergabung</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:linear-gradient(135deg,#7c3aed,#4f46e5)">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <div>
                                <div class="font-medium" style="color:var(--text-primary)">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <div class="flex flex-wrap gap-1">
                            @forelse($user->roles as $role)
                                <span class="badge" style="background:rgba(124,58,237,0.1);color:#7c3aed">{{ ucfirst($role->name) }}</span>
                            @empty
                                <span class="badge" style="background:var(--hover-bg);color:var(--text-muted)">Tidak ada role</span>
                            @endforelse
                        </div>
                    </td>
                    <td style="color:var(--text-muted)">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                    <td>
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#6366f1"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#f59e0b"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            @if(!$user->hasRole('super_admin'))
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="p-2 rounded-lg hover:bg-red-500/10 transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#ef4444"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-4 py-3 border-t" style="border-color:var(--divider)">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection