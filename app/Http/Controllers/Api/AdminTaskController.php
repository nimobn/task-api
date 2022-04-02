<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminTaskController extends Controller
{
    public function getUsers()
    {
        return response()->json(User::all(), 200);
    }

    public function index()
    {
        return response()->json(Task::all(), 200);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update([
            'title' => $request->title,
            'description' => $request->description
        ]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(["message" => "task has deleted."], 204);
    }

    public function mention(Task $task)
    {
        if (TaskUser::where('user_id', auth()->user()->id)->where('task_id' , $task->id)->exists())
            return response()->json(["message" => "you have mentioned to this task befor!"], 422);
        
        TaskUser::create([
            'user_id' => auth()->user()->id,
            'task_id' => $task->id
        ]);

    }
}
