<?php

namespace App\Http\Requests\Testimonial;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonialRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'rating'  => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:20|max:1000',
        ];
    }
}
