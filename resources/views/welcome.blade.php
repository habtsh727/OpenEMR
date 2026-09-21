<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head', ['title' => 'Firdos Cultural Medical Center | HMS Portal'])
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.05); }
        }
        .animate-float {
            animation: float 5s ease-in-out infinite;
        }
        .animate-glow {
            animation: pulse-glow 6s ease-in-out infinite;
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card-hover:hover {
            transform: translateY(-4px);
            border-color: rgba(45, 212, 191, 0.3);
            box-shadow: 0 20px 40px -15px rgba(20, 184, 166, 0.15);
        }
        .hero-gradient-text {
            background: linear-gradient(135deg, #ffffff 0%, #a7f3d0 50%, #2dd4bf 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .accent-gradient-bg {
            background: linear-gradient(135deg, #0d9488 0%, #059669 100%);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 font-sans antialiased selection:bg-teal-500 selection:text-white">

    <!-- Background Decorative Glows -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-teal-600/20 rounded-full blur-3xl animate-glow"></div>
        <div class="absolute top-1/3 -right-40 w-96 h-96 bg-emerald-600/15 rounded-full blur-3xl animate-glow" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-cyan-600/15 rounded-full blur-3xl animate-glow" style="animation-delay: 4s;"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:32px_32px] opacity-25"></div>
    </div>

    <div class="relative z-10 flex flex-col min-h-screen">
        
        <!-- Navigation Header -->
        <header class="sticky top-0 z-50 backdrop-blur-md bg-slate-950/80 border-b border-slate-800/60 transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-teal-500 to-emerald-400 p-0.5 shadow-lg shadow-teal-500/20 group-hover:scale-105 transition-transform">
                        <div class="w-full h-full bg-slate-950 rounded-[10px] flex items-center justify-center">
                            <svg class="w-6 h-6 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight text-white block leading-tight">Firdos Cultural</span>
                        <span class="text-xs font-semibold text-teal-400 tracking-wider uppercase">Medical Center</span>
                    </div>
                </a>

                <!-- Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
                    <a href="#services" class="hover:text-teal-400 transition-colors">Specialized Care</a>
                    <a href="#modules" class="hover:text-teal-400 transition-colors">HMS Modules</a>
                    <a href="#about" class="hover:text-teal-400 transition-colors">About Center</a>
                    <a href="#contact" class="hover:text-teal-400 transition-colors">Contact</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 shadow-sm transition-all hover:scale-[1.02]">
                            <svg class="w-4 h-4 mr-2 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl text-sm font-semibold text-slate-950 accent-gradient-bg hover:opacity-95 shadow-lg shadow-teal-500/25 transition-all hover:scale-[1.02]">
                            <svg class="w-4 h-4 mr-2 text-slate-950" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Staff Portal Login
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative py-20 lg:py-28 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto flex-1 flex flex-col justify-center">
            <div class="text-center max-w-4xl mx-auto">
                
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-card text-xs font-semibold text-teal-300 border border-teal-500/30 mb-8 animate-float">
                    <span class="w-2 h-2 rounded-full bg-teal-400 animate-ping"></span>
                    <span>Integrated Healthcare & Traditional Hijama Management System</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white mb-6 leading-tight">
                    Excellence in Care, <br />
                    <span class="hero-gradient-text">Traditional Wisdom & Technology</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-lg sm:text-xl text-slate-300 mb-10 max-w-2xl mx-auto leading-relaxed font-normal">
                    Welcome to <strong class="text-white">Firdos Cultural Medical Center</strong>. Our unified digital system seamlessly orchestrates outpatient care, Cupping (Hijama) therapy, inpatient rehabilitation, pharmacy, diagnostic lab, and radiology.
                </p>

                <!-- Primary Call to Action -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold text-base text-slate-950 accent-gradient-bg hover:opacity-95 shadow-xl shadow-teal-500/30 transition-all hover:scale-105 flex items-center justify-center group">
                        <span>Access HMS Portal</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a href="#services" class="w-full sm:w-auto px-8 py-4 rounded-xl font-semibold text-base text-slate-200 glass-card hover:bg-slate-800/80 border border-slate-700/80 transition-all hover:scale-105">
                        Explore Specialized Services
                    </a>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 pt-8 border-t border-slate-800/80">
                    <div class="glass-card p-5 rounded-2xl">
                        <div class="text-2xl sm:text-3xl font-extrabold text-teal-400">10,000+</div>
                        <div class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Patients Managed</div>
                    </div>
                    <div class="glass-card p-5 rounded-2xl">
                        <div class="text-2xl sm:text-3xl font-extrabold text-emerald-400">6 Care Units</div>
                        <div class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Integrated Departments</div>
                    </div>
                    <div class="glass-card p-5 rounded-2xl">
                        <div class="text-2xl sm:text-3xl font-extrabold text-cyan-400">100% Digital</div>
                        <div class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">EHR & Pharmacy</div>
                    </div>
                    <div class="glass-card p-5 rounded-2xl">
                        <div class="text-2xl sm:text-3xl font-extrabold text-teal-300">24 / 7</div>
                        <div class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">System Availability</div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="py-20 bg-slate-900/50 border-t border-slate-800/60 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-xs uppercase font-bold tracking-widest text-teal-400 mb-2">Core Clinical Departments</h2>
                    <p class="text-3xl sm:text-4xl font-extrabold text-white">Comprehensive Medical & Holistic Services</p>
                    <p class="text-slate-400 mt-3 text-base">Combining modern clinical practice with holistic Hijama therapy and physical rehabilitation.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    
                    <!-- Service Card 1: Outpatient & Triage -->
                    <div class="glass-card glass-card-hover p-8 rounded-2xl flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400 mb-6">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Outpatient & Triage</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-6">
                                Streamlined patient registration, vital sign tracking, nurse triage queues, and doctor consultation workflows with electronic health records.
                            </p>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-semibold text-teal-400 hover:text-teal-300">
                            Launch Unit Portal <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>

                    <!-- Service Card 2: Hijama Therapy -->
                    <div class="glass-card glass-card-hover p-8 rounded-2xl flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 mb-6">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Hijama (Cupping) Therapy</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-6">
                                Dedicated Cupping package management, session booking, customized location mapping, therapist assignment, and treatment outcome tracking.
                            </p>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-semibold text-emerald-400 hover:text-emerald-300">
                            Launch Unit Portal <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>

                    <!-- Service Card 3: Inpatient Rehabilitation -->
                    <div class="glass-card glass-card-hover p-8 rounded-2xl flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 mb-6">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Rehab & Bed Management</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-6">
                                Inpatient admissions, bed class allocations (VIP, Standard, Private), customized rehab treatment packages, and installment billing.
                            </p>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-semibold text-cyan-400 hover:text-cyan-300">
                            Launch Unit Portal <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>

                    <!-- Service Card 4: Pharmacy -->
                    <div class="glass-card glass-card-hover p-8 rounded-2xl flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400 mb-6">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.794.757M16 6l2 2m0 0l-2 2m2-2H8" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Pharmacy & Dispensation</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-6">
                                Stock transaction logging, custom medication compounding, walk-in sales, electronic prescription verification, and dosage tracking.
                            </p>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-semibold text-teal-400 hover:text-teal-300">
                            Launch Unit Portal <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>

                    <!-- Service Card 5: Diagnostic Laboratory -->
                    <div class="glass-card glass-card-hover p-8 rounded-2xl flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 mb-6">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.794.757M16 6l2 2m0 0l-2 2m2-2H8" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Diagnostic Laboratory</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-6">
                                Lab test cataloging, sample collection tracking, result entry, cashier payment verification, and automated diagnostic reports.
                            </p>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-semibold text-emerald-400 hover:text-emerald-300">
                            Launch Unit Portal <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>

                    <!-- Service Card 6: Radiology & Imaging -->
                    <div class="glass-card glass-card-hover p-8 rounded-2xl flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 mb-6">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Radiology & Medical Imaging</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-6">
                                Imaging orders (X-ray, Ultrasound), body part mapping, digital result image uploads, and physician consultation integration.
                            </p>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-semibold text-cyan-400 hover:text-cyan-300">
                            Launch Unit Portal <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>

                </div>

            </div>
        </section>

        <!-- CTA Banner -->
        <section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
            <div class="glass-card p-10 sm:p-14 rounded-3xl border border-teal-500/30 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="max-w-xl text-center md:text-left">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white mb-3">Ready to Access the HMS System?</h3>
                    <p class="text-slate-300 text-sm sm:text-base">
                        Authorized medical practitioners, nurses, cashiers, and administrators can log in to access active queues and patient records.
                    </p>
                </div>
                <a href="{{ route('login') }}" class="px-8 py-4 rounded-xl font-bold text-base text-slate-950 accent-gradient-bg hover:opacity-95 shadow-lg shadow-teal-500/30 transition-all hover:scale-105 shrink-0">
                    Log In to Staff Portal
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer id="contact" class="mt-auto border-t border-slate-800/80 bg-slate-950 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6 text-slate-400 text-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-teal-500/20 border border-teal-500/40 flex items-center justify-center text-teal-400 font-bold">
                        F
                    </div>
                    <span>&copy; {{ date('Y') }} <strong>Firdos Cultural Medical Center</strong>. All rights reserved.</span>
                </div>
                <div class="flex items-center gap-6 text-xs sm:text-sm font-medium">
                    <a href="{{ route('login') }}" class="hover:text-teal-400 transition-colors">Staff Login</a>
                    <span class="text-slate-700">|</span>
                    <span class="text-slate-400">Emergency & Care Desk Active</span>
                </div>
            </div>
        </footer>

    </div>

</body>
</html>