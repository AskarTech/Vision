<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutWithWalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'idempotency_key' => ['required', 'string', 'max:80'],
            'points_to_redeem' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.package_id' => ['required', 'integer', 'exists:packages,id'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
