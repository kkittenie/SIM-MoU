@extends('layouts.app', ['title' => 'Tambah Tracer Study Kuliah'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Tambah Tracer Study Kuliah" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-2xl">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                Formulir Tambah Data Tracer Study Kuliah
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Isi data di bawah ini untuk mendokumentasikan pelacakan alumni yang melanjutkan pendidikan.
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

            <form action="{{ route('bk.tracer-kuliah.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Alumni Kuliah Selection -->
                <div>
                    <label for="alumni_kuliah_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nama Alumni <span class="text-error-500">*</span>
                    </label>
                    <select id="alumni_kuliah_id" name="alumni_kuliah_id" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">-- Pilih Alumni --</option>
                        @foreach($alumniList as $alumni)
                            <option value="{{ $alumni->id }}" {{ old('alumni_kuliah_id') == $alumni->id ? 'selected' : '' }}>
                                {{ $alumni->nama_alumni }} ({{ $alumni->universitas->nama_universitas ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('alumni_kuliah_id')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kampus Tujuan -->
                <div>
                    <label for="kampus_tujuan" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Kampus / Universitas Tujuan <span class="text-xs text-gray-400 dark:text-gray-500">(Opsional)</span>
                    </label>
                    <input type="text" id="kampus_tujuan" name="kampus_tujuan" value="{{ old('kampus_tujuan') }}"
                        placeholder="e.g. Universitas Indonesia, ITB, dll"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Program Studi -->
                <div>
                    <label for="program_studi" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Program Studi / Jurusan <span class="text-xs text-gray-400 dark:text-gray-500">(Opsional)</span>
                    </label>
                    <input type="text" id="program_studi" name="program_studi" value="{{ old('program_studi') }}"
                        placeholder="e.g. Teknik Informatika, Sistem Informasi, dll"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Status Kuliah -->
                <div>
                    <label for="status_kuliah" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Status Kuliah Saat Ini <span class="text-error-500">*</span>
                    </label>
                    <select id="status_kuliah" name="status_kuliah" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">-- Pilih Status Kuliah --</option>
                        <option value="aktif" {{ old('status_kuliah') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="lulus" {{ old('status_kuliah') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="cuti" {{ old('status_kuliah') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="putus" {{ old('status_kuliah') === 'putus' ? 'selected' : '' }}>Putus Kuliah</option>
                    </select>
                    @error('status_kuliah')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Detail Status -->
                <div>
                    <label for="detail_status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Keterangan Detail <span class="text-xs text-gray-400 dark:text-gray-500">(Opsional)</span>
                    </label>
                    <textarea id="detail_status" name="detail_status" rows="3" placeholder="Keterangan tambahan tentang status kuliah..."
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('detail_status') }}</textarea>
                </div>

                <!-- Testimoni -->
                <div>
                    <label for="testimoni" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Testimoni / Kesan & Pesan <span class="text-xs text-gray-400 dark:text-gray-500">(Opsional)</span>
                    </label>
                    <textarea id="testimoni" name="testimoni" rows="4" placeholder="Kesan selama bersekolah atau saran pengembangan sekolah..."
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('testimoni') }}</textarea>
                </div>

                <!-- Tanggal Update -->
                <div>
                    <label for="tanggal_update" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Tanggal Update <span class="text-xs text-gray-400 dark:text-gray-500">(Opsional)</span>
                    </label>
                    <input type="date" id="tanggal_update" name="tanggal_update" value="{{ old('tanggal_update', date('Y-m-d')) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                        Simpan
                    </button>
                    <a href="{{ route('bk.tracer-kuliah.index') }}"
                        class="border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
