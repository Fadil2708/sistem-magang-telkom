<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupervisorProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'full_name'    => 'required|string|max:255',
            'employee_id'  => 'nullable|string|max:100',
            'division'     => 'nullable|string|max:255',
            'position'     => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:20',
        ];
    }
}
