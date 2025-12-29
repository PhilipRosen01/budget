<!-- filepath: /Users/devaccount/Desktop/budget/resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Budget') }} - Sign In</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .animate-fade-in-up {
                animation: fadeInUp 0.6s ease-out;
            }
            
            .gradient-border {
                position: relative;
                background: white;
                border-radius: 1rem;
            }
            
            .gradient-border::before {
                content: '';
                position: absolute;
                inset: -2px;
                border-radius: 1rem;
                padding: 2px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .gradient-border:hover::before {
                opacity: 1;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            <!-- Left Side - Login Form -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-6 py-12 bg-white">
                <div class="w-full max-w-md animate-fade-in-up">
                    <!-- Logo and Header -->
                    <div class="text-center mb-8">
                        <div class="mx-auto w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg transform hover:scale-105 transition-transform duration-200">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
                        <p class="text-gray-600">Sign in to manage your budget and finances</p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-150 ease-in-out text-gray-900 placeholder-gray-400"
                                    placeholder="you@example.com">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input id="password" type="password" name="password" required autocomplete="current-password"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition duration-150 ease-in-out text-gray-900 placeholder-gray-400"
                                    placeholder="••••••••">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer" name="remember">
                                <span class="ml-2 text-sm text-gray-700 font-medium">Remember me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold transition-colors" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Sign In Button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all duration-150 hover:scale-[1.02] active:scale-[0.98] shadow-lg">
                                Sign In
                            </button>
                        </div>
                    </form>

                    <!-- Register Link -->
                    @if (Route::has('register'))
                        <div class="mt-8 relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500 font-medium">New to Budget?</span>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <a href="{{ route('register') }}" class="w-full inline-flex justify-center items-center px-4 py-3 border-2 border-indigo-600 rounded-lg text-indigo-600 font-semibold hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150 transform hover:scale-[1.02]">
                                Create an Account
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="mt-12 text-center text-sm text-gray-500">
                    <p>&copy; {{ date('Y') }} Budget. All rights reserved.</p>
                </div>
            </div>

            <!-- Right Side - Feature Showcase -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800 p-12 flex-col justify-center items-center relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
                </div>
                
                <div class="relative z-10 max-w-lg text-white">
                    <h2 class="text-4xl font-bold mb-6">Take Control of Your Finances</h2>
                    <p class="text-lg text-indigo-100 mb-12">Simple, powerful budget management that helps you track expenses, set goals, and achieve financial freedom.</p>
                    
                    <!-- Feature Cards -->
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-5 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-1">Track Your Spending</h3>
                                <p class="text-indigo-100 text-sm">Monitor all your expenses in one place with detailed insights and analytics.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-5 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-1">Set Financial Goals</h3>
                                <p class="text-indigo-100 text-sm">Create and track progress toward your savings goals and financial milestones.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-5 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-1">Real-Time Updates</h3>
                                <p class="text-indigo-100 text-sm">Get instant updates on your budget status and spending patterns as they happen.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>