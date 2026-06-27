@extends('layouts.app', ['title' => 'Data Perusahaan Mitra'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Data Perusahaan Mitra" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
        <!-- Table Header Actions -->
        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-gray-800">
            <!-- Search & Filter Form -->
            <form action="{{ route('bkk.perusahaan-mitra.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:max-w-xl">
                <!-- Search input -->
                <div class="relative flex-1">
                    <span class="absolute -translate-y-1/2 pointer-events-none left-4 top-1/2">
                        <svg class="fill-gray-400 dark:fill-gray-500" width="18" height="18" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama perusahaan atau PIC..."
                        class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-10 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                </div>
                
                <!-- Filter Dropdown -->
                <div class="w-full sm:w-48">
                    <select name="industri" onchange="this.form.submit()"
                        class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 px-3 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:focus:border-brand-800">
                        <option value="">Semua Industri</option>
                        @foreach($industriList as $ind)
                            <option value="{{ $ind }}" {{ $industri == $ind ? 'selected' : '' }}>{{ $ind }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <!-- Add Button -->
            <a href="{{ route('bkk.perusahaan-mitra.create') }}" 
                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition whitespace-nowrap">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Mitra Baru
            </a>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Nama Perusahaan</th>
                        <th class="px-6 py-4">Bidang Industri</th>
                        <th class="px-6 py-4">Kontak / Email</th>
                        <th class="px-6 py-4">PIC</th>
                        <th class="px-6 py-4">Status Kerja Sama</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                    @forelse ($mitras as $mitra)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800 dark:text-white/95">
                                {{ $mitra->nama_perusahaan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-medium bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ $mitra->bidang_industri }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs">
                                    <span class="block text-gray-800 dark:text-white/80 font-medium">{{ $mitra->nomor_telepon }}</span>
                                    <span class="block text-gray-400">{{ $mitra->email }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $mitra->pic }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($mitra->status_aktif === 'Aktif')
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('bkk.perusahaan-mitra.show', $mitra->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Detail
                                    </a>
                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <a href="{{ route('bkk.perusahaan-mitra.edit', $mitra->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                        Ubah
                                    </a>
                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <form action="{{ route('bkk.perusahaan-mitra.destroy', $mitra->id) }}" method="POST" class="inline-block delete-form" 
                                        data-name="{{ $mitra->nama_perusahaan }}">
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
                                Tidak ada data perusahaan mitra ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        @if ($mitras->hasPages())
            <div class="border-t border-gray-100 p-5 dark:border-gray-800">
                {{ $mitras->links() }}
            </div>
        @endif
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
                text: `Anda akan menghapus perusahaan mitra "${name}" dan data terkait. Tindakan ini tidak dapat dibatalkan!`,
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
