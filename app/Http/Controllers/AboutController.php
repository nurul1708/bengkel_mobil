<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\Vehicle;
use App\Models\User; // 1. Pastikan Model User di-import di sini
use App\Models\Sparepart; // Ta

class AboutController extends Controller
{
    public function index()
    {
        return view('about.index', [
            'title' => 'About',
            'testimonials' => Testimonial::with('user')
                ->where('status', 'approved')
                ->latest('reviewed_at')
                ->latest('id')
                ->take(8)
                ->get(),
            
            // 2. Tambahkan variabel team di sini
            'team' => User::where('role', 'mekanik')->get(), 

        ]);
    }
}
