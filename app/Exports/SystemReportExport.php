<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SystemReportExport implements WithMultipleSheets
{
    protected $reportData;
    protected $payouts;

    public function __construct($reportData, $payouts)
    {
        $this->reportData = $reportData;
        $this->payouts = $payouts;
    }

    public function sheets(): array
    {
        return [
            new OverviewSheet($this->reportData, $this->payouts), // Sheet ទី១
            new GlobalSalesSheet(),                               // Sheet ទី២
        ];
    }
}
