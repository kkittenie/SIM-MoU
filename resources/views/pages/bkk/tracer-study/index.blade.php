@extends('layouts.app', ['title' => 'Tracer Study Bekerja'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Tracer Study Bekerja" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
        <!-- Table Header Actions -->
        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-gray-800">
            <!-- Search & Filters -->
            <form action="{{ route('bkk.tracer-study.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:max-w-xl">
                <!-- Search input -->
                <div class="relative flex-1">
                    <span class="absolute -translate-y-1/2 pointer-events-none left-4 top-1/2">
                        <svg class="fill-gray-400 dark:fill-gray-500" width="18" height="18" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama alumni atau detail..."
                        class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-10 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
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

            <!-- Add Button -->
            <a href="{{ route('bkk.tracer-study.create') }}"
                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition whitespace-nowrap">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Tracer Bekerja
            </a>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                        </th>
                        <th class="px-6 py-4">Nama Alumni</th>
                        <th class="px-6 py-4">Tahun Lulus</th>
                        <th class="px-6 py-4">Status Alumni</th>
                        <th class="px-6 py-4">Detail Status</th>
                        <th class="px-6 py-4">Testimoni</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                    @forelse ($tracers as $row)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="tracer-checkbox rounded border-gray-300" value="{{ $row->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800 dark:text-white/95">
                                {{ $row->nama_alumni }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 dark:text-gray-300">
                                {{ $row->tahun_lulus }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-xs">
                                @if($row->status_alumni === 'Bekerja')
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400">
                                        Bekerja
                                    </span>
                                @elseif($row->status_alumni === 'Kuliah')
                                    <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-0.5 font-semibold text-purple-700 ring-1 ring-inset ring-purple-600/20 dark:bg-purple-500/10 dark:text-purple-400">
                                        Kuliah
                                    </span>
                                @elseif($row->status_alumni === 'Wirausaha')
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 font-semibold text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400">
                                        Wirausaha
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-0.5 font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-400">
                                        Mencari Kerja
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                {{ $row->detail_status ?? '-' }}
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate">
                                {{ $row->testimoni ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                <a href="{{ route('bkk.tracer-study.edit', $row->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                    Ubah
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                Tidak ada tanggapan Tracer Study ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Bulk Delete Button -->
        <div class="border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-800 dark:bg-white/5">
            <button id="bulkDeleteBtn" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 disabled:opacity-50" disabled>
                Hapus Dipilih
            </button>
        </div>

        <!-- Pagination Links -->
        @if ($tracers->hasPages())
            <div class="border-t border-gray-100 p-5 dark:border-gray-800">
                {{ $tracers->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.tracer-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    updateBulkDeleteBtn();
});

document.querySelectorAll('.tracer-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkDeleteBtn);
});

function updateBulkDeleteBtn() {
    const checked = document.querySelectorAll('.tracer-checkbox:checked');
    const btn = document.getElementById('bulkDeleteBtn');
    btn.disabled = checked.length === 0;
}

document.getElementById('bulkDeleteBtn').addEventListener('click', function() {
    const ids = Array.from(document.querySelectorAll('.tracer-checkbox:checked')).map(cb => cb.value);

    if (ids.length === 0) {
        Swal.fire({
            title: 'Perhatian!',
            text: 'Pilih minimal 1 data untuk dihapus',
            icon: 'info',
            confirmButtonColor: '#3085d6'
        });
        return;
    }

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Anda akan menghapus ${ids.length} data Tracer Study. Tindakan ini tidak dapat dibatalkan!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("bkk.tracer-study.bulk-delete") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ ids })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghapus data',
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
            });
        }
    });
});
</script>
@endpush
