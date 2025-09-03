<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Contract Management App') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Enhanced Arabic Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100;200;300;400;500;600;700;800;900&family=Cairo:wght@200;300;400;500;600;700;800;900&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])


    
        <script>
            // Enhanced Language toggle functionality
            function toggleLanguage() {
                const toggle = document.getElementById('languageToggle');
                const slider = document.getElementById('toggleSlider');
                const currentLang = toggle.getAttribute('data-current-lang');
                
                // Toggle the visual state
                toggle.classList.toggle('toggle-active');
                
                // Determine new language
                const newLang = currentLang === 'en' ? 'ar' : 'en';
                
                // Redirect to change language
                window.location.href = '{{ route("settings.language", ":lang") }}'.replace(':lang', newLang);
            }
            
            // Set initial toggle position based on current language
            document.addEventListener('DOMContentLoaded', function() {
                const toggle = document.getElementById('languageToggle');
                const currentLang = toggle.getAttribute('data-current-lang');
                
                if (currentLang === 'ar') {
                    toggle.classList.add('toggle-active');
                }
                
                // Add RTL specific enhancements
                if (document.documentElement.dir === 'rtl') {
                    document.body.classList.add('rtl-enhanced');
                }
            });
        </script>
    </head>
    <body class="antialiased min-h-screen gradient-bg overflow-x-hidden">
        <!-- Enhanced Navigation -->
        <nav class="absolute top-0 left-0 right-0 z-50 p-6">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="text-white text-2xl font-bold arabic-nav">
                    {{ config('app.name', 'CMSYS') }}
                </div>
                
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" 
                               class="arabic-btn px-6 py-3 bg-white text-purple-600 hover:bg-gray-100 font-semibold rounded-lg transition-all duration-300 shadow-lg border border-white border-opacity-30 hover:border-opacity-50">
                                {{ __('Dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="arabic-btn group relative px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 transform overflow-hidden">
                                <span class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                <span class="relative flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    {{ __('Log in') }}
                                </span>
                            </a>
                        @endauth
                    @endif
                    
                    <!-- Enhanced Language Switcher -->
                    <div class="relative px-4 py-2">
                        <button 
                            onclick="toggleLanguage()"
                            class="relative inline-flex h-8 w-16 items-center rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2 shadow-md hover:shadow-lg"
                            id="languageToggle"
                            data-current-lang="{{ app()->getLocale() }}"
                        >
                            <span 
                                class="inline-block h-6 w-6 transform rounded-full bg-white shadow-lg transition-transform duration-300 ease-in-out"
                                id="toggleSlider"
                            ></span>
                            <span class="absolute left-1 text-xs font-bold text-white drop-shadow-sm px-1 py-0.5">EN</span>
                            <span class="absolute right-1 text-xs font-bold text-white drop-shadow-sm px-1 py-0.5">AR</span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Enhanced Main Content -->
        <main class="min-h-screen flex items-center justify-center px-6 pt-20">
            <div class="max-w-6xl mx-auto text-center">
                <!-- Enhanced Hero Section -->
                <div class="mb-16 slide-in arabic-hero">
                    <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold text-white mb-8 leading-tight">
                        @if(app()->getLocale() === 'ar')
                            نظام إدارة العقود
                        @else
                            Contract Management App
                        @endif
                    </h1>
                    
                    <p class="text-xl md:text-2xl lg:text-3xl text-white text-opacity-90 mb-12 max-w-4xl mx-auto leading-relaxed text-center">
                        @if(app()->getLocale() === 'ar')
                            منصة متكاملة لإدارة العقود والعملاء والمدفوعات. نظام ذكي يساعدك على تنظيم أعمالك وزيادة كفاءتك مع واجهة سهلة الاستخدام
                        @else
                            A comprehensive platform for managing contracts, clients, and payments. A smart system that helps you organize your business and increase your efficiency
                        @endif
                    </p>
                </div>

                <!-- Enhanced Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                    <!-- Enhanced Feature 1 -->
                    <div class="glass-effect rounded-2xl p-8 floating-animation arabic-feature" style="animation-delay: 0s;">
                        <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                            <svg class="w-10 h-10 text-purple-600 absolute inset-0 m-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4 rtl-text">
                            @if(app()->getLocale() === 'ar')
                                إدارة العقود
                            @else
                                Contract Management
                            @endif
                        </h3>
                        <p class="text-white text-opacity-80 rtl-text">
                            @if(app()->getLocale() === 'ar')
                                إنشاء وإدارة العقود بسهولة مع تتبع التواريخ والحالة والوثائق المرفقة
                            @else
                                Create and manage contracts easily with date and status tracking
                            @endif
                        </p>
                    </div>

                    <!-- Enhanced Feature 2 -->
                    <div class="glass-effect rounded-2xl p-8 floating-animation arabic-feature" style="animation-delay: 0.2s;">
                        <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                            <svg class="w-10 h-10 text-blue-600 absolute inset-0 m-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4 rtl-text">
                            @if(app()->getLocale() === 'ar')
                                إدارة العملاء
                            @else
                                Client Management
                            @endif
                        </h3>
                        <p class="text-white text-opacity-80 rtl-text">
                            @if(app()->getLocale() === 'ar')
                                تنظيم معلومات العملاء والعناوين مع قاعدة بيانات شاملة وسهولة البحث
                            @else
                                Organize client information and addresses with a comprehensive database
                            @endif
                        </p>
                    </div>

                    <!-- Enhanced Feature 3 -->
                    <div class="glass-effect rounded-2xl p-8 floating-animation arabic-feature" style="animation-delay: 0.4s;">
                        <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                            <svg class="w-10 h-10 text-green-600 absolute inset-0 m-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4 rtl-text">
                            @if(app()->getLocale() === 'ar')
                                تتبع المدفوعات
                            @else
                                Payment Tracking
                            @endif
                        </h3>
                        <p class="text-white text-opacity-80 rtl-text">
                            @if(app()->getLocale() === 'ar')
                                مراقبة المدفوعات والمستحقات مع تقارير مفصلة وتنبيهات ذكية
                            @else
                                Monitor payments and receivables with detailed reports
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Enhanced Call to Action -->
                <div class="text-center slide-in" style="animation-delay: 0.6s;">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="arabic-btn inline-block px-12 py-4 bg-white text-purple-600 font-bold text-xl rounded-full hover:bg-gray-100 transform hover:scale-105 transition-all duration-300 shadow-2xl pulse-animation">
                            @if(app()->getLocale() === 'ar')
                                الذهاب إلى لوحة التحكم
                            @else
                                Go to Dashboard
                            @endif
                        </a>
                    @endif
                </div>
            </div>
        </main>

        <!-- Enhanced Footer -->
        <footer class="absolute bottom-0 left-0 right-0 p-6">
            <div class="max-w-7xl mx-auto text-center">
                <p class="text-white text-opacity-70 arabic-footer rtl-text">
                    @if(app()->getLocale() === 'ar')
                        © {{ date('Y') }} نظام إدارة العقود. جميع الحقوق محفوظة.
                    @else
                        © {{ date('Y') }} Contract Management App. All rights reserved.
                    @endif
                </p>
            </div>
        </footer>

        <!-- Enhanced Background Elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white bg-opacity-5 rounded-full"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white bg-opacity-5 rounded-full"></div>
        </div>
    </body>
</html>
