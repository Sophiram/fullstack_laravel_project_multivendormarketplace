<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SystemReportExport; // ប្តូរមកប្រើ Export Class ថ្មីនេះ

class SystemReportController extends Controller
{
    public function index()
    {
        $reportData = $this->getReportData();
        $payouts = PayoutRequest::with('user')->latest()->take(10)->get();
        return view('admin.reports.index', compact('reportData', 'payouts'));
    }


    public function export()
    {
        $reportData = $this->getReportData();
        $payouts = PayoutRequest::with('user')->latest()->get();
        return Excel::download(new SystemReportExport($reportData, $payouts), 'global_system_report.xlsx');
    }

    private function getReportData()
    {
        $startDate = request('start_date');
        $endDate = request('end_date');
        $filterDate = function($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                return $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }
        };

        return [
            'total_users'      => User::when($startDate && $endDate, $filterDate)->count(),
            'total_vendors'    => User::where('role', 'vendor')->when($startDate && $endDate, $filterDate)->count(),
            'total_orders'     => Order::when($startDate && $endDate, $filterDate)->count(),
            'total_sales'      => Order::where('status', 'completed')->when($startDate && $endDate, $filterDate)->sum('total_amount'),
            'total_products'   => Product::when($startDate && $endDate, $filterDate)->count(),
            'pending_payouts'  => PayoutRequest::where('status', 'pending')->when($startDate && $endDate, $filterDate)->sum('amount'),
            'approved_payouts' => PayoutRequest::where('status', 'approved')->when($startDate && $endDate, $filterDate)->sum('amount'),
        ];
    }
}
