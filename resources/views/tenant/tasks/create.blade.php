<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-white">Create Task</h2>
    </x-slot>

    <form method="POST" action="{{ route('tenant.tasks.store', tenant()) }}" class="rounded-2xl border border-white/10 bg-white/5 p-5 space-y-4" enctype="multipart/form-data">
        @csrf
        <select name="project_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100" required>
            <option value="">Project</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
        </select>
        <select name="assigned_user_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
            <option value="">Assignee</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <x-text-input name="title" placeholder="Task title" class="block w-full" required />
        <textarea name="description" placeholder="Description" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100"></textarea>
        <div class="grid grid-cols-2 gap-3">
            <select name="priority" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
                @foreach (['low', 'medium', 'high', 'urgent'] as $priority)
                    <option value="{{ $priority }}">{{ ucfirst($priority) }}</option>
                @endforeach
            </select>
            <select name="status" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
                @foreach (['todo', 'in_progress', 'blocked', 'done'] as $status)
                    <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <x-text-input type="date" name="due_date" class="block w-full" />
            <x-text-input type="number" step="0.01" name="estimated_hours" placeholder="Estimated hrs" class="block w-full" />
            <x-text-input type="number" step="0.01" name="actual_hours" placeholder="Actual hrs" class="block w-full" />
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Attachments</label>
            <input type="file" name="attachments[]" multiple class="block w-full text-sm text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-cyan-600 file:text-white hover:file:bg-cyan-500">
        </div>
        <x-primary-button>Create task</x-primary-button>
    </form>
</x-app-layout>
