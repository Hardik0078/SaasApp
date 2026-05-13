<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function __construct(private readonly TaskService $tasks)
    {
    }

    public function store(Request $request, $tenant, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $comment = $this->tasks->addComment(
            $task,
            (int) $request->user()->id,
            $request->validate([
                'comment' => ['required', 'string'],
                'attachments.*' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg,doc,docx,xlsx,csv,txt', 'max:4096'],
            ])['comment'],
            $request->file('attachments', []),
        );

        return response()->json($comment->load('user'), 201);
    }
}
