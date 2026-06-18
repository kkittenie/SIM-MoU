@extends('layouts.app', ['title' => 'Detail Kerja Sama'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Detail Kerja Sama" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-3xl">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Rincian Kemitraan & MoU
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Detail informasi kerja sama yang terdaftar di sistem.
                </p>
            </div>
            
            <!-- Ubah Button (Visible to Admin and BKK only) -->
            @if (auth()->user()->isAdmin() || auth()->user()->isBKK())
                <a href="{{ route('kerja-sama.edit', $kerjaSama->id) }}"
                    class="bg-yellow-500 shadow-theme-xs hover:bg-yellow-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Ubah Kerja Sama
                </a>
            @endif
        </div>

        <div class="p-6 space-y-6">
            <!-- Details Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Nama Mitra -->
                <div class="sm:col-span-2">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Nama Mitra</span>
                    <span class="mt-1 block text-base font-bold text-gray-800 dark:text-white/95">{{ $kerjaSama->nama_mitra }}</span>
                </div>

                <!-- Jenis Mitra -->
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Jenis Mitra</span>
                    <span class="mt-1 block text-sm font-medium text-gray-800 dark:text-white/90">{{ $kerjaSama->jenis_mitra }}</span>
                </div>

                <!-- PIC -->
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">PIC Mitra</span>
                    <span class="mt-1 block text-sm font-medium text-gray-800 dark:text-white/90">{{ $kerjaSama->pic }}</span>
                </div>

                <!-- Email -->
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Email Kemitraan</span>
                    <span class="mt-1 block text-sm font-medium text-gray-800 dark:text-white/90">
                        <a href="mailto:{{ $kerjaSama->email }}" class="text-brand-600 hover:underline dark:text-brand-400">{{ $kerjaSama->email }}</a>
                    </span>
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Nomor Telepon</span>
                    <span class="mt-1 block text-sm font-medium text-gray-800 dark:text-white/90">{{ $kerjaSama->nomor_telepon }}</span>
                </div>

                <!-- Nomor MoU -->
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Nomor MoU</span>
                    <span class="mt-1 block text-sm font-mono text-gray-800 dark:text-white/90">{{ $kerjaSama->nomor_mou }}</span>
                </div>

                <!-- Status -->
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Status MoU</span>
                    <span class="mt-1.5 inline-flex items-center">
                        @if ($kerjaSama->status === 'Aktif')
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                Aktif
                            </span>
                        @elseif ($kerjaSama->status === 'Akan Berakhir')
                            <span class="inline-flex items-center rounded-md bg-yellow-50 px-2.5 py-0.5 text-xs font-semibold text-yellow-700 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400 dark:ring-yellow-500/20">
                                Akan Berakhir
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-md bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">
                                Expired
                            </span>
                        @endif
                    </span>
                </div>

                <!-- Masa Berlaku -->
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Masa Berlaku MoU</span>
                    <span class="mt-1 block text-sm text-gray-850 dark:text-gray-300">
                        {{ $kerjaSama->tanggal_mulai ? $kerjaSama->tanggal_mulai->translatedFormat('d F Y') : '-' }} s.d.
                        {{ $kerjaSama->tanggal_berakhir ? $kerjaSama->tanggal_berakhir->translatedFormat('d F Y') : '-' }}
                    </span>
                </div>

                <!-- Alamat -->
                <div class="sm:col-span-2">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Alamat Lengkap</span>
                    <span class="mt-1 block text-sm text-gray-800 dark:text-white/90 leading-relaxed">{{ $kerjaSama->alamat }}</span>
                </div>

                <!-- Deskripsi -->
                <div class="sm:col-span-2">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Deskripsi/Cakupan Kerja Sama</span>
                    <span class="mt-1 block text-sm text-gray-850 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $kerjaSama->deskripsi ?: '-' }}</span>
                </div>

                <!-- Dokumen PDF -->
                <div class="sm:col-span-2">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Dokumen Pendukung (PDF)</span>
                    <div class="mt-2">
                        @if ($kerjaSama->dokumen_pdf)
                            <a href="{{ route('kerja-sama.download', $kerjaSama->id) }}" 
                                class="inline-flex items-center gap-2 rounded-lg bg-brand-50 px-4 py-2.5 text-xs font-semibold text-brand-700 hover:bg-brand-100 ring-1 ring-inset ring-brand-700/10 dark:bg-brand-500/10 dark:text-brand-400 dark:hover:bg-brand-500/20 dark:ring-brand-500/20 transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Unduh Dokumen MoU (PDF)
                            </a>
                        @else
                            <span class="text-xs text-gray-400 italic">Belum ada file dokumen MoU yang diunggah.</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Back Actions -->
            <div class="pt-6 border-t border-gray-100 dark:border-gray-800">
                <a href="{{ route('kerja-sama.index') }}"
                    class="border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium transition">
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
@endsection
