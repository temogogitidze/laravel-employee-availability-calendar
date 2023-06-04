<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCalendarEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id', 'integer'],
            'start_date' => ['required'],
            'end_time' => ['required'],
            'event_type' => ['required', 'string', 'max:255']
        ];
    }
}
