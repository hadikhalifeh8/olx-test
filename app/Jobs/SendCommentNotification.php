<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCommentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;
    protected $commenter;
    protected $commentText;

    /**
     * Create a new job instance.
     */
    public function __construct(Task $task, User $commenter, string $commentText)
    {
        $this->task = $task;
        $this->commenter = $commenter;
        $this->commentText = $commentText;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
    $owner = $this->task->user_rln;

    if ($owner && $owner->email) {
        Mail::raw(
            "A new comment was added to your task: {$this->task->task_name}\n\n" .
            "Comment: {$this->commentText}\n" .
            "By: {$this->commenter->name}",
            function ($message) use ($owner) {
                $message->to($owner->email)
                        ->subject('New Comment Notification');
            }
        );
    }
    }
}
