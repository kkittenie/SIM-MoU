{{-- Design Read: Public job vacancy detail page for SMKN 1 Cirebon alumni and job seekers,
     with a clean split layout (left: detail, right: apply form), using the landing layout. --}}
@extends('layouts.landing', ['title' => $lowongan->judul . ' — Lowongan Kerja'])

@push('styles')
<style>
    /* ── Scroll Reveal ── */
    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ── Form Inputs (explicit styles to override Tailwind v4 Preflight) ── */
    .landing-input {
        display: block;
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        line-height: 1.5;
        color: #111827;
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 0.75rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        -webkit-appearance: none;
        appearance: none;
    }
    .landing-input::placeholder { color: #9ca3af; }
    .landing-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }
    .dark .landing-input {
        color: #f3f4f6;
        background-color: #111827;
        border-color: #374151;
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
        background-repeat: no-repeat;
        background-size: 1.25em 1.25em;
        padding-right: 2.5rem;
    }
    .dark .landing-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    }

    /* ── Textarea ── */
    .landing-textarea { resize: vertical; min-height: 100px; }

    /* ── File Input ── */
    .landing-file-input { cursor: pointer; }
    .landing-file-input::file-selector-button {
        padding: 0.375rem 0.75rem; margin-right: 0.75rem; font-size: 0.75rem;
        font-weight: 600; color: #2563eb; background-color: #eff6ff;
        border: none; border-radius: 0.5rem; cursor: pointer;
        transition: background-color 0.15s ease;
    }
    .landing-file-input::file-selector-button:hover { background-color: #dbeafe; }
    .dark .landing-file-input::file-selector-button {
        color: #93c5fd; background-color: rgba(37, 99, 235, 0.1);
    }

    /* ── Detail Card ── */
    .detail-card {
        background-color: #ffffff; border: 1px solid #e5e7eb;
        border-radius: 1rem; padding: 1.5rem;
    }
    .dark .detail-card {
        background-color: rgba(255, 255, 255, 0.03); border-color: #1f2937;
    }

    /* ── Info Pill ── */
    .info-pill {
        background-color: #f9fafb; border: 1px solid #e5e7eb;
        border-radius: 0.75rem; padding: 0.625rem 1rem;
    }
    .dark .info-pill { background-color: #111827; border-color: #1f2937; }

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
    .dark .form-card-body { background-color: rgba(255,255,255,0.02); }
</style>
@endpush

@section('content')

<!-- ════════════════════════════════════════════════ -->
<!-- LOWONGAN DETAIL HEADER -->
<!-- ════════════════════════════════════════════════ -->
<section class="pt-28 pb-10 lg:pb-12 bg-gray-50 dark:bg-gray-950 border-b border-gray-200/50 dark:border-gray-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="reveal flex items-center gap-2 text-sm mb-8 text-gray-500 dark:text-gray-400">
            <a href="{{ url('/') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Beranda</a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ url('/#lowongan') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Lowongan Kerja</a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="font-medium truncate max-w-[200px] text-gray-700 dark:text-gray-200">{{ $lowongan->judul }}</span>
        </nav>

        <!-- Job Header Card -->
        <div class="reveal detail-card" style="padding: 2rem;">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Left: Job Info -->
                <div class="space-y-3 flex-1 min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-600 dark:bg-blue-950 dark:text-blue-400">
                            {{ $lowongan->posisi ?: 'Umum' }}
                        </span>
                        @if($lowongan->status === 'Aktif')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700 dark:bg-green-950 dark:text-green-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Lowongan Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Lowongan Ditutup
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        {{ $lowongan->judul }}
                    </h1>
                    <p class="text-base font-semibold text-brand-600 dark:text-brand-400">
                        {{ $lowongan->nama_perusahaan }}
                    </p>
                </div>

                <!-- Right: Meta Info -->
                <div class="flex flex-wrap gap-3 shrink-0">
                    @if($lowongan->gaji)
                    <div class="info-pill">
                        <span class="text-xs block text-gray-400 dark:text-gray-500">Rentang Gaji</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $lowongan->gaji }}</span>
                    </div>
                    @endif
                    <div class="info-pill">
                        <span class="text-xs block text-gray-400 dark:text-gray-500">Batas Lamaran</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ $lowongan->tanggal_tutup ? $lowongan->tanggal_tutup->format('d M Y') : 'Tanpa batas' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════ -->
<!-- MAIN CONTENT: Detail + Apply Form -->
<!-- ════════════════════════════════════════════════ -->
<section class="py-10 lg:py-14 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">

            <!-- LEFT: Job Details (7/12) -->
            <div class="lg:col-span-7 space-y-6">

                <!-- Persyaratan -->
                <div class="reveal">
                    <h2 class="text-base font-bold mb-3 flex items-center gap-2 text-gray-900 dark:text-white">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 bg-blue-50 dark:bg-blue-950">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </span>
                        Persyaratan
                    </h2>
                    <div class="detail-card">
                        <div class="text-sm leading-relaxed text-gray-600 dark:text-gray-300">
                            {!! nl2br(e($lowongan->persyaratan)) !!}
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                @if($lowongan->deskripsi)
                <div class="reveal">
                    <h2 class="text-base font-bold mb-3 flex items-center gap-2 text-gray-900 dark:text-white">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 bg-blue-50 dark:bg-blue-950">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        Deskripsi Pekerjaan
                    </h2>
                    <div class="detail-card">
                        <div class="text-sm leading-relaxed text-gray-600 dark:text-gray-300">
                            {!! nl2br(e($lowongan->deskripsi)) !!}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Company Info -->
                @if($lowongan->perusahaanMitra)
                <div class="reveal">
                    <h2 class="text-base font-bold mb-3 flex items-center gap-2 text-gray-900 dark:text-white">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 bg-blue-50 dark:bg-blue-950">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </span>
                        Tentang Perusahaan
                    </h2>
                    <div class="detail-card">
                        <h3 class="text-base font-bold mb-3 text-gray-900 dark:text-white">{{ $lowongan->perusahaanMitra->nama_perusahaan }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-xs block mb-0.5 text-gray-400 dark:text-gray-500">Bidang Industri</span>
                                <span class="font-medium text-gray-700 dark:text-gray-200">{{ $lowongan->perusahaanMitra->bidang_industri }}</span>
                            </div>
                            <div>
                                <span class="text-xs block mb-0.5 text-gray-400 dark:text-gray-500">Person in Charge</span>
                                <span class="font-medium text-gray-700 dark:text-gray-200">{{ $lowongan->perusahaanMitra->pic }}</span>
                            </div>
                            @if($lowongan->perusahaanMitra->alamat)
                            <div class="sm:col-span-2">
                                <span class="text-xs block mb-0.5 text-gray-400 dark:text-gray-500">Alamat</span>
                                <span class="font-medium text-gray-700 dark:text-gray-200">{{ $lowongan->perusahaanMitra->alamat }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Back button -->
                <div class="reveal pt-2">
                    <a href="{{ url('/#lowongan') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-brand-600 dark:hover:text-brand-400 hover:gap-3 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali ke Daftar Lowongan
                    </a>
                </div>

            </div>

            <!-- RIGHT: Application Form (5/12) -->
            <div class="lg:col-span-5">
                <div class="reveal lg:sticky lg:top-24">
                    <div class="form-card-container">
                        <!-- Form Header -->
                        <div style="background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%); padding: 1.25rem 1.5rem;">
                            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Lamar Posisi Ini
                            </h2>
                            <p class="text-sm mt-1 text-white/70">Lengkapi data berikut untuk mendaftar</p>
                        </div>

                        <!-- Form Body -->
                        <div class="form-card-body">
                            <form class="space-y-4" x-data="{ submitting: false }">
                                @csrf

                                <!-- Nama Lengkap -->
                                <div>
                                    <label for="nama_lengkap" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" id="nama_lengkap" name="nama_lengkap" required
                                        class="landing-input"
                                        placeholder="Masukkan nama lengkap Anda">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">Alamat Email <span class="text-red-500">*</span></label>
                                    <input type="email" id="email" name="email" required
                                        class="landing-input"
                                        placeholder="contoh@email.com">
                                </div>

                                <!-- No. Telepon -->
                                <div>
                                    <label for="nomor_telepon" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">No. Telepon / WhatsApp <span class="text-red-500">*</span></label>
                                    <input type="tel" id="nomor_telepon" name="nomor_telepon" required
                                        class="landing-input"
                                        placeholder="08xxxxxxxxxx">
                                </div>

                                <!-- Tahun Lulus & Jurusan -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="tahun_lulus" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">Tahun Lulus <span class="text-red-500">*</span></label>
                                        <input type="number" id="tahun_lulus" name="tahun_lulus" min="2000" max="{{ date('Y') }}" required
                                            class="landing-input"
                                            placeholder="{{ date('Y') }}">
                                    </div>
                                    <div>
                                        <label for="program_keahlian" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">Jurusan <span class="text-red-500">*</span></label>
                                        <select id="program_keahlian" name="program_keahlian" required
                                            class="landing-input landing-select">
                                            <option value="" disabled selected>Pilih Jurusan</option>
                                            @foreach($programKeahlians as $pk)
                                                <option value="{{ $pk->nama }}">{{ $pk->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Surat Lamaran -->
                                <div>
                                    <label for="surat_lamaran" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">Pesan / Surat Lamaran <span class="text-red-500">*</span></label>
                                    <textarea id="surat_lamaran" name="surat_lamaran" rows="4" required
                                        class="landing-input landing-textarea"
                                        placeholder="Ceritakan mengapa Anda tertarik dengan posisi ini..."></textarea>
                                </div>

                                <!-- Upload CV -->
                                <div>
                                    <label for="file_cv" class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-200">Upload CV (PDF)</label>
                                    <input type="file" id="file_cv" name="file_cv" accept=".pdf"
                                        class="landing-input landing-file-input">
                                    <p class="text-xs mt-1 text-gray-400 dark:text-gray-500">Maks. 2MB. Format: PDF</p>
                                </div>

                                <!-- Divider -->
                                <div class="border-t border-gray-200 dark:border-gray-700"></div>

                                <!-- Submit Button -->
                                <button type="submit"
                                    class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 flex items-center justify-center gap-2">
                                    <span>Kirim Lamaran</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
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
</script>
@endpush
