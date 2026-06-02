<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;

class SalesReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $vendor_store_ids = \App\Models\Store::where('user_id', Auth::id())->pluck('id');

        return Order::whereHas('items.product', function ($query) use ($vendor_store_ids) {
            $query->whereIn('store_id', $vendor_store_ids);
        })
        ->where('status', 'delivered')
        ->select('id', 'total_amount', 'status', 'created_at')
        ->get();
    }

    public function headings(): array
    {
        return ["Order ID", "Total Amount", "Status", "Date"];
    }
}
