@extends('layouts.app', ['title' => 'Pengaturan'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Pengaturan" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Settings Form -->
        <div class="lg:col-span-1">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                        Integrasi WhatsApp Fonnte
                    </h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Konfigurasi kredensial API Fonnte untuk mengirim notifikasi MoU ke WhatsApp PIC Mitra.
                    </p>
                </div>

                <div class="p-6">
                    <form action="{{ route('setting.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Active Toggle -->
                        <div class="flex items-center justify-between">
                            <span class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-800 dark:text-white/90">Aktifkan Notifikasi WhatsApp</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Kirim notifikasi ke nomor WhatsApp PIC Mitra jika aktif.</span>
                            </span>
                            <div x-data="{ toggle: {{ $settings->whatsapp_active ? 'true' : 'false' }} }">
                                <label for="whatsapp_active" class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="whatsapp_active" id="whatsapp_active" value="1" class="sr-only" x-model="toggle" />
                                    <div class="h-6 w-11 rounded-full transition-colors duration-205" 
                                        :class="toggle ? 'bg-brand-500' : 'bg-gray-200 dark:bg-gray-800'">
                                        <div class="h-5 w-5 rounded-full bg-white shadow-sm transition-transform duration-200 transform mt-0.5 ml-0.5" 
                                            :class="toggle ? 'translate-x-5' : 'translate-x-0'"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @error('whatsapp_active')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror

                        <!-- Fonnte Token -->
                        <div>
                            <label for="fonnte_token" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Token API Fonnte
                            </label>
                            <input type="password" id="fonnte_token" name="fonnte_token" value="{{ $settings->fonnte_token }}" placeholder="Masukkan token Fonnte Anda"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('fonnte_token')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-[11px] text-gray-400">Dapatkan token dari dashboard akun Fonnte Anda.</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                        Log Pengiriman WhatsApp
                    </h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Catatan riwayat notifikasi pesan yang dikirimkan menggunakan Fonnte.
                    </p>
                </div>

                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-4">Mitra</th>
                                <th class="px-6 py-4">Tipe</th>
                                <th class="px-6 py-4">Penerima</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                                    <td class="px-6 py-4 whitespace-normal font-medium text-gray-800 dark:text-white/90">
                                        @if($log->kerjaSama)
                                            <a href="{{ route('kerja-sama.show', $log->kerja_sama_id) }}" class="text-brand-600 hover:underline dark:text-brand-400">
                                                {{ $log->kerjaSama->nama_mitra }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Data Dihapus</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold">
                                        @if($log->type === 'expired' || $log->type === 'berakhir')
                                            <span class="text-red-500">Berakhir</span>
                                        @else
                                            <span class="text-amber-500">{{ ucfirst($log->type) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-mono">
                                        {{ $log->recipient }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($log->status === 'success')
                                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                                Sukses
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">
                                                Gagal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs">
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <details class="cursor-pointer text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            <summary class="outline-none">Lihat Respons</summary>
                                            <div class="mt-2 p-3 bg-gray-50 dark:bg-white/[0.02] border border-gray-150 dark:border-gray-800 rounded-md text-[11px] whitespace-pre-wrap max-w-xs overflow-x-auto leading-relaxed">
                                                @php
                                                    $responseText = $log->response;
                                                    $decoded = json_decode($log->response, true);
                                                    if (is_array($decoded)) {
                                                        if (isset($decoded['status']) && $decoded['status'] == false) {
                                                            $reason = $decoded['reason'] ?? '';
                                                            if ($reason === 'request invalid on disconnected device') {
                                                                $responseText = "Gagal mengirim pesan karena perangkat WhatsApp (HP) Anda terputus atau tidak terhubung (disconnected) pada dashboard Fonnte.";
                                                            } elseif ($reason === 'credential not found' || str_contains(strtolower($reason), 'token') || str_contains(strtolower($reason), 'credential')) {
                                                                $responseText = "Token API Fonnte tidak ditemukan atau tidak valid. Silakan periksa kembali pengaturan token Anda.";
                                                            } elseif ($reason === 'target invalid') {
                                                                $responseText = "Nomor tujuan WhatsApp tidak terdaftar atau format nomor salah.";
                                                            } else {
                                                                $responseText = "Gagal: " . ucfirst($reason);
                                                            }
                                                        } elseif (isset($decoded['status']) && $decoded['status'] == true) {
                                                            $responseText = "Pesan berhasil terkirim dan masuk antrean Fonnte.";
                                                        }
                                                    }
                                                @endphp
                                                {{ $responseText }}
                                            </div>
                                        </details>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                        Belum ada riwayat pengiriman WhatsApp.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                @if ($logs->hasPages())
                    <div class="border-t border-gray-100 p-5 dark:border-gray-800">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <!-- Manage Categories Row -->
    <div class="mt-6 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                Kelola Kategori Jenis Mitra
            </h3>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Tambah atau hapus kategori jenis mitra yang dapat dipilih pada dokumen kerja sama.
            </p>
        </div>
        
        <div class="p-6 grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Add Category Form -->
            <div class="md:col-span-1">
                <form action="{{ route('setting.kategori.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="nama" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Kategori Baru
                        </label>
                        <input type="text" id="nama" name="nama" placeholder="Contoh: Yayasan, Lembaga Swadaya" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('nama')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="w-full bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        Tambah Kategori
                    </button>
                </form>
            </div>

            <!-- Categories List Table -->
            <div class="md:col-span-2 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400 font-medium">
                    <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Nama Kategori</th>
                            <th class="px-6 py-3">Jumlah Mitra</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                        @forelse ($kategoriMitra as $index => $kategori)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-800 dark:text-white/90">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-850 dark:text-white/90 font-semibold">
                                    {{ $kategori->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                    {{ $kategori->kerjaSama->count() }} mitra
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                    <form action="{{ route('setting.kategori.destroy', $kategori->id) }}" method="POST" class="inline-block delete-kategori-form" 
                                        data-name="{{ $kategori->nama }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-400 dark:text-gray-500">
                                    Belum ada kategori kustom.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Manage Program Keahlian Row -->
    <div class="mt-6 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                Kelola Program Keahlian
            </h3>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Tambah atau hapus program keahlian yang dapat dipilih pada dokumen kerja sama.
            </p>
        </div>
        
        <div class="p-6 grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Add Program Form -->
            <div class="md:col-span-1">
                <form action="{{ route('setting.program-keahlian.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="nama_program" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Program Keahlian
                        </label>
                        <input type="text" id="nama_program" name="nama" placeholder="Contoh: Rekayasa Perangkat Lunak" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('nama')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="singkatan_program" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Singkatan (Opsional)
                        </label>
                        <input type="text" id="singkatan_program" name="singkatan" placeholder="Contoh: RPL"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('singkatan')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="w-full bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        Tambah Program
                    </button>
                </form>
            </div>

            <!-- Program List Table -->
            <div class="md:col-span-2 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400 font-medium">
                    <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Nama Program</th>
                            <th class="px-6 py-3">Singkatan</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                        @forelse ($programKeahlian as $index => $program)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-800 dark:text-white/90">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-850 dark:text-white/90 font-semibold">
                                    {{ $program->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                    {{ $program->singkatan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                    <form action="{{ route('setting.program-keahlian.destroy', $program->id) }}" method="POST" class="inline-block delete-program-form" 
                                        data-name="{{ $program->nama }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-400 dark:text-gray-500">
                                    Belum ada program keahlian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
@if(auth()->user()->isAdmin())
<script>
    document.querySelectorAll('.delete-kategori-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const name = this.getAttribute('data-name');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus kategori jenis mitra "${name}". Kerja sama yang merujuk kategori ini akan dikosongkan kategorinya.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#3b82f6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl dark:bg-gray-900 border dark:border-gray-800'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    document.querySelectorAll('.delete-program-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const name = this.getAttribute('data-name');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus program keahlian "${name}". Kerja sama yang merujuk program ini akan dikosongkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#3b82f6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl dark:bg-gray-900 border dark:border-gray-800'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endif
@endpush
