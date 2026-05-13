<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-white">Projects</h2>
            <a href="{{ route('tenant.projects.create', tenant()) }}" class="inline-flex items-center rounded-xl bg-cyan-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-cyan-500">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Project
            </a>
        </div>
    </x-slot>

    <div class="rounded-2xl border border-white/10 bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-white/10 bg-white/5">
                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">Owner</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">Start Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">End Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">Budget</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse ($projects as $project)
                        <tr class="transition hover:bg-white/[0.02]">

                            <td class="px-6 py-4">
                                <a href="{{ route('tenant.projects.show', ['tenant' => tenant(), 'project' => $project]) }}" class="text-white font-medium hover:text-cyan-300 transition">
                                    {{ $project->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $project->status === 'active' ? 'bg-emerald-500/20 text-emerald-300' : '' }}
                                    {{ $project->status === 'draft' ? 'bg-slate-500/20 text-slate-300' : '' }}
                                    {{ $project->status === 'on_hold' ? 'bg-amber-500/20 text-amber-300' : '' }}
                                    {{ $project->status === 'completed' ? 'bg-cyan-500/20 text-cyan-300' : '' }}
                                    {{ $project->status === 'cancelled' ? 'bg-red-500/20 text-red-300' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-300">{{ $project->owner?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-400">{{ $project->start_date ? $project->start_date->format('M j, Y') : '-' }}</td>
                            <td class="px-6 py-4 text-slate-400">{{ $project->end_date ? $project->end_date->format('M j, Y') : '-' }}</td>
                            <td class="px-6 py-4 text-slate-300">{{ $project->budget ? '$' . number_format($project->budget, 2) : '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tenant.projects.edit', ['tenant' => tenant(), 'project' => $project]) }}" class="rounded-lg bg-slate-700 p-2 text-slate-300 transition hover:bg-slate-600 hover:text-white" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('tenant.projects.destroy', ['tenant' => tenant(), 'project' => $project]) }}" class="inline" onsubmit="return confirm('Are you sure you want to archive this project?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-slate-700 p-2 text-slate-300 transition hover:bg-red-600 hover:text-white" title="Archive">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                    <p class="text-lg font-medium">No projects found</p>
                                    <p class="text-sm mt-1">Get started by creating your first project.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($projects->hasPages())
            <div class="border-t border-white/10 px-6 py-4">
                <nav class="flex items-center justify-between">
                    <div class="flex justify-between flex-1 sm:hidden">
                        @if ($projects->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 bg-slate-800/50 border border-white/10 cursor-not-allowed rounded-lg">Previous</span>
                        @else
                            <a href="{{ $projects->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800/50 border border-white/10 rounded-lg hover:bg-slate-700 transition">Previous</a>
                        @endif

                        @if ($projects->hasMorePages())
                            <a href="{{ $projects->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-slate-300 bg-slate-800/50 border border-white/10 rounded-lg hover:bg-slate-700 transition">Next</a>
                        @else
                            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-slate-500 bg-slate-800/50 border border-white/10 cursor-not-allowed rounded-lg">Next</span>
                        @endif
                    </div>

                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-slate-400">
                                Showing <span class="font-medium text-slate-300">{{ $projects->firstItem() }}</span> to <span class="font-medium text-slate-300">{{ $projects->lastItem() }}</span> of <span class="font-medium text-slate-300">{{ $projects->total() }}</span> results
                            </p>
                        </div>
                        <div>
                            <span class="relative z-0 inline-flex rounded-md shadow-sm">
                                @if ($projects->onFirstPage())
                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 bg-slate-800/50 border border-white/10 rounded-l-lg cursor-not-allowed">Previous</span>
                                @else
                                    <a href="{{ $projects->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800/50 border border-white/10 rounded-l-lg hover:bg-slate-700 transition">Previous</a>
                                @endif

                                @if ($projects->hasMorePages())
                                    <a href="{{ $projects->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-slate-300 bg-slate-800/50 border border-white/10 rounded-r-lg hover:bg-slate-700 transition">Next</a>
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