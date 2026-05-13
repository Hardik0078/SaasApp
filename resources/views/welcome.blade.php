<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SaaS App</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-white">
        <main class="mx-auto flex min-h-screen max-w-7xl flex-col justify-center px-6 py-16">
            <div class="max-w-3xl rounded-[2rem] border border-cyan-400/20 bg-white/5 p-10 shadow-2xl shadow-cyan-950/30 backdrop-blur">
                <p class="mb-4 text-sm uppercase tracking-[0.35em] text-cyan-300">Laravel Multi-Tenant SaaS App</p>

                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('companies.create') }}" class="rounded-full bg-cyan-400 px-6 py-3 font-medium text-slate-950">Register Company and Login</a>
                    <a href="{{ route('login') }}" class="rounded-full border border-white/15 px-6 py-3 font-medium text-white">Super Admin Login</a>
                </div>
            </div>
        </main>
    </body>
</html>
