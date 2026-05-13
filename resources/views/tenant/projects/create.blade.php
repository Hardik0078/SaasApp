<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-white">Create Project</h2>
    </x-slot>

    <form method="POST" action="{{ route('tenant.projects.store', tenant()) }}" class="rounded-2xl border border-white/10 bg-white/5 p-5 space-y-4">
        @csrf
        <x-text-input name="name" placeholder="Project name" class="block w-full" required />
        <textarea name="description" placeholder="Description" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100"></textarea>
        <div class="grid grid-cols-2 gap-3">
            <x-text-input type="date" title="Start Date" name="start_date" class="block w-full" />
            <x-text-input type="date" title="End Date" name="end_date" class="block w-full" />
        </div>
        <x-text-input type="number" step="0.01" name="budget" placeholder="Budget" class="block w-full" />
        <select name="status" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
            <option value="draft">Draft</option>
            <option value="active">Active</option>
            <option value="on_hold">On hold</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <select name="owner_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
            <option value="">Owner</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <x-primary-button>Create project</x-primary-button>
    </form>
</x-app-layout>
