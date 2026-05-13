<?php

namespace App\Services;

use App\Contracts\TaskRepositoryInterface;
use App\Events\TaskAssigned;
use App\Events\TaskCommentAdded;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskHistory;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskCommentAddedNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function __construct(private readonly TaskRepositoryInterface $tasks)
    {
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->tasks->paginateForCurrentTenant();
    }

    public function create(array $attributes): Task
    {
        return DB::transaction(function () use ($attributes) {
            $task = $this->tasks->create($attributes);

            $this->recordHistory($task, 'created', null, $task->status, ['assigned_user_id' => $task->assigned_user_id]);

            if ($task->assigned_user_id) {
                TaskAssigned::dispatch($task);
                $task->assignedUser?->notify(new TaskAssignedNotification($task));
            }

            return $task;
        });
    }

    public function update(Task $task, array $attributes): Task
    {
        return DB::transaction(function () use ($task, $attributes) {
            $originalStatus = $task->status;
            $originalAssignee = $task->assigned_user_id;
            $updatedTask = $this->tasks->update($task, $attributes);

            if ($originalStatus !== $updatedTask->status) {
                $this->recordHistory($updatedTask, 'status_changed', $originalStatus, $updatedTask->status);
            }

            if ($originalAssignee !== $updatedTask->assigned_user_id) {
                $this->recordHistory($updatedTask, 'assignee_changed', (string) $originalAssignee, (string) $updatedTask->assigned_user_id);
                TaskAssigned::dispatch($updatedTask);
                $updatedTask->assignedUser?->notify(new TaskAssignedNotification($updatedTask));
            }

            return $updatedTask;
        });
    }

    public function addComment(Task $task, int $userId, string $comment, array $attachments = []): TaskComment
    {
        return DB::transaction(function () use ($task, $userId, $comment, $attachments) {
            $taskComment = $task->comments()->create([
                'user_id' => $userId,
                'comment' => $comment,
            ]);

            foreach ($attachments as $attachment) {
                if ($attachment instanceof UploadedFile) {
                    $media = $task->addMedia($attachment)->toMediaCollection('attachments');
                    $media->update(['tenant_id' => tenant()?->id]);
                }
            }

            $this->recordHistory($task, 'comment_added', null, $comment, ['comment_id' => $taskComment->id]);
            TaskCommentAdded::dispatch($taskComment);

            $task->assignedUser?->notify(new TaskCommentAddedNotification($taskComment));

            return $taskComment;
        });
    }

    private function recordHistory(Task $task, string $event, ?string $fromValue, ?string $toValue, array $meta = []): void
    {
        TaskHistory::query()->create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'event' => $event,
            'from_value' => $fromValue,
            'to_value' => $toValue,
            'meta' => $meta,
        ]);
    }
}
