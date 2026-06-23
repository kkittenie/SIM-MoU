@extends('layouts.app', ['title' => 'Tambah Alumni Kuliah'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Tambah Alumni Kuliah" />

    <div class="mx-auto max-w-2xl">
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="mb-6 text-2xl font-bold text-gray-800 dark:text-white">Tambah Data Alumni Kuliah</h2>

            <form action="{{ route('bk.alumni-kuliah.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Nama Alumni --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Alumni *</label>
                    <input type="text" name="nama_alumni" value="{{ old('nama_alumni') }}" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('nama_alumni') border-red-500 @enderror">
                    @error('nama_alumni') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- NIS --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIS (Nomor Induk Siswa) *</label>
                    <input type="text" name="nis" value="{{ old('nis') }}" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('nis') border-red-500 @enderror">
                    @error('nis') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Tahun Lulus --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Lulus *</label>
                    <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus') }}" min="2000" max="{{ date('Y') }}" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('tahun_lulus') border-red-500 @enderror">
                    @error('tahun_lulus') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Universitas --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Universitas *</label>
                    <select name="universitas_id" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('universitas_id') border-red-500 @enderror">
                        <option value="">-- Pilih Universitas --</option>
                        @foreach($universitas as $u)
                            <option value="{{ $u->id }}" {{ old('universitas_id') == $u->id ? 'selected' : '' }}>{{ $u->nama_universitas }}</option>
                        @endforeach
                    </select>
                    @error('universitas_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Program Studi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Program Studi *</label>
                    <input type="text" name="program_studi" value="{{ old('program_studi') }}" placeholder="e.g. Teknik Informatika" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('program_studi') border-red-500 @enderror">
                    @error('program_studi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Email Alumni --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Alumni</label>
                    <input type="email" name="email_alumni" value="{{ old('email_alumni') }}" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('email_alumni') border-red-500 @enderror">
                    @error('email_alumni') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Telepon</label>
                    <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon') }}" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('nomor_telepon') border-red-500 @enderror">
                    @error('nomor_telepon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Status Alumni --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Alumni *</label>
                    <select name="status_alumni" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('status_alumni') border-red-500 @enderror">
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif" {{ old('status_alumni') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="lulus" {{ old('status_alumni') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="cuti" {{ old('status_alumni') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="belum_terdata" {{ old('status_alumni') === 'belum_terdata' ? 'selected' : '' }}>Belum Terdata</option>
                    </select>
                    @error('status_alumni') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3 pt-6">
                    <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 font-medium text-white hover:bg-blue-700">
                        Simpan Alumni
                    </button>
                    <a href="{{ route('bk.alumni-kuliah.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
