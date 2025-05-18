<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize("viewAny", User::class);
        $query = User::query();

        if ($request->has("search")) {
            $query->where("name", "like", "%{$request->search}%")
                ->orWhere("email", "like", "%{$request->search}%");
        }

        $sortBy = $request->query("sort_by", "created_at");
        if (!in_array($sortBy, ["name", "email", "created_at"])) {
            $sortBy = "created_at";
        }

        $sortOrder = $request->query("direction", "desc");
        if (!in_array($sortOrder, ["asc", "desc"])) {
            $sortOrder = "desc";
        }

        $query->orderBy($sortBy, $sortOrder);

        $size = $request->query("size", 10);

        $paginatedUser = $query->paginate($size);
        return ApiResponse::send($paginatedUser, "Successfully fetch user data", 200, true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize("create", User::class);
        $user = User::create($request->all());
        return ApiResponse::send($user, "Successfully created user", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $this->authorize("view", $user);
        return ApiResponse::send($user, "Successfully fetch user data by id");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->email !== $user->email) {
            User::where("email", $request->email)->exists() && throw ValidationException::withMessages([
                'email' => ['Email already exists']
            ]);
        }
        $this->authorize("update", $user);
        $user->update($request->all());
        return ApiResponse::send($user, "Successfully updated user");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize("delete", $user);
        $user->is_active = false;
        $user->save();
        return ApiResponse::send($user, "Successfully deleted user");
    }

    public function indexNoPagination()
    {
        $this->authorize("viewAny", User::class);
        $users = User::all();
        return ApiResponse::send($users, "Successfully fetch user data");
    }
}
