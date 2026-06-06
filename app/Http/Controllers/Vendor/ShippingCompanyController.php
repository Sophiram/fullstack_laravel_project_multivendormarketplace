<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\ShippingCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingCompanyController extends Controller
{
    // បង្ហាញបញ្ជីក្រុមហ៊ុន
    public function index()
    {
        $companies = ShippingCompany::where('vendor_id', Auth::id())->get();
        return view('vendor.shipping.index', compact('companies'));
    }

    // រក្សាទុកក្រុមហ៊ុនថ្មី
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tracking_url_template' => 'nullable', // ដក url validation ចេញបើ template មាន placeholder {tracking_number}
            'shipping_fee' => 'required|numeric|min:0', // បន្ថែម Validation សម្រាប់ថ្លៃដឹកជញ្ជូន
        ]);

        ShippingCompany::create([
            'vendor_id' => Auth::id(),
            'name' => $request->name,
            'tracking_url_template' => $request->tracking_url_template,
            'shipping_fee' => $request->shipping_fee, // បញ្ចូលតម្លៃថ្លៃដឹកជញ្ជូន
            'is_active' => $request->has('is_active'),
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

    // សម្រាប់ Update ទិន្នន័យ
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'shipping_fee' => 'required|numeric|min:0', // បន្ថែម Validation
        ]);

        $company = ShippingCompany::where('vendor_id', Auth::id())->findOrFail($id);
        $company->update([
            'name' => $request->name,
            'tracking_url_template' => $request->tracking_url_template,
            'shipping_fee' => $request->shipping_fee, // អាប់ដេតថ្លៃដឹកជញ្ជូន
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Shipping company updated successfully!');
    }
}
