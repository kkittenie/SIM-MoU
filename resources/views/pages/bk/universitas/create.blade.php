@extends('layouts.app', ['title' => 'Tambah Universitas'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Tambah Universitas" />

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Universitas Baru</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Masukkan data universitas yang akan menjadi tujuan alumni</p>
    </div>

    {{-- Form Card --}}
    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Data Universitas</h3>
        </div>

        <form action="{{ route('universitas.store') }}" method="POST" class="space-y-6 p-6">
            @csrf

            {{-- Nama Universitas & Jenis --}}
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="nama_universitas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nama Universitas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_universitas" name="nama_universitas" value="{{ old('nama_universitas') }}"
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
                        <option value="negeri" {{ old('jenis') === 'negeri' ? 'selected' : '' }}>Negeri</option>
                        <option value="swasta" {{ old('jenis') === 'swasta' ? 'selected' : '' }}>Swasta</option>
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
                    <input type="text" id="kota" name="kota" value="{{ old('kota') }}"
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
                    <input type="text" id="provinsi" name="provinsi" value="{{ old('provinsi') }}"
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white
                        @error('provinsi') border-red-500 dark:border-red-500 @enderror"
                        placeholder="DKI Jakarta">
                    @error('provinsi')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Lokasi Kuliah --}}
            <div>
                <label for="lokasi_kuliah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Lokasi Kuliah <span class="text-red-500">*</span>
                </label>
                <select id="lokasi_kuliah" name="lokasi_kuliah"
                    class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white
                    @error('lokasi_kuliah') border-red-500 dark:border-red-500 @enderror">
                    <option value="">-- Pilih Lokasi --</option>
                    <option value="dalam_negeri" {{ old('lokasi_kuliah') === 'dalam_negeri' ? 'selected' : '' }}>Dalam Negeri</option>
                    <option value="luar_negeri" {{ old('lokasi_kuliah') === 'luar_negeri' ? 'selected' : '' }}>Luar Negeri</option>
                </select>
                @error('lokasi_kuliah')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Akreditasi & Status --}}
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="akreditasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Akreditasi <span class="text-gray-500 text-xs">(Opsional)</span>
                    </label>
                    <input type="text" id="akreditasi" name="akreditasi" value="{{ old('akreditasi') }}"
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
                        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
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
                    <input type="url" id="website" name="website" value="{{ old('website') }}"
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
                    <input type="tel" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}"
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
                    <span>Simpan Universitas</span>
                </button>
                <a href="{{ route('universitas.index') }}"
                    class="flex-1 rounded-lg border border-gray-300 px-6 py-2 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5 transition-colors text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
