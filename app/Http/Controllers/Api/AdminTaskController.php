<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\DeleteTaskResource;
use App\Http\Resources\MentionTaskResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UpdateTaskResource;
use App\Http\Resources\UserResource;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class AdminTaskController extends Controller
{
    public function getUsers()
    {
        return UserResource::collection(User::all());
    }

    public function index()
    {
        return TaskResource::collection((Task::all()));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return new UpdateTaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return new DeleteTaskResource($task);
    }

    public function mention(Task $task)
    {
        if (TaskUser::where('user_id', auth()->user()->id)->where('task_id' , $task->id)->exists())
            return Response::error('you have mentioned to this task befor.', 422);
        
        TaskUser::create([
            'user_id' => auth()->user()->id,
            'task_id' => $task->id
        ]);

        return new MentionTaskResource($task);
    }
}
