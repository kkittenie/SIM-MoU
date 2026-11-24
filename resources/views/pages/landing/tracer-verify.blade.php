{{-- Public NIS verification page for SMKN 1 Cirebon Tracer Study --}}
@extends('layouts.landing', ['title' => 'Verifikasi NIS — Tracer Study Alumni'])

@push('styles')
<style>
    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }

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
    .dark .landing-input {
        color: #f3f4f6; background-color: #111827; border-color: #374151;
    }
    .dark .landing-input::placeholder { color: #6b7280; }
    .dark .landing-input:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
    }

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
            <span class="font-medium text-gray-700 dark:text-gray-200">Verifikasi NIS</span>
        </nav>

        <div class="reveal space-y-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-blue-50 dark:bg-blue-950">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Verifikasi NIS
            </h1>
            <p class="text-sm sm:text-base leading-relaxed max-w-xl text-gray-600 dark:text-gray-400">
                Masukkan Nomor Induk Siswa (NIS) Anda semasa bersekolah di SMKN 1 Cirebon untuk melanjutkan pengisian formulir Tracer Study Alumni.
            </p>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════ -->
<!-- VERIFICATION FORM SECTION -->
<!-- ════════════════════════════════════════════════ -->
<section class="py-10 lg:py-14 bg-white dark:bg-gray-900">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Alert error validasi --}}
        @if ($errors->any())
            <div class="reveal mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700 dark:border-red-900/80 dark:bg-red-950/60 dark:text-red-400 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="font-semibold">Verifikasi Gagal</p>
                    <ul class="mt-1 list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="reveal">
            <div class="form-card-container">
                <!-- Card Header -->
                <div style="background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%); padding: 1.25rem 1.5rem;">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Cek Status & Identitas Alumni</h2>
                            <p class="text-sm mt-0.5 text-white/75">Setiap NIS hanya dapat mengisi formulir Tracer Study satu kali</p>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="form-card-body">
                    <form method="POST" action="{{ route('landing.tracer-study.verify.submit') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="nis" class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">
                                Nomor Induk Siswa (NIS) <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="nis"
                                name="nis"
                                value="{{ old('nis') }}"
                                required
                                autofocus
                                autocomplete="off"
                                class="landing-input @error('nis') !border-red-500 @enderror"
                                placeholder="Masukkan NIS Anda (contoh: NIS000030)"
                            >
                            <p class="text-xs mt-1.5 text-gray-400 dark:text-gray-500">
                                Pastikan NIS yang Anda masukkan sesuai dengan data kelulusan sekolah.
                            </p>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-800 pt-5 flex flex-col sm:flex-row items-center gap-3">
                            <button
                                type="submit"
                                class="w-full sm:flex-1 bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-5 rounded-xl transition duration-200 flex items-center justify-center gap-2 shadow-sm"
                            >
                                <span>Verifikasi NIS</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                            <a
                                href="{{ url('/') }}"
                                class="w-full sm:w-auto text-center border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 font-semibold py-3 px-6 rounded-xl transition duration-200 text-sm"
                            >
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
