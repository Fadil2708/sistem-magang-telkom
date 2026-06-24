<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInternProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'full_name'          => 'required|string|max:255',
            'phone'              => 'nullable|string|max:20',
            'date_of_birth'      => 'nullable|date|before:today',
            'institution_name'   => 'required|string|max:255',
            'institution_type'   => 'required|in:university,vocational,highschool',
            'major'              => 'required|string|max:255',
            'student_id'         => 'required|string|max:100',
            'photo_url'          => 'nullable|file|mimetypes:image/jpeg,image/png|max:2048',
            'cv_url'             => 'nullable|file|mimetypes:application/pdf|max:5120',
            'cover_letter_url'   => 'nullable|file|mimetypes:application/pdf|max:5120',
        ];
    }
}
