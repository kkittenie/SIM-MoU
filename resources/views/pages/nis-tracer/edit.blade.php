@extends('layouts.app', ['title' => 'Ubah NIS Siswa'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Ubah NIS Siswa" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-2xl">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                Ubah Data NIS Siswa: {{ $student->nama_siswa }}
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Perbarui informasi Nomor Induk Siswa (NIS) dan nama lengkap siswa.
            </p>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('nis-tracer.update', $student->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- NIS -->
                <div>
                    <label for="nis" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        NIS (Nomor Induk Siswa) <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="nis" name="nis" value="{{ old('nis', $student->nis) }}" placeholder="Masukkan NIS siswa" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Nama Lengkap Siswa -->
                <div>
                    <label for="nama_siswa" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nama Lengkap Siswa <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa', $student->nama_siswa) }}" placeholder="Masukkan nama lengkap siswa" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Tahun Lulus (Angkatan) -->
                <div>
                    <label for="tahun_lulus" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Tahun Lulus (Angkatan)
                    </label>
                    <input type="number" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus', $student->tahun_lulus) }}" placeholder="Contoh: {{ date('Y') }}" min="1990" max="{{ date('Y') + 5 }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    <p class="mt-1 text-xs text-gray-400">Opsional, masukkan 4 digit tahun kelulusan siswa (contoh: 2024).</p>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-white transition">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('nis-tracer.index') }}"
                        class="border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
