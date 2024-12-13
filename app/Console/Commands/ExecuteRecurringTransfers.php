<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\PerformWalletTransfer;
use App\Models\RecurringTransfers;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExecuteRecurringTransfers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:execute-recurring-transfers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(PerformWalletTransfer $performWalletTransfer)
    {
        $today = Carbon::today();

        $recurringTransfers = RecurringTransfers::where('start_date', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })->get();

        foreach ($recurringTransfers as $transfer) {
            $daysSinceStart = $transfer->start_date->diffInDays($today);

            if ($daysSinceStart % $transfer->frequency_in_day === 0) {
                try {
                    $performWalletTransfer->execute(
                        User::find($transfer->user_id),
                        $transfer->targetWallet->user,
                        $transfer->amount,
                        $transfer->reason,
                    );
                } catch (Exception $e) {
                    Log::error('Trace log command : ' . $e->getMessage());
                }
            }
        }
    }
}
