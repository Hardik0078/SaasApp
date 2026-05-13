<x-guest-layout>
    <div class="space-y-2 text-center">
        <p class="text-sm uppercase tracking-[0.3em] text-cyan-300">
            {{ tenant() ? tenant()->name.' Workspace' : 'Central Admin Access' }}
        </p>
        <h1 class="text-3xl font-semibold text-white">Sign in</h1>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ request()->url() }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" name="remember" class="rounded border-white/20 bg-white/5">
            <span>Remember me</span>
        </label>

        <x-primary-button class="w-full justify-center">
            {{ __('Sign in') }}
        </x-primary-button>
    </form>
</x-guest-layout>
