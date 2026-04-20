<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;

class RoomSearchRequest extends FormRequest
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
            'checkInDates' => 'nullable|string',
            'checkInTimes' => 'nullable|string',
            'adult' => 'nullable|integer|min:0',
            'children' => 'nullable|integer|min:0',
            'category' => 'nullable|string',
            'sort' => 'nullable|string|in:new,old,farthest',
            'location_val' => 'nullable|string',
            'title' => 'nullable|string',
            'amenitie' => 'nullable|string',
        ];
    }
}
