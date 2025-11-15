<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TasksPerUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tasks-per-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List the number of tasks per user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::withCount('tasks_rln')->get();

        $this->info("Number of tasks per user:");
        $this->table(
            ['User ID', 'Name', 'Email', 'Tasks Count'],
            $users->map(fn($user) => [
                $user->id,
                $user->name,
                $user->email,
                $user->tasks_rln_count
            ])
        );
    }
}
