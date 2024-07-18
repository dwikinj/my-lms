<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    public function AllCategory()
    {
        $category = Category::latest()->get();
        return view('admin.backend.category.all_category', compact('category'));
    } //end method

    public function AddCategory()
    {
        return view('admin.backend.category.add_category');
    } //end method

    public function StoreCategory(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $request->category_name,
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5048',
        ]);

        if ($validator->fails()) {
            $notification = [
                'message' => 'Category insert failed. ' . $validator->errors()->first(),
                'alert-type' => 'error'
            ];

            return back()->with($notification)->withErrors($validator)->withInput();
        }


        $image = $request->file('image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

        $resizedImage = Image::read($image->getRealPath())->resize(370, 246);
        $resizedImage->save('upload/category/' . $name_gen);
        $save_url = 'upload/category/' . $name_gen;

        Category::insert([
            'category_name' => $request->category_name,
            'category_slug' => Str::slug($request->category_name),
            'image' => $save_url,
        ]);

        $notification = [
            'message' => 'Category Inserted Succesfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.category')->with($notification);
    } //end method

    public function EditCategory($id)
    {
        $category = Category::find($id);
        return view('admin.backend.category.edit_category', compact('category'));
    } //end method

    public function UpdateCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $request->category_name,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
        ]);

        if ($validator->fails()) {
            $notification = [
                'message' => 'Category update failed. ' . $validator->errors()->first(),
                'alert-type' => 'error'
            ];

            return back()->with($notification)->withErrors($validator)->withInput();
        }

        $category = Category::find($request->id);
        $category->category_name = $request->category_name;
        $category->category_slug = Str::slug($request->category_name);

        if ($request->file('image')) {
            if (file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            $resizedImage = Image::read($image->getRealPath())->resize(370, 246);
            $resizedImage->save('upload/category/' . $name_gen);
            $save_url = 'upload/category/' . $name_gen;

            $category->image = $save_url;
        }

        $category->save();

        $notification = [
            'message' => 'Category Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.category')->with($notification);
    } //end method
}
