@extends('layouts.app', ['title' => 'Detail NIS Siswa'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Detail NIS Siswa" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-3xl">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Informasi Siswa & Status Tracer Study
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Detail pendaftaran NIS dan status tanggapan Tracer Study siswa.
                </p>
            </div>
            <div>
                @if ($student->has_submitted)
                    <span class="inline-flex items-center rounded-md bg-green-50 px-3 py-1 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                        Sudah Mengisi
                    </span>
                @else
                    <span class="inline-flex items-center rounded-md bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400 dark:ring-yellow-500/20">
                        Belum Mengisi
                    </span>
                @endif
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Student Registration Data -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-gray-100 dark:border-gray-800 p-4">
                    <span class="block text-xs font-medium text-gray-400 uppercase">NIS</span>
                    <span class="mt-1 block text-sm font-bold text-gray-800 dark:text-white/90">{{ $student->nis }}</span>
                </div>

                <div class="rounded-xl border border-gray-100 dark:border-gray-800 p-4">
                    <span class="block text-xs font-medium text-gray-400 uppercase">Nama Lengkap Siswa</span>
                    <span class="mt-1 block text-sm font-bold text-gray-800 dark:text-white/90">{{ $student->nama_siswa }}</span>
                </div>

                <div class="rounded-xl border border-gray-100 dark:border-gray-800 p-4">
                    <span class="block text-xs font-medium text-gray-400 uppercase">Program Keahlian / Jurusan</span>
                    <span class="mt-1 block text-sm font-semibold text-gray-800 dark:text-white/90">{{ $student->programKeahlian?->nama ?? '-' }}</span>
                </div>

                <div class="rounded-xl border border-gray-100 dark:border-gray-800 p-4">
                    <span class="block text-xs font-medium text-gray-400 uppercase">Status Tracer Study</span>
                    <span class="mt-1 block text-sm font-semibold {{ $student->has_submitted ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                        {{ $student->status_tracer_label }}
                    </span>
                </div>
            </div>

            <!-- Tracer Study Submission Details (If Submitted) -->
            @if ($student->tracerStudy)
                <div class="border-t border-gray-100 dark:border-gray-800 pt-6 space-y-4">
                    <h4 class="text-sm font-bold text-gray-800 dark:text-white/90">
                        Data Pengisian Tracer Study
                    </h4>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-xl border border-gray-100 dark:border-gray-800 p-4">
                            <span class="block text-xs font-medium text-gray-400 uppercase">Status Saat Ini</span>
                            <span class="mt-1 block text-sm font-bold text-brand-600 dark:text-brand-400">{{ $student->tracerStudy->status_alumni }}</span>
                        </div>
                        <div class="rounded-xl border border-gray-100 dark:border-gray-800 p-4">
                            <span class="block text-xs font-medium text-gray-400 uppercase">Tahun Lulus</span>
                            <span class="mt-1 block text-sm font-semibold text-gray-800 dark:text-white/90">{{ $student->tracerStudy->tahun_lulus }}</span>
                        </div>
                        <div class="rounded-xl border border-gray-100 dark:border-gray-800 p-4 sm:col-span-2">
                            <span class="block text-xs font-medium text-gray-400 uppercase">Instansi / Perguruan Tinggi / Usaha</span>
                            <span class="mt-1 block text-sm font-semibold text-gray-800 dark:text-white/90">{{ $student->tracerStudy->detail_status ?: '-' }}</span>
                        </div>
                        @if ($student->tracerStudy->testimoni)
                            <div class="rounded-xl border border-gray-100 dark:border-gray-800 p-4 sm:col-span-2">
                                <span class="block text-xs font-medium text-gray-400 uppercase">Testimoni</span>
                                <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $student->tracerStudy->testimoni }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Footer Actions -->
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <a href="{{ route('nis-tracer.edit', $student->id) }}"
                    class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                    Ubah Data
                </a>
                <a href="{{ route('nis-tracer.index') }}"
                    class="border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium transition">
                    Kembali
                </a>
            </div>
        </div>
    </div>
@endsection
