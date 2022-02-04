<?php

namespace App\Http\Controllers;

use App\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function getFeatured(){
        $tests = Testimonial::where('is_featured', true)->where('is_confirmed', true)->with('user.service')->take(4)->get();

        return response()->json($tests, 200);
    }

    public function getPgndtTestimonials(){
        $tests = Testimonial::with('user.service')->latest()->paginate(20);

        return response()->json($tests, 200);
    }
}
