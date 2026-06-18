@extends('layouts.app', ['title' => 'Kelola Notifikasi'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Kelola Notifikasi" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
        <!-- Table Header Actions -->
        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-gray-800">
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Notifikasi Sistem
                </h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Pemberitahuan otomatis terkait masa berlaku MoU dan kerja sama.
                </p>
            </div>

            @if($notifications->where('is_read', false)->count() > 0)
                <form action="{{ route('notifikasi.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" 
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto w-full">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Notifikasi</th>
                        <th class="px-6 py-4">Kemitraan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                    @forelse ($notifications as $notif)
                        @php
                            $isExpired = $notif->type === 'expired';
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors {{ !$notif->is_read ? 'bg-brand-50/30 dark:bg-brand-950/10' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isExpired)
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">
                                        Expired
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-500/20">
                                        {{ str_replace('warning_', '', $notif->type) }} Hari
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800 dark:text-white/95">
                                    {{ $notif->title }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $notif->message }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-normal font-medium text-brand-600 dark:text-brand-400">
                                @if($notif->kerjaSama)
                                    <a href="{{ route('kerja-sama.show', $notif->kerja_sama_id) }}" class="hover:underline">
                                        {{ $notif->kerjaSama->nama_mitra }}
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">Data Dihapus</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($notif->is_read)
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2.5 py-0.5 text-xs font-semibold text-gray-600 ring-1 ring-inset ring-gray-500/10 dark:bg-gray-800 dark:text-gray-400">
                                        Dibaca
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400">
                                        Baru
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                {{ $notif->created_at->format('d/m/Y H:i') }}
                                <div class="text-[10px] text-gray-400 mt-0.5">
                                    {{ $notif->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$notif->is_read)
                                        <form action="{{ route('notifikasi.read', $notif->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-brand-600 hover:text-brand-900 dark:text-brand-400 dark:hover:text-brand-300">
                                                Tandai Dibaca
                                            </button>
                                        </form>
                                        <span class="text-gray-300 dark:text-gray-700">|</span>
                                    @endif
                                    <form action="{{ route('notifikasi.destroy', $notif->id) }}" method="POST" class="inline delete-form" data-name="notifikasi #{{ $notif->id }}">
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
                                Tidak ada notifikasi saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        @if ($notifications->hasPages())
            <div class="border-t border-gray-100 p-5 dark:border-gray-800">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Notifikasi ini akan dihapus secara permanen!',
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
