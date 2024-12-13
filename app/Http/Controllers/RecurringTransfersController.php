<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RecurringTransfersRequest;
use App\Models\RecurringTransfers;
use App\Models\User;
use App\Services\RecurringTransfersService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RecurringTransfersController
{
    public function __construct(private readonly RecurringTransfersService $recurringTransfersService){}

    public function list()
    {
        $userId = Auth::user()->id;
        $recurringTransfers = RecurringTransfers::where('user_id', $userId)->get();

        return view('transfert-recurrent', ['recurringTransfers' => $recurringTransfers]);
    }

    public function create(RecurringTransfersRequest $request)
    {
        try {
            /**
             * @var User
             */
            $user = Auth::user();

            $recurringTransfer = $this->recurringTransfersService->createRecurringTransfer(
                $user,
                $request->validated()
            );

            return redirect()->route('api.recurring-transfers.list');

        } catch(Exception $e) {

            Log::error('Trace log : ' . $e->getMessage() . ' ' .$e->getTraceAsString());
        }
    }

    public function delete(int $id): JsonResponse
    {
        $userId = Auth::user()->id;
        $recurringTransfer = RecurringTransfers::where('user_id', $userId)->findOrFail($id);

        if ($recurringTransfer) {
            $recurringTransfer->delete();
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
