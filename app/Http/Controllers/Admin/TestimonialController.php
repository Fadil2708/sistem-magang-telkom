<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    use ApiResponse;

    public function togglePublish(string $id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update(['is_published' => !$testimonial->is_published]);

        $status = $testimonial->fresh()->is_published ? 'ditayangkan' : 'disembunyikan';

        return $this->success(
            new TestimonialResource($testimonial->fresh()->load('intern.internProfile')),
            "Testimoni berhasil {$status}."
        );
    }
}
