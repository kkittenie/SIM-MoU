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

                <!-- Add Student NIS Button -->
                <a href="{{ route('nis-tracer.create') }}"
                    class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition whitespace-nowrap">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah NIS Siswa
                </a>
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
@endsection

@push('scripts')
<script>
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
