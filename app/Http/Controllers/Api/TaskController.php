<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\DeleteTaskResource;
use App\Http\Resources\StoreTaskResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\TaskUser;
use Illuminate\Support\Facades\Response;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = auth()->user()->tasks;

        return TaskResource::collection($tasks);
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

        return new StoreTaskResource($task);
    }

    public function destroy($id)
    {
        $task = TaskUser::where('user_id', auth()->user()->id)->where('task_id' , $id)->first();
        if($task === null)
            return Response::error('there is nothing to delete.' , 404);
        Task::destroy($id);
        return new DeleteTaskResource($task);
        
    }
}
