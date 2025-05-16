<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

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
        if(!in_array($sortOrder, ["asc", "desc"])) {
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
        return ApiResponse::send($user, "Successfully created user");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->authorize("view", User::class);
        $user = User::findOrFail($id);
        return ApiResponse::send($user, "Successfully fetch user data by id");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $this->authorize("update", User::class);
        $user = User::findOrFail($id);


        $user->update($request->all());
        return ApiResponse::send($user, "Successfully updated user");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize("delete", User::class);
        $user = User::findOrFail($id);
        $user->is_active = false;
        $user->save();
        return ApiResponse::send($user, "Successfully deleted user");
    }
}
