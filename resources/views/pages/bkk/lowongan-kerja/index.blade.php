@extends('layouts.app', ['title' => 'Lowongan Kerja'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Lowongan Kerja" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
        <!-- Table Header Actions -->
        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-gray-800">
            <!-- Search & Filters -->
            <form action="{{ route('bkk.lowongan-kerja.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:max-w-2xl">
                <!-- Search input with Real-time PT Suggestions -->
                <x-form.pt-search-input 
                    name="search" 
                    :value="$search" 
                    placeholder="Cari judul lowongan, perusahaan, atau posisi..." 
                    :suggestions="$ptSuggestions ?? []" 
                />

                <!-- Filter Status -->
                <div class="w-full sm:w-36">
                    <select name="status" onchange="this.form.submit()"
                        class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 px-3 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:focus:border-brand-800">
                        <option value="">Status</option>
                        <option value="Aktif" {{ $status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tutup" {{ $status === 'Tutup' ? 'selected' : '' }}>Tutup</option>
                    </select>
                </div>
            </form>

            <!-- Add Button -->
            <a href="{{ route('bkk.lowongan-kerja.create') }}" 
                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition whitespace-nowrap">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Lowongan Kerja
            </a>
        </div>

        <!-- Table Content -->
        <div id="table-container">
            <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Judul Lowongan / Posisi</th>
                        <th class="px-6 py-4">Perusahaan</th>
                        <th class="px-6 py-4">Gaji</th>
                        <th class="px-6 py-4">Tanggal Tutup</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                    @forelse ($lowongans as $row)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-gray-800 dark:text-white/95">
                                    {{ $row->judul }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $row->posisi }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($row->perusahaanMitra)
                                    <a href="{{ route('bkk.perusahaan-mitra.show', $row->perusahaan_mitra_id) }}" class="text-brand-600 dark:text-brand-400 hover:underline font-medium">
                                        {{ $row->nama_perusahaan }}
                                    </a>
                                @else
                                    <span class="text-gray-800 dark:text-white/80 font-medium">{{ $row->nama_perusahaan }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 dark:text-gray-300">
                                {{ $row->gaji ?? 'Tidak dicantumkan' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $row->tanggal_tutup ? $row->tanggal_tutup->format('d M Y') : 'Tanpa batas waktu' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-xs">
                                @if($row->status === 'Aktif')
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 font-semibold text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400">
                                        Tutup
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('bkk.lowongan-kerja.show', $row->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Detail
                                    </a>
                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <a href="{{ route('bkk.lowongan-kerja.edit', $row->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                        Ubah
                                    </a>
                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <form action="{{ route('bkk.lowongan-kerja.destroy', $row->id) }}" method="POST" class="inline-block delete-form" 
                                        data-name="{{ $row->judul }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                Tidak ada lowongan kerja ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        @if ($lowongans->hasPages())
            <div class="border-t border-gray-100 p-5 dark:border-gray-800">
                {{ $lowongans->links() }}
            </div>
        @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const name = this.getAttribute('data-name');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus lowongan "${name}". Tindakan ini tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush
