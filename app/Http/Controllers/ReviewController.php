<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // 1. ពិនិត្យផ្ទៀងផ្ទាត់ទិន្នន័យ (Validation)
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'review'    => 'required|string|max:1000',
        ]);

        // 2. រក្សាទុកចូលក្នុង Table reviews
        Review::create([
            'product_id' => $request->product_id,
            'user_id'    => Auth::id(), // ទាញយក ID របស់ User ដែលកំពុង Login
            'rating'     => $request->rating,
            'review'    => $request->review,
            'status'     => 'pending', // 💡 អ្នកអាចដាក់ 'pending' ប្រសិនបើចង់ឱ្យ Admin ពិនិត្យសិន
        ]);

        return redirect()->back()->with('success', 'Thank you for your review!');
    }
}
