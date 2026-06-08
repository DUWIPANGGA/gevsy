@extends('admin.layouts.app')
@section('title', 'Detail Pengguna')

@section('content')
<div class="max-w-2xl">
    <div class="page-header">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali
        </a>
        <h1>Detail Pengguna</h1>
        <p>Informasi lengkap akun pengguna</p>
    </div>

    <div class="card overflow-hidden">
        <div class="px-6 py-8 flex items-center gap-5" style="background:linear-gradient(135deg,#7c3aed,#4f46e5)">
            <div class="w-16 h-16 rounded-full bg-white/20 border-2 border-white/40 flex items-center justify-center text-white text-2xl font-bold shrink-0 shadow-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                <p class="text-violet-200 text-sm mt-0.5">{{ $user->email }}</p>
                <div class="flex gap-2 mt-2">
                    @foreach($user->roles as $role)
                        <span class="badge text-xs" style="background:rgba(255,255,255,0.15);color:#e9d5ff">{{ ucfirst($role->name) }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Role & Akses</p>
                    <div class="flex flex-wrap gap-1.5">
                        @forelse($user->roles as $role)
                            <span class="badge" style="background:rgba(124,58,237,0.1);color:#7c3aed">{{ ucfirst($role->name) }}</span>
                        @empty
                            <span style="color:var(--text-muted)">Tidak ada role</span>
                        @endforelse
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Permissions</p>
                    <div class="flex flex-wrap gap-1.5">
                        @forelse($user->getAllPermissions() as $perm)
                            <span class="badge text-xs" style="background:rgba(99,102,241,0.08);color:#6366f1">{{ $perm->name }}</span>
                        @empty
                            <span style="color:var(--text-muted)">Tidak ada permission spesifik</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <hr class="my-6" style="border-color:var(--divider)">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Tanggal Dibuat</p>
                    <p style="color:var(--text-primary)">{{ $user->created_at->translatedFormat('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Terakhir Diperbarui</p>
                    <p style="color:var(--text-primary)">{{ $user->updated_at->translatedFormat('d M Y H:i') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-6 border-t" style="border-color:var(--divider)">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Pengguna
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection