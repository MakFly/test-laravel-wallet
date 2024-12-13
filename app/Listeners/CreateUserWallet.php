<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Wallet;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class CreateUserWallet
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     * @var Registered $event
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        try {
            $wallet = new Wallet([
                'balance' => 0
            ]);

            $wallet->user()->associate($user);

            $wallet->save();
        } catch (Exception $e) {
            Log::error('Create Wallet User Register : ' . $e->getMessage() . ' ' . $e->getTrace());
        }
    }
}
