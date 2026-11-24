@extends('layouts.app', ['title' => 'Ubah Kerja Sama'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Ubah Kerja Sama" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-3xl">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                Ubah Kerja Sama dengan {{ $kerjaSama->nama_mitra }}
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Perbarui data kerja sama & MoU di bawah ini.
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

            <form action="{{ route('kerja-sama.update', $kerjaSama->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Nama Mitra -->
                    <div class="sm:col-span-2" x-data="{
                        nama: @js(old('nama_mitra', $kerjaSama->nama_mitra) ?? ''),
                        existing: @js($existingMitras ?? []),
                        get isDuplicate() {
                            if (!this.nama || !this.nama.trim()) return false;
                            const clean = this.nama.toLowerCase().trim().replace(/\s+/g, ' ');
                            return this.existing.some(m => m.toLowerCase().trim().replace(/\s+/g, ' ') === clean);
                        }
                    }">
                        <label for="nama_mitra" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Mitra <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="nama_mitra" name="nama_mitra" value="{{ old('nama_mitra', $kerjaSama->nama_mitra) }}" 
                            x-model="nama"
                            placeholder="Contoh: PT. Maju Bersama" required
                            :class="{'!border-red-500 !ring-red-500/20': isDuplicate}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nama_mitra') border-red-500 @enderror" />
                        
                        <div x-show="isDuplicate" x-cloak class="mt-1.5 flex items-center gap-1.5 text-xs text-red-600 dark:text-red-400">
                            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span>Perhatian: Nama mitra ini sudah digunakan oleh kerja sama lain.</span>
                        </div>

                        @error('nama_mitra')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Mitra -->
                    <div>
                        <label for="kategori_mitra_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Jenis Mitra <span class="text-error-500">*</span>
                        </label>
                        <select id="kategori_mitra_id" name="kategori_mitra_id" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('kategori_mitra_id', $kerjaSama->kategori_mitra_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_mitra_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Program Keahlian -->
                    <div>
                        <label for="program_keahlian_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Program Keahlian (Opsional)
                        </label>
                        <select id="program_keahlian_id" name="program_keahlian_id"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">-- Berlaku untuk Semua Jurusan --</option>
                            @foreach ($programKeahlians as $pk)
                                <option value="{{ $pk->id }}" {{ old('program_keahlian_id', $kerjaSama->program_keahlian_id) == $pk->id ? 'selected' : '' }}>{{ $pk->nama }}</option>
                            @endforeach
                        </select>
                        @error('program_keahlian_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- PIC -->
                    <div>
                        <label for="pic" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Person In Charge (PIC) <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="pic" name="pic" value="{{ old('pic', $kerjaSama->pic) }}" placeholder="Nama PIC mitra" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Email Kemitraan <span class="text-error-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $kerjaSama->email) }}" placeholder="emailmitra@domian.com" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="nomor_telepon" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nomor Telepon/HP <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $kerjaSama->nomor_telepon) }}" placeholder="Masukkan nomor telepon" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <!-- Nomor MoU -->
                    <div class="sm:col-span-2">
                        <label for="nomor_mou" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nomor MoU <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="nomor_mou" name="nomor_mou" value="{{ old('nomor_mou', $kerjaSama->nomor_mou) }}" placeholder="Contoh: 123/MOU/VI/2026" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nomor_mou') border-red-500 @enderror" />
                        @error('nomor_mou')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="tanggal_mulai" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Tanggal Mulai <span class="text-error-500">*</span>
                        </label>
                        <x-form.date-picker id="tanggal_mulai" name="tanggal_mulai" defaultDate="{{ old('tanggal_mulai', $kerjaSama->tanggal_mulai ? $kerjaSama->tanggal_mulai->format('Y-m-d') : '') }}" />
                    </div>

                    <!-- Tanggal Berakhir -->
                    <div>
                        <label for="tanggal_berakhir" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Tanggal Berakhir <span class="text-error-500">*</span>
                        </label>
                        <x-form.date-picker id="tanggal_berakhir" name="tanggal_berakhir" defaultDate="{{ old('tanggal_berakhir', $kerjaSama->tanggal_berakhir ? $kerjaSama->tanggal_berakhir->format('Y-m-d') : '') }}" />
                    </div>

                    <!-- Alamat -->
                    <div class="sm:col-span-2">
                        <label for="alamat" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Alamat Mitra <span class="text-error-500">*</span>
                        </label>
                        <textarea id="alamat" name="alamat" rows="3" placeholder="Alamat lengkap mitra" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('alamat', $kerjaSama->alamat) }}</textarea>
                    </div>

                    <!-- Deskripsi Kemitraan -->
                    <div class="sm:col-span-2">
                        <label for="deskripsi" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Deskripsi Kemitraan
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Rincian cakupan kerja sama (opsional)"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('deskripsi', $kerjaSama->deskripsi) }}</textarea>
                    </div>

                    <!-- Dokumen PDF -->
                    <div class="sm:col-span-2">
                        <label for="dokumen_pdf" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Dokumen Pendukung (PDF)
                        </label>
                        @if ($kerjaSama->dokumen_pdf)
                            <div class="mb-3 flex items-center gap-2 p-2.5 rounded-lg border border-gray-200 dark:border-gray-800 text-sm bg-gray-50 dark:bg-white/[0.02]">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-xs text-gray-600 dark:text-gray-300">File saat ini: MoU_{{ str_replace(' ', '_', $kerjaSama->nama_mitra) }}.pdf</span>
                                <a href="{{ route('kerja-sama.download', $kerjaSama->id) }}" class="text-xs text-brand-600 hover:underline font-semibold ml-auto">Unduh</a>
                            </div>
                        @endif
                        <input type="file" id="dokumen_pdf" name="dokumen_pdf" accept="application/pdf"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-850 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        <p class="mt-1 text-xs text-gray-400 font-normal">Pilih file PDF baru jika ingin mengganti file saat ini. Ukuran maksimal 10MB.</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-6 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('kerja-sama.index') }}"
                        class="border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
