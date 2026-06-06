<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GlobalSalesSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    public function collection()
    {
        // ប្រើប្រាស់ Eager Loading (with) ដើម្បីទាញយកទិន្នន័យ Stores និង Items មកតែម្តង ការពារបញ្ហា N+1 Query
        return Order::with(['user', 'items.product.store'])
            ->whereIn('status', ['completed', 'delivered'])
            ->latest()
            ->get()
            ->map(function ($order) {

                // ១. ប្រមូលឈ្មោះហាងទាំងអស់នៅក្នុង Order នេះ (មិនយកឈ្មោះជាន់គ្នាឡើយ)
                $storeNames = $order->items->map(function ($item) {
                    return $item->product->store->name ?? 'N/A';
                })->unique()->implode(', ');

                // ២. គណនាចំនួនមុខទំនិញសរុប (បូកបញ្ចូលចំនួន Quantity នៃ Item នីមួយៗ)
                // សម្គាល់៖ ប្រសិនបើ Table របស់លោកអ្នកគ្មាន Column 'quantity' ទេ អាចប្តូរទៅជា $order->items->count() វិញបាន
                $totalItems = $order->items->sum('quantity');

                return [
                    'id'           => $order->id,
                    'customer'     => $order->user->name ?? 'Guest',
                    'stores'       => $storeNames ?: 'N/A',
                    'total_items'  => $totalItems,
                    'total_amount' => '$' . number_format($order->total_amount, 2),
                    'status'       => ucfirst($order->status),
                    'date'         => $order->created_at?->format('Y-m-d H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            "Order ID",
            "Customer Name",
            "Store Name",
            "Total Items",
            "Total Amount",
            "Status",
            "Date"
        ];
    }

    public function title(): string
    {
        return 'Global Sales Performance';
    }
}
