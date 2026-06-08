@extends('admin.layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
<div class="max-w-2xl">
    <div class="page-header">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali
        </a>
        <h1>Edit Pengguna</h1>
        <p>Perbarui informasi akun pengguna</p>
    </div>

    <div class="card overflow-hidden">
        <div class="px-6 py-5 flex items-center gap-4" style="background:linear-gradient(135deg,#7c3aed,#4f46e5)">
            <div class="w-12 h-12 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center text-white text-lg font-bold shrink-0">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <h3 class="text-white font-semibold">{{ $user->name }}</h3>
                <p class="text-violet-200 text-sm">{{ $user->email }}</p>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf @method('PUT')
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="label">Nama Lengkap <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input-field" required>
                            @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Email <span class="text-red-400">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-field" required>
                            @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="rounded-xl p-4" style="background:rgba(245,158,11,0.05);border:1px solid rgba(245,158,11,0.15)">
                        <p class="text-xs font-medium" style="color:#d97706">Kosongkan password jika tidak ingin mengubahnya</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="label">Password Baru</label>
                            <input type="password" name="password" class="input-field" placeholder="Min. 8 karakter">
                            @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="input-field" placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div>
                        <label class="label">Role <span class="text-red-400">*</span></label>
                        <select name="role" class="input-field" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('role')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t" style="border-color:var(--divider)">
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection