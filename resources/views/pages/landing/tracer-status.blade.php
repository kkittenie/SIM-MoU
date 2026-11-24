{{-- Public notification page when an NIS has already submitted Tracer Study --}}
@extends('layouts.landing', ['title' => 'Status Pengisian Tracer Study'])

@push('styles')
<style>
    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }

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
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="reveal flex items-center gap-2 text-sm mb-6 text-gray-500 dark:text-gray-400">
            <a href="{{ url('/') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Beranda</a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('landing.tracer-study.verify') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Verifikasi NIS</a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="font-medium text-gray-700 dark:text-gray-200">Status Pengisian</span>
        </nav>

        <div class="reveal space-y-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-emerald-50 dark:bg-emerald-950">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Status Pengisian Tracer Study
            </h1>
            <p class="text-sm sm:text-base leading-relaxed max-w-xl text-gray-600 dark:text-gray-400">
                Informasi status pengisian formulir Tracer Study untuk Nomor Induk Siswa (NIS) Anda.
            </p>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════ -->
<!-- NOTIFICATION CARD SECTION -->
<!-- ════════════════════════════════════════════════ -->
<section class="py-10 lg:py-14 bg-white dark:bg-gray-900">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        @if (session('success'))
            <div class="reveal mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800 dark:border-emerald-900/80 dark:bg-emerald-950/60 dark:text-emerald-300 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="reveal">
            <div class="form-card-container">
                <!-- Card Header -->
                <div style="background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%); padding: 1.25rem 1.5rem;">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Pemberitahuan Tracer Study</h2>
                            <p class="text-sm mt-0.5 text-white/75">Data partisipasi alumni telah tercatat di sistem</p>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="form-card-body space-y-6">
                    <!-- Main Notice Banner -->
                    <div class="rounded-xl border border-blue-200 bg-blue-50/80 dark:border-blue-900/60 dark:bg-blue-950/40 p-4 flex items-start gap-3.5">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="space-y-1">
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                NIS ini sudah pernah mengisi formulir Tracer Study.
                            </p>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                Setiap alumni hanya dapat mengirimkan satu tanggapan Tracer Study. Terima kasih atas partisipasi dan kontribusi Anda bagi SMKN 1 Cirebon.
                            </p>
                        </div>
                    </div>

                    <!-- Registered Alumni Details -->
                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 divide-y divide-gray-100 dark:divide-gray-800 overflow-hidden">
                        <div class="px-5 py-3.5 bg-gray-50/70 dark:bg-gray-950/50 flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">NIS</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $alumni['nis'] }}</span>
                        </div>

                        <div class="px-5 py-3.5 bg-white dark:bg-gray-900 flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Nama Lengkap</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $alumni['nama_alumni'] }}</span>
                        </div>

                        <div class="px-5 py-3.5 bg-gray-50/70 dark:bg-gray-950/50 flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status Pengisian</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 w-fit">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Sudah Mengisi
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                        <a
                            href="{{ url('/') }}"
                            class="w-full sm:flex-1 bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-5 rounded-xl transition duration-200 text-center text-sm shadow-sm"
                        >
                            Kembali ke Beranda
                        </a>
                        <a
                            href="{{ route('landing.tracer-study.verify') }}"
                            class="w-full sm:w-auto text-center border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 font-semibold py-3 px-6 rounded-xl transition duration-200 text-sm"
                        >
                            Verifikasi NIS Lain
                        </a>
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
        }, { threshold: 0.1 });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });
</script>
@endpush
