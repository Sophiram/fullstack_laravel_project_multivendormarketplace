<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorStoreController extends Controller
{
    public function index(){
        return view('vendor.store.create');
    }
    public function manage(){
        $userid = Auth::user()->id;
        $stores = Store::where('user_id', $userid)->get();
        return view('vendor.store.manage', compact('stores'));
    }

    public function store(Request $request)
{
    // ១. Validate ទិន្នន័យទាំងអស់
    $validated = $request->validate([
        'store_name' => 'required|unique:stores|max:100|min:3',
        'slug'       => 'nullable|unique:stores|max:100|min:3',
        'details'    => 'nullable|string',
        'store_email'=> 'nullable|email',
        'store_phone'=> 'nullable|string|max:20',
        'address'    => 'nullable|string',
        'logo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // ២. Handle ការ Upload រូបភាព
    $logoPath = null;
    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('stores', 'public');
    }

    $vendor = Auth::user()->vendor;
   if (!$vendor) {
        return redirect()->back()->withErrors(['error' => 'You do not have a Vendor profile! Please register as a vendor first.']);
    }


    Store::create([
        'user_id'    => auth()->id(), // បន្ថែមចំណុចនេះឱ្យបាន!
        'vendor_id'   => $vendor->id, // ប្រើ vendor_id ជំនួស user_id
        'store_name'  => $request->store_name,
        'slug'        => $request->slug ? \Illuminate\Support\Str::slug($request->slug) : \Illuminate\Support\Str::slug($request->store_name),
        'details'     => $request->details,
        'store_email' => $request->store_email,
        'store_phone' => $request->store_phone,
        'address'     => $request->address,
        'logo'        => $logoPath,
        'status'      => 'pending', // កំណត់លំនាំដើម
    ]);

    return redirect()->back()->with('success', 'Store Created Successfully!');
}

     public function showstore($id){
        $store_info = Store::find($id);
        return view('vendor.store.edit', compact('store_info'));
    }

    public function updatestore(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $validate_data = $request->validate([
            'store_name' => 'required|max:100|min:3',
            'slug'       => 'nullable|max:100|min:3',
            'details'    => 'nullable|string|max:500',
            'store_email'=> 'nullable|email',
            'store_phone'=> 'nullable|string',
            'address'    => 'nullable|string',
            'logo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update រូបភាពបើមានការផ្លាស់ប្តូរ
        if ($request->hasFile('logo')) {
            // លុបរូបភាពចាស់
            if ($store->logo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($store->logo);
            }
            // រក្សាទុករូបភាពថ្មី
            $validate_data['logo'] = $request->file('logo')->store('stores', 'public');
        }

        $store->update($validate_data);

        return redirect()->back()->with('success', 'Store Updated Successfully!');
    }

    public function deletestore($id){
        Store::find($id)->delete();
        return redirect()->back()->with('success', 'Store Deleted Successfully');
    }
}
