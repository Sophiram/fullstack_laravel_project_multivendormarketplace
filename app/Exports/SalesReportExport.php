<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReportExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $storeIds;

    // Accept the store IDs from the controller
    public function __construct(array $storeIds)
    {
        $this->storeIds = $storeIds;
    }

    // Filter the orders to only include items from this vendor's stores
    public function query()
    {
        return Order::query()
            ->whereHas('items.product', function ($query) {
                $query->whereIn('store_id', $this->storeIds);
            })
            ->where('status', 'completed')
            ->with(['user', 'items.product']); // Eager load relationships
    }

    // Map the data for each row in the Excel sheet
    public function map($order): array
    {
        return [
            $order->id,
            $order->user->name ?? 'Guest',
            $order->total_amount,
            $order->status,
            $order->created_at->format('Y-m-d H:i:s'),
        ];
    }

    // Set the column headers
    public function headings(): array
    {
        return [
            'Order ID',
            'Customer Name',
            'Total Amount ($)',
            'Status',
            'Order Date',
        ];
    }
}
