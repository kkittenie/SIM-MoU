<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'OneLink' }} | Bursa Kerja & Alumni SMKN 1 Cirebon</title>
    <meta name="description" content="OneLink — Sistem Informasi Bursa Kerja Khusus (BKK) & Tracer Study Alumni SMK Negeri 1 Cirebon.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')
    @stack('styles')

    <!-- Entrance & Animation Styles -->
    <style>
        @keyframes heroFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .animate-float {
            animation: heroFloat 5s ease-in-out infinite;
        }

        /* ── Dynamic Mouse Glow overlay (Dark Mode Only) ── */
        @media (hover: hover) {
            #cursor-glow {
                background: radial-gradient(
                    500px circle at var(--mouse-x, 0px) var(--mouse-y, 0px),
                    rgba(37, 99, 235, 0.07),
                    transparent 80%
                );
            }
        }
    </style>

    <!-- Theme Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        body.classList.add('dark', 'bg-gray-950');
                    } else {
                        html.classList.remove('dark');
                        body.classList.remove('dark', 'bg-gray-950');
                    }
                }
            });
        });
    </script>

    <!-- Apply dark mode immediately -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>

<body class="bg-gray-50 text-gray-800 antialiased dark:bg-gray-950 dark:text-gray-100 flex flex-col min-h-screen selection:bg-brand-500 selection:text-white">

    <!-- Cursor Follower Light Overlay (Dark Mode Only) -->
    <div class="pointer-events-none fixed inset-0 z-30 opacity-0 dark:opacity-100 transition-opacity duration-500" id="cursor-glow"></div>

    <!-- ════════════════════════════════════════════════ -->
    <!-- HEADER NAVBAR (Sticky Glassmorphic) -->
    <!-- ════════════════════════════════════════════════ -->
    <header x-data="{ mobileMenuOpen: false, scrolled: false }" 
            @scroll.window="scrolled = (window.pageYOffset > 20)"
            :class="{ 'bg-white/80 dark:bg-gray-900/80 backdrop-blur-md shadow-sm border-b border-gray-200/50 dark:border-gray-800/50 py-3.5': scrolled, 'bg-transparent py-5': !scrolled }"
            class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <!-- Brand Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white shadow-md shadow-brand-500/20 group-hover:scale-105 transition-transform duration-200">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="text-lg font-extrabold tracking-tight text-gray-900 dark:text-white leading-none group-hover:text-brand-600 transition-colors">
                            OneLink
                        </span>
                        <span class="text-[9px] font-semibold text-gray-400 dark:text-gray-500 tracking-wider uppercase mt-0.5">
                            Powered by SMK Negeri 1 Cirebon
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-8 font-medium text-sm text-gray-600 dark:text-gray-300">
                    <a href="{{ url('/#hero') }}" class="hover:text-brand-500 dark:hover:text-brand-400 transition-colors">Beranda</a>
                    <a href="{{ url('/#keunggulan') }}" class="hover:text-brand-500 dark:hover:text-brand-400 transition-colors">Layanan</a>
                    <a href="{{ url('/#lowongan') }}" class="hover:text-brand-500 dark:hover:text-brand-400 transition-colors">Lowongan Kerja</a>
                    <a href="{{ url('/#tracer-study') }}" class="hover:text-brand-500 dark:hover:text-brand-400 transition-colors">Tracer Study</a>
                    <a href="{{ url('/#kontak') }}" class="hover:text-brand-500 dark:hover:text-brand-400 transition-colors">Kontak</a>
                </nav>

                <!-- Actions: Theme Toggle & Login Button -->
                <div class="hidden md:flex items-center gap-3">
                    <!-- Theme Switcher -->
                    <button @click="$store.theme.toggle()" 
                        type="button" 
                        class="p-2 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg x-show="$store.theme.theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <svg x-show="$store.theme.theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </button>

                    @auth
                        <a href="{{ url('/dashboard') }}" 
                            class="bg-brand-500 hover:bg-brand-600 text-white font-medium text-sm px-5 py-2.5 rounded-xl shadow-md shadow-brand-500/20 hover:shadow-brand-500/30 transition-all flex items-center gap-2">
                            <span>Dashboard</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                            class="bg-brand-500 hover:bg-brand-600 text-white font-medium text-sm px-5 py-2.5 rounded-xl shadow-md shadow-brand-500/20 hover:shadow-brand-500/30 transition-all flex items-center gap-2">
                            <span>Masuk / Login</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center gap-2 md:hidden">
                    <button @click="$store.theme.toggle()" type="button" class="p-2 text-gray-500 dark:text-gray-400">
                        <svg x-show="$store.theme.theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <svg x-show="$store.theme.theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </button>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 text-gray-600 dark:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileMenuOpen" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="md:hidden mt-4 bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-xl border border-gray-100 dark:border-gray-800 space-y-4">
                <a @click="mobileMenuOpen = false" href="{{ url('/#hero') }}" class="block font-medium text-gray-700 dark:text-gray-200 hover:text-brand-500 py-1">Beranda</a>
                <a @click="mobileMenuOpen = false" href="{{ url('/#keunggulan') }}" class="block font-medium text-gray-700 dark:text-gray-200 hover:text-brand-500 py-1">Layanan</a>
                <a @click="mobileMenuOpen = false" href="{{ url('/#lowongan') }}" class="block font-medium text-gray-700 dark:text-gray-200 hover:text-brand-500 py-1">Lowongan Kerja</a>
                <a @click="mobileMenuOpen = false" href="{{ url('/#tracer-study') }}" class="block font-medium text-gray-700 dark:text-gray-200 hover:text-brand-500 py-1">Tracer Study</a>
                <a @click="mobileMenuOpen = false" href="{{ url('/#kontak') }}" class="block font-medium text-gray-700 dark:text-gray-200 hover:text-brand-500 py-1">Kontak</a>
                <div class="pt-2 border-t border-gray-100 dark:border-gray-800">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-medium py-2.5 rounded-xl text-center block shadow-md">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-medium py-2.5 rounded-xl text-center block shadow-md">Masuk / Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-1 pt-24">
        @yield('content')
    </main>

    <!-- ════════════════════════════════════════════════ -->
    <!-- FOOTER (FlexStart Inspired) -->
    <!-- ════════════════════════════════════════════════ -->
    <footer id="kontak" class="bg-gray-900 text-gray-300 pt-16 pb-12 border-t border-gray-800 relative overflow-hidden">
        <!-- Background accent light -->
        <div class="absolute -top-40 right-0 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
                <!-- Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white shadow-md shadow-brand-500/20">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="text-lg font-bold text-white leading-none">OneLink</span>
                            <span class="text-[8px] font-semibold text-gray-400 tracking-wider uppercase mt-0.5">Powered by SMK Negeri 1 Cirebon</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Sistem Informasi Bursa Kerja Khusus (BKK) & Penelusuran Alumni (Tracer Study) SMKN 1 Cirebon untuk menyalurkan lulusan ke dunia kerja dan melacak rekam jejak alumni.
                    </p>
                </div>

                <!-- Tautan Cepat -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ url('/#hero') }}" class="hover:text-brand-400 transition-colors">Beranda</a></li>
                        <li><a href="{{ url('/#keunggulan') }}" class="hover:text-brand-400 transition-colors">Layanan Karir</a></li>
                        <li><a href="{{ url('/#lowongan') }}" class="hover:text-brand-400 transition-colors">Lowongan Kerja Terbaru</a></li>
                        <li><a href="{{ url('/#tracer-study') }}" class="hover:text-brand-400 transition-colors">Portal Tracer Study</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-brand-400 transition-colors">Area Login Admin / BKK / BK</a></li>
                    </ul>
                </div>

                <!-- Layanan / Program -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Layanan & Fitur</h4>
                    <ul class="space-y-2.5 text-sm text-gray-400">
                        <li>Informasi Perusahaan Mitra</li>
                        <li>Bursa Kerja Khusus (BKK)</li>
                        <li>Penelusuran Alumni (BK)</li>
                        <li>Informasi Alumni Bekerja, Kuliah, & Wirausaha</li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div class="space-y-3 text-sm">
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Hubungi Kami</h4>
                    <p class="text-gray-400">SMKN 1 CIREBON</p>
                    <p class="text-gray-400 flex items-start gap-2">
                        <svg class="w-5 h-5 text-brand-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Jl. Perjuangan No. 9, Sunyaragi, Kec. Kesambi, Kota Cirebon, Jawa Barat</span>
                    </p>
                    <p class="text-gray-400 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>info@smkn1-cirebon.sch.id</span>
                    </p>
                </div>
            </div>

            <!-- Copyright -->
            <div class="pt-8 border-t border-gray-800 text-center text-xs text-gray-500 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>&copy; {{ date('Y') }} OneLink SMKN 1 CIREBON. All rights reserved.</p>
                <p class="text-gray-600">Designed with FlexStart Aesthetic</p>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <!-- Smooth Cursor Follower Light Overlay Script (Dark Mode Only) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const glow = document.getElementById('cursor-glow');
            if (!glow) return;

            let mouseX = 0, mouseY = 0;
            let currentX = 0, currentY = 0;
            let active = false;

            const updateGlow = () => {
                if (!active) return;

                // Smooth linear interpolation (lerp) with 0.08 factor for premium lag/inertia
                currentX += (mouseX - currentX) * 0.08;
                currentY += (mouseY - currentY) * 0.08;

                glow.style.setProperty('--mouse-x', `${currentX}px`);
                glow.style.setProperty('--mouse-y', `${currentY}px`);

                requestAnimationFrame(updateGlow);
            };

            window.addEventListener('mousemove', (e) => {
                // Safeguard: Only compute and run cursor glow if dark mode is active
                if (!document.documentElement.classList.contains('dark')) {
                    active = false;
                    return;
                }

                mouseX = e.clientX;
                mouseY = e.clientY;

                if (!active) {
                    active = true;
                    requestAnimationFrame(updateGlow);
                }
            });

            // Turn off glow when mouse leaves the viewport
            document.addEventListener('mouseleave', () => {
                active = false;
            });
        });
    </script>
</body>

</html>
