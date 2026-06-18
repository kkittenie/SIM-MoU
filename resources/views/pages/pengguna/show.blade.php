@extends('layouts.app', ['title' => 'Detail Pengguna'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Detail Pengguna" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-2xl">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Informasi Akun Pengguna
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Berikut adalah rincian data akun yang terdaftar dalam sistem.
                </p>
            </div>
            
            <a href="{{ route('pengguna.edit', $user->id) }}"
                class="bg-yellow-500 shadow-theme-xs hover:bg-yellow-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Ubah Data
            </a>
        </div>

        <div class="p-6 space-y-6">
            <!-- Details Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Nama -->
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Nama Lengkap</span>
                    <span class="mt-1 block text-sm font-medium text-gray-800 dark:text-white/90">{{ $user->name }}</span>
                </div>

                <!-- Email -->
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Alamat Email</span>
                    <span class="mt-1 block text-sm font-medium text-gray-800 dark:text-white/90">{{ $user->email }}</span>
                </div>

                <!-- Peran -->
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Peran (Role)</span>
                    <span class="mt-1.5 inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200 capitalize">
                        {{ $user->role }}
                    </span>
                </div>

                <!-- Status -->
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Status Akun</span>
                    <span class="mt-1.5 inline-flex items-center">
                        @if ($user->status === 'aktif')
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-md bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">
                                Nonaktif
                            </span>
                        @endif
                    </span>
                </div>

                <!-- Dibuat Pada -->
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Terdaftar Pada</span>
                    <span class="mt-1 block text-sm text-gray-600 dark:text-gray-300">
                        {{ $user->created_at ? $user->created_at->translatedFormat('d F Y H:i') : '-' }}
                    </span>
                </div>

                <!-- Diperbarui Pada -->
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Pembaruan Terakhir</span>
                    <span class="mt-1 block text-sm text-gray-600 dark:text-gray-300">
                        {{ $user->updated_at ? $user->updated_at->translatedFormat('d F Y H:i') : '-' }}
                    </span>
                </div>
            </div>

            <!-- Back Actions -->
            <div class="pt-6 border-t border-gray-100 dark:border-gray-800">
                <a href="{{ route('pengguna.index') }}"
                    class="border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium transition">
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
@endsection
