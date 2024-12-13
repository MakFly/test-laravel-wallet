<?php

declare(strict_types=1);

use App\Models\RecurringTransfers;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\actingAs;

it('can create recurring transfer', function () {
    Mail::fake();

    /** @var User $user */
    $user = User::factory()->has(Wallet::factory()->state(['balance' => 1000000]))->create();
    $targetUser = User::factory()->create();

    $response = actingAs($user)->postJson('/recurring-transfers/create', [
        'user_id' => $user->id,
        'target_wallet_id' => Wallet::factory()->for($targetUser)->create()->id,
        'amount' => 500,
        'reason' => 'Monthly rent',
        'start_date' => now(),
        'frequency_in_day' => 30
    ]);

    $response->assertStatus(201)->assertJson(['message' => 'Recurring transfer created success']);

    expect(RecurringTransfers::where([
        'user_id' => $user->id,
        'amount' => 500,
        'reason' => 'Monthly rent',
    ])->exist())->toBeTrue();

    
});