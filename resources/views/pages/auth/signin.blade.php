@extends('layouts.fullscreen-layout')

@push('styles')
<style>
    /* ═══════════════════════════════════════════════ */
    /*  LOGIN PAGE STYLES                              */
    /* ═══════════════════════════════════════════════ */

    .login-page {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        background: #F8FBFF;
        min-height: 100dvh;
    }
    .dark .login-page {
        background: #0f172a;
    }

    /* ── Left Branding Panel ── */
    .login-brand-panel {
        background: linear-gradient(155deg, #2278D8 0%, #399EF2 45%, #5BB6FF 100%);
        position: relative;
        overflow: hidden;
    }
    .login-brand-panel::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 20% 80%, rgba(255,255,255,0.08) 0%, transparent 50%),
            radial-gradient(ellipse at 80% 20%, rgba(255,255,255,0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    /* Subtle floating academic shapes */
    .login-shapes {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
    }
    .login-shape {
        position: absolute;
        border: 1.5px solid rgba(255,255,255,0.08);
        border-radius: 50%;
        animation: floatShape 20s ease-in-out infinite;
    }
    .login-shape:nth-child(1) {
        width: 200px; height: 200px;
        top: 10%; left: -5%;
        animation-delay: 0s;
        animation-duration: 22s;
    }
    .login-shape:nth-child(2) {
        width: 120px; height: 120px;
        top: 60%; right: 10%;
        animation-delay: -5s;
        animation-duration: 18s;
    }
    .login-shape:nth-child(3) {
        width: 80px; height: 80px;
        bottom: 15%; left: 20%;
        animation-delay: -10s;
        border-radius: 12px;
        transform: rotate(45deg);
    }
    .login-shape:nth-child(4) {
        width: 160px; height: 160px;
        top: 25%; right: -3%;
        animation-delay: -7s;
        animation-duration: 25s;
    }

    @keyframes floatShape {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        25% { transform: translate(10px, -15px) rotate(5deg); }
        50% { transform: translate(-5px, 10px) rotate(-3deg); }
        75% { transform: translate(8px, 5px) rotate(2deg); }
    }

    /* Grid pattern overlay */
    .login-grid-pattern {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
    }

    /* ── Login Card ── */
    .login-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow:
            0 1px 3px rgba(16, 24, 40, 0.06),
            0 8px 32px -4px rgba(16, 24, 40, 0.08);
        border: 1px solid #E4E7EC;
    }
    .dark .login-card {
        background: #1e293b;
        border-color: #334155;
        box-shadow:
            0 1px 3px rgba(0, 0, 0, 0.2),
            0 8px 32px -4px rgba(0, 0, 0, 0.3);
    }

    /* ── Input Styling ── */
    .login-input {
        height: 48px;
        width: 100%;
        border-radius: 10px;
        border: 1.5px solid #D9E8F7;
        background: #F8FBFF;
        padding: 0 16px;
        font-size: 14px;
        font-weight: 500;
        color: #1E293B;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
    }
    .login-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }
    .login-input:focus {
        border-color: #5BB6FF;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(91, 182, 255, 0.15);
    }
    .dark .login-input {
        background: #0f172a;
        border-color: #334155;
        color: #e2e8f0;
    }
    .dark .login-input:focus {
        border-color: #5BB6FF;
        background: #1e293b;
        box-shadow: 0 0 0 3px rgba(91, 182, 255, 0.15);
    }
    .dark .login-input::placeholder {
        color: #475569;
    }

    /* ── Button ── */
    .login-btn {
        height: 48px;
        width: 100%;
        border-radius: 10px;
        background: #5BB6FF;
        color: #ffffff;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: -0.01em;
        border: none;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 1px 3px rgba(91, 182, 255, 0.3);
    }
    .login-btn:hover {
        background: #399EF2;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(57, 158, 242, 0.35);
    }
    .login-btn:active {
        transform: translateY(0);
        box-shadow: 0 1px 3px rgba(91, 182, 255, 0.3);
    }

    /* ── Entrance Animations ── */
    @keyframes loginFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes loginSlideUp {
        from { opacity: 0; transform: translateY(24px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes loginSlideLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .anim-fade {
        animation: loginFadeIn 0.6s ease-out both;
    }
    .anim-slide-up {
        animation: loginSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    .anim-slide-left {
        animation: loginSlideLeft 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    .anim-d1 { animation-delay: 100ms; }
    .anim-d2 { animation-delay: 200ms; }
    .anim-d3 { animation-delay: 300ms; }
    .anim-d4 { animation-delay: 400ms; }
    .anim-d5 { animation-delay: 500ms; }

    /* ── Checkbox ── */
    .login-checkbox {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 1.5px solid #D0D5DD;
        background: #fff;
        appearance: none;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        flex-shrink: 0;
    }
    .login-checkbox:checked {
        background: #5BB6FF;
        border-color: #5BB6FF;
    }
    .login-checkbox:checked::after {
        content: '';
        position: absolute;
        left: 5px; top: 2px;
        width: 5px; height: 9px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    .dark .login-checkbox {
        background: #1e293b;
        border-color: #475569;
    }
</style>
@endpush

@section('content')
    <div class="login-page">
        <div class="flex min-h-dvh flex-col lg:flex-row">

            {{-- ════════════════════════════════════════════════ --}}
            {{--  LEFT: BRANDING PANEL                           --}}
            {{-- ════════════════════════════════════════════════ --}}
            <div class="login-brand-panel hidden lg:flex lg:w-[52%] items-center justify-center p-12 relative">
                <div class="login-shapes">
                    <div class="login-shape"></div>
                    <div class="login-shape"></div>
                    <div class="login-shape"></div>
                    <div class="login-shape"></div>
                </div>
                <div class="login-grid-pattern"></div>

                <div class="relative z-10 max-w-md anim-slide-left">
                    {{-- Logo / Brand --}}
                    <div class="mb-10">
                        <div class="inline-flex items-center gap-3 mb-6">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/15 backdrop-blur-sm border border-white/10">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-2xl font-bold text-white tracking-tight">SIM-MoU</span>
                        </div>
                        <h1 class="text-3xl font-bold text-white leading-tight tracking-tight mb-4">
                            Sistem Informasi<br>Kerja Sama & MoU
                        </h1>
                        <p class="text-[15px] text-white/70 leading-relaxed max-w-sm">
                            Platform terpadu untuk mengelola dokumen kemitraan, monitoring masa berlaku, dan administrasi kerja sama institusi pendidikan.
                        </p>
                    </div>

                    {{-- Feature List --}}
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 backdrop-blur-sm flex-shrink-0 mt-0.5">
                                <svg class="h-4 w-4 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white/90">Kelola dokumen MoU</p>
                                <p class="text-xs text-white/50 mt-0.5">Pantau dan kelola seluruh dokumen kemitraan secara digital</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 backdrop-blur-sm flex-shrink-0 mt-0.5">
                                <svg class="h-4 w-4 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white/90">Monitoring otomatis</p>
                                <p class="text-xs text-white/50 mt-0.5">Notifikasi masa berlaku dan peringatan kontrak berakhir</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 backdrop-blur-sm flex-shrink-0 mt-0.5">
                                <svg class="h-4 w-4 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white/90">Laporan & analitik</p>
                                <p class="text-xs text-white/50 mt-0.5">Dashboard visual untuk evaluasi dan pengambilan keputusan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ════════════════════════════════════════════════ --}}
            {{--  RIGHT: LOGIN FORM                              --}}
            {{-- ════════════════════════════════════════════════ --}}
            <div class="flex flex-1 flex-col items-center justify-center px-5 py-10 lg:px-12 lg:w-[48%]">
                {{-- Mobile Logo --}}
                <div class="mb-8 flex items-center gap-2 lg:hidden anim-fade">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-500 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-gray-800 dark:text-white">SIM-MoU</span>
                </div>

                <div class="login-card w-full max-w-[420px] p-8 sm:p-10 anim-slide-up anim-d1">
                    {{-- Header --}}
                    <div class="mb-7">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">
                            Masuk ke akun Anda
                        </h2>
                        <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Masukkan kredensial untuk mengakses sistem
                        </p>
                    </div>

                    {{-- Error Messages --}}
                    @if ($errors->any())
                        <div class="mb-5 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 p-4 anim-fade" role="alert">
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    @foreach ($errors->all() as $error)
                                        <p class="text-sm text-red-700 dark:text-red-400 font-medium">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-5 rounded-xl bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 p-4 anim-fade" role="alert">
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-green-700 dark:text-green-400 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('login') }}" method="POST" id="loginForm">
                        @csrf
                        <div class="space-y-5">
                            {{-- Email --}}
                            <div class="anim-slide-up anim-d2">
                                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Email
                                </label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                        <svg class="h-[18px] w-[18px] text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                        placeholder="nama@email.com"
                                        class="login-input !pl-11"
                                    />
                                </div>
                            </div>

                            {{-- Password --}}
                            <div x-data="{ showPassword: false }" class="anim-slide-up anim-d3">
                                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Password
                                </label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                        <svg class="h-[18px] w-[18px] text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        id="password"
                                        name="password"
                                        required
                                        placeholder="Masukkan password"
                                        class="login-input !pl-11 !pr-12"
                                    />
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                        aria-label="Toggle password visibility"
                                    >
                                        <svg x-show="!showPassword" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg x-show="showPassword" x-cloak class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Remember Me --}}
                            <div class="flex items-center gap-2.5 anim-slide-up anim-d4">
                                <input type="checkbox" id="remember" name="remember" class="login-checkbox">
                                <label for="remember" class="text-sm text-gray-600 dark:text-gray-400 cursor-pointer select-none">
                                    Ingat saya
                                </label>
                            </div>

                            {{-- Submit Button --}}
                            <div class="anim-slide-up anim-d5">
                                <button type="submit" class="login-btn">
                                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                    </svg>
                                    Masuk
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Footer --}}
                <p class="mt-8 text-xs text-gray-400 dark:text-gray-500 text-center anim-fade anim-d5">
                    &copy; {{ date('Y') }} SIM-MoU &mdash; Sistem Informasi Kerja Sama
                </p>
            </div>

        </div>
    </div>
@endsection
