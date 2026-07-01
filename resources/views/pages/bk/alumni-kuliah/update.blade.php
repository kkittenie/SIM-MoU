@extends('layouts.app', ['title' => 'Edit Alumni Kuliah'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Edit Alumni Kuliah" />

    <div class="mx-auto max-w-2xl">
        <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="mb-6 text-2xl font-bold text-gray-800 dark:text-white">Edit Data Alumni Kuliah</h2>

            <form action="{{ route('bk.alumni-kuliah.update', $alumniKuliah->id) }}" method="POST">
                @csrf @method('PUT')

                {{-- Nama Alumni --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Alumni *</label>
                    <input type="text" name="nama_alumni" value="{{ old('nama_alumni', $alumniKuliah->nama_alumni) }}" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('nama_alumni') border-red-500 @enderror">
                    @error('nama_alumni') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- NIS --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIS (Nomor Induk Siswa) *</label>
                    <input type="text" name="nis" value="{{ old('nis', $alumniKuliah->nis) }}" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('nis') border-red-500 @enderror">
                    @error('nis') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Tahun Lulus --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Lulus *</label>
                    <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus', $alumniKuliah->tahun_lulus) }}" min="2000" max="{{ date('Y') }}" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('tahun_lulus') border-red-500 @enderror">
                    @error('tahun_lulus') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Universitas dengan info lokasi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Universitas *</label>
                    <select name="universitas_id" id="universitasSelect" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('universitas_id') border-red-500 @enderror">
                        <option value="">-- Pilih Universitas --</option>
                        @foreach($universitas as $u)
                            <option value="{{ $u->id }}" data-lokasi="{{ $u->lokasi_kuliah }}" {{ old('universitas_id', $alumniKuliah->universitas_id) == $u->id ? 'selected' : '' }}>
                                {{ $u->nama_universitas }}
                            </option>
                        @endforeach
                    </select>
                    @error('universitas_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                    <!-- Info Box untuk menampilkan lokasi universitas -->
                    <div id="infoBox" class="mt-2 p-3 rounded-lg bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-700 hidden">
                        <p class="text-sm text-blue-700 dark:text-blue-400">
                            <span class="font-semibold">Lokasi Universitas:</span>
                            <span id="lokasiText"></span>
                        </p>
                    </div>
                </div>

                {{-- Program Studi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Program Studi *</label>
                    <input type="text" name="program_studi" value="{{ old('program_studi', $alumniKuliah->program_studi) }}" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('program_studi') border-red-500 @enderror">
                    @error('program_studi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Cara Masuk --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cara Masuk <span class="text-gray-500 text-xs">(Opsional)</span></label>
                    <select name="cara_masuk" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('cara_masuk') border-red-500 @enderror">
                        <option value="">-- Pilih Cara Masuk --</option>
                        <option value="snbp" {{ old('cara_masuk', $alumniKuliah->cara_masuk) === 'snbp' ? 'selected' : '' }}>SNBP</option>
                        <option value="utbk" {{ old('cara_masuk', $alumniKuliah->cara_masuk) === 'utbk' ? 'selected' : '' }}>UTBK</option>
                        <option value="ujian_masuk" {{ old('cara_masuk', $alumniKuliah->cara_masuk) === 'ujian_masuk' ? 'selected' : '' }}>Ujian Masuk Mandiri</option>
                        <option value="beasiswa" {{ old('cara_masuk', $alumniKuliah->cara_masuk) === 'beasiswa' ? 'selected' : '' }}>Beasiswa</option>
                        <option value="transfer" {{ old('cara_masuk', $alumniKuliah->cara_masuk) === 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="lainnya" {{ old('cara_masuk', $alumniKuliah->cara_masuk) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('cara_masuk') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Email Alumni --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Alumni <span class="text-gray-500 text-xs">(Opsional)</span></label>
                    <input type="email" name="email_alumni" value="{{ old('email_alumni', $alumniKuliah->email_alumni) }}" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('email_alumni') border-red-500 @enderror">
                    @error('email_alumni') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Telepon <span class="text-gray-500 text-xs">(Opsional)</span></label>
                    <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon', $alumniKuliah->nomor_telepon) }}" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('nomor_telepon') border-red-500 @enderror">
                    @error('nomor_telepon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Status Alumni --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Alumni *</label>
                    <select name="status_alumni" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-white/5 @error('status_alumni') border-red-500 @enderror">
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif" {{ old('status_alumni', $alumniKuliah->status_alumni) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="lulus" {{ old('status_alumni', $alumniKuliah->status_alumni) === 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="cuti" {{ old('status_alumni', $alumniKuliah->status_alumni) === 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="belum_terdata" {{ old('status_alumni', $alumniKuliah->status_alumni) === 'belum_terdata' ? 'selected' : '' }}>Belum Terdata</option>
                    </select>
                    @error('status_alumni') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-lg bg-blue-600 px-6 py-2 font-medium text-white hover:bg-blue-700 transition">
                        Perbarui Alumni
                    </button>
                    <a href="{{ route('bk.alumni-kuliah.show', $alumniKuliah->id) }}" class="flex-1 rounded-lg border border-gray-300 px-6 py-2 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5 text-center transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('universitasSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const lokasi = selectedOption.getAttribute('data-lokasi');
            const infoBox = document.getElementById('infoBox');
            const lokasiText = document.getElementById('lokasiText');

            if (lokasi) {
                const lokasiLabel = lokasi === 'dalam_negeri' ? '🇮🇩 Dalam Negeri' : 'Luar Negeri';
                lokasiText.textContent = lokasiLabel;
                infoBox.classList.remove('hidden');
            } else {
                infoBox.classList.add('hidden');
            }
        });

        // Trigger on page load jika ada pilihan sebelumnya
        document.getElementById('universitasSelect').dispatchEvent(new Event('change'));
    </script>
@endsection
