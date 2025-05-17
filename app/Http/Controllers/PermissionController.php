<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index() {
        $permissions = Permission::all();
        return ApiResponse::send($permissions, "Successfully fetch permissions");
    }
}
