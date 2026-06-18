<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\ShippingCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingCompanyController extends Controller
{
    // បង្ហាញបញ្ជីក្រុមហ៊ុនដឹកជញ្ជូនរបស់ Vendor ម្នាក់ៗ
    public function index()
    {
        $companies = ShippingCompany::where('vendor_id', Auth::id())->get();
        return view('vendor.shipping.index', compact('companies'));
    }

    // រក្សាទុកក្រុមហ៊ុនថ្មី
    public function store(Request $request)
    {
        // 💡 បន្ថែម shipping_fee ចូលក្នុង Validation វិញ
        $request->validate([
            'name' => 'required|string|max:255',
            'shipping_fee' => 'required|numeric|min:0', // កំណត់ឱ្យបញ្ចូលជាលេខ និងមិនតូចជាង ០
            'tracking_url_template' => 'nullable',
        ]);

        // 💡 បង្កើតទិន្នន័យដោយរួមបញ្ចូល shipping_fee
        ShippingCompany::create([
            'vendor_id' => Auth::id(),
            'name' => $request->name,
            'shipping_fee' => $request->shipping_fee, // ដាក់ fee វិញ
            'tracking_url_template' => $request->tracking_url_template,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Shipping company added successfully!');
    }

    // លុបក្រុមហ៊ុន
    public function destroy($id)
    {
        $company = ShippingCompany::where('vendor_id', Auth::id())->findOrFail($id);
        $company->delete();

        return redirect()->back()->with('success', 'Shipping company removed.');
    }

    // សម្រាប់ Update ទិន្នន័យក្រុមហ៊ុន
    public function update(Request $request, $id)
    {
        // 💡 បន្ថែម shipping_fee ចូលក្នុង Validation វិញ
        $request->validate([
            'name' => 'required|string|max:255',
            'shipping_fee' => 'required|numeric|min:0', // ដាក់ fee វិញ
            'tracking_url_template' => 'nullable',
        ]);

        $company = ShippingCompany::where('vendor_id', Auth::id())->findOrFail($id);

        // 💡 ធ្វើបច្ចុប្បន្នភាពដោយរួមបញ្ចូល shipping_fee
        $company->update([
            'name' => $request->name,
            'shipping_fee' => $request->shipping_fee, // ដាក់ fee វិញ
            'tracking_url_template' => $request->tracking_url_template,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Shipping company updated successfully!');
    }
}
