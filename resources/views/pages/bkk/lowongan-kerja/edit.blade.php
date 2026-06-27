@extends('layouts.app', ['title' => 'Ubah Lowongan Kerja'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Ubah Lowongan Kerja" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-2xl">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                Formulir Ubah Lowongan Kerja
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Perbarui formulir di bawah ini untuk mengubah data lowongan pekerjaan.
            </p>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-gray-800/25 dark:text-red-400" role="alert">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('bkk.lowongan-kerja.update', $lowongan->id) }}" method="POST" class="space-y-6" x-data="{ sumber: '{{ $lowongan->perusahaan_mitra_id ? 'mitra' : 'manual' }}' }">
                @csrf
                @method('PUT')

                <!-- Judul Lowongan -->
                <div>
                    <label for="judul" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Judul Lowongan <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $lowongan->judul) }}" placeholder="e.g. Lowongan Web Developer Senior" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Posisi -->
                <div>
                    <label for="posisi" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Posisi Kerja <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="posisi" name="posisi" value="{{ old('posisi', $lowongan->posisi) }}" placeholder="e.g. Backend Engineer" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Pilihan Perusahaan Sumber -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Pemberi Kerja / Perusahaan <span class="text-error-500">*</span>
                    </label>
                    <div class="flex items-center gap-6 mt-1.5">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="sumber_perusahaan" value="mitra" x-model="sumber" class="h-4 w-4 text-brand-600 border-gray-300 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800" />
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-400">Pilih dari Perusahaan Mitra</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="sumber_perusahaan" value="manual" x-model="sumber" class="h-4 w-4 text-brand-600 border-gray-300 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800" />
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-400">Ketik Manual (Non-Mitra)</span>
                        </label>
                    </div>
                </div>

                <!-- Bagian 1: Dropdown Mitra Perusahaan -->
                <div x-show="sumber === 'mitra'" x-transition>
                    <label for="perusahaan_mitra_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Perusahaan Mitra <span class="text-error-500">*</span>
                    </label>
                    <select id="perusahaan_mitra_id" name="perusahaan_mitra_id" :required="sumber === 'mitra'"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">-- Pilih Mitra Perusahaan --</option>
                        @foreach($mitras as $mitra)
                            <option value="{{ $mitra->id }}" {{ old('perusahaan_mitra_id', $lowongan->perusahaan_mitra_id) == $mitra->id ? 'selected' : '' }}>{{ $mitra->nama_perusahaan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bagian 2: Manual Input Perusahaan -->
                <div x-show="sumber === 'manual'" x-transition>
                    <label for="perusahaan_nama" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nama Perusahaan Non-Mitra <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="perusahaan_nama" name="perusahaan_nama" value="{{ old('perusahaan_nama', $lowongan->perusahaan_nama) }}" :required="sumber === 'manual'" placeholder="e.g. PT Ruangguru Indonesia"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Persyaratan -->
                <div>
                    <label for="persyaratan" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Persyaratan Lowongan <span class="text-error-500">*</span>
                    </label>
                    <textarea id="persyaratan" name="persyaratan" rows="4" placeholder="Masukkan persyaratan lowongan" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('persyaratan', $lowongan->persyaratan) }}</textarea>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Deskripsi Pekerjaan (Opsional)
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi lowongan"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('deskripsi', $lowongan->deskripsi) }}</textarea>
                </div>

                <!-- Grid untuk Gaji dan Tanggal Tutup -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Gaji -->
                    <div>
                        <label for="gaji" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Rentang Gaji (Opsional)
                        </label>
                        <input type="text" id="gaji" name="gaji" value="{{ old('gaji', $lowongan->gaji) }}" placeholder="e.g. Rp 4.500.000 - Rp 6.000.000"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <!-- Tanggal Tutup -->
                    <div>
                        <label for="tanggal_tutup" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Batas Akhir Pendaftaran (Opsional)
                        </label>
                        <input type="date" id="tanggal_tutup" name="tanggal_tutup" value="{{ old('tanggal_tutup', $lowongan->tanggal_tutup ? $lowongan->tanggal_tutup->format('Y-m-d') : '') }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Status Publikasi <span class="text-error-500">*</span>
                    </label>
                    <select id="status" name="status" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="Aktif" {{ old('status', $lowongan->status) === 'Aktif' ? 'selected' : '' }}>Aktif (Tampilkan)</option>
                        <option value="Tutup" {{ old('status', $lowongan->status) === 'Tutup' ? 'selected' : '' }}>Tutup (Sembunyikan)</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('bkk.lowongan-kerja.index') }}"
                        class="border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
