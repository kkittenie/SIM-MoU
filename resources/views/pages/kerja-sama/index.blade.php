@extends('layouts.app', ['title' => 'Kelola Kerja Sama'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Kelola Kerja Sama" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
        <!-- Table Header Actions (Search & Filter) -->
        <div class="p-5 border-b border-gray-100 dark:border-gray-800 space-y-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Daftar Kerja Sama & MoU
                </h3>

                <!-- Add Button (Visible to Admin and BKK only) -->
                @if (auth()->user()->isAdmin() || auth()->user()->isBKK())
                    <a href="{{ route('kerja-sama.create') }}" 
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Kerja Sama
                    </a>
                @endif
            </div>

            <!-- Filters Form -->
            <form action="{{ route('kerja-sama.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                <!-- Search Input -->
                <div class="relative">
                    <span class="absolute -translate-y-1/2 pointer-events-none left-4 top-1/2">
                        <svg class="fill-gray-400 dark:fill-gray-500" width="18" height="18" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari mitra, PIC, MoU..."
                        class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-10 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                </div>

                <!-- Jenis Mitra -->
                <div>
                    <select name="jenis_mitra" 
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Semua Jenis Mitra</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->nama }}" {{ $jenisMitra === $cat->nama ? 'selected' : '' }}>
                                {{ $cat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun MoU -->
                <div>
                    <select name="year" 
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Semua Tahun</option>
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <select name="status" 
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Semua Status MoU</option>
                        <option value="Aktif" {{ $status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Akan Berakhir" {{ $status === 'Akan Berakhir' ? 'selected' : '' }}>Akan Berakhir (<=30 hari)</option>
                        <option value="Expired" {{ $status === 'Expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>

                <!-- Action Button -->
                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="bg-gray-100 hover:bg-gray-200 dark:bg-white/5 dark:hover:bg-white/10 h-10 text-gray-700 dark:text-white/95 rounded-lg px-4 py-2 text-sm font-medium transition w-full sm:w-auto">
                        Filter
                    </button>
                    @if ($search || $jenisMitra || $status || $year)
                        <a href="{{ route('kerja-sama.index') }}"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-sm whitespace-nowrap px-2">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto w-full">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4 min-w-[180px]">Mitra</th>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4 min-w-[120px]">PIC</th>
                        <th class="px-6 py-4 min-w-[140px]">No. MoU</th>
                        <th class="px-6 py-4">Masa Berlaku</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Dokumen</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                    @forelse ($kerjaSama as $ks)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                            <td class="px-6 py-4 whitespace-normal font-medium text-gray-800 dark:text-white/95">
                                {{ $ks->nama_mitra }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                {{ $ks->jenis_mitra }}
                            </td>
                            <td class="px-6 py-4 whitespace-normal">{{ $ks->pic }}</td>
                            <td class="px-6 py-4 whitespace-normal text-xs">{{ $ks->nomor_mou }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                {{ $ks->tanggal_mulai ? $ks->tanggal_mulai->format('d/m/Y') : '-' }} s.d.
                                {{ $ks->tanggal_berakhir ? $ks->tanggal_berakhir->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($ks->status === 'Aktif')
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                        Aktif
                                    </span>
                                @elseif ($ks->status === 'Akan Berakhir')
                                    <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-0.5 text-xs font-semibold text-yellow-700 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400 dark:ring-yellow-500/20">
                                        Akan Berakhir
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">
                                        Expired
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                @if ($ks->dokumen_pdf)
                                    <a href="{{ route('kerja-sama.download', $ks->id) }}" 
                                        class="inline-flex items-center gap-1 text-brand-600 hover:text-brand-800 dark:text-brand-400 dark:hover:text-brand-300 font-medium">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Unduh PDF
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('kerja-sama.show', $ks->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Detail
                                    </a>
                                    <!-- Edit & Delete are only visible to Admin & BKK -->
                                    @if (auth()->user()->isAdmin() || auth()->user()->isBKK())
                                        @if ($ks->status !== 'Aktif')
                                            <span class="text-gray-300 dark:text-gray-700">|</span>
                                            <form action="{{ route('kerja-sama.kirim-wa', $ks->id) }}" method="POST" class="inline-block wa-form" 
                                                data-name="{{ $ks->nama_mitra }}">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                    Ingatkan WA
                                                </button>
                                            </form>
                                        @endif
                                        <span class="text-gray-300 dark:text-gray-700">|</span>
                                        <a href="{{ route('kerja-sama.edit', $ks->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                            Ubah
                                        </a>
                                        <span class="text-gray-300 dark:text-gray-700">|</span>
                                        <form action="{{ route('kerja-sama.destroy', $ks->id) }}" method="POST" class="inline-block delete-form" 
                                            data-name="{{ $ks->nama_mitra }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                             </td>
                         </tr>
                     @empty
                         <tr>
                             <td colspan="8" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                 Tidak ada data kerja sama ditemukan.
                             </td>
                         </tr>
                     @endforelse
                 </tbody>
             </table>
         </div>
 
         <!-- Pagination Links -->
         @if ($kerjaSama->hasPages())
             <div class="border-t border-gray-100 p-5 dark:border-gray-800">
                 {{ $kerjaSama->links() }}
             </div>
         @endif
     </div>
 @endsection
 
 @push('scripts')
 <script>
         // WhatsApp Reminder confirmation
    document.querySelectorAll('.wa-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const name = this.getAttribute('data-name');
            Swal.fire({
                title: 'Kirim Pengingat WhatsApp?',
                text: `Anda akan mengirim pesan pengingat ke PIC Mitra terkait kerja sama dengan "${name}".`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
         form.addEventListener('submit', function(e) {
             e.preventDefault();
             const name = this.getAttribute('data-name');
             Swal.fire({
                 title: 'Apakah Anda yakin?',
                 text: `Anda akan menghapus data kerja sama dengan "${name}". Tindakan ini tidak dapat dibatalkan!`,
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
