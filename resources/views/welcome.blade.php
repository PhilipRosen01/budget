<!-- filepath: /Users/devaccount/Desktop/budget/resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Budget') }} - Smart Budget Management</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
            <!-- Header -->
            <header class="absolute top-0 left-0 right-0 z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-xl">B</span>
                            </div>
                            <span class="text-xl font-bold text-gray-900">Budget</span>
                        </div>
                        
                        @if (Route::has('login'))
                            <div class="flex items-center space-x-4">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Log in</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 transition">
                                            Get Started
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Hero Section -->
            <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 tracking-tight">
                            Take Control of Your
                            <span class="text-indigo-600">Budget</span>
                        </h1>
                        <p class="mt-6 text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto">
                            Simple, powerful budget tracking. Set goals, track spending, and achieve your financial dreams.
                        </p>
                        
                        <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-indigo-600 border border-transparent rounded-lg font-semibold text-base text-white hover:bg-indigo-700 transition shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Start Free Today
                                </a>
                            @endif
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white border border-gray-300 rounded-lg font-semibold text-base text-gray-700 hover:bg-gray-50 transition shadow-sm hover:shadow-md">
                                Sign In
                            </a>
                        </div>
                    </div>

                    <!-- Feature Cards -->
                    <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Budget Templates -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Smart Templates</h3>
                            <p class="text-gray-600 text-sm">Create budget templates once and auto-generate monthly budgets with a single click.</p>
                        </div>

                        <!-- Track Spending -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Real-time Tracking</h3>
                            <p class="text-gray-600 text-sm">See exactly where your money goes with instant purchase tracking and spending insights.</p>
                        </div>

                        <!-- Achieve Goals -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Reach Your Goals</h3>
                            <p class="text-gray-600 text-sm">Set savings targets and track progress toward your financial milestones.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="bg-indigo-600 py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-3xl font-bold text-white mb-4">Ready to master your finances?</h2>
                    <p class="text-indigo-100 mb-8 max-w-2xl mx-auto">Join thousands of users who have taken control of their budget. Get started in minutes.</p>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-white border border-transparent rounded-lg font-semibold text-base text-indigo-600 hover:bg-indigo-50 transition shadow-lg">
                            Create Your Free Account
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
                    <p>&copy; {{ date('Y') }} Budget. Simple budget management for everyone.</p>
                </div>
            </footer>
        </div>
    </body>
</html>