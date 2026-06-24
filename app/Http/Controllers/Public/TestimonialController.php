<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $testimonials = Testimonial::published()
            ->with('intern.internProfile')
            ->latest()
            ->paginate(15);

        return $this->success(
            TestimonialResource::collection($testimonials),
            meta: [
                'current_page' => $testimonials->currentPage(),
                'total' => $testimonials->total(),
            ]
        );
    }
}
