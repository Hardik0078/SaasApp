<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SaaS App</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-white">

    <main class="mx-auto flex min-h-screen max-w-7xl items-center justify-center px-6 py-16">

        <div
            class="w-full max-w-3xl rounded-[2rem] border border-cyan-400/20 bg-white/5 p-10 shadow-2xl shadow-cyan-950/30 backdrop-blur">

            <!-- Heading -->
            <div class="text-center">
                <p class="mb-3 text-sm uppercase tracking-[0.35em] text-cyan-300">
                    Laravel Multi-Tenant SaaS App
                </p>

                <h1 class="text-4xl font-bold text-white">
                    Welcome to SaaS Platform
                </h1>

                <p class="mt-4 text-slate-400">
                    Register your company, access tenant portals, or login as Super Admin.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">

                <a href="{{ route('companies.create') }}"
                    class="rounded-full bg-cyan-400 px-6 py-3 font-medium text-slate-950 transition hover:bg-cyan-300">
                    Register Company & Login
                </a>

                <a href="{{ route('login') }}"
                    class="rounded-full border border-white/15 px-6 py-3 font-medium text-white transition hover:bg-white/10">
                    Super Admin Login
                </a>

            </div>

            <!-- Tenant Login Section -->
            <div class="mt-12 rounded-2xl border border-white/10 bg-slate-900/40 p-6">

                <h2 class="mb-5 text-xl font-semibold text-white">
                    Tenant Login
                </h2>

                <div class="flex flex-col gap-4 sm:flex-row">

                    <select id="tenantSelect"
                        class="w-full rounded-xl border border-slate-700 bg-slate-900 px-4 py-3 text-slate-100 outline-none transition focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/30">

                        <option value="">
                            Select Tenant
                        </option>
                        @foreach (App\Models\Tenant::query()->get() as $tenant)
                            <option value="{{ $tenant->slug }}">
                                {{ $tenant->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="button"
                        onclick="redirectToTenant()"
                        class="rounded-xl bg-blue-600 px-6 py-3 font-medium text-white transition hover:bg-blue-700">
                        Login
                    </button>

                </div>

            </div>

        </div>

    </main>

    <script id="n5p8v1">
        function redirectToTenant() {

            let tenantId = document.getElementById('tenantSelect').value;

            if (!tenantId) {
                alert('Please select tenant');
                return;
            }

            window.location.href = `/${tenantId}/login`;
        }
    </script>
</body>

</html>

