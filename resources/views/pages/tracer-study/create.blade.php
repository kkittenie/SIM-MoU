@extends('layouts.app', ['title' => 'Tambah Tracer Study'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Tambah Tracer Study" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-2xl">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                Formulir Tambah Data Tracer Study
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Isi data di bawah ini untuk mendokumentasikan pelacakan alumni (Tracer Study).
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

            <form action="{{ route('tracer-study.store') }}" method="POST" class="space-y-6" 
                x-data="{ 
                    status: '{{ old('status_alumni', '') }}',
                    get detailLabel() {
                        if (this.status === 'Bekerja') return 'Nama Perusahaan';
                        if (this.status === 'Kuliah') return 'Nama Kampus / Universitas';
                        if (this.status === 'Wirausaha') return 'Nama Usaha / Bidang Bisnis';
                        return 'Keterangan Tambahan';
                    },
                    get detailPlaceholder() {
                        if (this.status === 'Bekerja') return 'e.g. PT Shopee Internasional Indonesia';
                        if (this.status === 'Kuliah') return 'e.g. Universitas Indonesia / ITB';
                        if (this.status === 'Wirausaha') return 'e.g. Toko Kelontong Berkah / Kuliner';
                        return 'e.g. Sedang mempersiapkan berkas lamaran';
                    }
                }">
                @csrf

                <!-- Nama Alumni -->
                <div>
                    <label for="nama_alumni" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nama Lengkap Alumni <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="nama_alumni" name="nama_alumni" value="{{ old('nama_alumni') }}" placeholder="Masukkan nama lengkap alumni" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Tahun Lulus -->
                <div>
                    <label for="tahun_lulus" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Tahun Lulus <span class="text-error-500">*</span>
                    </label>
                    <input type="number" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus', date('Y')) }}" placeholder="e.g. 2024" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Status Alumni -->
                <div>
                    <label for="status_alumni" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Status Alumni Saat Ini <span class="text-error-500">*</span>
                    </label>
                    <select id="status_alumni" name="status_alumni" required x-model="status"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">-- Pilih Status Alumni --</option>
                        <option value="Bekerja">Bekerja</option>
                        <option value="Kuliah">Kuliah / Studi Lanjut</option>
                        <option value="Wirausaha">Wirausaha / Mandiri</option>
                        <option value="Mencari Kerja">Mencari Kerja / Belum Bekerja</option>
                    </select>
                </div>

                <!-- Detail Status (Conditional Label & Hint) -->
                <div>
                    <label for="detail_status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        <span x-text="detailLabel">Keterangan Detail</span> <span class="text-xs text-gray-400 dark:text-gray-500">(Opsional)</span>
                    </label>
                    <input type="text" id="detail_status" name="detail_status" value="{{ old('detail_status') }}" :placeholder="detailPlaceholder"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Testimoni / Pesan -->
                <div>
                    <label for="testimoni" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Testimoni / Kesan & Pesan <span class="text-xs text-gray-400 dark:text-gray-500">(Opsional)</span>
                    </label>
                    <textarea id="testimoni" name="testimoni" rows="4" placeholder="Kesan selama bersekolah atau saran pengembangan sekolah..."
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('testimoni') }}</textarea>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                        Simpan
                    </button>
                    <a href="{{ route('tracer-study.index') }}"
                        class="border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
