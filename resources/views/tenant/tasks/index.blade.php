<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-white">Tasks</h2>
            <a href="{{ route('tenant.tasks.create', tenant()) }}" class="inline-flex items-center rounded-xl bg-cyan-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-cyan-500">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Task
            </a>
        </div>
    </x-slot>

    <div class="rounded-2xl border border-white/10 bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-white/10 bg-white/5">
                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">Project</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">Assignee</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">Due Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">Est / Act</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">Attachments</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse ($tasks as $task)
                        <tr class="transition hover:bg-white/[0.02]">
                            <td class="px-6 py-4">
                                <a href="{{ route('tenant.tasks.show', ['tenant' => tenant(), 'task' => $task]) }}" class="text-white font-medium hover:text-cyan-300 transition">
                                    {{ $task->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                @if ($task->project)
                                    <a href="{{ route('tenant.projects.show', ['tenant' => tenant(), 'project' => $task->project]) }}" class="hover:text-cyan-300 transition">
                                        {{ $task->project->name }}
                                    </a>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-300">{{ $task->assignedUser?->name ?? 'Unassigned' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $task->priority === 'urgent' ? 'bg-red-500/20 text-red-300' : '' }}
                                    {{ $task->priority === 'high' ? 'bg-orange-500/20 text-orange-300' : '' }}
                                    {{ $task->priority === 'medium' ? 'bg-amber-500/20 text-amber-300' : '' }}
                                    {{ $task->priority === 'low' ? 'bg-slate-500/20 text-slate-300' : '' }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $task->status === 'done' ? 'bg-emerald-500/20 text-emerald-300' : '' }}
                                    {{ $task->status === 'in_progress' ? 'bg-cyan-500/20 text-cyan-300' : '' }}
                                    {{ $task->status === 'todo' ? 'bg-slate-500/20 text-slate-300' : '' }}
                                    {{ $task->status === 'blocked' ? 'bg-red-500/20 text-red-300' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400">{{ $task->due_date ? $task->due_date->format('M j, Y') : '-' }}</td>
                            <td class="px-6 py-4 text-slate-300">
                                {{ $task->estimated_hours ? number_format($task->estimated_hours, 1) : '-' }} /
                                {{ $task->actual_hours ? number_format($task->actual_hours, 1) : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @foreach ($task->getMedia('attachments')->take(3) as $media)
                                    @if (Illuminate\Support\Facades\File::exists($media->getPath()))
                                        <a href="{{ $media->getUrl() }}" target="_blank" class="inline-block relative mr-1 mb-1">
                                            @if (str_starts_with($media->mime_type, 'image/'))
                                                <img src="{{ $media->getUrl() }}" alt="attachment" class="h-10 w-10 rounded object-cover border border-white/10 hover:border-cyan-400/40 transition">
                                            @else
                                                <div class="h-10 w-10 rounded bg-slate-700 flex items-center justify-center border border-white/10 hover:border-cyan-400/40 transition">
                                                    <svg class="h-5 w-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </a>
                                    @else
                                        <div class="h-10 w-10 rounded bg-red-500/20 flex items-center justify-center border border-red-500/30 mr-1 mb-1" title="File missing">
                                            <svg class="h-5 w-5 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </div>
                                    @endif
                                @endforeach
                                @if ($task->getMedia('attachments')->isEmpty())
                                    <span class="text-slate-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tenant.tasks.edit', ['tenant' => tenant(), 'task' => $task]) }}" class="rounded-lg bg-slate-700 p-2 text-slate-300 transition hover:bg-slate-600 hover:text-white" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-lg font-medium">No tasks found</p>
                                    <p class="text-sm mt-1">Get started by creating your first task.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tasks->hasPages())
            <div class="border-t border-white/10 px-6 py-4">
                <nav class="flex items-center justify-between">
                    <div class="flex justify-between flex-1 sm:hidden">
                        @if ($tasks->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 bg-slate-800/50 border border-white/10 cursor-not-allowed rounded-lg">Previous</span>
                        @else
                            <a href="{{ $tasks->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800/50 border border-white/10 rounded-lg hover:bg-slate-700 transition">Previous</a>
                        @endif

                        @if ($tasks->hasMorePages())
                            <a href="{{ $tasks->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-slate-300 bg-slate-800/50 border border-white/10 rounded-lg hover:bg-slate-700 transition">Next</a>
                        @else
                            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-slate-500 bg-slate-800/50 border border-white/10 cursor-not-allowed rounded-lg">Next</span>
                        @endif
                    </div>

                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-slate-400">
                                Showing <span class="font-medium text-slate-300">{{ $tasks->firstItem() }}</span> to <span class="font-medium text-slate-300">{{ $tasks->lastItem() }}</span> of <span class="font-medium text-slate-300">{{ $tasks->total() }}</span> results
                            </p>
                        </div>
                        <div>
                            <span class="relative z-0 inline-flex rounded-md shadow-sm">
                                @if ($tasks->onFirstPage())
                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 bg-slate-800/50 border border-white/10 rounded-l-lg cursor-not-allowed">Previous</span>
                                @else
                                    <a href="{{ $tasks->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800/50 border border-white/10 rounded-l-lg hover:bg-slate-700 transition">Previous</a>
                                @endif

                                @if ($tasks->hasMorePages())
                                    <a href="{{ $tasks->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-slate-300 bg-slate-800/50 border border-white/10 rounded-r-lg hover:bg-slate-700 transition">Next</a>
                                @else
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-slate-500 bg-slate-800/50 border border-white/10 rounded-r-lg cursor-not-allowed">Next</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </nav>
            </div>
        @endif
    </div>
</x-app-layout>
