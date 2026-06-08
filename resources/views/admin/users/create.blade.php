@extends('admin.layouts.app')
@section('title', 'Tambah Pengguna')

@section('content')
<div class="max-w-2xl">
    <div class="page-header">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali
        </a>
        <h1>Tambah Pengguna Baru</h1>
        <p>Buat akun baru untuk memberikan akses ke sistem</p>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="label">Nama Lengkap <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="input-field" required placeholder="Masukkan nama lengkap">
                        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Email <span class="text-red-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input-field" required placeholder="contoh@email.com">
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="label">Password <span class="text-red-400">*</span></label>
                        <input type="password" name="password" class="input-field" required placeholder="Min. 8 karakter">
                        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Konfirmasi Password <span class="text-red-400">*</span></label>
                        <input type="password" name="password_confirmation" class="input-field" required placeholder="Ulangi password">
                    </div>
                </div>

                <div>
                    <label class="label">Role <span class="text-red-400">*</span></label>
                    <select name="role" class="input-field" required>
                        <option value="" disabled {{ !old('role') ? 'selected' : '' }}>Pilih role...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    @error('role')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3 pt-4 border-t" style="border-color:var(--divider)">
                    <button type="submit" class="btn-primary">Simpan Pengguna</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection