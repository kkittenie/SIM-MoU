@extends('layouts.app', ['title' => 'Data Alumni Kuliah'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Data Alumni Kuliah" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
        <!-- Table Header Actions -->
        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-gray-800">
            <!-- Search & Filters -->
            <form action="{{ route('bk.alumni-kuliah.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:max-w-3xl">
                <!-- Search input -->
                <div class="relative flex-1">
                    <span class="absolute -translate-y-1/2 pointer-events-none left-4 top-1/2">
                        <svg class="fill-gray-400 dark:fill-gray-500" width="18" height="18" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama alumni, universitas, atau program studi..."
                        class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-10 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                </div>

                <!-- Filter Status Kuliah -->
                <div class="w-full sm:w-40">
                    <select name="status" onchange="this.form.submit()"
                        class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 px-3 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:focus:border-brand-800">
                        <option value="">Status Kuliah</option>
                        <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="lulus" {{ $status === 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="cuti" {{ $status === 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="belum_terdata" {{ $status === 'belum_terdata' ? 'selected' : '' }}>Belum Terdata</option>
                    </select>
                </div>

                <!-- Filter Tahun Lulus -->
                <div class="w-full sm:w-36">
                    <select name="tahun_lulus" onchange="this.form.submit()"
                        class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 px-3 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:focus:border-brand-800">
                        <option value="">Tahun Lulus</option>
                        @foreach($tahunLulusList as $tahun)
                            <option value="{{ $tahun }}" {{ $tahunLulus == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Export Button -->
                <a href="{{ route('bk.alumni-kuliah.export-excel', ['search' => $search, 'status' => $status]) }}"
                    class="shadow-theme-xs hover:bg-green-600 inline-flex items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white transition whitespace-nowrap">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Excel
                </a>

                <!-- Add Button -->
                <a href="{{ route('bk.alumni-kuliah.create') }}"
                    class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition whitespace-nowrap">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Alumni Kuliah
                </a>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Nama Alumni</th>
                        <th class="px-6 py-4">NIS</th>
                        <th class="px-6 py-4">Universitas</th>
                        <th class="px-6 py-4">Program Studi</th>
                        <th class="px-6 py-4">Tahun Lulus</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                    @forelse ($alumni as $row)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800 dark:text-white/95">
                                {{ $row->nama_alumni }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $row->nis }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                {{ $row->universitas->nama_universitas ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                {{ $row->program_studi }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-800 dark:text-white/90">
                                {{ $row->tahun_lulus }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-xs">
                                @if($row->status_alumni === 'aktif')
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 font-semibold text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400">
                                        Aktif
                                    </span>
                                @elseif($row->status_alumni === 'lulus')
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400">
                                        Lulus
                                    </span>
                                @elseif($row->status_alumni === 'cuti')
                                    <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-0.5 font-semibold text-yellow-700 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400">
                                        Cuti
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-0.5 font-semibold text-gray-700 ring-1 ring-inset ring-gray-600/20 dark:bg-gray-500/10 dark:text-gray-400">
                                        Belum Terdata
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('bk.alumni-kuliah.show', $row->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Detail
                                    </a>
                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <a href="{{ route('bk.alumni-kuliah.edit', $row->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                        Ubah
                                    </a>
                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <form action="{{ route('bk.alumni-kuliah.destroy', $row->id) }}" method="POST" class="inline-block delete-form"
                                        data-name="{{ $row->nama_alumni }}">
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
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                Tidak ada data alumni kuliah ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Tambahkan ini di halaman alumni-kuliah index, sebelum </div> terakhir -->

    <!-- Import Excel Section -->
    <div class="mt-6 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
        <div class="p-5 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Import Data Alumni dari Excel</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Isi template Excel dan upload untuk menambah data alumni secara bulk</p>
        </div>

        <div class="p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Download Template -->
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-6 text-center hover:border-blue-400 transition">
                    <svg class="h-12 w-12 text-gray-400 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h4 class="font-semibold text-gray-800 dark:text-white mb-2">1. Download Template</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Download file Excel template kosong</p>
                    <a href="{{ route('bk.alumni-kuliah.download-template') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Template Excel
                    </a>
                </div>

                <!-- Upload File -->
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-6 hover:border-blue-400 transition">
                    <h4 class="font-semibold text-gray-800 dark:text-white mb-2">2. Upload File Excel</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Pilih file Excel yang sudah diisi data</p>

                    <form action="{{ route('bk.alumni-kuliah.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        <div class="space-y-3">
                            <div class="relative">
                                <input type="file" name="file" id="fileInput" accept=".xlsx,.xls" class="hidden" required>
                                <label for="fileInput" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg cursor-pointer transition">
                                    <svg class="h-4 w-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Pilih File Excel
                                </label>
                                <p id="fileName" class="text-xs text-gray-500 dark:text-gray-400 mt-2">Tidak ada file dipilih</p>
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition font-medium">
                                Upload & Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-700 rounded-lg">
                <p class="text-sm text-blue-800 dark:text-blue-300">
                    <strong>Informasi:</strong> File harus berformat .xlsx atau .xls, ukuran maksimal 5MB. Pastikan nama universitas dan status sudah sesuai dengan yang tersedia di sistem.
                </p>
            </div>
        </div>
    </div>

    <!-- Import Errors (jika ada) -->
    @if(session()->has('import_errors'))
        <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 dark:border-red-700 dark:bg-red-500/10 shadow-xs">
            <div class="p-5 border-b border-red-200 dark:border-red-700">
                <h3 class="text-lg font-bold text-red-800 dark:text-red-300">⚠️ Ada Kesalahan pada Import</h3>
            </div>
            <div class="p-5 max-h-64 overflow-y-auto">
                @foreach(session('import_errors') as $error)
                    <p class="text-sm text-red-700 dark:text-red-400 mb-2">• {{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Script untuk display nama file -->
    <script>
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Tidak ada file dipilih';
            document.getElementById('fileName').textContent = fileName;
        });

        // Form submission dengan loading state
        document.getElementById('importForm').addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.textContent = 'Sedang memproses...';
        });
    </script>
        </div>

        <!-- Pagination Links -->
        @if ($alumni->hasPages())
            <div class="border-t border-gray-100 p-5 dark:border-gray-800">
                {{ $alumni->links() }}
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
                text: `Anda akan menghapus data alumni "${name}". Tindakan ini tidak dapat dibatalkan!`,
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
