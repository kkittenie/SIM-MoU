@extends('layouts.app', ['title' => 'Tambah Alumni Wirausaha'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Tambah Alumni Wirausaha" />

    <div class="mx-auto max-w-2xl">
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="mb-6 text-2xl font-bold text-gray-800 dark:text-white">Tambah Data Alumni Wirausaha</h2>

            <form action="{{ route('bk.alumni-wirausaha.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Nama Alumni --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Alumni *</label>
                    <input type="text" name="nama_alumni" value="{{ old('nama_alumni') }}" placeholder="e.g. Budi Santoso" required
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('nama_alumni') border-red-500 @enderror dark:text-white">
                    @error('nama_alumni') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Nama Usaha --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Usaha *</label>
                    <input type="text" name="nama_usaha" value="{{ old('nama_usaha') }}" placeholder="e.g. Toko Kelontong Berkah" required
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('nama_usaha') border-red-500 @enderror dark:text-white">
                    @error('nama_usaha') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Bidang Usaha --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bidang Usaha *</label>
                    <input type="text" name="bidang_usaha" value="{{ old('bidang_usaha') }}" placeholder="e.g. Kuliner, Teknologi, Perdagangan" required
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('bidang_usaha') border-red-500 @enderror dark:text-white">
                    @error('bidang_usaha') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Lama Usaha --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lama Usaha *</label>
                    <input type="text" name="lama_usaha" value="{{ old('lama_usaha') }}" placeholder="e.g. 1 Tahun, 6 Bulan" required
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('lama_usaha') border-red-500 @enderror dark:text-white">
                    @error('lama_usaha') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Tahun Lulus --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Lulus *</label>
                    <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus', date('Y')) }}" min="2000" max="{{ date('Y') + 5 }}" required
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('tahun_lulus') border-red-500 @enderror dark:text-white">
                    @error('tahun_lulus') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3 pt-6">
                    <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 font-medium text-white hover:bg-blue-700">
                        Simpan Alumni
                    </button>
                    <a href="{{ route('bk.alumni-wirausaha.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
