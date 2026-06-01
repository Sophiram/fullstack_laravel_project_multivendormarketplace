<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayoutRequest; // ប្រើមួយនេះ
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class VendorPayoutController extends Controller
{
    /**
     * បង្ហាញទំព័រដកប្រាក់ និងគណនាទឹកប្រាក់សរុបរបស់ Vendor
     */
    public function index()
    {
        $vendorId = Auth::id();

        // ១. គណនាចំណូលសុទ្ធសរុបពីការលក់ (Net Earnings) ដែលទទួលបានជោគជ័យ
        $totalEarnings = OrderItem::where('vendor_id', $vendorId)
            ->whereHas('order', function($query) {
                $query->where('status', 'completed'); // គិតតែ Order ដែលជោគជ័យ
            })
            ->sum('vendor_net_amount');

        // ២. គណនាទឹកប្រាក់ដែលបានដកជោគជ័យរួចរាល់
        $totalPaid = \App\Models\VendorPayout::where('vendor_id', $vendorId)
            ->where('status', 'Approved')
            ->sum('amount');

        // ៣. គណនាទឹកប្រាក់ដែលកំពុងស្ថិតក្នុងលក្ខខណ្ឌរង់ចាំ (Pending)
        $totalPending = \App\Models\VendorPayout::where('vendor_id', $vendorId)
            ->where('status', 'Pending')
            ->sum('amount');

        // ៤. ទឹកប្រាក់នៅសល់ក្នុងកាបូបដែលអាចដកបាន (Available Balance)
        $availableBalance = $totalEarnings - $totalPaid - $totalPending;

        // ៥. ទាញយកប្រវត្តិនៃការដកប្រាក់របស់ Vendor នេះ
        $payouts = PayoutRequest::where('user_id', $vendorId)
        ->latest()->paginate(10);
        return view('vendor.payout', compact('availableBalance', 'totalEarnings', 'totalPending', 'payouts'));
    }

    /**
     * ដំណើរការដាក់ពាក្យស្នើសុំដកប្រាក់
     */
    public function requestPayout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:5',
        ], [
            'amount.min' => 'The amount must be at least $5.00',
        ]);

        $vendorId = Auth::id();
        $user = Auth::user();

        // ទាញយកព័ត៌មានគណនីធនាគារពី Profile (តារាង vendor ឬ user)
        $bankInfo = $user->vendor->bank_account_info ?? '';

        if (empty($bankInfo)) {
            return redirect()->back()->with('error', 'Please configure your bank details in profile settings first.');
        }

        // ពិនិត្យមើលទឹកប្រាក់ក្នុងកាបូប (Logic ដូចខាងលើ)
        $totalEarnings = OrderItem::where('vendor_id', $vendorId)->whereHas('order', function($q){$q->where('status','completed');})->sum('vendor_net_amount');
        $totalPaid = \App\Models\VendorPayout::where('vendor_id', $vendorId)->where('status', 'Approved')->sum('amount');
        $totalPending = \App\Models\VendorPayout::where('vendor_id', $vendorId)->where('status', 'Pending')->sum('amount');
        $availableBalance = $totalEarnings - $totalPaid - $totalPending;

        if ($request->amount > $availableBalance) {
            return redirect()->back()->with('error', 'Insufficient balance for this withdrawal request.');
        }

        // បង្កើតកំណត់ត្រាស្នើសុំដកប្រាក់
        \App\Models\VendorPayout::create([
            'vendor_id' => $vendorId,
            'amount' => $request->amount,
            'bank_details_snapshot' => $bankInfo, // រក្សាទុកកុំឱ្យមានបញ្ហានៅពេល Vendor ដូរលេខកាតក្រោយ
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Your payout request has been submitted successfully.');
    }
}
