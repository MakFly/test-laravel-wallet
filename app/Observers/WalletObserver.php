<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use App\Models\Wallet;
use App\Notifications\LowBalanceNotification;
use Illuminate\Support\Facades\Log;

class WalletObserver
{
    public function updating(Wallet $wallet)
    {
        // check if the sold < at 10 €
        if ($wallet->balance < 1000 && !$wallet->getOriginal('balance') < 1000) {
            if (isset($wallet->user) && $wallet->user instanceof User) {
                $wallet->user->notify(new LowBalanceNotification());
            } else {
                Log::error('Notification Impossible for the wallet user');
            }
        }

        Log::info('Your wallet has been update');
    }
}
