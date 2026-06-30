@extends('layouts.app', ['title' => 'Tambah Kerja Sama'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Tambah Kerja Sama" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-3xl">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                Formulir Tambah Kerja Sama Baru
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Isi data di bawah ini dengan lengkap untuk mendaftarkan kerja sama & MoU baru.
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

            <form action="{{ route('kerja-sama.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Nama Mitra -->
                    <div class="sm:col-span-2">
                        <label for="nama_mitra" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Mitra <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="nama_mitra" name="nama_mitra" value="{{ old('nama_mitra') }}" placeholder="Contoh: PT. Maju Bersama" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
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
                                <option value="{{ $cat->id }}" {{ old('kategori_mitra_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
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
                                <option value="{{ $pk->id }}" {{ old('program_keahlian_id') == $pk->id ? 'selected' : '' }}>{{ $pk->nama }}</option>
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
                        <input type="text" id="pic" name="pic" value="{{ old('pic') }}" placeholder="Nama penghubung mitra" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Email Kemitraan <span class="text-error-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="emailmitra@domian.com" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="nomor_telepon" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nomor Telepon/HP <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" placeholder="Masukkan nomor telepon" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <!-- Nomor MoU -->
                    <div class="sm:col-span-2">
                        <label for="nomor_mou" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nomor MoU <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="nomor_mou" name="nomor_mou" value="{{ old('nomor_mou') }}" placeholder="Contoh: 123/MOU/VI/2026" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="tanggal_mulai" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Tanggal Mulai <span class="text-error-500">*</span>
                        </label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    </div>

                    <!-- Tanggal Berakhir -->
                    <div>
                        <label for="tanggal_berakhir" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Tanggal Berakhir <span class="text-error-500">*</span>
                        </label>
                        <input type="date" id="tanggal_berakhir" name="tanggal_berakhir" value="{{ old('tanggal_berakhir') }}" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    </div>

                    <!-- Alamat -->
                    <div class="sm:col-span-2">
                        <label for="alamat" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Alamat Mitra <span class="text-error-500">*</span>
                        </label>
                        <textarea id="alamat" name="alamat" rows="3" placeholder="Alamat lengkap mitra" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('alamat') }}</textarea>
                    </div>

                    <!-- Deskripsi Kemitraan -->
                    <div class="sm:col-span-2">
                        <label for="deskripsi" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Deskripsi Kemitraan
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Rincian cakupan kerja sama (opsional)"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('deskripsi') }}</textarea>
                    </div>

                    <!-- Dokumen PDF -->
                    <div class="sm:col-span-2">
                        <label for="dokumen_pdf" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Dokumen Pendukung (PDF)
                        </label>
                        <input type="file" id="dokumen_pdf" name="dokumen_pdf" accept="application/pdf"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-850 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        <p class="mt-1 text-xs text-gray-400 font-normal">Hanya diperbolehkan format PDF, ukuran maksimal 10MB.</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-6 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                        Simpan
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
