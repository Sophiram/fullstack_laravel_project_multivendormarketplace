<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;


class UserMainController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // ស្ថិតិ: យើងមិនគួរប្រើ query object តែមួយសម្រាប់គ្រប់កន្លែងទេ
        // ដើម្បីចៀសវាងការជាន់គ្នា (Mutation)
        $stats = [
            'totalOrders'     => \App\Models\Order::where('user_id', $userId)->count(),
            'totalSpent'      => \App\Models\Order::where('user_id', $userId)->where('status', 'completed')->sum('total_amount'),
            'pendingOrders'   => \App\Models\Order::where('user_id', $userId)->whereIn('status', ['pending', 'processing'])->count(),
            'completedOrders' => \App\Models\Order::where('user_id', $userId)->where('status', 'completed')->count(),
        ];

        // ទាញយក Orders ថ្មីៗដោយប្រើ query ថ្មីមួយដាច់ដោយឡែក
        $orders = \App\Models\Order::where('user_id', $userId)->latest()->get();

        return view('user.dashboard', compact('stats', 'orders'));
    }

    public function history()
    {
        // ប្រើ paginate(10) ដើម្បីបង្ហាញតែ 10 orders ក្នុងមួយទំព័រ
        $orders = Order::where('user_id', auth()->id())->latest()->paginate(10);
        return view('user.orders.history', compact('orders'));
    }

    public function payment(){
        return view('user.payment');
    }
    public function affiliate(){
        return view('user.affiliate');
    }

    public function profile(Request $request): View
    {
        return view('user.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Fill ទិន្នន័យអក្សរទាំងអស់ (name, email, gender...) ចូលទៅក្នុង Model មុនគេ
        $user->fill($request->validated());

        // 2. បន្ទាប់មកទើបពិនិត្យ និងចាត់ចែងការ Upload រូបភាពតាមក្រោយ
        if ($request->hasFile('image')) {
            // លុបរូបភាពចាស់ចេញពី Storage (បើមាន)
            if ($user->getOriginal('image') && Storage::disk('public')->exists($user->getOriginal('image'))) {
                Storage::disk('public')->delete($user->getOriginal('image'));
            }

            // ប្រើ store() ដើម្បីផ្លាស់ទីរូបភាពទៅ storage/app/public/profile-images
            $path = $request->file('image')->store('profile-images', 'public');

            // កំណត់ Path ថ្មីចូលទៅក្នុង Model (វានឹងជាន់លើតម្លៃចាស់ដោយសុវត្ថិភាព)
            $user->image = $path;
        }

        // 3. រក្សាទុកទិន្នន័យទាំងអស់ចូល Database ព្រមគ្នាតែម្តង
        $user->save();

        return Redirect::route('user.profile')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


       public function show($id)
        {
            // ទាញយកពត៌មានលម្អិតនៃ Order និង Items ដែលមានក្នុង Order នោះ
            $order = \App\Models\Order::with(['items.product', 'shipping'])->findOrFail($id);

            // ពិនិត្យមើលថាតើ Order នេះជារបស់អ្នកប្រើដែលកំពុង Login មែនឬទេ (ការពារសុវត្ថិភាព)
            if ($order->user_id !== auth()->id()) {
                abort(403);
            }

            return view('user.orders.show', compact('order'));
        }
}
