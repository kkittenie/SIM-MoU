{{-- Design Read: Landing page for SMKN 1 Cirebon BKK & Tracer Study, with a modern clean-tech educational portal aesthetic, leaning toward Tailwind v4 utility styles + FlexStart isometric assets + smooth intersection observers for entrance animations. --}}
@extends('layouts.landing', ['title' => 'Bursa Kerja Khusus & Tracer Study Alumni'])

@push('styles')
<style>
    /* ── Scroll Reveal System (Anti-Slop Restrained Animations) ── */
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

    /* ── Hero Custom Graphic float ── */
    @keyframes isometricFloat {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-10px) scale(1.01); }
    }
    .isometric-float {
        animation: isometricFloat 6s ease-in-out infinite;
    }

    /* ── Counter Scale Accent ── */
    .counter-value {
        font-feature-settings: "tnum";
    }

    /* ── Custom Button Shadows & Materiality ── */
    .btn-premium-shadow {
        box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.2);
    }
    .btn-premium-shadow:hover {
        box-shadow: 0 6px 20px 0 rgba(37, 99, 235, 0.3);
    }

    /* ── Premium Card Hover Transitions ── */
    .premium-card {
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                    border-color 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                    background-color 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .premium-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
        border-color: rgba(37, 99, 235, 0.25);
    }
    .dark .premium-card:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        border-color: rgba(37, 99, 235, 0.4);
    }

    /* ── Hero Entrance Animations (Professional & Elegant) ── */
    @keyframes heroFadeInUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes heroImageEntrance {
        0% {
            opacity: 0;
            transform: scale(0.97) translateY(12px);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    .hero-animate-fade-up {
        opacity: 0;
        animation: heroFadeInUp 1.0s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .hero-animate-image {
        opacity: 0;
        animation: heroImageEntrance 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
@endpush

@section('content')

<!-- ════════════════════════════════════════════════ -->
<!-- HERO SECTION (Clean, Split Screen, Isometric) -->
<!-- ════════════════════════════════════════════════ -->
<section id="hero" class="relative min-h-[90dvh] flex items-center overflow-hidden pt-12 pb-20 lg:py-24">
    <!-- Wavy Background Shape from FlexStart Assets -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none opacity-[0.06] dark:opacity-[0.03] -z-10" 
         style="background-image: url('{{ asset('images/flexstart/hero-bg.png') }}');"></div>
    
    <!-- Accent Blur Blobs -->
    <div class="absolute top-1/4 left-1/3 w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left: Text Column (Restrained Hierarchy) -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <!-- Headline (Max 2 lines, outfit style display) -->
                <h1 class="hero-animate-fade-up text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-none" style="animation-delay: 100ms;">
                    Portal Karir & Penelusuran <span class="bg-gradient-to-r from-brand-600 to-indigo-600 bg-clip-text text-transparent">Alumni SMKN 1 Cirebon</span>
                </h1>

                <!-- Subtitle (Restrained to 20 words body, clean reading) -->
                <p class="hero-animate-fade-up text-base sm:text-lg text-gray-600 dark:text-gray-300 leading-relaxed max-w-2xl mx-auto lg:mx-0" style="animation-delay: 250ms;">
                    Temukan lowongan kerja terbaik dari perusahaan mitra sekolah dan bantu almamater melalui formulir Tracer Study Alumni.
                </p>

                <!-- Action Buttons (High contrast, WCAG compliant) -->
                <div class="hero-animate-fade-up flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2" style="animation-delay: 400ms;">
                    <a href="#lowongan" class="w-full sm:w-auto bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-4 rounded-xl btn-premium-shadow hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2.5">
                        <span>Lihat Lowongan Kerja</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </a>
                    <a href="#tracer-study" class="w-full sm:w-auto bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-800 dark:text-gray-200 font-semibold px-6 py-4 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Isi Tracer Study</span>
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right: Real FlexStart Isometric Asset -->
            <div class="lg:col-span-5 relative flex justify-center hero-animate-image" style="animation-delay: 200ms;">
                <div class="relative max-w-md lg:max-w-none w-full">
                    <!-- Subtle ambient aura behind image -->
                    <div class="absolute -inset-4 bg-gradient-to-tr from-brand-600/10 to-indigo-600/10 rounded-full blur-2xl pointer-events-none"></div>
                    <!-- Isometric Image -->
                    <img src="{{ asset('images/flexstart/hero-img.png') }}" 
                         alt="OneLink Ilustrasi" 
                         class="relative w-full h-auto object-contain isometric-float select-none pointer-events-none">
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════ -->
<!-- STATISTIK RINGKAS -->
<!-- ════════════════════════════════════════════════ -->
<section class="py-12 bg-white dark:bg-gray-900 border-y border-gray-200/50 dark:border-gray-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 gap-8 text-center" x-data="counterSection()" x-intersect.once="startCount()">
            
            <!-- Stat 1 -->
            <div class="space-y-1">
                <div class="text-4xl lg:text-5xl font-extrabold text-brand-600 dark:text-brand-400 counter-value" x-text="counts.lowongan">0</div>
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lowongan Kerja Aktif</div>
            </div>

            <!-- Stat 2 -->
            <div class="space-y-1">
                <div class="text-4xl lg:text-5xl font-extrabold text-brand-600 dark:text-brand-400 counter-value" x-text="counts.mitra">0</div>
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Perusahaan Mitra</div>
            </div>

            <!-- Stat 3 -->
            <div class="space-y-1">
                <div class="text-4xl lg:text-5xl font-extrabold text-brand-600 dark:text-brand-400 counter-value" x-text="counts.alumni">0</div>
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alumni Terdata</div>
            </div>

        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════ -->
<!-- KEUNGGULAN / VALUE CARDS (Professional Layout) -->
<!-- ════════════════════════════════════════════════ -->
<section id="keunggulan" class="py-20 lg:py-28 bg-gray-50 dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header (No Eyebrow - Keep Clean) -->
        <div class="reveal text-center max-w-3xl mx-auto mb-16 space-y-4">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Layanan Karir & Penelusuran Alumni
            </h2>
            <p class="text-base text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                Platform resmi SMKN 1 Cirebon untuk menyalurkan lulusan ke dunia kerja dan melacak jejak karir alumni secara transparan.
            </p>
        </div>

        <!-- 2-Column Visual Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">

            <!-- Value Card 1: BKK -->
            <div class="reveal premium-card bg-white dark:bg-gray-900 rounded-2xl p-8 border border-gray-200/60 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                <div class="space-y-6">
                    <div class="w-12 h-12 rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Bursa Kerja Khusus (BKK)</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            Akses lowongan pekerjaan yang diverifikasi langsung oleh sekolah untuk menyalurkan alumni ke mitra industri tepercaya.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Value Card 2: Tracer Study -->
            <div class="reveal premium-card bg-white dark:bg-gray-900 rounded-2xl p-8 border border-gray-200/60 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                <div class="space-y-6">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tracer Study Alumni (BK)</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            Penelusuran status alumni setelah lulus (bekerja, kuliah, wirausaha) demi akreditasi sekolah dan sinkronisasi kurikulum industri.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════ -->
<!-- DETAILED FEATURES SECTION -->
<!-- ════════════════════════════════════════════════ -->
<section class="py-20 lg:py-28 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-24 lg:space-y-36">

        <!-- Feature 1: BKK / Lowongan Kerja (Left Text, Right Image) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center reveal">
            <div class="lg:col-span-6 space-y-6">
                <div class="w-10 h-10 rounded-lg bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white leading-tight">
                    Penyaluran Kerja Terverifikasi dari Perusahaan Mitra
                </h3>
                <p class="text-base text-gray-600 dark:text-gray-400 leading-relaxed">
                    Setiap lowongan kerja yang ditampilkan telah diverifikasi langsung oleh Tim BKK SMKN 1 Cirebon. Alumni dapat melamar posisi dari perusahaan mitra industri tepercaya dengan proses yang transparan dan aman.
                </p>
                <div class="pt-2">
                    <a href="#lowongan" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-300">
                        <span>Lihat Lowongan Kerja Terbaru</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            <!-- Isometric Feature Asset -->
            <div class="lg:col-span-6 flex justify-center">
                <img src="{{ asset('images/flexstart/features.png') }}" 
                     alt="Fitur Bursa Kerja Khusus" 
                     class="max-w-md w-full h-auto object-contain select-none pointer-events-none">
            </div>
        </div>

        <!-- Feature 2: Tracer Study (Left Image, Right Text) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center reveal">
            <!-- Isometric Feature Asset -->
            <div class="lg:col-span-6 flex justify-center order-last lg:order-first">
                <img src="{{ asset('images/flexstart/alt-features.png') }}" 
                     alt="Fitur Tracer Study Alumni" 
                     class="max-w-md w-full h-auto object-contain select-none pointer-events-none">
            </div>
            <div class="lg:col-span-6 space-y-6">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
                <h3 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white leading-tight">
                    Analisis Jejak Karir untuk Pengambilan Keputusan Kurikulum
                </h3>
                <p class="text-base text-gray-600 dark:text-gray-400 leading-relaxed">
                    Data alumni yang kuliah, bekerja, atau berwirausaha dihimpun untuk menyusun laporan evaluasi resmi. Informasi ini membantu sekolah memetakan kebutuhan industri demi meningkatkan daya serap lulusan di pasar kerja global.
                </p>
                <div class="pt-2">
                    <a href="#tracer-study" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-300">
                        <span>Lihat Portal Tracer Study</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>


<!-- ════════════════════════════════════════════════ -->
<!-- SECTION LOWONGAN KERJA TERBARU -->
<!-- ════════════════════════════════════════════════ -->
<section id="lowongan" class="py-20 lg:py-28 bg-gray-50 dark:bg-gray-950 border-t border-gray-200/50 dark:border-gray-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="reveal flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-brand-600 dark:text-brand-400 uppercase tracking-widest">Bursa Kerja Khusus</span>
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Lowongan Kerja Terbaru</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Kesempatan berkarir resmi langsung dari perusahaan mitra sekolah.</p>
            </div>
            <div>
                <span class="text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900 px-4 py-2.5 rounded-xl border border-gray-200/80 dark:border-gray-800 inline-block font-medium">
                    Diperbarui secara berkala oleh Tim BKK
                </span>
            </div>
        </div>

        <!-- Grid Cards Lowongan (Restrained Card Layout) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($lowonganTerbaru as $job)
                <div class="reveal premium-card bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200/60 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full bg-brand-50 text-brand-600 dark:bg-brand-950 dark:text-brand-400">
                                {{ $job->posisi ?: 'Umum' }}
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
                <div class="reveal col-span-full bg-white dark:bg-gray-900 rounded-2xl p-12 text-center border border-dashed border-gray-200 dark:border-gray-800">
                    <div class="w-12 h-12 rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m0 5v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2m14 0V9a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 22h6"/></svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Informasi Lowongan Sedang Disiapkan</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                        Tim BKK sedang memproses pembukaan posisi magang dan kerja dari perusahaan mitra. Lowongan kerja terbaru akan diperbarui langsung di halaman ini.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- View All Button -->
        <div class="reveal flex justify-center mt-12">
            <a href="{{ route('landing.lowongan.index') }}" 
               class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-8 py-4 rounded-xl shadow-md hover:-translate-y-0.5 transition-all duration-200 text-sm flex items-center gap-2">
                <span>Lihat Semua Lowongan Kerja</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7m-7-7H3"/></svg>
            </a>
        </div>

    </div>
</section>


<!-- ════════════════════════════════════════════════ -->
<!-- SECTION TRACER STUDY ALUMNI -->
<!-- ════════════════════════════════════════════════ -->
<section id="tracer-study" class="py-20 lg:py-28 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Premium CTA Card (Non-Slop, Balanced Colors) -->
        <div class="reveal relative bg-gradient-to-r from-brand-600 to-indigo-700 rounded-2xl p-8 sm:p-12 text-white shadow-xl overflow-hidden mb-16">
            <div class="absolute -right-8 -bottom-8 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-12 -top-12 w-60 h-60 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 max-w-3xl space-y-4">
                <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-xs font-semibold uppercase tracking-wider">
                    Khusus Alumni SMKN 1 Cirebon
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                    Mari Isi Tracer Study Lulusan
                </h2>
                <p class="text-white/80 text-sm sm:text-base leading-relaxed">
                    Bantu almamater memvalidasi kurikulum pengajaran dan melacak ke mana alumni berkarya. Kontribusi Anda sangat berharga bagi kualitas pembelajaran masa depan.
                </p>
                <div class="pt-2">
                    <a href="{{ route('landing.tracer-study') }}" class="inline-flex bg-white hover:bg-gray-50 text-brand-600 font-bold px-6 py-3.5 rounded-xl shadow-md hover:-translate-y-0.5 transition-all duration-200 text-sm items-center gap-2">
                        <span>Formulir Tracer Study Alumni</span>
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- 3 Category Stat Panels (Restrained Layouts) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Category 1 -->
            <div class="reveal premium-card bg-gray-50 dark:bg-gray-950 rounded-2xl p-8 border border-gray-200/60 dark:border-gray-800/80 shadow-sm flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>Alumni Bekerja</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Data penyerapan lulusan di dunia usaha, industri komersial, maupun instansi pemerintahan.
                    </p>
                </div>
                <div class="mt-8 pt-4 border-t border-gray-200/50 dark:border-gray-800/50 text-sm font-semibold text-brand-600 dark:text-brand-400">
                    Terdata: {{ $totalAlumniBekerja }} Lulusan
                </div>
            </div>

            <!-- Category 2 -->
            <div class="reveal premium-card bg-gray-50 dark:bg-gray-950 rounded-2xl p-8 border border-gray-200/60 dark:border-gray-800/80 shadow-sm flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>Alumni Kuliah</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Lulusan yang melanjutkan jenjang pendidikan tinggi baik di perguruan tinggi negeri maupun swasta.
                    </p>
                </div>
                <div class="mt-8 pt-4 border-t border-gray-200/50 dark:border-gray-800/50 text-sm font-semibold text-brand-600 dark:text-brand-400">
                    Terdata: {{ $totalAlumniKuliah }} Lulusan
                </div>
            </div>

            <!-- Category 3 -->
            <div class="reveal premium-card bg-gray-50 dark:bg-gray-950 rounded-2xl p-8 border border-gray-200/60 dark:border-gray-800/80 shadow-sm flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>Alumni Wirausaha</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Lulusan yang merintis bisnis mandiri, start-up, UMKM, maupun usaha kreatif independen.
                    </p>
                </div>
                <div class="mt-8 pt-4 border-t border-gray-200/50 dark:border-gray-800/50 text-sm font-semibold text-brand-600 dark:text-brand-400">
                    Terdata: {{ $totalAlumniWirausaha }} Lulusan
                </div>
            </div>

        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
    // ── Simple Intersection Observer for Clean Scroll Reveals ──
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

    // ── Simple Count-up animation for counters ──
    function counterSection() {
        return {
            counts: { mitra: 0, lowongan: 0, alumni: 0 },
            targets: {
                mitra: {{ $totalMitra }},
                lowongan: {{ $totalLowongan }},
                alumni: {{ $totalAlumni }}
            },
            startCount() {
                Object.keys(this.targets).forEach(key => {
                    this.animateValue(key, 0, this.targets[key], 1200);
                });
            },
            animateValue(key, start, end, duration) {
                if (start === end) return;
                let range = end - start;
                let current = start;
                let increment = end > start ? 1 : -1;
                let stepTime = Math.abs(Math.floor(duration / range));
                stepTime = Math.max(stepTime, 15); // limit min delay
                
                let timer = setInterval(() => {
                    if (range > 60) {
                        current += Math.ceil(range / 30);
                    } else {
                        current += increment;
                    }
                    if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                        this.counts[key] = end;
                        clearInterval(timer);
                    } else {
                        this.counts[key] = current;
                    }
                }, stepTime);
            }
        }
    }
</script>
@endpush
