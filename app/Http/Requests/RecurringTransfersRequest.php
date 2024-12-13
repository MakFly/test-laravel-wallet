<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecurringTransfersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'target_wallet_id' => 'required:exists:wallets,id',
            'amount' => 'required|integer|min:1',
            'reason' => 'nullable|string',
            'start_date' => 'required|date|after:today',
            'end_data' => 'nullable|date|after:start_date',
            'frequency_in_day' => 'required|integer|min:1'
        ];
    }
}
