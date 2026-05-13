<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthTokenController extends Controller
{
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $token = $request->user()->createToken($request->user()->email.'-token');

        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $request->user(),
            'tenant' => tenant(),
        ], 201);
    }
}
