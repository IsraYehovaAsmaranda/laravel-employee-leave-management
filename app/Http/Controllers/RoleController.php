<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize("viewAny", Role::class);
        $query = Role::query();

        if (request()->has("search")) {
            $query->where("name", "like", "%".request()->search."%");
        }

        $sortBy = request()->query("sort_by", "created_at");
        if (!in_array($sortBy, ["name", "created_at"])) {
            $sortBy = "created_at";
        }

        $sortOrder = request()->query("direction", "desc");
        if(!in_array($sortOrder, ["asc", "desc"])) {
            $sortOrder = "desc";
        }

        $query->orderBy($sortBy, $sortOrder);

        $size = request()->query("size", 10);
        $roles = $query->with('permissions')->paginate($size);
        return ApiResponse::send($roles, "Successfully fetch roles", 200, true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $this->authorize("create", Role::class);
        $role = Role::create([
            "id" => (string) Str::uuid(),
            "name" => $request->name,
            "guard_name" => "api",
        ]);

        if($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return ApiResponse::send($role->load('permissions'), "Successfully created role", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $this->authorize("view", $role);
        return ApiResponse::send($role->load('permissions'), "Successfully fetch role");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->authorize("update", $role);
        $role->update($request->all());
        if($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return ApiResponse::send($role->load('permissions'), "Successfully updated role");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->authorize("delete", $role);
        $role->deleted_by = Auth::user()->id;
        $role->delete();
        $response = [
            "deleted_at" => $role->deleted_at,
            "deleted_by" => $role->deleted_by
        ];

        return ApiResponse::send($response, "Successfully deleted role");
    }

    public function indexNoPagination() {
        $this->authorize("viewAny", Role::class);
        $roles = Role::with('permissions')->get();
        return ApiResponse::send($roles, "Successfully fetch roles");
    }
}
