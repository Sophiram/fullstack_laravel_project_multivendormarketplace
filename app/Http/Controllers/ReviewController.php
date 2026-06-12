<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'review'    => 'required|string|max:1000',
        ]);

        Review::create([
            'product_id' => $request->product_id,
            'user_id'    => Auth::id(),
            'rating'     => $request->rating,
            'review'    => $request->review,
            'status'     => 'pending',
        ]);

        return redirect()->back()->with('success', 'Thank you for your review!');
    }
}
