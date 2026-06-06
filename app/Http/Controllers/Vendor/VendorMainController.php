<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class VendorMainController extends Controller
{
    public function index()
    {
        // ១. ទាញយក ID ហាងទាំងអស់របស់ Vendor ដែលកំពុង Login
        $vendor_store_ids = Store::where('user_id', auth()->id())->pluck('id');

        // ២. គណនាទិន្នន័យកាតស្ថិតិសរុប (Top Metrics)
        $total_stores = $vendor_store_ids->count();
        $total_products = Product::whereIn('store_id', $vendor_store_ids)->count();
        $total_orders = Order::whereHas('items.product', function ($query) use ($vendor_store_ids) {
            $query->whereIn('store_id', $vendor_store_ids);
        })->count();

        // ៣. 📊 ទាញទិន្នន័យលក់ប្រចាំខែសម្រាប់ Line Chart (ឆ្នាំបច្ចុប្បន្ន)
        $monthly_sales = Order::whereHas('items.product', function ($query) use ($vendor_store_ids) {
                $query->whereIn('store_id', $vendor_store_ids);
            })
            ->where('status', 'delivered')
            ->whereYear('created_at', date('Y'))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // រៀបចំឈ្មោះខែ និងទិន្នន័យទឹកប្រាក់សម្រាប់បោះទៅកាន់ ApexCharts
        $month_names = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'];
        $chart_sales_months = [];
        $chart_sales_data = [];

        foreach ($monthly_sales as $sale) {
            $chart_sales_months[] = $month_names[$sale->month] ?? 'Unknown';
            $chart_sales_data[] = (float)$sale->total;
        }

        // ៤. 📊 ទាញទិន្នន័យហាងដែលលក់ដាច់បំផុតទាំង ៥ សម្រាប់ Bar Chart (Top Store Performance)
        $store_performance = DB::table('stores')
            ->join('products', 'products.store_id', '=', 'stores.id')
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('stores.user_id', auth()->id())
            ->where('orders.status', 'completed')
            ->select('stores.store_name', DB::raw('SUM(orders.total_amount) as revenue'))
            ->groupBy('stores.id', 'stores.store_name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        $chart_store_names = $store_performance->pluck('store_name')->toArray();
        $chart_store_data = $store_performance->pluck('revenue')->toArray();

        // ៥. 🔔 ទាញយកការទិញដូរថ្មីៗបំផុតទាំង ៥ ធ្វើជា Activity Feed (Recent Activity)
        $recent_orders = Order::whereHas('items.product', function ($query) use ($vendor_store_ids) {
                $query->whereIn('store_id', $vendor_store_ids);
            })
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        // បំប្លែងទិន្នន័យ Order ទៅជា Object សម្រាប់បង្ហាញលើ Timeline UI
        $recent_activities = $recent_orders->map(function($order) {
            return (object)[
                'title' => 'New Order #' . $order->id . ' has been placed',
                'icon' => 'shopping-cart',
                'created_at' => $order->created_at,
                'description' => 'Received an order worth $' . number_format($order->total_amount, 2)
            ];
        });

        // ៦. បញ្ជូនទិន្នន័យទាំងអស់ទៅកាន់ View
        return view('vendor.dashboard', compact(
            'total_stores',
            'total_products',
            'total_orders',
            'chart_sales_data',
            'chart_sales_months',
            'chart_store_data',
            'chart_store_names',
            'recent_activities'
        ));
    }

    public function orderhistory()
    {
        $vendor_store_ids = Store::where('user_id', auth()->id())->pluck('id');

        $orders = Order::whereHas('items.product', function ($query) use ($vendor_store_ids) {
            $query->whereIn('store_id', $vendor_store_ids);
        })->with(['user', 'items.product.store'])->latest()->get();

        return view('vendor.orderhistory', compact('orders'));
    }

    public function profile()
    {
        $vendor = auth()->user()->load('vendor');
        return view('vendor.profile', compact('vendor'));
    }

    public function settings()
    {
        $vendor = auth()->user();
        return view('vendor.settings', compact('vendor'));
    }

    public function salesReport()
    {
        $vendor_store_ids = Store::where('user_id', auth()->id())->pluck('id');

        $total_earnings = Order::whereHas('items.product', function ($query) use ($vendor_store_ids) {
            $query->whereIn('store_id', $vendor_store_ids);
        })->where('status', 'completed')->sum('total_amount');

        $total_items_sold = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('products.store_id', $vendor_store_ids)
            ->where('orders.status', 'completed')
            ->sum('order_items.quantity');

        $monthly_sales = Order::whereHas('items.product', function ($query) use ($vendor_store_ids) {
                $query->whereIn('store_id', $vendor_store_ids);
            })
            ->where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(id) as count')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return view('vendor.sales_report', compact('total_earnings', 'total_items_sold', 'monthly_sales'));
    }

    public function exportSalesReport()
        {
            // 1. Get the current vendor's store IDs
            $vendor_store_ids = Store::where('user_id', auth()->id())->pluck('id')->toArray();

            // 2. Generate a dynamic filename with the current date
            $fileName = 'sales_report_' . date('Y_m_d_H_i') . '.xlsx';

            // 3. Pass the store IDs into the Export class
            return Excel::download(new SalesReportExport($vendor_store_ids), $fileName);
        }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'gender' => 'nullable|in:male,female',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bank_account_info' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->fill([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
            ]);

            if ($user->vendor) {
                $user->vendor->update([
                    'store_name' => $request->store_name,
                    'bank_account_info' => $request->bank_account_info,
                ]);
            }

            if ($request->hasFile('image')) {
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                $user->image = $request->file('image')->store('profiles', 'public');
            }

            $user->save();
        });

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function destroy($id)
    {
        $store = Store::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($store->image) {
            Storage::disk('public')->delete($store->image);
        }

        $store->delete();

        return redirect()->back()->with('success', 'Store deleted successfully!');
    }

    public function becomeVendor(Request $request)
    {
        $request->validate([
            'bank_account_info' => 'required|string',
        ]);

        \App\Models\Vendor::create([
            'user_id'         => Auth::id(),
            'commission_rate' => 10,
            'approval_status' => 'pending',
            'bank_account_info' => $request->bank_account_info,
        ]);

        return redirect()->back()->with('success', 'You have applied to be a vendor!');
    }
}
