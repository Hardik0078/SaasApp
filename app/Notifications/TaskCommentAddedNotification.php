<?php

namespace App\Notifications;

use App\Models\TaskComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskCommentAddedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly TaskComment $comment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->comment->task_id,
            'comment_id' => $this->comment->id,
            'comment' => $this->comment->comment,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('New task comment')
            ->line('A new comment was added to one of your tasks.');
    }
}
