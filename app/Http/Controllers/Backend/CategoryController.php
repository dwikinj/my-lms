<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
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

    public function DeleteCategory($id)
    {
        $category = Category::find($id);

        if ($category) {
            // Hapus gambar jika ada
            if (file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $category->delete();

            $notification = [
                'message' => 'Category Deleted Successfully',
                'alert-type' => 'success'
            ];
        } else {
            $notification = [
                'message' => 'Category not found.',
                'alert-type' => 'error'
            ];
        }

        return redirect()->route('all.category')->with($notification);
    } //end method

    //----------------- All SubCategory Methods -------------------//
    public function AllSubCategory()  {
        $subcategory = SubCategory::with('category')->latest()->get();
        return view('admin.backend.subcategory.all_subcategory', compact('subcategory'));
    }//endmethod

    public function AddSubCategory()
    {
        $category = Category::all();
        return view('admin.backend.subcategory.add_subcategory',compact('category'));
    } //end method

    public function StoreSubCategory(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subcategory_name' => 'required|string|max:255|unique:sub_categories,subcategory_name,' . $request->subcategory_name,
            'category_id' => 'required|string|exists:categories,id',
        ]);

        if ($validator->fails()) {
            $notification = [
                'message' => 'SubCategory insert failed. ' . $validator->errors()->first(),
                'alert-type' => 'error'
            ];

            return back()->with($notification)->withErrors($validator)->withInput();
        }

        SubCategory::insert([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => Str::slug($request->subcategory_name),
        ]);

        $notification = [
            'message' => 'SubCategory Inserted Succesfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.subcategory')->with($notification);
    } //end method

    public function EditSubCategory($id)
    {
        $subcategory = SubCategory::find($id);
        $category = Category::all();
        return view('admin.backend.subcategory.edit_subcategory', compact('subcategory','category'));
    } //end method

    public function UpdateSubCategory(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'subcategory_name' => 'required|string|max:255|unique:sub_categories,id,' . $request->id,
            'category_id' => 'required|string|exists:categories,id',
        ]);

        if ($validator->fails()) {
            $notification = [
                'message' => 'SubCategory update failed. ' . $validator->errors()->first(),
                'alert-type' => 'error'
            ];

            return back()->with($notification)->withErrors($validator)->withInput();
        }

        $subcategory = SubCategory::find($request->id);
        if (!$subcategory) {
            $notification = [
                'message' => 'SubCategory update failed. SubCategory not exist',
                'alert-type' => 'error'
            ];

            return back()->with($notification);
        }

        $subcategory->subcategory_name = $request->subcategory_name;
        $subcategory->subcategory_slug = Str::slug($request->subcategory_name);
        $subcategory->category_id = $request->category_id;

        $subcategory->save();

        $notification = [
            'message' => 'SubCategory Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.subcategory')->with($notification);
    } //end method

    public function DeleteSubCategory($id)
    {
        $subcategory = SubCategory::find($id);

        if ($subcategory) {
            $subcategory->delete();

            $notification = [
                'message' => 'SubCategory Deleted Successfully',
                'alert-type' => 'success'
            ];
        } else {
            $notification = [
                'message' => 'SubCategory not found.',
                'alert-type' => 'error'
            ];
        }

        return redirect()->route('all.subcategory')->with($notification);
    } //end method
}
