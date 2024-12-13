<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LoginController
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = User::query()->where('email', $request->validated('email'))->first();

        if (is_null($user)) {
            return response()->json([
                'message' => 'Invalid credentials.',
                'code' => 'BAD_LOGIN',
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$user->wallet) {
            $user->wallet()->create([
                'balance' => 0
            ]);
        }

        $token = $user->createToken($request->validated('device_name'))->plainTextToken;

        return response()->json([
            'data' => [
                'token' => $token,
            ],
        ], Response::HTTP_CREATED);
    }
}
