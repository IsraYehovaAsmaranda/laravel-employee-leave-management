<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\IntervieweeTask;
use App\Http\Requests\StoreIntervieweeTaskRequest;
use App\Http\Requests\UpdateIntervieweeTaskRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class IntervieweeTaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize("view-any", IntervieweeTask::class);

        $query = IntervieweeTask::query();

        if (request()->has("search")) {
            $query->where("name", "like", "%" . request()->search . "%")
                ->orWhere("description", "like", "%" . request()->search . "%");
        }

        if (request()->has("interviewee_id")) {
            $query->where("interviewee_id", request()->interviewee_id);
        }

        if (request()->has("task_id")) {
            $query->where("task_id", request()->task_id);
        }

        $sortBy = request()->query("sort_by", "created_at");
        if (!in_array($sortBy, ["name", "description", "created_at"])) {
            $sortBy = "created_at";
        }

        $sortOrder = request()->query("direction", "desc");
        if (!in_array($sortOrder, ["asc", "desc"])) {
            $sortOrder = "desc";
        }

        $query->orderBy($sortBy, $sortOrder);

        $size = request()->query("size", 10);
        $intervieweeTasks = $query->paginate($size);
        return ApiResponse::send($intervieweeTasks, "Successfully fetch interviewee tasks", 200, true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIntervieweeTaskRequest $request)
    {
        $this->authorize("create", IntervieweeTask::class);

        if (
            IntervieweeTask::where('interviewee_id', $request->interviewee_id)
                ->where('task_id', $request->task_id)
                ->exists()
        ) {
            throw ValidationException::withMessages([
                'interviewee_id' => ['Interviewee task already exists.']
            ]);
        }


        $data = $request->all();

        if ($request->hasFile("attachment")) {
            $path = $request->file("attachment")->store("interviewee_tasks", "public");
            $data["attachment"] = $path;
        }

        if ($request["score"] !== null) {
            $request["is_graded"] = true;
        }

        $intervieweeTask = IntervieweeTask::create($data);
        return ApiResponse::send($intervieweeTask, "Successfully created interviewee task");
    }

    /**
     * Display the specified resource.
     */
    public function show(IntervieweeTask $intervieweeTask)
    {
        $this->authorize("view", $intervieweeTask);
        return ApiResponse::send($intervieweeTask, "Successfully fetch interviewee task");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIntervieweeTaskRequest $request, IntervieweeTask $intervieweeTask)
    {
        $this->authorize("update", $intervieweeTask);

        if ($request->interviewee_id !== $intervieweeTask->interviewee_id || $request->task_id !== $intervieweeTask->task_id) {
            if (
                IntervieweeTask::where('interviewee_id', $request->interviewee_id)
                    ->where('task_id', $request->task_id)
                    ->exists()
            ) {
                throw ValidationException::withMessages([
                    'interviewee_id' => ['Interviewee task already exists.']
                ]);
            }
        }

        $data = $request->all();

        if ($request->hasFile("attachment")) {
            $path = $request->file("attachment")->store("interviewee_tasks", "public");
            $data["attachment"] = $path;

            if ($intervieweeTask->attachment && Storage::disk("public")->exists($intervieweeTask->attachment)) {
                Storage::disk("public")->delete($intervieweeTask->attachment);
            }
        }

        if ($request["score"] !== null) {
            $request["is_graded"] = true;
        }

        $intervieweeTask->update($data);
        return ApiResponse::send($intervieweeTask, "Successfully updated interviewee task");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IntervieweeTask $intervieweeTask)
    {
        $this->authorize("delete", $intervieweeTask);
        $intervieweeTask->delete();
        $response = [
            "deleted_at" => $intervieweeTask->deleted_at
        ];
        return ApiResponse::send($response, "Successfully deleted interviewee task");
    }
}
