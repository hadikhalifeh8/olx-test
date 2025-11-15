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
use Illuminate\Support\Facades\Log;

class SendCommentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $taskId;
    protected $commenterName;
    protected $commentText;
    protected $ownerEmail;

    /**
     * Create a new job instance.
     */
    public function __construct($taskId, $commenterName, $commentText, $ownerEmail)
    {
        $this->taskId = $taskId;
        $this->commenterName = $commenterName;
        $this->commentText = $commentText;
        $this->ownerEmail = $ownerEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $task = Task::find($this->taskId);
            
            if (!$task) {
                Log::error('Task not found with ID: ' . $this->taskId);
                return;
            }

            if (!$this->ownerEmail) {
                Log::warning('Owner email not provided for task ID: ' . $this->taskId);
                return;
            }

            Mail::raw(
                "A new comment was added to your task: {$task->task_name}\n\n" .
                "Comment: {$this->commentText}\n" .
                "By: {$this->commenterName}",
                function ($message) {
                    $message->to($this->ownerEmail)
                            ->subject('New Comment Notification');
                }
            );
            Log::info('Comment notification sent to ' . $this->ownerEmail);
        } catch (\Exception $e) {
            Log::error('Failed to send comment notification: ' . $e->getMessage());
        }
    }
}
