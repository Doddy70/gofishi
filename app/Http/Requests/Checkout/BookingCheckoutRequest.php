<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class BookingCheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'room_id' => 'required|integer|exists:rooms,id',
            'day_package' => 'required|in:1,2,3',
            'checkInDate' => 'required|date',
            'adult' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'checkInTime' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'day_package.required' => __('Silakan pilih paket hari terlebih dahulu.'),
            'room_id.required' => __('Silakan pilih perahu terlebih dahulu.')
        ];
    }
}
