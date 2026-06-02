<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PayoutsExport;

class SystemReportController extends Controller
{
    public function index()
    {
        $reportData = [
            // ស្ថិតិទូទៅ
            'total_users'     => User::count(),
            'total_vendors'   => User::where('role', 'vendor')->count(),
            'total_orders'    => Order::count(),
            'total_sales'     => Order::where('status', 'completed')->sum('total_amount'),
            'total_products'  => Product::count(),

            // ស្ថិតិដកប្រាក់
            'pending_payouts'  => PayoutRequest::where('status', 'pending')->sum('amount'),
            'approved_payouts' => PayoutRequest::where('status', 'approved')->sum('amount'),
        ];

        $payouts = PayoutRequest::with('user')->latest()->take(10)->get();

        return view('admin.reports.index', compact('reportData', 'payouts'));
    }

    public function export()
    {
        return Excel::download(new PayoutsExport, 'payout_report.xlsx');
    }
}
