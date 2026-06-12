<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // 💡 ចាំបាច់ត្រូវថែម ដើម្បីប្រើប្រាស់មុខងារលុប File

class MasterCategoryController extends Controller
{
    public function storecategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:categories,category_name|max:100|min:3',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // ទំហំអតិបរមា 2MB
            'status'        => 'required|in:active,inactive',
        ]);
        $data = [
            'category_name' => $request->category_name,
            'status'        => $request->status,
        ];
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/categories'), $imageName);

            $data['image'] = 'uploads/categories/' . $imageName;
        }
        Category::create($data);

        return redirect()->back()->with('success', 'Category Added Successfully');
    }
    public function showcat($id)
    {
        $category_info = Category::findOrFail($id);
        return view('admin.category.edit', compact('category_info'));
    }
    public function updatecat(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'category_name' => 'required|max:100|min:3|unique:categories,category_name,' . $id,
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status'        => 'required|in:active,inactive',
        ]);
        $data = [
            'category_name' => $request->category_name,
            'status'        => $request->status,
        ];
        if ($request->hasFile('image')) {
            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/categories'), $imageName);
            $data['image'] = 'uploads/categories/' . $imageName;
        }
        $category->update($data);

        return redirect()->back()->with('success', 'Category Updated Successfully');
    }
    public function deletecat($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image && File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }
        $category->delete();

        return redirect()->back()->with('success', 'Category Deleted Successfully');
    }
}
