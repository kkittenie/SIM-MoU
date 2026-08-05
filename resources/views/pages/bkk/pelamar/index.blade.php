@extends('layouts.app', ['title' => 'Data Pelamar Kerja'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Data Pelamar Kerja" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
        <!-- Table Header Actions -->
        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-gray-800">
            <!-- Search & Filters -->
            <form action="{{ route('bkk.pelamar.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:max-w-3xl">
                <!-- Search input -->
                <div class="relative flex-1">
                    <span class="absolute -translate-y-1/2 pointer-events-none left-4 top-1/2">
                        <svg class="fill-gray-400 dark:fill-gray-500" width="18" height="18" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama pelamar, email, telp, jurusan..."
                        class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-10 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                </div>

                <!-- Filter Lowongan -->
                <div class="w-full sm:w-48">
                    <select name="lowongan_kerja_id" onchange="this.form.submit()"
                        class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 px-3 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:focus:border-brand-800">
                        <option value="">Semua Lowongan</option>
                        @foreach($lowongans as $l)
                            <option value="{{ $l->id }}" {{ $lowonganId == $l->id ? 'selected' : '' }}>{{ $l->judul }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status -->
                <div class="w-full sm:w-36">
                    <select name="status" onchange="this.form.submit()"
                        class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 px-3 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:focus:border-brand-800">
                        <option value="">Status</option>
                        <option value="Pending" {{ $status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Diterima" {{ $status === 'Diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="Ditolak" {{ $status === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Notification Alerts -->
        @if(session('success'))
            <div class="m-5 p-4 rounded-xl bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-800 text-sm text-green-700 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="m-5 p-4 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-sm text-red-700 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Pelamar</th>
                        <th class="px-6 py-4">Lowongan Kerja</th>
                        <th class="px-6 py-4">Jurusan / Lulus</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent" x-data="{ openDetailId: null }">
                    @forelse ($pelamars as $row)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-gray-800 dark:text-white/95">
                                    {{ $row->nama_lengkap }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    Daftar: {{ $row->created_at->format('d M Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-700 dark:text-gray-300">
                                    {{ $row->lowonganKerja->judul ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $row->lowonganKerja->nama_perusahaan ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-800 dark:text-white/80 font-medium">
                                    {{ $row->program_keahlian }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    Angkatan / Lulus {{ $row->tahun_lulus }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs text-gray-700 dark:text-gray-300 font-medium">
                                    {{ $row->email }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $row->nomor_telepon }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($row->status === 'Diterima')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700 dark:bg-green-950 dark:text-green-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Diterima
                                    </span>
                                @elseif($row->status === 'Ditolak')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Ditolak
                                    </span>
                                @else
                                    <div class="flex items-center gap-1.5">
                                        <!-- Terima Button -->
                                        <form action="{{ route('bkk.pelamar.status', $row->id) }}" method="POST" class="inline-block form-terima" data-nama="{{ $row->nama_lengkap }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Diterima">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-600 hover:bg-green-700 text-white transition shadow-xs">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Terima
                                            </button>
                                        </form>

                                        <!-- Tolak Button -->
                                        <form action="{{ route('bkk.pelamar.status', $row->id) }}" method="POST" class="inline-block form-tolak" data-nama="{{ $row->nama_lengkap }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/40 dark:hover:bg-red-900/60 dark:text-red-400 border border-red-200 dark:border-red-800 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click="openDetailId = (openDetailId === {{ $row->id }} ? null : {{ $row->id }})" 
                                        class="text-brand-600 hover:text-brand-900 dark:text-brand-400 dark:hover:text-brand-300">
                                        Pesan & Details
                                    </button>

                                    @if($row->file_cv)
                                        <span class="text-gray-300 dark:text-gray-700">|</span>
                                        <a href="{{ route('bkk.pelamar.download-cv', $row->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            Download
                                        </a>
                                        <span class="text-gray-300 dark:text-gray-700">|</span>
                                        <form action="{{ route('bkk.pelamar.delete-cv', $row->id) }}" method="POST" class="inline-block form-hapus-cv" data-nama="{{ $row->nama_lengkap }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-orange-500 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300" title="Hapus file PDF CV dari server">
                                                Hapus CV
                                            </button>
                                        </form>
                                    @endif

                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <form action="{{ route('bkk.pelamar.destroy', $row->id) }}" method="POST" class="inline-block form-hapus" data-nama="{{ $row->nama_lengkap }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <!-- Detail Modal / Drawer Row -->
                        <tr x-show="openDetailId === {{ $row->id }}" x-cloak class="bg-gray-50/80 dark:bg-white/[0.02]">
                            <td colspan="6" class="px-6 py-4">
                                <div class="grid grid-cols-1 {{ $row->file_cv ? 'lg:grid-cols-2' : '' }} gap-4 text-xs">
                                    <div class="space-y-2">
                                        <div class="font-bold text-gray-700 dark:text-gray-200">Surat Lamaran / Pesan:</div>
                                        <div class="p-3.5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                                            {{ $row->surat_lamaran }}
                                        </div>
                                    </div>

                                    @if($row->file_cv)
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between font-bold text-gray-700 dark:text-gray-200">
                                            <span>Pratinjau CV:</span>
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('bkk.pelamar.view-cv', $row->id) }}" target="_blank" class="text-xs text-brand-600 hover:underline font-normal">
                                                    Buka Tab Baru &nearr;
                                                </a>
                                                <form action="{{ route('bkk.pelamar.delete-cv', $row->id) }}" method="POST" class="inline-block form-hapus-cv" data-nama="{{ $row->nama_lengkap }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-semibold bg-orange-50 hover:bg-orange-100 text-orange-600 dark:bg-orange-950/40 dark:hover:bg-orange-900/60 dark:text-orange-400 border border-orange-200 dark:border-orange-800 transition"
                                                        title="Hapus file PDF ini dari server untuk hemat disk space">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        Hapus File CV
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800 h-64 bg-gray-100 dark:bg-gray-950">
                                            <iframe src="{{ route('bkk.pelamar.view-cv', $row->id) }}" class="w-full h-full border-0"></iframe>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                                Belum ada data pelamar kerja.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pelamars->hasPages())
            <div class="p-5 border-t border-gray-100 dark:border-gray-800">
                {{ $pelamars->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    // Form Terima (SweetAlert2)
    document.querySelectorAll('.form-terima').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const nama = this.getAttribute('data-nama');
            Swal.fire({
                title: 'Konfirmasi Penerimaan',
                text: `Terima pelamar "${nama}"? Pelamar lain di lowongan ini akan otomatis ditolak, notifikasi WA dikirim, dan data dipindahkan ke Alumni Bekerja.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Terima!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // Form Tolak (SweetAlert2)
    document.querySelectorAll('.form-tolak').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const nama = this.getAttribute('data-nama');
            Swal.fire({
                title: 'Konfirmasi Penolakan',
                text: `Tolak pelamar "${nama}"? Notifikasi WA penolakan akan dikirimkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // Form Hapus CV saja (SweetAlert2)
    document.querySelectorAll('.form-hapus-cv').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const nama = this.getAttribute('data-nama');
            Swal.fire({
                title: 'Hapus File CV?',
                text: `File PDF CV milik "${nama}" akan dihapus dari server. Data pelamar tetap ada, hanya file CV-nya yang dihapus untuk menghemat disk space.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus File CV!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // Form Hapus (SweetAlert2)
    document.querySelectorAll('.form-hapus').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const nama = this.getAttribute('data-nama');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Data pelamar "${nama}" akan dihapus permanen!`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
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
