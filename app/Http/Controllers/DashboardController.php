<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::inRandomOrder()->take(3)->get();

        return view('dashboard', compact('testimonials'));
    }
}
