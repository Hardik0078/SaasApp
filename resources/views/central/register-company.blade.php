<x-guest-layout>
    <div class="space-y-2 text-center">
        <p class="text-sm uppercase tracking-[0.3em] text-cyan-300">Company onboarding</p>
        <h1 class="text-3xl font-semibold text-white">Create your workspace</h1>
    </div>

    <form method="POST" action="{{ route('companies.store') }}" class="mt-8 space-y-4">
        @csrf
        <x-text-input name="company_name" :value="old('company_name')" placeholder="Company name" class="block w-full" required />
        <x-text-input name="company_slug" :value="old('company_slug')" placeholder="Workspace slug" class="block w-full" required />
        <x-text-input type="email" name="billing_email" :value="old('billing_email')" placeholder="Billing email" class="block w-full" required />
        <x-text-input name="admin_name" :value="old('admin_name')" placeholder="Admin name" class="block w-full" required />
        <x-text-input type="email" name="admin_email" :value="old('admin_email')" placeholder="Admin email" class="block w-full" required />
        <x-text-input type="password" name="password" placeholder="Password" class="block w-full" required />
        <x-text-input type="password" name="password_confirmation" placeholder="Confirm password" class="block w-full" required />
        <select name="plan_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
            <option value="">Select starter plan</option>
            @foreach ($plans as $plan)
                <option value="{{ $plan->id }}">{{ $plan->name }} ({{ strtoupper($plan->billing_period) }}) - {{ $plan->currency }} {{ $plan->price }}</option>
            @endforeach
        </select>
        <x-primary-button class="w-full justify-center">Create company</x-primary-button>
    </form>
</x-guest-layout>
