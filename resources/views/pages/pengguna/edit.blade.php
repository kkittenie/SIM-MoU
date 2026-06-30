@extends('layouts.app', ['title' => 'Ubah Pengguna'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Ubah Pengguna" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-2xl">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                Ubah Data Pengguna: {{ $user->name }}
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Perbarui data pengguna. Kosongkan kolom password jika tidak ingin mengubahnya.
            </p>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pengguna.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nama Lengkap <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Alamat Email <span class="text-error-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="contoh@gmail.com" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Password <span class="text-xs text-gray-400 font-normal">(Kosongkan jika tidak ingin mengubah password)</span>
                    </label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password baru jika ingin diubah"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Peran (Role) -->
                <div>
                    <label for="role" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Peran (Role) <span class="text-error-500">*</span>
                    </label>
                    <select id="role" name="role" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="bk" {{ old('role', $user->role) === 'bk' ? 'selected' : '' }}>BK (Bimbingan Konseling)</option>
                        <option value="bkk" {{ old('role', $user->role) === 'bkk' ? 'selected' : '' }}>BKK (Bursa Kerja Khusus)</option>
                        <option value="admin_jurusan" {{ old('role', $user->role) === 'admin_jurusan' ? 'selected' : '' }}>Admin Jurusan</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Status <span class="text-error-500">*</span>
                    </label>
                    @if ($user->id === auth()->id())
                        <select id="status" name="status" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 bg-gray-100 cursor-not-allowed" disabled>
                            <option value="aktif" selected>Aktif</option>
                        </select>
                        <input type="hidden" name="status" value="aktif">
                        <p class="mt-1 text-xs text-gray-400 font-normal">Anda tidak dapat menonaktifkan akun Anda sendiri.</p>
                    @else
                        <select id="status" name="status" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="aktif" {{ old('status', $user->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $user->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('pengguna.index') }}"
                        class="border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
