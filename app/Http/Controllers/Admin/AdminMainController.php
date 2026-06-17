<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePageSetting;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminMainController extends Controller
{

    public function index(Request $request)
    {
        // ១. ចាប់យកថ្ងៃខែដែលបានជ្រើសរើសពី Flatpickr Filter (បើគ្មានទេ វានឹងយកលំនាំដើមពីដើមឆ្នាំដល់ចុងឆ្នាំ)
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfYear();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfYear();

        // ២. ទាញទិន្នន័យសសរុបទឹកប្រាក់លក់ដាច់បំបែកតាមខែ សម្រាប់យកទៅគូរក្រាហ្វិក (ApexCharts Area) ទៅតាមចន្លោះកាលបរិច្ឆេទ
        $salesData = Order::where('status', 'complete') // គណនាតែការកុម្ម៉ង់ដែលជោគជ័យ
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('SUM(total_amount) as total'),
                DB::raw("DATE_FORMAT(created_at, '%b') as month")
            )
            ->groupBy('month')
            ->orderBy(DB::raw('MIN(created_at)'), 'ASC') // <--- កែប្រែត្រង់ជួរនេះ ដោយប្រើ Aggregate Function MIN()
            ->get();

        // រៀបចំទិន្នន័យជាទម្រង់ Array JSON សម្រាប់ផ្ញើទៅកាន់ JavaScript ApexCharts
        $labels = json_encode($salesData->pluck('month')->toArray());
        $values = json_encode($salesData->pluck('total')->toArray());

        // ៣. ទាញយកបញ្ជីបញ្ជាទិញថ្មីៗបំផុតចំនួន ៥ (Recent Orders) ភ្ជាប់ជាមួយទិន្នន័យ User
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // ៤. ទាញយកផលិតផលលក់ដាច់បំផុតកំពូលទាំង ៥ (Top Selling Products) ទៅតាមចន្លោះថ្ងៃខែដែលបានរើស
       // Query ទាញយកផលិតផលលក់ដាច់បំផុតកំពូលទាំង ៥ រួមជាមួយរូបភាពចម្បង (Primary Image)
        $topProducts = Product::join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('product_images', function($join) {
                $join->on('products.id', '=', 'product_images.product_id')
                    ->where('product_images.is_primary', '=', 1); // យកតែរូបភាពណាដែលកំណត់ថាជា Primary
            })
            ->select(
                'products.id',
                'products.product_name',
                'product_images.image_path', // ទាញយកផ្លូវរូបភាពពី Table product_images
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', 'completed')
            ->groupBy('products.id', 'products.product_name', 'product_images.image_path') // ត្រូវថែម image_path ចូលក្នុង groupBy ដែរ
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // ៥. រៀបចំទិន្នន័យស្ថិតិទាំងអស់បញ្ជូនទៅកាន់ផ្ទាំង Dashboard (Blade View)
        $data = [
            'categoryCount'      => Category::count(),
            'productCount'       => Product::count(),
            'pendingVendorCount' => User::where('role', 1)->where('is_approved', false)->count(),
            'orderCount'         => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'labels'             => $labels,
            'values'             => $values,
            'recentOrders'       => $recentOrders,
            'topProducts'        => $topProducts, // បានបន្ថែមអថេរនេះចូលទៅក្នុង Array រួចរាល់
            'startDate'          => $startDate,
            'endDate'            => $endDate,
        ];

        return view('admin.dashboard', $data);
    }

    public function setting()
    {
        $products = Product::all();
        $homepagesetting = HomePageSetting::first() ?? new HomePageSetting();
        return view('admin.settings', compact('products', 'homepagesetting'));
    }
    function updatehomepagesetting(Request $request){
       $request->validate([
            'discounted_product_id' => 'required|exists:products,id',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'discount_heading' => 'required|string|max:255',
            'discount_subheading' => 'required|string|max:255',
            'featured_product_1_id' => 'nullable|exists:products,id',
            'featured_product_2_id' => 'nullable|exists:products,id',
       ]);

       $homepagesetting = HomePageSetting::first() ?? new HomePageSetting();
       $homepagesetting->fill($request->all());
       $homepagesetting->save();

       return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }

    public function order_history()
    {
        return view('admin.order.history');
    }

    public function manage_profile()
    {
        return view('admin.admin_profile');
    }


   public function update_profile(Request $request)
    {
        // ១. Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = Auth::user();

        // ២. បំពេញទិន្នន័យ (Fill data)
        $user->fill($request->only(['name', 'email']));

        // ៣. ចាត់ចែងរូបភាព
        if ($request->hasFile('image')) {
            // លុបរូបភាពចាស់ (បើមាន)
            if ($user->image && file_exists(public_path('upload/admin_images/' . $user->image))) {
                unlink(public_path('upload/admin_images/' . $user->image));
            }

            // Upload រូបភាពថ្មី
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);

            $user->image = $filename;
        }

        // ៤. ផ្លាស់ប្តូរ Password (បើមាន)
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password ចាស់មិនត្រឹមត្រូវទេ។']);
            }
            $user->password = Hash::make($request->password);
        }

        // ៥. រក្សាទុកចូល Database
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function pendingVendors()
    {
        $pendingVendors = User::where('role', 1) // ប្តូរទៅជាលេខ 1 ប្រសិនបើអ្នកប្រើ integer
                              ->where('is_approved', false)
                              ->get();

        return view('admin.pending-vendors', compact('pendingVendors'));
    }


    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->is_approved = true;
        $user->save();

        return back()->with('success', 'Vendor approved successfully!');
    }


    public function markAsRead($id)
    {
        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
        return redirect()->route('admin.pending');
    }

    public function exportReport()
    {
        $filename = "report_" . date('Y-m-d') . ".csv";

        $callback = function() {
            $handle = fopen('php://output', 'w');
            // បង្កើត header
            fputcsv($handle, ['ID', 'Customer', 'Total', 'Status']);

            // ទាញយកទិន្នន័យពី Database
            // ប្រើ chunk ដើម្បីកុំឱ្យស៊ី Memory ច្រើនបើទិន្នន័យមានចំនួនច្រើន
            Order::chunk(100, function($orders) use ($handle) {
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->id,
                        $order->user->name ?? 'N/A',
                        $order->total_amount,
                        $order->status
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ]);
    }

    public function cart_history()
    {
        // ទាញយកទិន្នន័យដោយប្រើ paginate ដើម្បីកុំឱ្យទំព័រយឺត
        $carts = \App\Models\Cart::with('user')->latest()->paginate(10);

        // គណនាស្ថិតិសម្រាប់ Dashboard
        $totalAbandoned = \App\Models\Cart::where('status', 'abandoned')->count();
        $totalConverted = \App\Models\Cart::where('status', 'converted')->count();

        return view('admin.cart.history', compact('carts', 'totalAbandoned', 'totalConverted'));
    }
}
