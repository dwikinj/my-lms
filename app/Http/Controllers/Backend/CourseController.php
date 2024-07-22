<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function AllCourse() {
        $id = Auth::user()->id;
        $courses = Course::where('instructor_id',$id)->orderBy('id','desc')->get();

        return view('instructor.courses.all_course',compact('courses'));
    }//endmethod

    public function AddCourse() {
        $categories = Category::latest()->get();
        return view('instructor.courses.add_course',compact('categories'));
    }//endmethod

    public function GetSubCategory($categoryId) {
        $categories = Category::findOrFail($categoryId);
        $subcategories = $categories->subCategories;

        return response()->json($subcategories);
    }
}
