<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Interviewee;
use App\Models\IntervieweeTask;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        return ApiResponse::send(Audit::paginate(10), "Successfully fetch audit data");
    }

    public function users()
    {
        $this->authorize("viewAny", User::class);
        $query = Audit::where('auditable_type', User::class);

        $query->orderBy('created_at', 'desc');

        $size = request()->query("size", 10);
        $audits = $query->with('auditable', 'user')->paginate($size);
        return ApiResponse::send($audits, "Successfully fetch audit data", 200, true);
    }

    public function userById(User $user)
    {
        $this->authorize("view", $user);
        $size = request()->query("size", 10);
        return ApiResponse::send($user->audits()->with('auditable', 'user')->paginate($size), "Successfully fetch audit data", 200, true);
    }

    public function roles()
    {
        $this->authorize("viewAny", Role::class);
        $query = Audit::where('auditable_type', Role::class);

        $query->orderBy('created_at', 'desc');

        $size = request()->query("size", 10);
        $audits = $query->with('auditable', 'user')->paginate($size);
        return ApiResponse::send($audits, "Successfully fetch audit data", 200, true);
    }

    public function roleById(Role $role)
    {
        $this->authorize("view", $role);
        $size = request()->query("size", 10);
        return ApiResponse::send($role->audits()->with('auditable', 'user')->paginate($size), "Successfully fetch audit data", 200, true);
    }

    public function tasks() {
        $this->authorize("viewAny", Task::class);
        $query = Audit::where('auditable_type', Task::class);

        $query->orderBy('created_at', 'desc');

        $size = request()->query("size", 10);
        $audits = $query->with('auditable', 'user')->paginate($size);
        return ApiResponse::send($audits, "Successfully fetch audit data", 200, true);
    }

    public function taskById(Task $task) {
        $this->authorize("view", $task);
        $size = request()->query("size", 10);
        return ApiResponse::send($task->audits()->with('auditable', 'user')->paginate($size), "Successfully fetch audit data", 200, true);
    }

    public function interviewees() {
        $this->authorize("viewAny", Interviewee::class);
        $query = Audit::where('auditable_type', Interviewee::class);

        $query->orderBy('created_at', 'desc');

        $size = request()->query("size", 10);
        $audits = $query->with('auditable', 'user')->paginate($size);
        return ApiResponse::send($audits, "Successfully fetch audit data", 200, true);
    }

    public function intervieweeById(Interviewee $interviewee) {
        $this->authorize("view", $interviewee);
        $size = request()->query("size", 10);
        return ApiResponse::send($interviewee->audits()->with('auditable', 'user')->paginate($size), "Successfully fetch audit data", 200, true);
    }

    public function intervieweeTasks() {
        $this->authorize("viewAny", IntervieweeTask::class);
        $query = Audit::where('auditable_type', IntervieweeTask::class);

        $query->orderBy('created_at', 'desc');

        $size = request()->query("size", 10);
        $audits = $query->with('auditable', 'user')->paginate($size);
        return ApiResponse::send($audits, "Successfully fetch audit data", 200, true);
    }

    public function intervieweeTaskById(IntervieweeTask $intervieweeTask) {
        $this->authorize("view", $intervieweeTask);
        $size = request()->query("size", 10);
        return ApiResponse::send($intervieweeTask->audits()->with('auditable', 'user')->paginate($size), "Successfully fetch audit data", 200, true);
    }
}
