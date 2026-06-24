<?php

namespace App\Http\Controllers\Public\Web;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::published()
            ->with('intern.internProfile')
            ->latest()
            ->paginate(12);

        return view('public.testimonials', compact('testimonials'));
    }
}
