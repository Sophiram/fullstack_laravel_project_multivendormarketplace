<?php

namespace App\Exports;

use App\Models\PayoutRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayoutsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PayoutRequest::with('user')->get()->map(function($payout) {
            return [
                $payout->created_at?->format('Y-m-d'),
                $payout->user->name ?? 'N/A',
                $payout->amount,
                ucfirst($payout->status),
            ];
        });
    }

    public function headings(): array
    {
        return ['Date', 'Vendor', 'Amount', 'Status'];
    }
}
