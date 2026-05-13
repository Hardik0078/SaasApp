<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <nav class="flex items-center space-x-2 text-xs font-medium uppercase tracking-wider text-cyan-400">
                    <span>{{ $task->project?->name ?? 'No Project' }}</span>
                    <span class="text-slate-500">/</span>
                    <span class="text-slate-300">Task Details</span>
                </nav>
                <h2 class="text-3xl font-bold tracking-tight text-white">{{ $task->title }}</h2>
                <div class="flex items-center gap-3 text-sm">
                    <span class="inline-flex items-center rounded-md bg-white/10 px-2 py-1 text-xs font-medium text-slate-300 ring-1 ring-inset ring-white/20">
                        Assigned: {{ $task->assignedUser?->name ?? 'Unassigned' }}
                    </span>
                </div>
            </div>

            <a href="{{ route('tenant.tasks.edit', ['tenant' => tenant(), 'task' => $task]) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-cyan-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cyan-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Task
            </a>
        </div>
    </x-slot>

    <div class="grid gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <section class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-sm backdrop-blur-sm">
                <div class="mb-6 flex items-center justify-between border-b border-white/5 pb-4">
                    <h3 class="text-lg font-bold text-white">Discussion</h3>
                    <span class="rounded-full bg-cyan-400/10 px-2.5 py-0.5 text-xs font-medium text-cyan-400 ring-1 ring-inset ring-cyan-400/20">
                        {{ $task->comments->count() }} Comments
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse ($task->comments as $comment)
                        <div class="group relative rounded-xl bg-white/[0.03] p-4 transition hover:bg-white/[0.05]">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-cyan-300">{{ $comment->user?->name }}</span>
                                <time class="text-[10px] uppercase tracking-widest text-slate-500">{{ $comment->created_at?->diffForHumans() }}</time>
                            </div>
                            <div class="mt-2 text-sm leading-relaxed text-slate-200">
                                {{ $comment->comment }}
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <p class="text-sm text-slate-500">No comments yet. Start the conversation!</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="lg:col-span-1">
            <section class="sticky top-6 rounded-2xl border border-white/10 bg-black/20 p-6">
                <h3 class="mb-6 text-sm font-bold uppercase tracking-widest text-slate-400">Activity Log</h3>

                <div class="relative">
                    <div class="absolute left-3 top-0 h-full w-px bg-white/10"></div>

                    <div class="space-y-6">
                        @forelse ($task->histories as $history)
                            <div class="relative pl-8">
                                <div class="absolute left-1.5 top-1.5 h-3 w-3 -translate-x-1/2 rounded-full border-2 border-cyan-500 bg-slate-900"></div>

                                <p class="text-xs font-semibold text-white">{{ $history->event }}</p>
                                <div class="mt-1 flex flex-wrap items-center gap-1 text-xs text-slate-400">
                                    <span class="line-through decoration-red-500/50">{{ $history->from_value ?: 'None' }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-cyan-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-medium text-cyan-200">{{ $history->to_value }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="pl-8 text-xs text-slate-500 text-italic">No history recorded.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
