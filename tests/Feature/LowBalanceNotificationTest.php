<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Wallet;
use App\Notifications\LowBalanceNotification;
use Illuminate\Support\Facades\Notification;

it('sends a low balance notification', function () {
    Notification::fake();

    $user = User::factory()->create();
    $wallet = Wallet::factory()->create([
        'user_id' => $user->id,
        'balance' => 2000, // 20 €
    ]);

    $wallet->update(['balance' => 500]); // 5 €

    Notification::assertSentTo(
        [$user],
        LowBalanceNotification::class
    );
});
