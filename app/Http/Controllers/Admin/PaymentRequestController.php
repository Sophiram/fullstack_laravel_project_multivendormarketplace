<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; 
use App\Models\PayoutRequest;
use Illuminate\Http\Request;

class PaymentRequestController extends Controller
{
    // បង្ហាញបញ្ជីសំណើទាំងអស់សម្រាប់ Admin
    public function index()
    {
        $requests = PayoutRequest::with('user')->latest()->get();
        return view('admin.payouts.index', compact('requests'));
    }

    // អនុម័តសំណើដកប្រាក់
    public function approve($id)
    {
        $payout = PayoutRequest::findOrFail($id);

        // ពិនិត្យមើលថាវា pending ទើបអាច approve បាន
        if ($payout->status !== 'pending') {
            return redirect()->back()->with('error', 'This request is already processed.');
        }

        $payout->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Payout request has been approved!');
    }
    // បដិសេធសំណើដកប្រាក់
    public function reject($id)
    {
        $payout = PayoutRequest::findOrFail($id);

        if ($payout->status !== 'pending') {
            return redirect()->back()->with('error', 'This request is already processed.');
        }

        $payout->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Payout request has been rejected.');
    }
}
