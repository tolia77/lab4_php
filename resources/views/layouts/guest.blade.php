<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <header class="w-full bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-3">
                        <a href="/" class="flex items-center gap-2">
                            <x-application-logo class="w-10 h-10 fill-current text-gray-600" />
                            <span class="font-semibold text-gray-800">{{ config('app.name', 'Laravel') }}</span>
                        </a>
                    </div>

                    <nav class="flex items-center gap-4">
                        @auth
                            <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                            @if(Auth::user()->isAdmin())
                                <a href="#" class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded">Admin</a>
                            @else
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded">Basic</span>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button class="ml-3 text-sm text-gray-700 hover:text-gray-900">Log out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-gray-900">Sign up</a>
                            @endif
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
