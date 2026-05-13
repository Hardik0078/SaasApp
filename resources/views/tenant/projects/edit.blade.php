<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-white">Edit Project</h2>
    </x-slot>

    <form method="POST" action="{{ route('tenant.projects.update', ['tenant' => tenant(), 'project' => $project]) }}" class="rounded-2xl border border-white/10 bg-white/5 p-5 space-y-4">
        @csrf
        @method('PUT')
        <x-text-input name="name" :value="$project->name" class="block w-full" required />
        <textarea name="description" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">{{ $project->description }}</textarea>
        <div class="grid grid-cols-2 gap-3">
            <x-text-input type="date" name="start_date" :value="optional($project->start_date)->toDateString()" class="block w-full" />
            <x-text-input type="date" name="end_date" :value="optional($project->end_date)->toDateString()" class="block w-full" />
        </div>
        <x-text-input type="number" step="0.01" name="budget" :value="$project->budget" class="block w-full" />
        <select name="status" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
            @foreach (['draft', 'active', 'on_hold', 'completed', 'cancelled'] as $status)
                <option value="{{ $status }}" @selected($project->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>
        <select name="owner_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
            <option value="">Owner</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected($project->owner_id === $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
        <x-primary-button>Save changes</x-primary-button>
    </form>
</x-app-layout>
