<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\InsufficientBalanceNotification;
use App\Models\RecurringTransfers;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RecurringTransfersService
{
    /**
     * @param \App\Models\User $user
     * @param array $data
     * @throws \Exception
     * @return mixed
     */
    public function createRecurringTransfer(User $user, array $data)
    {
        // vérifie si le wallet exist avant toute création de transfère.

        if (!$this->checkExistWallet(intval($data['target_wallet_id']))) {
            throw new Exception('Target wallet does not exist');
        }

        if ($user->wallet->balance < $data['amount']) {
            Mail::to($user->email)->send(new InsufficientBalanceNotification(
                amount: $data['amount'],
                balance: $user->wallet->balance
            ));

            throw new Exception('Insufficient balance for recurring transfer');
        }

        try {
            return DB::transaction(function () use ($user, $data) {
                $user->wallet->decrement('balance', $data['amount']);

                // create recurring transfer
                return RecurringTransfers::create([
                    'user_id' => $user->id,
                    'target_wallet_id' => $data['target_wallet_id'],
                    'amount' => $data['amount'],
                    'reason' => $data['reason'] ?? null,
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'] ?? null,
                    'frequency_in_day' => $data['frequency_in_day'],
                ]);
            });
        } catch (Exception $e) {
            Log::error('An error occurred : ' . $e->getMessage() . ' ' . $e->getTrace());
        }
    }

    /**
     * @param int $walletId
     * @return bool
     */
    private function checkExistWallet(int $walletId): bool
    {
        return User::whereHas('wallet', function ($query) use ($walletId) {
            $query->where('id', $walletId);
        })->exists();
    }
}
