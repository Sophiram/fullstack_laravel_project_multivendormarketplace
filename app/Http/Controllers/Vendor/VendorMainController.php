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
    $vendor_store_ids = Store::where('user_id', auth()->id())->pluck('id');

    $total_stores = $vendor_store_ids->count();

    $total_products = Product::whereIn('store_id', $vendor_store_ids)->count();

    $total_orders = Order::whereHas('items.product', function ($query) use ($vendor_store_ids) {
        $query->whereIn('store_id', $vendor_store_ids);
    })->count();

    return view('vendor.dashboard', compact('total_stores', 'total_products', 'total_orders'));
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
        // ទាញយកព័ត៌មាន Vendor ដែលកំពុង Login
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
        // ១. ទាញយក ID ហាងទាំងអស់របស់ Vendor
        $vendor_store_ids = Store::where('user_id', auth()->id())->pluck('id');

        // ២. គណនាប្រាក់ចំណូលសរុប (Total Earnings) ពីការលក់ដែលជោគជ័យ (delivered)
        $total_earnings = Order::whereHas('items.product', function ($query) use ($vendor_store_ids) {
            $query->whereIn('store_id', $vendor_store_ids);
        })->where('status', 'delivered')->sum('total_amount');

        // ៣. រាប់ចំនួនទំនិញដែលលក់ដាច់សរុប (Total Items Sold)
        $total_items_sold = DB::table('order_items')
        ->join('products', 'order_items.product_id', '=', 'products.id') // កែពី :: មកជា .
        ->join('orders', 'order_items.order_id', '=', 'orders.id')       // កែពី :: មកជា .
        ->whereIn('products.store_id', $vendor_store_ids)
        ->where('orders.status', 'delivered')
        ->sum('order_items.quantity');

        // ៤. ទាញទិន្នន័យលក់សរុបប្រចាំខែ (Monthly Sales) សម្រាប់ឆ្នាំបច្ចុប្បន្ន
        $monthly_sales = Order::whereHas('items.product', function ($query) use ($vendor_store_ids) {
                $query->whereIn('store_id', $vendor_store_ids);
            })
            ->where('status', 'delivered')
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
        return Excel::download(new SalesReportExport, 'sales_report.xlsx');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'gender' => 'nullable|in:male,female',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bank_account_info' => 'nullable|string', // បន្ថែម Validation
        ]);

        // ១. ធ្វើការ Update ក្នុង Transaction
        DB::transaction(function () use ($request, $user) {
            $user->fill([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
            ]);

            if ($user->vendor) {
                $user->vendor->update([
                    'store_name' => $request->store_name,
                ]);
            }

            if ($user->vendor) {
            $user->vendor->update([
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

            // ប្រសិនបើអ្នកមាន Update តារាង vendors នៅទីនេះ វានឹងមានសុវត្ថិភាព
            // ឧទាហរណ៍: $user->vendor->update(['store_name' => $request->store_name]);
        });

        // ២. ការ Redirect ត្រូវនៅខាងក្រៅ transaction
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function destroy($id)
    {
        // ពិនិត្យថា Store នោះជារបស់ Vendor មែន
        $store = Store::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // លុបរូបភាពហាងចេញពី Storage (ប្រសិនបើមាន)
        if ($store->image) {
            Storage::disk('public')->delete($store->image);
        }

        $store->delete();

        return redirect()->back()->with('success', 'Store deleted successfully!');
    }


    public function becomeVendor(Request $request)
    {
    // Validate ព័ត៌មាន
        $request->validate([
            'bank_account_info' => 'required|string',
        ]);

        // បង្កើត Record ថ្មីក្នុងតារាង vendors
        \App\Models\Vendor::create([
            'user_id'         => Auth::id(), // ភ្ជាប់នឹង User ដែលកំពុង Login
            'commission_rate' => 10,         // កំណត់តម្លៃ Default
            'approval_status' => 'pending',  // រង់ចាំ Admin អនុម័ត
            'bank_account_info' => $request->bank_account_info,
        ]);

        return redirect()->back()->with('success', 'You have applied to be a vendor!');
    }

}
