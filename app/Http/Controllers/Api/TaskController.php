<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Models\TaskUser;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = auth()->user()->tasks;

        return response()->json($tasks, 200);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description
        ]);

        TaskUser::create([
            'user_id' => auth()->user()->id,
            'task_id' => $task->id
        ]);
    }

    public function destroy($id)
    {
        $task = TaskUser::where('user_id', auth()->user()->id)->where('task_id' , $id)->first();
        if($task === null)
            return 'nothing to delete';    
        $task->delete();
        return 'deleted';
        
    }
}
