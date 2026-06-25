@extends('layouts.app', ['title' => 'Detail Universitas'])

@section('content')
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('bk.universitas.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $universitas->nama_universitas }}</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">
                @php
                $lokasi = array_filter([
                    $universitas->kota ?? null,
                    $universitas->provinsi ?? null
                ]);
            @endphp
            {{ implode(', ', $lokasi) ?: '-' }}
            </p>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        {{-- Main Info Card --}}
        <div class="md:col-span-2">
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Informasi Universitas</h2>
                </div>

                <div class="space-y-6 p-6">
                    {{-- Nama Universitas --}}
                    <div class="grid gap-2 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Nama Universitas</label>
                            <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">{{ $universitas->nama_universitas }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Jenis</label>
                            <p class="mt-1">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                    @if($universitas->jenis === 'negeri')
                                        bg-blue-100 text-blue-800 dark:bg-blue-500/15 dark:text-blue-400
                                    @else
                                        bg-purple-100 text-purple-800 dark:bg-purple-500/15 dark:text-purple-400
                                    @endif">
                                    {{ ucfirst($universitas->jenis) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    {{-- Lokasi --}}
                    <div class="grid gap-2 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Kota</label>
                            <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $universitas->kota ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Provinsi</label>
                            <p class="mt-1 text-base text-gray-800 dark:text-white">{{ $universitas->provinsi ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Akreditasi & Status --}}
                    <div class="grid gap-2 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Akreditasi</label>
                            <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white">{{ $universitas->akreditasi ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Status</label>
                            <p class="mt-1">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                    @if($universitas->status === 'aktif')
                                        bg-green-100 text-green-800 dark:bg-green-500/15 dark:text-green-400
                                    @else
                                        bg-red-100 text-red-800 dark:bg-red-500/15 dark:text-red-400
                                    @endif">
                                    {{ ucfirst($universitas->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    {{-- Website --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Website</label>
                        @if($universitas->website)
                            <a href="{{ $universitas->website }}" target="_blank" class="mt-1 text-base text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 break-all">
                                {{ $universitas->website }}
                            </a>
                        @else
                            <p class="mt-1 text-base text-gray-500 dark:text-gray-400">-</p>
                        @endif
                    </div>

                    {{-- Nomor Telepon --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Nomor Telepon</label>
                        @if($universitas->nomor_telepon)
                            <a href="tel:{{ $universitas->nomor_telepon }}" class="mt-1 text-base text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                {{ $universitas->nomor_telepon }}
                            </a>
                        @else
                            <p class="mt-1 text-base text-gray-500 dark:text-gray-400">-</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Stats --}}
        <div class="space-y-4">
            {{-- Alumni Statistics Card --}}
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Alumni Kuliah</p>
                        <p class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">
                            {{ $alumniCount ?? 0 }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">dari universitas ini</p>
                    </div>
                    <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-500/15">
                        <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.25S6.5 28 12 28s10-4.745 10-10.75S17.5 6.253 12 6.253z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 font-semibold text-gray-800 dark:text-white">Informasi Tambahan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Dibuat pada:</span>
                        <span class="font-medium text-gray-800 dark:text-white">{{ $universitas->created_at?->format('d M Y') ?? '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Diperbarui:</span>
                        <span class="font-medium text-gray-800 dark:text-white">{{ $universitas->updated_at?->format('d M Y H:i') ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Back Button --}}
            <a href="{{ route('bk.universitas.index') }}" class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-3 font-medium text-gray-800 hover:bg-gray-50 dark:border-gray-800 dark:bg-white/[0.03] dark:text-white dark:hover:bg-white/5">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>
@endsection
