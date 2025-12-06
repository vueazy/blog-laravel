<?php

namespace App\Http\Controllers;

use App\Http\Resources\Auth\LoginResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt($request->validated())) {

            $user = Auth::user();
            $token = $user->createToken('auth_user');

            return responseJson(
                message: 'Login Success',
                data: [
                    'user' => new LoginResource($user),
                    'access_token' => $token->plainTextToken,
                ]
            );
        } 

        return responseJson(
            success: false,
            message: 'Invalid credentials',
            code: 401
        );
    }

    public function revokingAccessToken(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return responseJson(
            message: 'Logout Successfully!',
            code: 201
        );
    }
}
