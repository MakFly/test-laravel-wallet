<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\AccountResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function getBalanceUser(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return new JsonResponse(['error' => 'User doest not have a wallet'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new JsonResponse(new AccountResource($user));
    }

    public function updateBalance(Request $request): JsonResponse
    {
        $request->validate([
            'balance' => 'required|numeric|min:0'
        ]);

        $user = Auth::user();

        if (!$user) {
            return new JsonResponse(['error' => 'User doest not have a wallet'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::find($user->id);
        $user->wallet->balance = $request->balance;

        $user->wallet->save();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
