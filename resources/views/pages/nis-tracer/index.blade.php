@extends('layouts.app', ['title' => 'Data NIS Tracer Study'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Data NIS Tracer Study" />

    <!-- Summary Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:gap-5 mb-6">
        <!-- Total Siswa Terdaftar -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                Total Siswa Terdaftar
            </span>
            <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90">
                {{ $totalStudents }}
            </h4>
        </div>

        <!-- Sudah Mengisi Tracer Study -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                Sudah Mengisi Tracer Study
            </span>
            <h4 class="mt-2 text-2xl font-extrabold text-green-600 dark:text-green-400">
                {{ $totalSubmitted }}
            </h4>
        </div>

        <!-- Belum Mengisi Tracer Study -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                Belum Mengisi Tracer Study
            </span>
            <h4 class="mt-2 text-2xl font-extrabold text-yellow-600 dark:text-yellow-400">
                {{ $totalNotSubmitted }}
            </h4>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
        <!-- Header & Filters -->
        <div class="p-5 border-b border-gray-100 dark:border-gray-800 space-y-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                        Daftar NIS Siswa Terdaftar
                    </h3>
                    @if (auth()->user()->isAdminJurusan())
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Jurusan: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $programKeahlians->first()?->nama ?? '-' }}</span>
                        </p>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Import Excel Button -->
                    <button type="button" onclick="openImportModal()"
                        class="bg-green-600 hover:bg-green-700 shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition whitespace-nowrap">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Import Excel
                    </button>

                    <!-- Add Student NIS Button -->
                    <a href="{{ route('nis-tracer.create') }}"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition whitespace-nowrap">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah NIS Siswa
                    </a>
                </div>
            </div>

            <!-- Filter Form -->
            <form action="{{ route('nis-tracer.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Real-time Search Input -->
                <x-form.pt-search-input
                    name="search"
                    :value="$search"
                    placeholder="Cari NIS atau nama siswa..."
                />

                <!-- Status Tracer Study Filter -->
                <div>
                    <select name="status_tracer" onchange="this.form.submit()"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Semua Siswa</option>
                        <option value="Sudah Mengisi" {{ $statusFilter === 'Sudah Mengisi' ? 'selected' : '' }}>Sudah Mengisi</option>
                        <option value="Belum Mengisi" {{ $statusFilter === 'Belum Mengisi' ? 'selected' : '' }}>Belum Mengisi</option>
                    </select>
                </div>

                <!-- Program Keahlian Filter (Admin Only) -->
                @if (auth()->user()->isAdmin())
                    <div>
                        <select name="program_keahlian_id" onchange="this.form.submit()"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">Semua Jurusan</option>
                            @foreach ($programKeahlians as $pk)
                                <option value="{{ $pk->id }}" {{ (string) $programKeahlianId === (string) $pk->id ? 'selected' : '' }}>
                                    {{ $pk->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Reset Button -->
                <div class="flex items-center">
                    @if ($search || $statusFilter || (auth()->user()->isAdmin() && $programKeahlianId))
                        <a href="{{ route('nis-tracer.index') }}"
                            class="bg-gray-100 hover:bg-gray-200 dark:bg-white/5 dark:hover:bg-white/10 h-10 text-gray-700 dark:text-white/95 rounded-lg px-4 py-2.5 text-sm font-medium transition inline-flex items-center justify-center w-full sm:w-auto whitespace-nowrap">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Content -->
        <div id="table-container">
            <div class="overflow-x-auto w-full">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4">NIS</th>
                            <th class="px-6 py-4">Nama Siswa</th>
                            <th class="px-6 py-4">Jurusan</th>
                            <th class="px-6 py-4">Status Tracer Study</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                        @forelse ($students as $student)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800 dark:text-white/95">
                                    {{ $student->nis }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800 dark:text-white/90">
                                    {{ $student->nama_siswa }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                    {{ $student->programKeahlian ? $student->programKeahlian->nama : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($student->has_submitted)
                                        <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                            Sudah Mengisi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2.5 py-0.5 text-xs font-semibold text-yellow-700 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400 dark:ring-yellow-500/20">
                                            Belum Mengisi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('nis-tracer.show', $student->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            Detail
                                        </a>
                                        <span class="text-gray-300 dark:text-gray-700">|</span>
                                        <a href="{{ route('nis-tracer.edit', $student->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                            Ubah
                                        </a>
                                        <span class="text-gray-300 dark:text-gray-700">|</span>
                                        <form action="{{ route('nis-tracer.destroy', $student->id) }}" method="POST" class="inline-block delete-form"
                                            data-name="{{ $student->nama_siswa }} ({{ $student->nis }})">
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
                            <tr class="empty-row">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                    Tidak ada data NIS siswa ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if ($students->hasPages())
                <div class="border-t border-gray-100 p-5 dark:border-gray-800">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Import Excel -->
    <div id="importModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-xs transition-opacity" onclick="closeImportModal()"></div>

        <!-- Modal Dialog -->
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900 border border-gray-100 dark:border-gray-800 transition-all z-10">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-base font-semibold text-gray-800 dark:text-white/90">
                            Import Data NIS Siswa
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Unggah file Excel untuk mendaftarkan NIS siswa secara massal
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeImportModal()"
                    class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Petunjuk Otomatis Ekstrak Kolom -->
            <div class="mt-4 rounded-xl bg-blue-50/80 p-3.5 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20">
                <div class="flex items-start gap-2.5">
                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-xs text-blue-800 dark:text-blue-300 space-y-1">
                        <p class="font-medium">Otomatis Ekstrak Kolom:</p>
                        <p>Jika file Excel Anda memiliki banyak kolom, sistem hanya akan mengambil kolom <strong>NIS</strong> dan <strong>Nama Siswa</strong>. Kolom lainnya akan otomatis diabaikan.</p>
                    </div>
                </div>
            </div>

            <!-- Form Upload -->
            <form action="{{ route('nis-tracer.import') }}" method="POST" enctype="multipart/form-data" id="importNisForm" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Pilih File Excel (.xlsx / .xls)
                    </label>
                    <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-5 text-center hover:border-brand-500 dark:hover:border-brand-500 transition cursor-pointer" onclick="document.getElementById('excelFileInput').click()">
                        <input type="file" name="file" id="excelFileInput" accept=".xlsx,.xls,.csv" class="hidden" required onchange="handleFileSelected(this)">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300" id="selectedFileName">
                                Klik untuk memilih file Excel
                            </span>
                            <span class="text-xs text-gray-400">
                                Format .xlsx atau .xls (Maksimal 10MB)
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                    <button type="button" onclick="closeImportModal()"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitImport"
                        class="bg-brand-500 hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-5 py-2.5 text-sm font-medium text-white transition shadow-theme-xs">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        <span id="btnSubmitText">Mulai Import</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openImportModal() {
        const modal = document.getElementById('importModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeImportModal() {
        const modal = document.getElementById('importModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function handleFileSelected(input) {
        const label = document.getElementById('selectedFileName');
        if (input.files && input.files.length > 0) {
            label.textContent = input.files[0].name;
            label.classList.add('text-brand-600', 'font-semibold');
        } else {
            label.textContent = 'Klik untuk memilih file Excel';
            label.classList.remove('text-brand-600', 'font-semibold');
        }
    }

    // Handle submit state
    document.getElementById('importNisForm')?.addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmitImport');
        const text = document.getElementById('btnSubmitText');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            if (text) text.textContent = 'Memproses...';
        }
    });

    document.addEventListener('submit', function(e) {
        const form = e.target.closest('.delete-form');
        if (!form) return;
        e.preventDefault();
        const name = form.getAttribute('data-name');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Anda akan menghapus data pendaftaran NIS "${name}". Tindakan ini tidak dapat dibatalkan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush
