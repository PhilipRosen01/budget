<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Budget') }}</title>

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
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-6 py-12 bg-gradient-to-br from-indigo-50 via-white to-purple-50">
            <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-xl rounded-2xl animate-fade-in-up">
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} Budget. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
