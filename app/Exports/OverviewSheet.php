<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class OverviewSheet implements FromView, ShouldAutoSize, WithTitle
{
    protected $reportData;
    protected $payouts;

    public function __construct($reportData, $payouts)
    {
        $this->reportData = $reportData;
        $this->payouts = $payouts;
    }

    public function view(): View
    {
        return view('admin.reports.export', [
            'reportData' => $this->reportData,
            'payouts'    => $this->payouts
        ]);
    }

    public function title(): string
    {
        return 'Overview & Payouts'; // ឈ្មោះ Tab ទី១ ក្នុង Excel
    }
}
