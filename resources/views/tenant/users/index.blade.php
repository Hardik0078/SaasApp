<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-white">Tenant Users</h2>
    </x-slot>

    <div class="grid gap-8 lg:grid-cols-[1fr,0.8fr]">
        <div class="space-y-3">
            @foreach ($users as $user)
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <p class="text-lg font-semibold text-white">{{ $user->name }}</p>
                    <p class="text-sm text-slate-300">{{ $user->email }} | {{ $user->job_title }}</p>
                </div>
            @endforeach
            {{ $users->links() }}
        </div>

        <form method="POST" action="{{ route('tenant.users.store', tenant()) }}" class="rounded-2xl border border-white/10 bg-white/5 p-5 space-y-4">
            @csrf
            <h3 class="text-lg font-semibold text-white">Add user</h3>
            <x-text-input name="name" placeholder="Name" class="block w-full" required />
            <x-text-input type="email" name="email" placeholder="Email" class="block w-full" required />
            <x-text-input name="job_title" placeholder="Job title" class="block w-full" />
            <select name="role" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
                @foreach ($roles as $role)
                    <option value="{{ $role }}">{{ $role }}</option>
                @endforeach
            </select>
            <x-text-input type="password" name="password" placeholder="Password" class="block w-full" required />
            <x-text-input type="password" name="password_confirmation" placeholder="Confirm password" class="block w-full" required />
            <x-primary-button>Create user</x-primary-button>
        </form>
    </div>
</x-app-layout>
