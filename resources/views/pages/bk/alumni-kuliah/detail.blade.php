@extends('layouts.app', ['title' => 'Detail Alumni Kuliah'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Detail Alumni Kuliah" />

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $alumniKuliah->nama_alumni }}</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">NIS: {{ $alumniKuliah->nis }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('bk.alumni-kuliah.edit', $alumniKuliah) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
            </a>
            <a href="{{ route('bk.alumni-kuliah.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        {{-- Data Pribadi --}}
        <div class="md:col-span-2">
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-6 text-lg font-bold text-gray-800 dark:text-white">Data Pribadi</h3>

                <div class="space-y-4">
                    {{-- Nama --}}
                    <div class="border-b border-gray-200 pb-4 dark:border-gray-800">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nama Lengkap</p>
                        <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $alumniKuliah->nama_alumni }}</p>
                    </div>

                    {{-- NIS --}}
                    <div class="border-b border-gray-200 pb-4 dark:border-gray-800">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nomor Induk Siswa (NIS)</p>
                        <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $alumniKuliah->nis }}</p>
                    </div>

                    {{-- Email --}}
                    <div class="border-b border-gray-200 pb-4 dark:border-gray-800">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Email</p>
                        <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">
                            @if($alumniKuliah->email_alumni)
                                <a href="mailto:{{ $alumniKuliah->email_alumni }}" class="text-blue-600 hover:underline">{{ $alumniKuliah->email_alumni }}</a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </p>
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nomor Telepon</p>
                        <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">
                            @if($alumniKuliah->nomor_telepon)
                                <a href="tel:{{ $alumniKuliah->nomor_telepon }}" class="text-blue-600 hover:underline">{{ $alumniKuliah->nomor_telepon }}</a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Data Pendidikan --}}
            <div class="mt-6 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-6 text-lg font-bold text-gray-800 dark:text-white">Data Pendidikan</h3>

                <div class="space-y-4">
                    {{-- Universitas --}}
                    <div class="border-b border-gray-200 pb-4 dark:border-gray-800">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Universitas</p>
                        <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">
                            @if($alumniKuliah->universitas)
                                {{ $alumniKuliah->universitas->nama_universitas }}
                                @if($alumniKuliah->universitas->kota)
                                    <span class="text-sm text-gray-500">({{ $alumniKuliah->universitas->kota }})</span>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </p>
                    </div>

                    {{-- Program Studi --}}
                    <div class="border-b border-gray-200 pb-4 dark:border-gray-800">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Program Studi</p>
                        <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $alumniKuliah->program_studi }}</p>
                    </div>

                    {{-- Tahun Lulus --}}
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tahun Lulus</p>
                        <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $alumniKuliah->tahun_lulus }}</p>
                    </div>
                </div>
            </div>

            {{-- Tracer Study --}}
            @if($alumniKuliah->tracerStudy->count() > 0)
                <div class="mt-6 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-6 text-lg font-bold text-gray-800 dark:text-white">Tracer Study</h3>

                    @foreach($alumniKuliah->tracerStudy as $tracer)
                        <div class="border-b border-gray-200 pb-4 dark:border-gray-800 last:border-0">
                            <div class="mb-4 grid gap-4 md:grid-cols-2">
                                {{-- Status Kuliah --}}
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status Kuliah</p>
                                    <p class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-medium
                                        @if($tracer->status_kuliah === 'aktif') bg-blue-100 text-blue-800 dark:bg-blue-500/15 dark:text-blue-400
                                        @elseif($tracer->status_kuliah === 'lulus') bg-green-100 text-green-800 dark:bg-green-500/15 dark:text-green-400
                                        @elseif($tracer->status_kuliah === 'cuti') bg-yellow-100 text-yellow-800 dark:bg-yellow-500/15 dark:text-yellow-400
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-500/15 dark:text-gray-400
                                        @endif">
                                        {{ ucfirst($tracer->status_kuliah) }}
                                    </p>
                                </div>

                                {{-- Semester --}}
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Semester Saat Ini</p>
                                    <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">{{ $tracer->semester_saat_ini ?? '-' }}</p>
                                </div>

                                {{-- IPK --}}
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">IPK</p>
                                    <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">{{ $tracer->ipk ?? '-' }}</p>
                                </div>

                                {{-- Tanggal Update --}}
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal Update</p>
                                    <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">{{ $tracer->tanggal_update->format('d M Y') }}</p>
                                </div>
                            </div>

                            {{-- Prestasi & Catatan --}}
                            @if($tracer->prestasi_akademik || $tracer->catatan)
                                <div class="grid gap-4 md:grid-cols-2">
                                    @if($tracer->prestasi_akademik)
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Prestasi Akademik</p>
                                            <p class="mt-1 text-sm text-gray-800 dark:text-gray-300">{{ $tracer->prestasi_akademik }}</p>
                                        </div>
                                    @endif

                                    @if($tracer->catatan)
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Catatan</p>
                                            <p class="mt-1 text-sm text-gray-800 dark:text-gray-300">{{ $tracer->catatan }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Sidebar Info --}}
        <div>
            {{-- Status Card --}}
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-lg font-bold text-gray-800 dark:text-white">Status Alumni</h3>

                <div class="mb-4">
                    <p class="inline-flex rounded-full px-4 py-2 text-sm font-semibold
                        @if($alumniKuliah->status_alumni === 'aktif') bg-blue-100 text-blue-800 dark:bg-blue-500/15 dark:text-blue-400
                        @elseif($alumniKuliah->status_alumni === 'lulus') bg-green-100 text-green-800 dark:bg-green-500/15 dark:text-green-400
                        @elseif($alumniKuliah->status_alumni === 'cuti') bg-yellow-100 text-yellow-800 dark:bg-yellow-500/15 dark:text-yellow-400
                        @else bg-gray-100 text-gray-800 dark:bg-gray-500/15 dark:text-gray-400
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $alumniKuliah->status_alumni)) }}
                    </p>
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Ditambahkan: <span class="font-semibold text-gray-800 dark:text-white">{{ $alumniKuliah->created_at->format('d M Y') }}</span>
                </p>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Diperbarui: <span class="font-semibold text-gray-800 dark:text-white">{{ $alumniKuliah->updated_at->format('d M Y') }}</span>
                </p>
            </div>

            {{-- Action Card --}}
            <div class="mt-6 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-lg font-bold text-gray-800 dark:text-white">Aksi</h3>

                <div class="space-y-2">
                    <a href="{{ route('bk.alumni-kuliah.edit', $alumniKuliah) }}" class="block rounded-lg bg-blue-50 px-4 py-2 text-center text-sm font-medium text-blue-600 hover:bg-blue-100 dark:bg-blue-500/10 dark:hover:bg-blue-500/20">
                        ✏️ Edit Alumni
                    </a>

                    <a href="{{ route('bk.tracer-kuliah.create') }}" class="block rounded-lg bg-green-50 px-4 py-2 text-center text-sm font-medium text-green-600 hover:bg-green-100 dark:bg-green-500/10 dark:hover:bg-green-500/20">
                        📝 Tambah Tracer Study
                    </a>

                    <form action="{{ route('bk.alumni-kuliah.destroy', $alumniKuliah) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="block w-full rounded-lg bg-red-50 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:hover:bg-red-500/20" onclick="return confirm('Yakin hapus alumni ini?')">
                            🗑️ Hapus Alumni
                        </button>
                    </form>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="mt-6 rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-500/20 dark:bg-blue-500/10">
                <p class="text-xs text-blue-800 dark:text-blue-300">
                    <strong>💡 Tip:</strong> Kelola tracer study alumni untuk memantau perkembangan kuliah mereka.
                </p>
            </div>
        </div>
    </div>
@endsection
