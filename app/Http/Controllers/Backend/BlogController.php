<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public function AllBlogCategory()
    {
        $categories = BlogCategory::latest()->get();
        return view('admin.backend.blogcategory.blog_category', compact('categories'));
    } //end method

    public function StoreBlogCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|min:3|max:255|unique:blog_categories,category_name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $slug = Str::slug($request->category_name, '-');

        //slug should unique
        $originalSlug = $slug;
        $count = 1;
        while (BlogCategory::where('category_slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // save to db
        try {
            $category = BlogCategory::create([
                'category_name' => $request->category_name,
                'category_slug' => $slug,
            ]);

            return response()->json([
                'message' => 'Blog Category added successfully!',
                'category' => $category
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'An internal server error occurred while saving the category. Please try again.'
            ], 500);
        }
    } //end method

    public function DeleteBlogCategory($id)
    {
        try {
            $category = BlogCategory::findOrFail($id);
            $category->delete(); // Hapus kategori

            if (request()->ajax()) {
                return response()->json(['message' => 'Blog Category deleted successfully!'], 200);
            }
        } catch (ModelNotFoundException $e) {
            if (request()->ajax()) {
                return response()->json(['message' => 'Category not found.'], 404);
            }
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['message' => 'An error occurred while deleting the category. Please try again.'], 500);
            }
        }
    }
    //end method

    public function getCategoryDataForEdit($id)
    {
        try {
            $category = BlogCategory::findOrFail($id);
            return response()->json(['category' => $category], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching category data.'], 500);
        }
    } //end method

    public function updateCategory(Request $request, $id)
    {
        try {
            $category = BlogCategory::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'category_name' => [
                    'required',
                    'min:3',
                    'string',
                    'max:255',
                    Rule::unique('blog_categories', 'category_name')->ignore($category->id),
                ],
                'category_slug' => [
                    'required',
                    'min:3',
                    'string',
                    'max:255',
                    Rule::unique('blog_categories', 'category_slug')->ignore($category->id),
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }


            $newSlug = $request->category_slug;
            if (empty($newSlug) || $newSlug === Str::slug($request->category_name)) {
                $newSlug = Str::slug($request->category_name);
            } else {
                $newSlug = Str::slug($request->category_slug);
            }


            $category->category_name = $request->category_name;
            $category->category_slug = $newSlug;
            $category->save();

            return response()->json([
                'message' => 'Blog Category updated successfully!',
                'category' => $category
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found for update.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the category.'], 500);
        }
    } //end method

    /////////Blog Post Controller///////////////

    function BlogPost()
    {
        $post = BlogPost::latest()->get();
        return view('admin.backend.post.all_post', compact('post'));
    } //end method

    function AddBlogPost()
    {
        $blogCategory = BlogCategory::latest()->get();
        return view('admin.backend.post.add_post', compact('blogCategory'));
    } //end method

    public function StoreBlogPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blog_category_id' => 'required|exists:blog_categories,id',
            'post_title' => 'required|string|min:3|max:255',
            'long_description' => 'nullable|string',
            'post_tags' => 'nullable|string',
            'post_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // max 2MB
        ], [
            'blog_category_id.required' => 'Blog category is required.',
            'blog_category_id.exists' => 'Selected blog category is invalid.',
            'post_title.required' => 'Post title is required.',
            'post_title.min' => 'Post title must be at least 3 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $save_url = null;
        if ($request->file('post_image')) {
            $image = $request->file('post_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $upload_path = 'upload/blog_post/';

            // Pastikan direktori ada
            if (!File::isDirectory(public_path($upload_path))) {
                File::makeDirectory(public_path($upload_path), 0755, true, true);
            }

            $image->move(public_path($upload_path), $name_gen);

            $save_url = $upload_path . $name_gen;
        }

        BlogPost::create([
            'blog_category_id' => $request->blog_category_id,
            'post_title' => $request->post_title,
            'post_slug' => Str::slug($request->post_title, '-'),
            'long_description' => $request->long_description,
            'post_tags' => $request->post_tags,
            'post_image' => $save_url,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Blog Post Create Successfully!',
            'redirect_url' => route('blog.post')
        ]);
    } //end method

    public function UploadImageQuill(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // Validasi gambar
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $uploadPath = 'upload/blog_post_featured/'; // Direktori untuk gambar dari Quill

            // Pastikan direktori ada
            if (!File::isDirectory(public_path($uploadPath))) {
                File::makeDirectory(public_path($uploadPath), 0755, true, true);
            }

            $image->move(public_path($uploadPath), $imageName);
            $imageUrl = asset($uploadPath . $imageName); // asset() akan menghasilkan URL publik penuh

            return response()->json(['success' => true, 'url' => $imageUrl]);
        }

        return response()->json(['success' => false, 'error' => 'No image uploaded or invalid image.'], 400);
    } // end method

    public function DeleteBlogPost($id)
    {
        try {
            $post = BlogPost::findOrFail($id);
            $image_path_to_delete = $post->post_image; // Simpan path gambar

            $post->delete(); // Hapus record post dari database

            // Hapus file gambar dari server jika path gambar ada dan file nya exist
            if ($image_path_to_delete) {
                $full_image_path = public_path($image_path_to_delete);
                if (File::exists($full_image_path)) {
                    File::delete($full_image_path);
                }
            }

            if (request()->ajax()) {
                return response()->json(['message' => 'Blog post deleted successfully!'], 200);
            }
        } catch (ModelNotFoundException $e) {
            if (request()->ajax()) {
                return response()->json(['message' => 'Blog Post not found.'], 404);
            }
            // return redirect()->route('blog.post')->with('error', 'Blog Post not found.');
        } catch (\Exception $e) {
            // Sebaiknya log error di sini
            // \Log::error('Error deleting blog post: ' . $e->getMessage());
            if (request()->ajax()) {
                return response()->json(['message' => 'An error occurred while deleting the blog post. Please try again.'], 500);
            }
            // return redirect()->route('blog.post')->with('error', 'An error occurred.');
        }
    }
    //end method

    public function BlogPostEdit($id)
    {
        $post = BlogPost::findOrFail($id);
        $blogCategory = BlogCategory::latest()->get();
        return view('admin.backend.post.update_post', compact('post', 'blogCategory'));
    }
    //end method

    public function BlogPostUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:blog_posts,id',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'post_title' => 'required|string|min:3|max:255',
            'long_description' => 'nullable|string',
            'post_tags' => 'nullable|string',
            'post_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // max 2MB
        ], [
            'blog_category_id.required' => 'Blog category is required.',
            'blog_category_id.exists' => 'Selected blog category is invalid.',
            'post_title.required' => 'Post title is required.',
            'post_title.min' => 'Post title must be at least 3 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $post = BlogPost::findOrFail($request->post_id);

        $post->blog_category_id = $request->blog_category_id;
        $post->post_title = $request->post_title;
        $post->post_slug = Str::slug($request->post_title); // Buat slug jika ada fieldnya
        $post->long_description = $request->long_description;
        $post->post_tags = $request->post_tags;

        if ($request->hasFile('post_image')) {
            // Hapus gambar lama jika ada dan bukan no_image.jpg
            if ($post->post_image && File::exists(public_path($post->post_image)) && basename($post->post_image) !== 'no_image.jpg') {
                File::delete(public_path($post->post_image));
            }

            $image = $request->file('post_image');
            $imageName = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $imagePath = 'upload/blog_posts/' . $imageName;

            $image->move(public_path('upload/blog_posts/'), $imageName);

            $post->post_image = $imagePath;
        }

        $post->save();

        // Untuk AJAX, kirim respons JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Blog Post Updated Successfully!',
            'redirect_url' => route('blog.post') // URL untuk redirect setelah sukses
        ]);
    }

    //Blog Post Detail
    public function BlogPostDetail($id, $slug)
    {
        $blog = BlogPost::where('id', $id)
            ->where('post_slug', $slug)
            ->firstOrFail();

        $blogCategory = BlogCategory::latest()->get();
        $post = BlogPost::latest()->limit(3)->get();

        return view('frontend.blog.blog_details', compact('blog', 'blogCategory', 'post'));
    } //end method

    public function BlogCategoryList($id)
    {
        $blog = BlogPost::where('blog_category_id', $id)->get();
        $blogCategory = BlogCategory::where('id', $id)->first();
        $blogCategoryAll = BlogCategory::latest()->get();
        $post = BlogPost::latest()->limit(3)->get();


        return view('frontend.blog.blog_category_list', compact('blog', 'blogCategory', 'blogCategoryAll', 'post'));
    } //end method

    public function BlogList()
    {
        $blog = BlogPost::latest()->get();
        $blogCategoryAll = BlogCategory::latest()->get();
        $post = BlogPost::latest()->limit(3)->get();

        return view('frontend.blog.blog_list', compact('blog', 'blogCategoryAll', 'post'));
    } //endmethod

}
