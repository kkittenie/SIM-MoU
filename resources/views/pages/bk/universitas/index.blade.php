@extends('layouts.app', ['title' => 'Data Universitas'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Data Universitas" />

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Universitas</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola data universitas tujuan alumni</p>
        </div>

        {{-- Tombol Tambah (Admin Only) --}}
        @if(auth()->user()->isAdmin())
            <a href="{{ route('universitas.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2 font-medium text-white hover:bg-blue-700 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Universitas</span>
            </a>
        @endif
    </div>

    {{-- Filter Section --}}
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form method="GET" action="{{ route('universitas.index') }}" class="grid gap-4 md:grid-cols-4">
            {{-- Search --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Cari Universitas</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, kota, provinsi..." class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white">
            </div>

            {{-- Jenis Filter --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Jenis</label>
                <select name="jenis" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white">
                    <option value="">-- Semua Jenis --</option>
                    <option value="negeri" {{ request('jenis') === 'negeri' ? 'selected' : '' }}>Negeri</option>
                    <option value="swasta" {{ request('jenis') === 'swasta' ? 'selected' : '' }}>Swasta</option>
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-white/5 dark:text-white">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Button --}}
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

    {{-- Table Universitas --}}
    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-white/5">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800 dark:text-white">Nama Universitas</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800 dark:text-white">Lokasi</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800 dark:text-white">Jenis</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800 dark:text-white">Akreditasi</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800 dark:text-white">Status</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-800 dark:text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($universitas as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">{{ $item->nama_universitas }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->website ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $item->kota }}, {{ $item->provinsi }}
                            </td>
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
                            <td class="px-6 py-4 text-center font-semibold text-gray-800 dark:text-white">
                                {{ $item->akreditasi ?? '-' }}
                            </td>
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
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    {{-- Detail Link (All roles) --}}
                                    <a href="{{ auth()->user()->isAdmin()
                                    ? route('universitas.show', $item)
                                    : route('bk.universitas.show', $item) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium text-xs">
                                        Lihat
                                    </a>

                                    {{-- Edit Link (Admin only) --}}
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('universitas.edit', $item) }}" class="text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300 font-medium text-xs">
                                            Edit
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data universitas
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
