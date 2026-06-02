<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CommissionRule;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()
            ->with(['items'])
            ->orderByDesc('created_at')
            ->paginate(10);

        $total_stores = Auth::user()->stores()->count();
        $total_products = Auth::user()->products()->count();
        $total_orders = Auth::user()->orders()->count();

        $activities = Auth::user()->activities()->latest()->take(4)->get();

        return view('orders.index', compact('orders', 'total_stores', 'total_products', 'total_orders', 'activities'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product.images', 'items.product.store', 'user'])->findOrFail($id);

        // ត្រួតពិនិត្យសិទ្ធិ: ម្ចាស់ Order ទើបអាចមើលបាន
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('orders.show', compact('order'));
    }

    public function vendorShowOrder($id)
    {
        $order = Order::with(['items.product.images', 'items.product.store', 'user'])->findOrFail($id);
        $userId = Auth::id();

        // ត្រងយកតែ Items ណាដែលជារបស់ Vendor នេះ
        $order->items = $order->items->filter(function ($item) use ($userId) {
            return ($item->product->store->user_id ?? null) == $userId;
        });

        // បើសិនជា Order នោះមិនមាន Items របស់ Vendor នេះទេ មិនអនុញ្ញាតឱ្យមើល
        if ($order->items->isEmpty()) {
            abort(403, 'You do not have permission to view this order.');
        }

        return view('vendor.ordershow', compact('order'));
    }

    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== Auth::id() && Auth::user()->role !== 0) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'Cannot cancel this order');
        }

        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('order.index')->with('success', 'Order cancelled successfully');
    }

   /**
 * 🛒 មុខងារគណនា Commission ពេលអតិថិជនកុម្ម៉ង់ទិញ (Checkout Process)
 */
    public function completeCheckout(Request $request)
    {
        $cartItems = $request->input('cart_items', []);
        $order = new Order();

        DB::transaction(function () use ($cartItems, $order) {

            foreach ($cartItems as $item) {
                // 🟢 កែប្រែត្រង់នេះ៖ ប្តូរពី .findOrFail() មកជា ->findOrFail()
                $product = Product::with('store.user')->findOrFail($item['product_id']);

                // ១. ស្វែងរក % Commission ផ្អែកលើ Category របស់ផលិតផល
                $rule = CommissionRule::where('category_id', $product->category_id)
                    ->where('status', 'Active')
                    ->first();

                $commissionRate = $rule ? $rule->commission_rate : 0.00;

                $totalPrice = $item['price'] * $item['quantity'];

                // ២. គណនាទឹកប្រាក់ដែលក្រុមហ៊ុនត្រូវកាត់ទុក
                $commissionAmount = ($totalPrice * $commissionRate) / 100;

                // ៣. គណនាទឹកប្រាក់សុទ្ធដែល Vendor ត្រូវទទួលបាន (Net Earnings)
                $vendorNetAmount = $totalPrice - $commissionAmount;

                // ៤. បញ្ចូលទៅក្នុងតារាងលម្អិត OrderItem
                OrderItem::create([
                    'order_id'          => $order->id,
                    'product_id'        => $product->id,
                    'vendor_id'         => $product->store->user_id,
                    'quantity'          => $item['quantity'],
                    'price'             => $item['price'],
                    'commission_rate'   => $commissionRate,
                    'commission_amount' => $commissionAmount,
                    'vendor_net_amount' => $vendorNetAmount,
                ]);

                // ៥. បន្ថយចំនួនស្តុកផលិតផល
                $product->decrement('stock_quantity', $item['quantity']);
            }
        });

        return redirect()->route('order.index')->with('success', 'Order placed successfully!');
    }

    /**
     * 🏪 បង្ហាញប្រវត្តិនៃការលក់របស់ Vendor និងចំណូលដែលបានកាត់ Commission រួច
     */
    /**
 * 🏪 បង្ហាញប្រវត្តិនៃការលក់របស់ Vendor និងចំណូលដែលបានកាត់ Commission រួច
 */
    public function vendorIndex(Request $request)
        {
            $vendor_store_ids = Store::where('user_id', Auth::id())->pluck('id');

            $orders = Order::whereHas('items.product', function ($query) use ($vendor_store_ids) {
                    $query->whereIn('store_id', $vendor_store_ids);
                })
                ->with(['user', 'items' => function($q) use ($vendor_store_ids) {
                    // ត្រងយកតែ Items ដែលជាផលិតផលរបស់ហាងខ្លួនឯងប៉ុណ្ណោះ
                    $q->whereHas('product', function($pQuery) use ($vendor_store_ids) {
                        $pQuery->whereIn('store_id', $vendor_store_ids);
                    });
                }])
                ->orderByDesc('created_at')
                ->paginate(10);

            return view('vendor.orderhistory', compact('orders'));
        }

        public function updateStatus(Request $request, Order $order)
        {
            $request->validate([
                'status' => 'required|in:pending,processing,shipped,completed,cancelled',
                // បន្ថែម validation សម្រាប់ Shipping (ចាំបាច់បើ status = shipped)
                'shipping_company' => 'required_if:status,shipped|string',
                'tracking_number'  => 'required_if:status,shipped|string',
            ]);

            // ប្រើ Database Transaction ដើម្បីសុវត្ថិភាព
            DB::transaction(function () use ($request, $order) {
                $order->update(['status' => $request->status]);

                if ($request->status == 'shipped') {
                    \App\Models\Shipping::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'shipping_company' => $request->shipping_company,
                            'tracking_number'  => $request->tracking_number,
                            'shipping_status'  => 'Shipped',
                            'shipping_date'    => now(),
                        ]
                    );
                }
            });
            $user = User::find($order->user_id);
            $order->user->notify(new OrderStatusUpdated($order));

            return redirect()->back()->with('success', 'Order status updated and customer notified!');
        }
}
