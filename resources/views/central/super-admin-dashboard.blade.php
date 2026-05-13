<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-white">Super Admin Dashboard</h2>
    </x-slot>

    <div class="grid gap-4">
        @foreach ($tenants as $tenant)
            <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-white">{{ $tenant->name }}</h3>
                        <p class="text-sm text-slate-300">Slug: {{ $tenant->slug }} | Billing: {{ $tenant->billing_email }}</p>
                    </div>
                    <a href="{{ route('tenant.login', $tenant) }}" class="rounded-full border border-cyan-400/30 px-4 py-2 text-sm text-cyan-200">Open workspace</a>
                </div>
            </div>
        @endforeach

        {{ $tenants->links() }}
    </div>
</x-app-layout>
