@extends('layouts.app', ['title' => 'Tambah Perusahaan Mitra'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Tambah Perusahaan Mitra" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-2xl">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                Formulir Tambah Perusahaan Mitra Baru
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Lengkapi formulir di bawah ini untuk menambahkan mitra baru.
            </p>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-gray-800/25 dark:text-red-400" role="alert">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('perusahaan-mitra.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nama Perusahaan -->
                <div>
                    <label for="nama_perusahaan" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nama Perusahaan <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" placeholder="Masukkan nama perusahaan" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Bidang Industri -->
                <div>
                    <label for="bidang_industri" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Bidang Industri <span class="text-error-500">*</span>
                    </label>
                    <select id="bidang_industri" name="bidang_industri" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">-- Pilih Bidang Industri --</option>
                        <option value="Teknologi" {{ old('bidang_industri') === 'Teknologi' ? 'selected' : '' }}>Teknologi / IT</option>
                        <option value="Manufaktur" {{ old('bidang_industri') === 'Manufaktur' ? 'selected' : '' }}>Manufaktur</option>
                        <option value="Keuangan" {{ old('bidang_industri') === 'Keuangan' ? 'selected' : '' }}>Keuangan / Perbankan</option>
                        <option value="Kesehatan" {{ old('bidang_industri') === 'Kesehatan' ? 'selected' : '' }}>Kesehatan / Farmasi</option>
                        <option value="Telekomunikasi" {{ old('bidang_industri') === 'Telekomunikasi' ? 'selected' : '' }}>Telekomunikasi</option>
                        <option value="Otomotif" {{ old('bidang_industri') === 'Otomotif' ? 'selected' : '' }}>Otomotif</option>
                        <option value="Jasa" {{ old('bidang_industri') === 'Jasa' ? 'selected' : '' }}>Jasa / Agensi</option>
                        <option value="Lainnya" {{ old('bidang_industri') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <!-- Alamat -->
                <div>
                    <label for="alamat" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Alamat Perusahaan <span class="text-error-500">*</span>
                    </label>
                    <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap perusahaan" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('alamat') }}</textarea>
                </div>

                <!-- Grid untuk Email dan Telepon -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Email -->
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Email Instansi <span class="text-error-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="hrd@perusahaan.com" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label for="nomor_telepon" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nomor Telepon Kantor/PIC <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" placeholder="Masukkan nomor telepon" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>
                </div>

                <!-- Grid untuk PIC dan Website -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- PIC -->
                    <div>
                        <label for="pic" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama PIC (Person in Charge) <span class="text-error-500">*</span>
                        </label>
                        <input type="text" id="pic" name="pic" value="{{ old('pic') }}" placeholder="Masukkan nama kontak PIC" required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>

                    <!-- Website -->
                    <div>
                        <label for="website" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Website Perusahaan (Opsional)
                        </label>
                        <input type="url" id="website" name="website" value="{{ old('website') }}" placeholder="https://perusahaan.com"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Deskripsi Perusahaan (Opsional)
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi singkat tentang perusahaan"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('deskripsi') }}</textarea>
                </div>

                <!-- Status Aktif -->
                <div>
                    <label for="status_aktif" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Status Kerja Sama <span class="text-error-500">*</span>
                    </label>
                    <select id="status_aktif" name="status_aktif" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="Aktif" {{ old('status_aktif', 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status_aktif') === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                        Simpan
                    </button>
                    <a href="{{ route('perusahaan-mitra.index') }}"
                        class="border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
