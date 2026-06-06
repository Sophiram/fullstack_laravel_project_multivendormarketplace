<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\ShippingCompany;
use App\Models\Store;
use App\Notifications\OrderShipped;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorOrderController extends Controller
{

    /**
 * 🏪 បង្ហាញប្រវត្តិនៃការលក់របស់ Vendor និងចំណូលដែលបានកាត់ Commission រួច
 */
    public function vendorIndex(Request $request)
    {
        $vendor_store_ids = Store::where('user_id', Auth::id())->pluck('id');

        $orders = Order::whereHas('items.product', function ($query) use ($vendor_store_ids) {
                $query->whereIn('store_id', $vendor_store_ids);
            })
            ->with(['user', 'shipping', 'items' => function($q) use ($vendor_store_ids) {
                $q->whereHas('product', function($pQuery) use ($vendor_store_ids) {
                    $pQuery->whereIn('store_id', $vendor_store_ids);
                });
            }])
            ->orderByDesc('created_at')
            ->paginate(10);

        // 🟢 បន្ថែមបន្ទាត់ទាញយក Shipping Companies នេះចូល
        $shippingCompanies = ShippingCompany::where('vendor_id', Auth::id())
                            ->where('is_active', true)
                            ->get();

        // 🟢 បន្ថែមវាចូលក្នុង compact()
        return view('vendor.orders.orderhistory', compact('orders', 'shippingCompanies'));
    }

    public function vendorShowOrder($id)
    {
        // 🟢 បន្ថែម 'shipping' ចូលក្នុង with()
        $order = Order::with(['items.product.images', 'items.product.store', 'user', 'shipping'])
                    ->findOrFail($id);

        $userId = Auth::id();

        // ត្រងយកតែ Items ណាដែលជារបស់ Vendor នេះ
        $order->items = $order->items->filter(function ($item) use ($userId) {
            return ($item->product->store->user_id ?? null) == $userId;
        });

        // បើសិនជា Order នោះមិនមាន Items របស់ Vendor នេះទេ មិនអនុញ្ញាតឱ្យមើល
        if ($order->items->isEmpty()) {
            abort(403, 'You do not have permission to view this order.');
        }
        $shippingCompanies = \App\Models\ShippingCompany::where('vendor_id', Auth::id())
                      ->where('is_active', true)
                      ->get();

        return view('vendor.orders.ordershow', compact('order', 'shippingCompanies'));
    }


     public function updateStatus(Request $request, Order $order)
        {
            $rules = [
                'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            ];

            if ($request->status == 'shipped') {
                $rules['shipping_company_id'] = 'required|exists:shipping_companies,id';
                // ធ្វើឱ្យ tracking_number មិនចាំបាច់ required ខ្លាំងពេកទេ បើអ្នកមាន logic បង្កើតវាដោយខ្លួនឯង
                $rules['tracking_number'] = 'nullable|string';
            }

            $request->validate($rules);

            // ប្រើ Database Transaction ដើម្បីសុវត្ថិភាព
            DB::transaction(function () use ($request, $order) {
                $order->update(['status' => $request->status]);

                if ($request->status == 'shipped') {
                    $trackingNumber = $request->tracking_number;
                        if (empty($trackingNumber)) {
                            $trackingNumber = 'TRK-' . strtoupper(substr(uniqid(), -8));
                            // លទ្ធផល: TRK-665D8F2A (កូដមានប្រវែងខ្លីជាងមុនបន្តិច)
                        }
                    \App\Models\Shipping::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'shipping_company_id' => $request->shipping_company_id,
                            'tracking_number'  => $trackingNumber,
                            'shipping_status'  => 'Shipped',
                            'shipped_at'       => now(),
                            'shipping_cost'    => $request->shipping_cost ?? 0.00,
                            'notes'            => $request->notes,
                        ]
                    );
                }

                if ($request->status == 'completed') {
                    \App\Models\Shipping::where('order_id', $order->id)->update([
                        'delivered_at' => now(),
                        'shipping_status' => 'delivered'
                    ]);
                }
            });
                if ($order->user) {
                        $order->user->notify(new OrderStatusUpdated($order));
             }

            return redirect()->back()->with('success', 'Order status updated and customer notified!');
        }
}
