<?php

namespace App\Http\Controllers;
use App\Jobs\SendCommentNotification;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\StoreRequest;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;


class TaskController extends Controller
{
    public function createTask(StoreRequest $request)
    {
        $validator = $request->validated();

            $tasks = new Task();
            $tasks->user_id = $validator['user_id'];
            $tasks->task_name = $validator['task_name'];
            $tasks->save();
    

    return response()->json([
        'status' => 'success',
        'data' => $tasks,
    ]);
    }
    
    
    public function updateTask(StoreRequest $request, $id)
    {
        $validator = $request->validated();

        $tasks = Task::find($id);
        if (!$tasks) {
            return response()->json([
                'status' => 'failure',
                'data' => 'Task not found',
            ]);
        }

        $tasks->user_id = $validator['user_id'];
        $tasks->task_name = $validator['task_name'];
        $tasks->save();

        return response()->json([
            'status' => 'success',
            'data' => $tasks,
        ]);
    }



    public function deleteTask($id)
{
    $task = Task::find($id);

    if (!$task) {
        return response()->json([
            'status' => 'failure',
            'message' => 'Task not found'
        ]);
    }

    $task->delete(); 

    return response()->json([
        'status' => 'success',
        'message' => 'Task soft deleted successfully'
    ]);
    }


    // public function gettask($user_id)
    // {
    //     $user = User::where('id', $user_id)->first();

    //     if($user){
    //         $tasks = Task::with('user_rln')->where('user_id', $user_id)->get();

    //         if($tasks->count() > 0){
                
    //             return response()->json([
    //                 'status' => 'success',
    //                 'data' => $tasks,
    //             ]);

    //          } else{
    //                 return response()->json([
    //                 'status' => 'failure',
    //                 'data' => 'No tasks found for this user '.$user_id,
    //             ]);

    //             }

            

    //     }else{
    //           return response()->json([
    //           'status' => 'failure',
    //           'data' => 'no user found '.$user_id,
    //           ]);

    //     }
    // }

    public function gettask($user_id)
 {

    $user = User::find($user_id);

    if (!$user) {
        return response()->json([
            'status' => 'failure',
            'data' => 'No user found ' . $user_id,
        ]);
    }


    $cacheKey = "tasks_user_{$user_id}";

    $tasks = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($user_id) {
        return Task::with('user_rln')
            ->where('user_id', $user_id)
            ->get();
    });

    if ($tasks->isEmpty()) {
        return response()->json([
            'status' => 'failure',
            'data' => 'No tasks found for this user ' . $user_id,
        ]);
    }

    return response()->json([
        'status' => 'success',
        'cached' => Cache::has($cacheKey),
        'data' => $tasks,
    ]);
 }


  public function addComment(CommentRequest $request, $id)
{
    $task = Task::find($id);
    if (!$task) {
        return response()->json([
            'status' => 'failure',
            'message' => 'Task not found',
        ], 404);
    }

    // Create comment
    $comment = Comment::create([
        'user_id' => $request->user()->id,
        'task_id' => $task->id,
        'description' => $request->description,
    ]);

    // Dispatch email notification job
     SendCommentNotification::dispatch($task, $request->user(), $request->description);

    return response()->json([
        'status' => 'success',
        'data' => $comment,
        'message' => 'Comment added and notification queued',
    ]);
}



}
