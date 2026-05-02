<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        if (!in_array($status, ['pending', 'approved', 'rejected', 'all'], true)) {
            $status = 'pending';
        }

        $testimonials = Testimonial::with(['user', 'booking.service', 'transaction', 'reviewer'])
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('id')
            ->get();

        return view('admin.testimonials.index', [
            'title' => 'Testimonial Review',
            'testimonials' => $testimonials,
            'status' => $status,
        ]);
    }

    public function approve(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $testimonial->update([
            'status' => 'approved',
            'admin_note' => $validated['admin_note'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect('/admin/testimonials?status=' . $request->query('status', 'pending'))
            ->with('success', 'Testimonial berhasil di-approve dan akan tampil di client.');
    }

    public function reject(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $testimonial->update([
            'status' => 'rejected',
            'admin_note' => $validated['admin_note'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect('/admin/testimonials?status=' . $request->query('status', 'pending'))
            ->with('success', 'Testimonial berhasil di-reject.');
    }
}
