<?php

namespace App\Http\Requests\Vacancy;

use Illuminate\Foundation\Http\FormRequest;

class StoreVacancyRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'                => 'required|string|max:255',
            'division'             => 'nullable|string|max:255',
            'description'          => 'required|string',
            'qualifications'       => 'required|string',
            'quota'                => 'required|integer|min:1',
            'start_date'           => 'required|date|after_or_equal:today',
            'end_date'             => 'required|date|after:start_date',
            'application_deadline' => 'required|date|before:start_date',
            'status'               => 'required|in:draft,open,closed',
        ];
    }
}
