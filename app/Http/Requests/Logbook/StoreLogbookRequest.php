<?php

namespace App\Http\Requests\Logbook;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogbookRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'activity_date' => 'required|date|before_or_equal:today',
            'activities'    => 'required|string|min:20',
            'output'        => 'required|string|min:10',
        ];
    }
}
