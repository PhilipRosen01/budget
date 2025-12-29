<!-- filepath: /Users/devaccount/Desktop/budget/resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Budget') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-10 bg-gradient-to-br from-indigo-50 via-white to-purple-50">
            <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-lg rounded-2xl">
                <!-- Logo -->
                <div class="text-center mb-6">
                    <div class="mx-auto w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center mb-4">
                        <span class="text-white font-bold text-xl">B</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">Welcome to Budget</h1>
                    <p class="mt-1 text-sm text-gray-600">Sign in to manage your finances</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-1 w-full"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-indigo-600 hover:text-indigo-700 font-medium" href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center">
                            {{ __('Sign in') }}
                        </x-primary-button>
                    </div>
                </form>

                @if (Route::has('register'))
                    <div class="mt-6 text-center text-sm text-gray-600">
                        <span>Don't have an account?</span>
                        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-700">Create one now</a>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} Budget. Simple budget management.</p>
            </div>
        </div>
    </body>
</html>