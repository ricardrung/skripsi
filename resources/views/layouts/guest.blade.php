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

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans text-gray-900 antialiased bg-gradient-to-b from-[#2c1f11] to-[#573d22] flex flex-col items-center justify-center min-h-screen">

    <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            {{-- <img src="/images/RRS_Background.png" class="w-20 h-20 fill-current text-[#8B5E3B]"> --}}
            {{-- <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-[#8B5E3B]" />
            </a> --}}
        </div>

        <div class="w-full sm:max-w-md mt-16 mb-16 px-6 py-6 bg-white shadow-2xl overflow-hidden rounded-3xl">
            <h2 class="text-3xl font-bold text-center drop-shadow-lg italic font-[Lucida_Calligraphy] text-[#000] mb-2">
                Roemah Rempah Spa <br> Manado
            </h2>
            <hr class="border-t border-gray-300 my-3">
            @php
                $routeName = Route::currentRouteName();
                $title = match ($routeName) {
                    'register' => 'Sign Up',
                    'login' => 'Login',
                    'password.request' => 'Reset Password',
                    default => 'Welcome',
                };
            @endphp

            <h2 class="text-xl font-semibold text-center text-[#000] mb-4">
                {{ __($title) }}
            </h2>

            {{ $slot }}

            {{-- @if (Route::currentRouteName() === 'register')
                <p class="text-center text-gray-700 mt-4">
                    Do you have an account?
                    <a href="{{ route('login') }}" class="text-[#8B5E3B] hover:underline">Login</a>
                </p>
            @endif --}}
        </div>
    </div>

</body>

</html>
