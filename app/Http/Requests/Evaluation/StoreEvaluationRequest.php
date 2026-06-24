<?php

namespace App\Http\Requests\Evaluation;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'soft_skill_score' => 'required|numeric|min:0|max:100',
            'hard_skill_score' => 'required|numeric|min:0|max:100',
            'attendance_score' => 'required|numeric|min:0|max:100',
            'attitude_score'   => 'required|numeric|min:0|max:100',
            'remarks'          => 'nullable|string|max:1000',
        ];
    }
}
