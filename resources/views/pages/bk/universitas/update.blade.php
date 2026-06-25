@extends('layouts.app', ['title' => 'Edit Universitas'])

@section('content')
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('universitas.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Edit {{ $universitas->nama_universitas }}</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Ubah data universitas tujuan alumni</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Data Universitas</h3>
        </div>

        <form action="{{ route('universitas.update', $universitas) }}" method="POST" class="space-y-6 p-6">
            @csrf
            @method('PUT')

            {{-- Nama Universitas & Jenis --}}
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="nama_universitas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nama Universitas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_universitas" name="nama_universitas" value="{{ old('nama_universitas', $universitas->nama_universitas) }}"
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white
                        @error('nama_universitas') border-red-500 dark:border-red-500 @enderror"
                        placeholder="Universitas Indonesia">
                    @error('nama_universitas')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Jenis <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis" name="jenis"
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white
                        @error('jenis') border-red-500 dark:border-red-500 @enderror">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="negeri" {{ old('jenis', $universitas->jenis) === 'negeri' ? 'selected' : '' }}>Negeri</option>
                        <option value="swasta" {{ old('jenis', $universitas->jenis) === 'swasta' ? 'selected' : '' }}>Swasta</option>
                    </select>
                    @error('jenis')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Kota & Provinsi --}}
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="kota" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Kota <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="kota" name="kota" value="{{ old('kota', $universitas->kota) }}"
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white
                        @error('kota') border-red-500 dark:border-red-500 @enderror"
                        placeholder="Jakarta">
                    @error('kota')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="provinsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Provinsi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="provinsi" name="provinsi" value="{{ old('provinsi', $universitas->provinsi) }}"
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white
                        @error('provinsi') border-red-500 dark:border-red-500 @enderror"
                        placeholder="DKI Jakarta">
                    @error('provinsi')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Akreditasi & Status --}}
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="akreditasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Akreditasi <span class="text-gray-500 text-xs">(Opsional)</span>
                    </label>
                    <input type="text" id="akreditasi" name="akreditasi" value="{{ old('akreditasi', $universitas->akreditasi) }}"
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white
                        @error('akreditasi') border-red-500 dark:border-red-500 @enderror"
                        placeholder="A">
                    @error('akreditasi')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status"
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white
                        @error('status') border-red-500 dark:border-red-500 @enderror">
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif" {{ old('status', $universitas->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $universitas->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Website & Nomor Telepon --}}
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Website <span class="text-gray-500 text-xs">(Opsional)</span>
                    </label>
                    <input type="url" id="website" name="website" value="{{ old('website', $universitas->website) }}"
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white
                        @error('website') border-red-500 dark:border-red-500 @enderror"
                        placeholder="https://www.universitas.ac.id">
                    @error('website')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nomor Telepon <span class="text-gray-500 text-xs">(Opsional)</span>
                    </label>
                    <input type="tel" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $universitas->nomor_telepon) }}"
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white
                        @error('nomor_telepon') border-red-500 dark:border-red-500 @enderror"
                        placeholder="(021) 1234-5678">
                    @error('nomor_telepon')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Button Actions --}}
            <div class="flex gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                <button type="submit"
                    class="flex-1 rounded-lg bg-blue-600 px-6 py-2 font-medium text-white hover:bg-blue-700 transition-colors">
                    <span>Simpan Perubahan</span>
                </button>
                <a href="{{ route('universitas.show', $universitas) }}"
                    class="flex-1 rounded-lg border border-gray-300 px-6 py-2 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5 transition-colors text-center">
                    Batal
                </a>
            </div>
        </form>

        {{-- Delete Section --}}
        <div class="border-t border-gray-200 px-6 py-6 dark:border-gray-800">
            <h3 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white">Zona Berbahaya</h3>
            <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Menghapus universitas akan menghapus semua data terkait. Aksi ini tidak dapat dibatalkan.
            </p>
            <form action="{{ route('universitas.destroy', $universitas) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus universitas ini?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                    Hapus Universitas
                </button>
            </form>
        </div>
    </div>
@endsection
