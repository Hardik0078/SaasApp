<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-white">{{ $project->name }}</h2>
                <p class="text-slate-300">{{ $project->description }}</p>
            </div>
            <a href="{{ route('tenant.projects.edit', ['tenant' => tenant(), 'project' => $project]) }}" class="rounded-full border border-cyan-400/30 px-4 py-2 text-sm text-cyan-200">Edit</a>
        </div>
    </x-slot>

    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
        <h3 class="text-lg font-semibold text-white">Tasks</h3>
        <div class="mt-4 grid gap-3">
            @foreach ($project->tasks as $task)
                <a href="{{ route('tenant.tasks.show', ['tenant' => tenant(), 'task' => $task]) }}" class="rounded-xl border border-white/10 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <span class="text-white">{{ $task->title }}</span>
                        <span class="text-sm text-cyan-200">{{ $task->status }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
