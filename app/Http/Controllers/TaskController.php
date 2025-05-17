<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize("viewAny", Task::class);
        $query = Task::query();

        if (request()->has("search")) {
            $query->where("name", "like", "%" . request()->search . "%")
                ->orWhere("description", "like", "%" . request()->search . "%");
        }

        $sortBy = request()->query("sort_by", "created_at");
        if (!in_array($sortBy, ["name", "created_at"])) {
            $sortBy = "created_at";
        }

        $sortOrder = request()->query("direction", "desc");
        if (!in_array($sortOrder, ["asc", "desc"])) {
            $sortOrder = "desc";
        }

        $query->orderBy($sortBy, $sortOrder);

        $size = request()->query("size", 10);
        $tasks = $query->paginate($size);
        return ApiResponse::send($tasks, "Successfully fetch tasks", 200, true);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $this->authorize("create", Task::class);

        $data = $request->all();

        if ($request->hasFile("attachment")) {
            $path = $request->file("attachment")->store("tasks", "public");
            $data["attachment"] = $path;
        }


        $task = Task::create($data);
        return ApiResponse::send($task, "Successfully created task");
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize("view", $task);
        return ApiResponse::send($task, "Successfully fetch task");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize("update", $task);

        $data = $request->all();

        if($request->hasFile("attachment")) {
            $path = $request->file("attachment")->store("tasks", "public");
            $data["attachment"] = $path;

            if ($task->attachment && Storage::disk("public")->exists($task->attachment)) {
                Storage::disk("public")->delete($task->attachment);
            }
        }

        $task->update($data);
        return ApiResponse::send($task, "Successfully updated task");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize("delete", $task);
        $task->delete();
        $response = [
            "deleted_at" => $task->deleted_at
        ];
        return ApiResponse::send($response, "Successfully deleted task");
    }

    public function indexNoPagination()
    {
        $this->authorize("viewAny", Task::class);
        $tasks = Task::all();
        return ApiResponse::send($tasks, "Successfully fetch tasks");
    }

    public function downloadAttachment(Task $task)
    {
        $this->authorize("view", $task);
        if (!$task->attachment || !Storage::disk("public")->exists($task->attachment)) {
            return ApiResponse::send(null, "Attachment not found", 404);
        }
        return Storage::disk("public")->download($task->attachment);
    }
}
