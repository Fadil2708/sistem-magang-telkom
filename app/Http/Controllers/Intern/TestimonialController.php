<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Http\Requests\Testimonial\StoreTestimonialRequest;
use App\Http\Resources\TestimonialResource;
use App\Models\Internship;
use App\Models\Testimonial;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    use ApiResponse;

    public function store(string $internshipId, StoreTestimonialRequest $request): JsonResponse
    {
        $internship = Internship::where('intern_id', $request->user()->id)
            ->where('status', 'completed')
            ->findOrFail($internshipId);

        if ($internship->testimonial) {
            return $this->error('Testimoni sudah pernah dikirim untuk magang ini.', 422);
        }

        $testimonial = Testimonial::create([
            'id' => (string) Str::uuid(),
            'intern_id' => $request->user()->id,
            'internship_id' => $internshipId,
            'rating' => $request->rating,
            'content' => $request->content,
            'is_published' => false,
        ]);

        return $this->success(
            new TestimonialResource($testimonial),
            'Testimoni berhasil dikirim.',
            201
        );
    }
}
