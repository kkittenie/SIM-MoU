{{-- Design Read: Public all job vacancies list page with search and pagination,
     using the same premium layout of the landing page. --}}
@extends('layouts.landing', ['title' => 'Lowongan Kerja'])

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
    .landing-input::placeholder {
        color: #9ca3af;
    }
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
    .dark .landing-input::placeholder {
        color: #6b7280;
    }
    .dark .landing-input:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
    }

    /* ── Premium Card ── */
    .premium-card {
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                    border-color 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                    background-color 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1.5rem;
    }
    .premium-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
        border-color: rgba(37, 99, 235, 0.25);
    }
    .dark .premium-card {
        background-color: #111827;
        border-color: #1f2937;
    }
    .dark .premium-card:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        border-color: rgba(37, 99, 235, 0.4);
    }
</style>
@endpush

@section('content')

<!-- ════════════════════════════════════════════════ -->
<!-- HEADER & SEARCH BAR -->
<!-- ════════════════════════════════════════════════ -->
<section class="pt-28 pb-10 lg:pb-12 bg-gray-50 dark:bg-gray-950 border-b border-gray-200/50 dark:border-gray-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="reveal flex items-center gap-2 text-sm mb-6 text-gray-500 dark:text-gray-400">
            <a href="{{ url('/') }}" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Beranda</a>
            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="font-medium text-gray-700 dark:text-gray-200">Lowongan Kerja</span>
        </nav>

        <div class="reveal flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                  Lowongan Kerja
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Cari dan temukan peluang karir terbaik yang disediakan oleh BKK SMKN 1 Cirebon.
                </p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('landing.lowongan.index') }}" method="GET" class="w-full md:max-w-md flex gap-2">
                <!-- Search Input with Real-time PT Suggestions -->
                <x-form.pt-search-input 
                    name="search" 
                    :value="$search" 
                    placeholder="Cari posisi, judul, atau perusahaan..." 
                    :suggestions="$ptSuggestions ?? []" 
                    variant="landing"
                />
                <button type="submit"
                    class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-5 py-3 rounded-xl shadow-md transition duration-200 text-sm flex items-center gap-1.5 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Cari</span>
                </button>
            </form>
        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════ -->
<!-- JOBS LIST SECTION -->
<!-- ════════════════════════════════════════════════ -->
<section class="py-12 lg:py-16 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="table-container">
        
        <!-- Search Results Tag -->
        @if($search)
            <div class="reveal mb-8 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                <p>Menampilkan hasil pencarian untuk "<span class="font-semibold text-gray-900 dark:text-white">{{ $search }}</span>"</p>
                <a href="{{ route('landing.lowongan.index') }}" class="text-brand-600 hover:text-brand-700 font-semibold flex items-center gap-1">
                    <span>Bersihkan Pencarian</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            </div>
        @endif

        <!-- Grid Cards Lowongan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($lowongans as $job)
                <div class="reveal premium-card flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full bg-brand-5 text-brand-600 dark:bg-brand-950 dark:text-brand-400">
                                {{ $job->posisi ?: 'Umum' }}
                            </span>
                            <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full bg-orange-50 text-orange-600 dark:bg-orange-950/30 dark:text-orange-400">
                                Dibutuhkan: {{ $job->kuota }} Orang
                            </span>
                            <span class="text-xs text-gray-400 font-medium">
                                s.d. {{ $job->tanggal_tutup ? $job->tanggal_tutup->format('d M Y') : 'Selesai' }}
                            </span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-1">{{ $job->judul }}</h3>
                            <p class="text-xs font-semibold text-brand-600 dark:text-brand-400">{{ $job->nama_perusahaan }}</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-3 leading-relaxed">
                            {{ $job->deskripsi ?: 'Persyaratan berkas serta jadwal wawancara terperinci dapat ditanyakan langsung di kantor BKK SMKN 1 Cirebon.' }}
                        </p>
                    </div>
                    <div class="pt-4 mt-6 border-t border-gray-100 dark:border-gray-800/60 flex items-center justify-between text-xs">
                        <span class="text-gray-400 font-medium">Gaji: <span class="text-gray-700 dark:text-gray-300 font-semibold">{{ $job->gaji ?: 'Negosiasi' }}</span></span>
                        <a href="{{ route('landing.lowongan.detail', $job->id) }}" class="font-bold text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 flex items-center gap-1">
                            <span>Detail</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <!-- Empty Placeholder -->
                <div class="reveal col-span-full bg-gray-50 dark:bg-gray-950 rounded-2xl p-12 text-center border border-dashed border-gray-200 dark:border-gray-800">
                    <div class="w-12 h-12 rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m0 5v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2m14 0V9a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 22h6"/></svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Informasi Lowongan Tidak Ditemukan</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                        Maaf, tidak ada lowongan kerja aktif yang sesuai dengan kriteria pencarian Anda saat ini.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="reveal mt-12 flex justify-center">
            {{ $lowongans->links() }}
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
