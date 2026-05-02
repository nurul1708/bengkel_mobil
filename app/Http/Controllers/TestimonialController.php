<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        return view('testimonial.index', [
            'title' => 'Testimonial',
            'testimonials' => Testimonial::with('user')
                ->where('status', 'approved')
                ->latest('reviewed_at')
                ->latest('id')
                ->get(),
        ]);
     }

     public function approve(Request $request, $id)
{
    $testimonial = Testimonial::findOrFail($id);
    $testimonial->update([
        'status' => 'approved',
        'reviewer_id' => auth()->id(), // ID Admin yang sedang login
        'reviewed_at' => now(),        // Merekam tanggal DAN JAM detik ini
        'admin_note' => $request->admin_note
    ]);

    return redirect()->back()->with('success', 'Testimonial berhasil disetujui!');
}
}
