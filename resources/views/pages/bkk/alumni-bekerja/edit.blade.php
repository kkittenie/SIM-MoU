@extends('layouts.app', ['title' => 'Ubah Alumni Bekerja'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Ubah Alumni Bekerja" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-2xl">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                Formulir Ubah Alumni Bekerja
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Perbarui data keterserapan kerja alumni di bawah ini.
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

            <form action="{{ route('bkk.alumni-bekerja.update', $alumni->id) }}" method="POST" class="space-y-6" 
                x-data="{ sumber: '{{ $alumni->perusahaan_mitra_id ? 'mitra' : 'manual' }}' }">
                @csrf
                @method('PUT')

                <!-- Nama Alumni -->
                <div>
                    <label for="nama_alumni" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nama Alumni <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="nama_alumni" name="nama_alumni" value="{{ old('nama_alumni', $alumni->nama_alumni) }}" placeholder="Masukkan nama lengkap alumni" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Tahun Lulus -->
                <div>
                    <label for="tahun_lulus" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Tahun Lulus <span class="text-error-500">*</span>
                    </label>
                    <input type="number" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus', $alumni->tahun_lulus) }}" placeholder="e.g. 2024" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Pilihan Perusahaan Sumber -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Sumber Informasi Perusahaan <span class="text-error-500">*</span>
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
                            <option value="{{ $mitra->id }}" {{ old('perusahaan_mitra_id', $alumni->perusahaan_mitra_id) == $mitra->id ? 'selected' : '' }}>{{ $mitra->nama_perusahaan }} ({{ $mitra->bidang_industri }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bagian 2: Manual Input Perusahaan & Bidang Industri -->
                <div x-show="sumber === 'manual'" x-transition class="space-y-6">
                    <div>
                        <label for="perusahaan_nama" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Perusahaan Non-Mitra <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="perusahaan_nama" name="perusahaan_nama" value="{{ old('perusahaan_nama', $alumni->perusahaan_nama) }}" :required="sumber === 'manual'" placeholder="e.g. PT Ruangguru Indonesia"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <div>
                        <label for="bidang_industri" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Bidang Industri <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="bidang_industri" name="bidang_industri" value="{{ old('bidang_industri', $alumni->bidang_industri) }}" :required="sumber === 'manual'" placeholder="e.g. Teknologi / IT, Manufaktur, Jasa"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>
                </div>

                <!-- Jabatan -->
                <div>
                    <label for="jabatan" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Jabatan / Posisi Kerja <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan', $alumni->jabatan) }}" placeholder="e.g. Junior Web Developer" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Grid Tanggal Masuk dan Gaji -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tanggal Masuk -->
                    <div>
                        <label for="tanggal_masuk" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Tanggal Masuk Kerja <span class="text-error-500">*</span>
                        </label>
                        <input type="date" id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', $alumni->tanggal_masuk->format('Y-m-d')) }}" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    </div>

                    <!-- Gaji -->
                    <div>
                        <label for="gaji" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Gaji Bulanan (Nominal Rupiah - Opsional)
                        </label>
                        <input type="number" id="gaji" name="gaji" value="{{ old('gaji', $alumni->gaji) }}" placeholder="e.g. 5000000"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>
                </div>

                <!-- Grid Status Pekerjaan dan Lokasi Kerja -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Status Pekerjaan -->
                    <div>
                        <label for="status_pekerjaan" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Status Pekerjaan <span class="text-error-500">*</span>
                        </label>
                        <select id="status_pekerjaan" name="status_pekerjaan" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="Tetap" {{ old('status_pekerjaan', $alumni->status_pekerjaan) === 'Tetap' ? 'selected' : '' }}>Tetap</option>
                            <option value="Kontrak" {{ old('status_pekerjaan', $alumni->status_pekerjaan) === 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="Magang" {{ old('status_pekerjaan', $alumni->status_pekerjaan) === 'Magang' ? 'selected' : '' }}>Magang / Apprentice</option>
                            <option value="Freelance" {{ old('status_pekerjaan', $alumni->status_pekerjaan) === 'Freelance' ? 'selected' : '' }}>Freelance</option>
                        </select>
                    </div>

                    <!-- Lokasi Kerja -->
                    <div>
                        <label for="lokasi_kerja" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Lokasi Kerja <span class="text-error-500">*</span>
                        </label>
                        <select id="lokasi_kerja" name="lokasi_kerja" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="Dalam Negeri" {{ old('lokasi_kerja', $alumni->lokasi_kerja) === 'Dalam Negeri' ? 'selected' : '' }}>Dalam Negeri</option>
                            <option value="Luar Negeri" {{ old('lokasi_kerja', $alumni->lokasi_kerja) === 'Luar Negeri' ? 'selected' : '' }}>Luar Negeri</option>
                        </select>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                        Perbarui
                    </button>
                    <a href="{{ route('bkk.alumni-bekerja.index') }}"
                        class="border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
