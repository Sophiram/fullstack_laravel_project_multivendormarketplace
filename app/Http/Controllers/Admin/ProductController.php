<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
   public function index(Request $request) {
    // បង្កើត query សម្រាប់យកទិន្នន័យទាំងអស់ដើម្បីគណនាស្ថិតិ
    $baseQuery = \App\Models\Product::query();

    $data['totalProducts'] = $baseQuery->count();
    $data['activeProducts'] = $baseQuery->clone()->where('status', 'Published')->count();
    $data['lowStock'] = $baseQuery->clone()->where('stock_quantity', '<', 5)->where('stock_quantity', '>', 0)->count();
    $data['outOfStock'] = $baseQuery->clone()->where('stock_quantity', 0)->count();

    // បង្កើត query សម្រាប់តារាង (មាន filter)
    $query = \App\Models\Product::with(['category', 'images']);

    // Filter Logic
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->filled('stock_status')) {
        if ($request->stock_status == 'low_stock') {
            $query->where('stock_quantity', '<', 5)->where('stock_quantity', '>', 0);
        } elseif ($request->stock_status == 'out_of_stock') {
            $query->where('stock_quantity', 0);
        } elseif ($request->stock_status == 'in_stock') {
            $query->where('stock_quantity', '>=', 5);
        }
    }

    $products = $query->latest()->paginate(10);

    return view('admin.product.manage', compact('products', 'data'));
}

    public function review_manage()
    {
        return view('admin.product.manage_product_review');
    }


    public function edit($id) {
    $product = \App\Models\Product::findOrFail($id);
    return view('admin.product.edit', compact('product'));
}

public function update(Request $request, $id) {
    // កូដសម្រាប់ Update ផលិតផល
    $product = \App\Models\Product::findOrFail($id);
    $product->update($request->all());

    return redirect()->route('product.manage')->with('success', 'Product updated successfully!');
}
    public function destroy($id)
    {
        // ១. ស្វែងរកផលិតផលតាមរយៈ ID
        $product = Product::findOrFail($id);
        // ២. (បន្ថែម) បើមានរូបភាពផលិតផល គួរលុបវាចេញពី Storage ផងដែរ
        if ($product->images) {
            foreach ($product->images as $image) {
                if (file_exists(storage_path('app/public/' . $image->image_path))) {
                    unlink(storage_path('app/public/' . $image->image_path));
                }
                $image->delete(); // លុបពី table product_images
            }
        }

        // ៣. លុបផលិតផលចេញពី Database
        $product->delete();

        // ៤. Redirect ត្រឡប់ទៅទំព័រដើមវិញ ជាមួយសារជូនដំណឹង
        return redirect()->back()->with('success', 'Product deleted successfully!');
    }


    public function store(Request $request)
    {
        // ១. Validate ទិន្នន័យ
        $request->validate([
            'product_name' => 'required|string|max:255',
            'store_id'     => 'required', // យក store_id ពី form
            'sku'          => 'required|unique:products,sku',
            'regular_price'=> 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id'  => 'required',
            'images.*'     => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $store = \App\Models\Store::find($request->store_id);
        $slug = Str::slug($request->product_name);
        // ២. រក្សាទុកទិន្នន័យចូល Database
            $product = Product::create([
            'product_name'   => $request->product_name,
            'slug'           => $slug, // បន្ថែមជួរនេះចូល
            'vendor_id'      => $store->vendor_id,
            'store_id'       => $request->store_id,
            'description'    => $request->description,
            'sku'            => $request->sku,
            'regular_price'  => $request->regular_price,
            'discounted_price'=> $request->discounted_price,
            'stock_quantity' => $request->stock_quantity,
            'category_id'    => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'status'         => $request->status,
        ]);

        // ៣. រក្សាទុករូបភាព (ប្រសិនបើមាន)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        // ៤. រក្សាទុក Attributes (ប្រសិនបើមាន)
        if ($request->has('attributes')) {
            foreach ($request->attributes as $attr) {
                if ($attr['attribute_id']) {
                    $product->attributes()->create($attr);
                }
            }
        }

        return redirect()->back()->with('success', 'Product created successfully!');
    }
}
