@extends('layouts.app', ['title' => 'Kelola File CV Pelamar'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Kelola File CV Pelamar" />

    <div class="space-y-5">


        {{-- Notification Alerts --}}
        @if(session('success'))
            <div class="p-4 rounded-xl bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-800 text-sm text-green-700 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-sm text-red-700 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        {{-- Main Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs"
            x-data="{
                selectedIds: [],
                hapusSemua: false,
                get jumlahDipilih() { return this.hapusSemua ? {{ $totalFiles }} : this.selectedIds.length; },
                toggleAll(checked) {
                    if (checked) {
                        this.selectedIds = Array.from(document.querySelectorAll('.cb-item')).map(el => el.value);
                    } else {
                        this.selectedIds = [];
                    }
                },
                isSelected(id) { return this.selectedIds.includes(String(id)); }
            }">

            {{-- Header: Search + Hapus Semua --}}
            <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-gray-800">
                {{-- Search --}}
                <form action="{{ route('bkk.kelola-cv.index') }}" method="GET" class="flex gap-3 w-full sm:max-w-sm">
                    <div class="relative flex-1">
                        <span class="absolute -translate-y-1/2 pointer-events-none left-4 top-1/2">
                            <svg class="fill-gray-400 dark:fill-gray-500" width="16" height="16" viewBox="0 0 20 20" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau jurusan..."
                            class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-10 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                    </div>
                    <button type="submit"
                        class="h-10 px-4 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition flex-shrink-0">
                        Cari
                    </button>
                </form>

                {{-- Hapus Semua Button --}}
                <form action="{{ route('bkk.kelola-cv.bulk-hapus') }}" method="POST" id="form-hapus-semua">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="hapus_semua" value="1">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 h-10 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition shadow-xs"
                        @click.prevent="
                            Swal.fire({
                                title: 'Hapus SEMUA File CV?',
                                text: 'Seluruh {{ $totalFiles }} file CV akan dihapus dari server. Data pelamar tetap ada. Tindakan ini tidak dapat dibatalkan!',
                                icon: 'error',
                                showCancelButton: true,
                                confirmButtonColor: '#dc2626',
                                cancelButtonColor: '#6b7280',
                                confirmButtonText: 'Ya, Hapus Semua!',
                                cancelButtonText: 'Batal'
                            }).then((result) => { if (result.isConfirmed) $el.closest('form').submit(); })
                        ">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Semua ({{ $totalFiles }})
                    </button>
                </form>
            </div>

            {{-- Bulk Action Bar — muncul kalau ada yang dipilih --}}
            <div x-show="selectedIds.length > 0" x-cloak
                class="flex items-center justify-between gap-3 px-5 py-3 bg-orange-50 dark:bg-orange-950/30 border-b border-orange-200 dark:border-orange-800/50">
                <p class="text-sm text-orange-700 dark:text-orange-300 font-medium">
                    <span x-text="selectedIds.length"></span> file CV dipilih
                </p>
                <form action="{{ route('bkk.kelola-cv.bulk-hapus') }}" method="POST" id="form-hapus-pilihan">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white text-xs font-semibold transition"
                        @click.prevent="
                            Swal.fire({
                                title: 'Hapus CV yang Dipilih?',
                                text: selectedIds.length + ' file CV akan dihapus dari server. Data pelamar tetap ada.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#ea580c',
                                cancelButtonColor: '#6b7280',
                                confirmButtonText: 'Ya, Hapus!',
                                cancelButtonText: 'Batal'
                            }).then((result) => { if (result.isConfirmed) $el.closest('form').submit(); })
                        ">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus yang Dipilih
                    </button>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-5 py-4 w-10">
                                <input type="checkbox" id="cb-all"
                                    class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 cursor-pointer"
                                    @change="toggleAll($event.target.checked)">
                            </th>
                            <th class="px-5 py-4">Nama Pelamar</th>
                            <th class="px-5 py-4">Kelas (Jurusan / Angkatan)</th>
                            <th class="px-5 py-4">File CV</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                        @forelse ($pelamars as $row)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors"
                                :class="isSelected({{ $row->id }}) ? 'bg-orange-50/60 dark:bg-orange-950/20' : ''">

                                {{-- Checkbox --}}
                                <td class="px-5 py-4">
                                    <input type="checkbox"
                                        class="cb-item w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 cursor-pointer"
                                        value="{{ $row->id }}"
                                        x-model="selectedIds">
                                </td>

                                {{-- Nama --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-800 dark:text-white/95">
                                        {{ $row->nama_lengkap }}
                                    </div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                        Daftar: {{ $row->created_at->format('d M Y') }}
                                    </div>
                                </td>

                                {{-- Jurusan / Kelas --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="text-gray-700 dark:text-gray-300 font-medium">{{ $row->program_keahlian }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">Lulus {{ $row->tahun_lulus }}</div>
                                </td>

                                {{-- File CV --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($row->file_cv)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-400">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Ada PDF
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('bkk.pelamar.view-cv', $row->id) }}" target="_blank"
                                            class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                            Lihat
                                        </a>
                                        <form action="{{ route('bkk.pelamar.delete-cv', $row->id) }}" method="POST"
                                            class="inline-block form-hapus-cv-single" data-nama="{{ $row->nama_lengkap }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-xs text-orange-600 hover:text-orange-800 dark:text-orange-400 dark:hover:text-orange-300 font-medium">
                                                Hapus CV
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-gray-400 dark:text-gray-500 font-medium">
                                            {{ $search ? 'Tidak ada hasil untuk "' . $search . '"' : 'Tidak ada file CV tersimpan di server' }}
                                        </p>
                                        @if($search)
                                            <a href="{{ route('bkk.kelola-cv.index') }}"
                                                class="text-xs text-brand-600 hover:underline">Lihat semua</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pelamars->hasPages())
                <div class="p-5 border-t border-gray-100 dark:border-gray-800">
                    {{ $pelamars->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Hapus CV satuan — konfirmasi SweetAlert2
    document.querySelectorAll('.form-hapus-cv-single').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const nama = this.getAttribute('data-nama');
            Swal.fire({
                title: 'Hapus File CV?',
                text: `File PDF CV milik "${nama}" akan dihapus dari server. Data pelamar tetap ada.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });
    });
</script>
@endpush
