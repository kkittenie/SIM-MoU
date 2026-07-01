@extends('layouts.app', ['title' => 'Data Universitas'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Data Universitas" />

    <style>
        html {
        scrollbar-width: none;
    }

        body::-webkit-scrollbar {
            display: none;
    }
    </style>

    {{-- Header Section --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Universitas</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola data universitas tujuan alumni</p>
        </div>

        {{-- Tombol Tambah (BK Only) --}}
        @if(auth()->user()->role === 'bk')
            <a href="{{ route('universitas.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2 font-medium text-white hover:bg-blue-700 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>+ Tambah Data Universitas</span>
            </a>
        @endif
    </div>

    {{-- Filter Section --}}
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form method="GET" action="{{ route('universitas.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            {{-- Search --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Cari Universitas</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, kota, provinsi..." class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white">
            </div>

            {{-- Jenis Filter --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Jenis</label>
                <select name="jenis" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white">
                    <option value="">-- Semua --</option>
                    <option value="negeri" {{ request('jenis') === 'negeri' ? 'selected' : '' }}>Negeri</option>
                    <option value="swasta" {{ request('jenis') === 'swasta' ? 'selected' : '' }}>Swasta</option>
                </select>
            </div>

            {{-- Lokasi Filter --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Lokasi</label>
                <select name="lokasi_kuliah" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white">
                    <option value="">-- Semua --</option>
                    <option value="dalam_negeri" {{ request('lokasi_kuliah') === 'dalam_negeri' ? 'selected' : '' }}>Dalam Negeri</option>
                    <option value="luar_negeri" {{ request('lokasi_kuliah') === 'luar_negeri' ? 'selected' : '' }}>Luar Negeri</option>
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white">
                    <option value="">-- Semua --</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Filter
                </button>
                <a href="{{ route('universitas.index') }}" class="rounded-lg bg-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-white/5">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800 dark:text-white">Nama Universitas</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800 dark:text-white">Kota / Provinsi</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800 dark:text-white">Lokasi Kuliah</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800 dark:text-white">Jenis</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800 dark:text-white">Akreditasi</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800 dark:text-white">No. Telepon</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800 dark:text-white">Status</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-800 dark:text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($universitas as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            {{-- Nama Universitas --}}
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">{{ $item->nama_universitas }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->website ?? '-' }}</p>
                                </div>
                            </td>

                            {{-- Kota / Provinsi --}}
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $item->kota }}, {{ $item->provinsi }}
                            </td>

                            {{-- Lokasi Kuliah --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                    @if($item->lokasi_kuliah === 'dalam_negeri')
                                        bg-teal-100 text-teal-800 dark:bg-teal-500/15 dark:text-teal-400
                                    @else
                                        bg-orange-100 text-orange-800 dark:bg-orange-500/15 dark:text-orange-400
                                    @endif">
                                    {{ $item->lokasi_kuliah === 'dalam_negeri' ? 'Dalam Negeri' : 'Luar Negeri' }}
                                </span>
                            </td>

                            {{-- Jenis --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                    @if($item->jenis === 'negeri')
                                        bg-blue-100 text-blue-800 dark:bg-blue-500/15 dark:text-blue-400
                                    @else
                                        bg-purple-100 text-purple-800 dark:bg-purple-500/15 dark:text-purple-400
                                    @endif">
                                    {{ ucfirst($item->jenis) }}
                                </span>
                            </td>

                            {{-- Akreditasi --}}
                            <td class="px-6 py-4 text-center font-semibold text-gray-800 dark:text-white">
                                {{ $item->akreditasi ?? '-' }}
                            </td>

                            {{-- No. Telepon --}}
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $item->nomor_telepon ?? '-' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                    @if($item->status === 'aktif')
                                        bg-green-100 text-green-800 dark:bg-green-500/15 dark:text-green-400
                                    @else
                                        bg-red-100 text-red-800 dark:bg-red-500/15 dark:text-red-400
                                    @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            {{-- Action Buttons --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- View Detail --}}
                                    <a href="{{ route('universitas.show', $item) }}"
                                       class="inline-flex items-center gap-1 rounded-md bg-blue-100 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-200 dark:bg-blue-500/15 dark:text-blue-400 dark:hover:bg-blue-500/25 transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat
                                    </a>

                                    @if(auth()->user()->role === 'bk')
                                        {{-- Edit --}}
                                        <a href="{{ route('universitas.edit', $item) }}"
                                           class="inline-flex items-center gap-1 rounded-md bg-amber-100 px-3 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-200 dark:bg-amber-500/15 dark:text-amber-400 dark:hover:bg-amber-500/25 transition-colors">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('universitas.destroy', $item) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus universitas &quot;{{ $item->nama_universitas }}&quot;?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 rounded-md bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-200 dark:bg-red-500/15 dark:text-red-400 dark:hover:bg-red-500/25 transition-colors">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="h-12 w-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p>Tidak ada data universitas</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $universitas->links() }}
    </div>
@endsection
