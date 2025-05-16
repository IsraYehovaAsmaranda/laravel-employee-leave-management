<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where("email", $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        $token = $user->createToken("auth")->plainTextToken;

        return response()->json([
            "token" => $token,
            "roles" => $user->getRoleNames(),
            "permission" => $user->getAllPermissions()->pluck("name")
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "message" => "Logout successfully",
            "timeLogout" => now()->format("Y-m-d H:i:s")
        ], 200);
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        $token = $user->createToken("auth")->plainTextToken;

        return response()->json([
            "token" => $token,
            "roles" => $user->getRoleNames(),
            "permission" => $user->getAllPermissions()->pluck(value: "name")
        ], 200);
    }
}
