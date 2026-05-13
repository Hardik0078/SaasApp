<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-cyan-300">{{ tenant()->name }}</p>
                <h2 class="text-2xl font-semibold text-white">Workspace dashboard</h2>
            </div>
            <div class="text-right text-sm text-slate-300">
                <p>Status: {{ tenant()->status }}</p>
                <p>Subscription until: {{ optional(tenant()->subscription_ends_at)->toDateString() ?? 'Trial' }}</p>
            </div>
        </div>
    </x-slot>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-5"><p class="text-slate-400">Projects</p><p class="mt-2 text-3xl font-semibold">{{ $projectCount }}</p></div>
        <div class="rounded-2xl border border-white/10 bg-white/5 p-5"><p class="text-slate-400">Open tasks</p><p class="mt-2 text-3xl font-semibold">{{ $openTaskCount }}</p></div>
        <div class="rounded-2xl border border-white/10 bg-white/5 p-5"><p class="text-slate-400">Completed tasks</p><p class="mt-2 text-3xl font-semibold">{{ $completedTaskCount }}</p></div>
        <div class="rounded-2xl border border-white/10 bg-white/5 p-5"><p class="text-slate-400">Current plan</p><p class="mt-2 text-xl font-semibold">{{ $subscription?->plan?->name ?? 'Trial' }}</p></div>
    </div>

    {{-- <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-5">
        <h3 class="text-lg font-semibold text-white">Recent tasks</h3>
        <div class="mt-4 grid gap-3">
            @forelse ($recentTasks as $task)
                <a href="{{ route('tenant.tasks.show', ['tenant' => tenant(), 'task' => $task]) }}" class="rounded-xl border border-white/10 px-4 py-3 transition hover:border-cyan-400/40">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-white">{{ $task->title }}</p>
                            <p class="text-sm text-slate-400">{{ $task->project?->name }} | {{ $task->assignedUser?->name ?? 'Unassigned' }}</p>
                        </div>
                        <span class="text-sm text-cyan-200">{{ $task->status }}</span>
                    </div>
                </a>
            @empty
                <p class="text-slate-400">No tasks yet.</p>
            @endforelse
        </div>
    </div> --}}
</x-app-layout>
