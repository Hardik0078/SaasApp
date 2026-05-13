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
    <body class="font-sans antialiased bg-slate-950 text-white">
        <div class="flex min-h-screen flex-col items-center justify-center bg-[radial-gradient(circle_at_top,_rgba(34,211,238,0.22),_transparent_35%),linear-gradient(180deg,_#020617,_#111827)] px-4 py-10">
            <div>
                <a href="{{ route('home') }}" class="text-xl font-semibold tracking-wide text-cyan-300">
                    SaaS App
                </a>
            </div>

            <div class="mt-6 w-full overflow-hidden rounded-[2rem] border border-white/10 bg-white/5 px-6 py-6 shadow-2xl shadow-cyan-950/30 sm:max-w-md">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
