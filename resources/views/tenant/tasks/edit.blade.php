<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-white">Edit Task</h2>
    </x-slot>

    <form method="POST" action="{{ route('tenant.tasks.update', ['tenant' => tenant(), 'task' => $task]) }}" enctype="multipart/form-data" class="rounded-2xl border border-white/10 bg-white/5 p-5 space-y-4">
        @csrf
        @method('PUT')
        <select name="project_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100" required>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @selected($task->project_id === $project->id)>{{ $project->name }}</option>
            @endforeach
        </select>
        <select name="assigned_user_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
            <option value="">Select</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected($task->assigned_user_id === $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
        <x-text-input name="title" :value="$task->title" class="block w-full" required />
        <textarea name="description" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">{{ $task->description }}</textarea>
        <div class="grid grid-cols-2 gap-3">
            <select name="priority" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
                @foreach (['low', 'medium', 'high', 'urgent'] as $priority)
                    <option value="{{ $priority }}" @selected($task->priority === $priority)>{{ ucfirst($priority) }}</option>
                @endforeach
            </select>
            <select name="status" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
                @foreach (['todo', 'in_progress', 'blocked', 'done'] as $status)
                    <option value="{{ $status }}" @selected($task->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <x-text-input type="date" name="due_date" :value="optional($task->due_date)->toDateString()" class="block w-full" />
            <x-text-input type="number" step="0.01" name="estimated_hours" :value="$task->estimated_hours" class="block w-full" />
            <x-text-input type="number" step="0.01" name="actual_hours" :value="$task->actual_hours" class="block w-full" />
        </div>
        <textarea name="comment" placeholder="Optional progress comment" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100"></textarea>
        <input type="file" name="attachments[]" multiple class="block w-full text-sm text-slate-300">
        <x-primary-button>Save task</x-primary-button>
    </form>
</x-app-layout>
