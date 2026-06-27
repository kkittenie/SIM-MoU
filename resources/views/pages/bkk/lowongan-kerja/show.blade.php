@extends('layouts.app', ['title' => 'Detail Lowongan Kerja'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Detail Lowongan Kerja" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Utama Lowongan -->
        <div class="lg:col-span-1 space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
                <div class="flex flex-col items-center text-center pb-6 border-b border-gray-100 dark:border-gray-800">
                    <!-- Icon Placeholder -->
                    <div class="w-20 h-20 rounded-2xl bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600 dark:text-brand-400 mb-4">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white/90">
                        {{ $lowongan->judul }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">
                        {{ $lowongan->posisi }}
                    </p>
                    <span class="mt-2.5 inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold bg-brand-50 text-brand-700 dark:bg-brand-900/30 dark:text-brand-300">
                        {{ $lowongan->nama_perusahaan }}
                    </span>
                </div>

                <div class="py-6 space-y-4">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Perusahaan</span>
                        @if($lowongan->perusahaanMitra)
                            <a href="{{ route('bkk.perusahaan-mitra.show', $lowongan->perusahaan_mitra_id) }}" class="mt-1 inline-flex items-center gap-1 text-sm text-brand-600 hover:text-brand-800 dark:text-brand-400 dark:hover:text-brand-300 font-medium">
                                {{ $lowongan->nama_perusahaan }}
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @else
                            <p class="mt-1 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                {{ $lowongan->nama_perusahaan }} (Non-Mitra)
                            </p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Rentang Gaji</span>
                            <span class="block mt-1 text-sm text-gray-700 dark:text-gray-300 font-semibold text-brand-600 dark:text-brand-400">
                                {{ $lowongan->gaji ?? 'Tidak dicantumkan' }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Batas Pendaftaran</span>
                            <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300 font-medium">
                                {{ $lowongan->tanggal_tutup ? $lowongan->tanggal_tutup->format('d M Y') : 'Tanpa batas waktu' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Status Lowongan</span>
                        <span class="block mt-1">
                            @if ($lowongan->status === 'Aktif')
                                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">
                                    Tutup
                                </span>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-2">
                    <a href="{{ route('bkk.lowongan-kerja.edit', $lowongan->id) }}"
                        class="flex-1 bg-yellow-500 hover:bg-yellow-600 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        Ubah Data
                    </a>
                    <a href="{{ route('bkk.lowongan-kerja.index') }}"
                        class="flex-1 border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium transition">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Detail Persyaratan & Deskripsi Lowongan -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Persyaratan Lowongan -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
                <div class="border-b border-gray-100 pb-4 dark:border-gray-800 mb-4">
                    <h3 class="text-base font-bold text-gray-800 dark:text-white/90">
                        Persyaratan Lowongan
                    </h3>
                </div>
                <div class="prose dark:prose-invert max-w-none text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                    {!! nl2br(e($lowongan->persyaratan)) !!}
                </div>
            </div>

            <!-- Deskripsi Pekerjaan -->
            @if($lowongan->deskripsi)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
                    <div class="border-b border-gray-100 pb-4 dark:border-gray-800 mb-4">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white/90">
                            Deskripsi Pekerjaan / Informasi Tambahan
                        </h3>
                    </div>
                    <div class="prose dark:prose-invert max-w-none text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                        {!! nl2br(e($lowongan->deskripsi)) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
