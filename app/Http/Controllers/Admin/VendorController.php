<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store; // ត្រូវប្រាកដថាអ្នកមាន Model Store
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function manage_vendor(Request $request)
    {
        // ស្វែងរកហាងទាំងអស់ អាចបន្ថែមការ Filter បាននៅទីនេះ
        $vendors = Vendor::with('user')->get();
        return view('admin.manage.vendor', compact('vendors'));
    }

    public function manage_store(Request $request){
        $stores = Store::all();
        return view('admin.manage.store', compact('stores'));
    }

    public function updateStore(Request $request, $id)
    {
        $store = \App\Models\Store::findOrFail($id);
        $store->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Store status updated successfully!');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected', // Validation ត្រឹមត្រូវតាម ENUM
        ]);

        $store = Store::findOrFail($id);
        $store->status = $request->status;

        // ប្រសិនបើចង់ឱ្យវា Active ស្វ័យប្រវត្តិតែម្តងពេល Approved
        if ($request->status === 'approved') {
            $store->is_active = 1;
        } else {
            $store->is_active = 0;
        }

        $store->save();

        return redirect()->back()->with('success', 'Store status updated successfully.');
    }


}
