@extends('admin.layouts.app')
@section('title', 'Edit Role')

@section('content')
<div class="max-w-2xl">
    <div class="page-header">
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali
        </a>
        <h1>Edit Role</h1>
        <p>Perbarui role: <span style="color:#7c3aed" class="font-semibold">{{ ucfirst($role->name) }}</span></p>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="label">Nama Role <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" class="input-field" required>
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label mb-3">Permissions</label>
                    <div class="space-y-3">
                        @foreach($permissions as $group => $groupPerms)
                        <div class="rounded-xl overflow-hidden" style="border:1px solid var(--card-border)">
                            <div class="px-4 py-3 flex items-center justify-between" style="background:var(--surface-bg)">
                                <h4 class="text-sm font-semibold uppercase tracking-wider" style="color:var(--text-muted)">{{ $group }}</h4>
                                <label class="flex items-center gap-2 text-xs cursor-pointer select-none" style="color:var(--text-muted)">
                                    <input type="checkbox" class="group-select rounded border-slate-300 text-violet-600 focus:ring-violet-500" onchange="this.closest('.rounded-xl').querySelectorAll('input[type=checkbox]').forEach(c => c.checked = this.checked)">
                                    Pilih semua
                                </label>
                            </div>
                            <div class="p-4 grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($groupPerms as $perm)
                                <label class="flex items-center gap-2.5 cursor-pointer text-sm px-2 py-1.5 rounded-lg hover:bg-[var(--nav-link-hover)] transition" style="color:var(--text-secondary)">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                        class="rounded border-slate-300 text-violet-600 focus:ring-violet-500"
                                        {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                                    {{ $perm->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('permissions')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3 pt-4 border-t" style="border-color:var(--divider)">
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection