{{-- Design Read: Public tracer study form page for SMKN 1 Cirebon alumni,
     with a centered single-column form layout on the landing layout. --}}
@extends('layouts.landing', ['title' => 'Formulir Tracer Study Alumni'])

@push('styles')
<style>
    /* ── Scroll Reveal ── */
    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    /* ── Form Inputs (explicit styles to override Tailwind v4 Preflight) ── */
    .landing-input {
        display: block; width: 100%;
        padding: 0.75rem 1rem; font-size: 0.875rem; line-height: 1.5;
        color: #111827; background-color: #ffffff;
        border: 1px solid #d1d5db; border-radius: 0.75rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        -webkit-appearance: none; appearance: none;
    }
    .landing-input::placeholder { color: #9ca3af; }
    .landing-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }
    /* ── Fix date input (biarin native, jangan appearance:none) ── */
    input[type="date"].landing-input {
        box-sizing: border-box;
        min-height: 2.75rem;
        width: 100%;
        -webkit-appearance: auto;
        appearance: auto;
        color-scheme: light;
    }
    .dark input[type="date"].landing-input {
        color-scheme: dark;
    }
    .dark .landing-input {
        color: #f3f4f6; background-color: #111827; border-color: #374151;
    }
    .dark .landing-input::placeholder { color: #6b7280; }
    .dark .landing-input:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
    }

    /* ── Custom Select Arrow ── */
    .landing-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat; background-size: 1.25em 1.25em;
        padding-right: 2.5rem;
    }
    .dark .landing-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    }

    /* ── Textarea ── */
    .landing-textarea { resize: vertical; min-height: 100px; }

    /* ── Form Card ── */
    .form-card-container {
        border-radius: 1rem; overflow: hidden;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.04), 0 2px 4px -2px rgba(0,0,0,0.04);
    }
    .dark .form-card-container {
        border-color: #1f2937;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3), 0 2px 4px -2px rgba(0,0,0,0.2);
    }
    .form-card-body { background-color: #ffffff; padding: 1.5rem; }
    @media (min-width: 640px) { .form-card-body { padding: 2rem; } }
    .dark .form-card-body { background-color: rgba(255, 255, 255, 0.02); }
</style>
@endpush

@section('content')

<!-- ════════════════════════════════════════════════ -->
<!-- HEADER -->
<!-- ════════════════════════════════════════════════ -->
<section class="pt-28 pb-10 lg:pb-12 bg-gray-50 dark:bg-gray-950 border-b border-gray-200/50 dark:border-gray-800/50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="reveal flex items-center gap-2 text-sm mb-6 text-gray-500 dark:text-gray-400">
            <a href="{{ url('/') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Beranda</a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="font-medium text-gray-700 dark:text-gray-200">Tracer Study</span>
        </nav>

        <div class="reveal space-y-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-blue-50 dark:bg-blue-950">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Formulir Tracer Study Alumni
            </h1>
            <p class="text-sm sm:text-base leading-relaxed max-w-xl text-gray-600 dark:text-gray-400">
                Bantu almamater mengevaluasi kurikulum dan memetakan karir lulusan. Data Anda bersifat rahasia dan digunakan untuk keperluan akademik.
            </p>
        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════ -->
<!-- FORM SECTION -->
<!-- ════════════════════════════════════════════════ -->
<section class="py-10 lg:py-14 bg-white dark:bg-gray-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Alert error validasi --}}
        @if ($errors->any())
            <div class="reveal mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-400">
                <p class="font-semibold mb-1">Periksa kembali data Anda:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="reveal">
            <div class="form-card-container">

                <!-- Form Header -->
                <div style="background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%); padding: 1.25rem 1.5rem;">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Data Alumni</h2>
                            <p class="text-sm mt-0.5 text-white/70">Isi formulir di bawah ini dengan data Anda</p>
                        </div>
                    </div>
                </div>

                <!-- Form Body -->
                <div class="form-card-body">
                    <form class="space-y-5" x-data="tracerForm()" method="POST" action="{{ route('landing.tracer-study.submit') }}">
                    @csrf

                        <!-- Nama Alumni -->
                        <div>
                            <label for="nama_alumni" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_alumni" name="nama_alumni" value="{{ old('nama_alumni') }}" required
                                class="landing-input"
                                placeholder="Masukkan nama lengkap Anda">
                        </div>

                        <!-- Tahun Lulus -->
                        <div>
                            <label for="tahun_lulus" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                Tahun Lulus <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus') }}" min="1990" max="{{ date('Y') }}" required
                                class="landing-input"
                                placeholder="{{ date('Y') }}">
                            <p class="text-xs mt-1 text-gray-400 dark:text-gray-500">Tahun kelulusan dari SMKN 1 Cirebon</p>
                        </div>

                        <!-- Jurusan -->
                        <div>
                            <label for="program_keahlian" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                Jurusan <span class="text-red-500">*</span>
                            </label>
                            <select id="program_keahlian" name="program_keahlian" required
                                class="landing-input landing-select">
                                <option value="" disabled selected>Pilih Jurusan</option>
                                @foreach($programKeahlians as $pk)
                                    <option value="{{ $pk->nama }}" @selected(old('program_keahlian') == $pk->nama)>{{ $pk->nama }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs mt-1 text-gray-400 dark:text-gray-500">Program keahlian/jurusan Anda sewaktu bersekolah</p>
                        </div>

                        <!-- Kelas -->
                        <div>
                            <label for="kelas" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                Kelas <span class="text-xs font-normal text-gray-400 dark:text-gray-500">(Opsional)</span>
                            </label>
                            <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}"
                                class="landing-input"
                                placeholder="">
                            <p class="text-xs mt-1 text-gray-400 dark:text-gray-500">Kelas terakhir Anda saat bersekolah di SMKN 1 Cirebon</p>
                        </div>

                        <!-- No WhatsApp -->
                        <div>
                            <label for="no_wa" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                No. WhatsApp <span class="text-xs font-normal text-gray-400 dark:text-gray-500">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                </div>
                                <input type="tel" id="no_wa" name="no_wa" value="{{ old('no_wa') }}"
                                    class="landing-input pl-9"
                                    placeholder="">
                            </div>
                            <p class="text-xs mt-1 text-gray-400 dark:text-gray-500">Nomor WhatsApp aktif untuk keperluan tindak lanjut</p>
                        </div>

                        <!-- Status Alumni -->
                        <div>
                            <label for="status_alumni" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                Status Saat Ini <span class="text-red-500">*</span>
                            </label>
                            <select id="status_alumni" name="status_alumni" required x-model="status"
                                class="landing-input landing-select">
                                <option value="" disabled selected>Pilih status Anda saat ini</option>
                                <option value="Bekerja">Bekerja</option>
                                <option value="Kuliah">Melanjutkan Kuliah</option>
                                <option value="Wirausaha">Wirausaha / Usaha Mandiri</option>
                                <option value="Mencari Kerja">Sedang Mencari Kerja</option>
                            </select>
                        </div>

                        <!-- Detail Status (Dynamic based on selection) -->
                        <div x-show="status && status !== 'Mencari Kerja'" x-transition.opacity.duration.300ms>
                            <label for="detail_status" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                <span x-text="detailLabel"></span> <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="detail_status" name="detail_status" value="{{ old('detail_status') }}"
                                class="landing-input"
                                :placeholder="detailPlaceholder">
                            <p class="text-xs mt-1 text-gray-400 dark:text-gray-500" x-text="detailHelper"></p>
                        </div>

                        <!-- ══ Field khusus KULIAH ══ -->
                        <div x-show="status === 'Kuliah'" x-transition.opacity.duration.300ms class="space-y-5">
                            <div>
                                <label for="nis" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                    NIS (Nomor Induk Siswa) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nis" name="nis" value="{{ old('nis') }}"
                                    class="landing-input"
                                    placeholder="contoh: 2021100123">
                                <p class="text-xs mt-1 text-gray-400 dark:text-gray-500">NIS Anda semasa sekolah di SMKN 1 Cirebon</p>
                            </div>

                            <div>
                                <label for="program_studi" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                    Program Studi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="program_studi" name="program_studi" value="{{ old('program_studi') }}"
                                    class="landing-input"
                                    placeholder="contoh: Teknik Informatika">
                                <p class="text-xs mt-1 text-gray-400 dark:text-gray-500">Jurusan/program studi yang Anda tempuh di perguruan tinggi</p>
                            </div>

                            <div>
                                <label for="cara_masuk" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                    Jalur Masuk <span class="text-red-500">*</span>
                                </label>
                                <select id="cara_masuk" name="cara_masuk"
                                    class="landing-input landing-select">
                                    <option value="" disabled selected>Pilih jalur masuk</option>
                                    <option value="snbp" @selected(old('cara_masuk') == 'snbp')>SNBP</option>
                                    <option value="utbk" @selected(old('cara_masuk') == 'utbk')>SNBT / UTBK</option>
                                    <option value="ujian_masuk" @selected(old('cara_masuk') == 'ujian_masuk')>Ujian Mandiri</option>
                                    <option value="beasiswa" @selected(old('cara_masuk') == 'beasiswa')>Beasiswa</option>
                                    <option value="transfer" @selected(old('cara_masuk') == 'transfer')>Transfer</option>
                                    <option value="lainnya" @selected(old('cara_masuk') == 'lainnya')>Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label for="status_kuliah" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                    Status Perkuliahan <span class="text-red-500">*</span>
                                </label>
                                <select id="status_kuliah" name="status_kuliah"
                                    class="landing-input landing-select">
                                    <option value="" disabled selected>Pilih status perkuliahan</option>
                                    <option value="aktif" @selected(old('status_kuliah') == 'aktif')>Masih Aktif Kuliah</option>
                                    <option value="lulus" @selected(old('status_kuliah') == 'lulus')>Sudah Lulus</option>
                                    <option value="cuti" @selected(old('status_kuliah') == 'cuti')>Sedang Cuti</option>
                                    <option value="belum_terdata" @selected(old('status_kuliah') == 'belum_terdata')>Belum Terdata</option>
                                </select>
                            </div>

                        </div>

                        <!-- ══ Field khusus BEKERJA ══ -->
                        <div x-show="status === 'Bekerja'" x-transition.opacity.duration.300ms class="space-y-5">
                            <div>
                                <label for="jabatan" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                    Jabatan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan') }}"
                                    class="landing-input"
                                    placeholder="contoh: Staff IT">
                            </div>

                            <div>
                                <label for="bidang_industri" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                    Bidang Industri <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="bidang_industri" name="bidang_industri" value="{{ old('bidang_industri') }}"
                                    class="landing-input"
                                    placeholder="contoh: Teknologi Informasi">
                            </div>

                            <div>
                                <label for="lokasi_kerja" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                    Lokasi Kerja <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="lokasi_kerja" name="lokasi_kerja" value="{{ old('lokasi_kerja') }}"
                                    class="landing-input"
                                    placeholder="contoh: Jakarta Selatan, DKI Jakarta">
                            </div>

                            <div>
                                <label for="status_pekerjaan" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                    Status Kepegawaian <span class="text-red-500">*</span>
                                </label>
                                <select id="status_pekerjaan" name="status_pekerjaan"
                                    class="landing-input landing-select">
                                    <option value="" disabled selected>Pilih status kepegawaian</option>
                                    <option value="Tetap" @selected(old('status_pekerjaan') == 'Tetap')>Karyawan Tetap</option>
                                    <option value="Kontrak" @selected(old('status_pekerjaan') == 'Kontrak')>Kontrak</option>
                                    <option value="Magang" @selected(old('status_pekerjaan') == 'Magang')>Magang</option>
                                </select>
                            </div>

                            <div>
                                <label for="tanggal_masuk" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                    Tanggal Mulai Bekerja <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" max="{{ date('Y-m-d') }}"
                                    class="landing-input">
                            </div>
                        </div>

                        <!-- ══ Field khusus WIRAUSAHA ══ -->
                        <div x-show="status === 'Wirausaha'" x-transition.opacity.duration.300ms class="space-y-5">
                            <div>
                                <label for="bidang_usaha" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                    Bidang Usaha <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="bidang_usaha" name="bidang_usaha" value="{{ old('bidang_usaha') }}"
                                    class="landing-input"
                                    placeholder="contoh: Kuliner, Fashion, Jasa Desain">
                            </div>

                            <div>
                                <label for="lama_usaha" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                    Lama Usaha Berjalan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="lama_usaha" name="lama_usaha" value="{{ old('lama_usaha') }}"
                                    class="landing-input"
                                    placeholder="contoh: 1 Tahun, 6 Bulan">
                            </div>
                        </div>

                        <!-- Testimoni -->
                        <div>
                            <label for="testimoni" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">
                                Kesan & Pesan untuk Almamater
                            </label>
                            <textarea id="testimoni" name="testimoni" rows="4"
                                class="landing-input landing-textarea"
                                placeholder="Bagikan pengalaman Anda setelah lulus dari SMKN 1 Cirebon...">{{ old('testimoni') }}</textarea>
                            <p class="text-xs mt-1 text-gray-400 dark:text-gray-500">Opsional. Testimoni Anda dapat membantu calon lulusan mempersiapkan karir mereka.</p>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 dark:border-gray-700"></div>

                        <!-- Submit / Back -->
                        <div class="flex flex-col sm:flex-row items-center gap-3">
                            <button type="submit"
                                class="w-full sm:flex-1 bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 flex items-center justify-center gap-2">
                                <span>Kirim Data Tracer Study</span>
                            </button>
                            <a href="{{ url('/') }}"
                                class="w-full sm:w-auto text-center border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 font-semibold py-3 px-6 rounded-xl transition duration-200 text-sm">
                                Kembali ke Beranda
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // ── Scroll Reveal ──
    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });

    // ── Alpine.js Tracer Form Logic ──
    function tracerForm() {
        return {
            status: '{{ old('status_alumni', '') }}',
            get detailLabel() {
                const labels = {
                    'Bekerja': 'Nama Perusahaan / Instansi',
                    'Kuliah': 'Nama Perguruan Tinggi',
                    'Wirausaha': 'Nama Usaha',
                };
                return labels[this.status] || 'Detail';
            },
            get detailPlaceholder() {
                const placeholders = {
                    'Bekerja': 'contoh: PT Telkom Indonesia',
                    'Kuliah': 'contoh: Universitas Indonesia',
                    'Wirausaha': 'contoh: Toko Online "Berkah Jaya"',
                };
                return placeholders[this.status] || '';
            },
            get detailHelper() {
                const helpers = {
                    'Bekerja': 'Tempat Anda bekerja saat ini',
                    'Kuliah': 'Kampus yang sedang Anda tempuh',
                    'Wirausaha': 'Nama bisnis atau usaha yang Anda jalankan',
                };
                return helpers[this.status] || '';
            },
        }
    }
</script>
@endpush
