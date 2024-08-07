<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function CourseDetails($id,$slug) {
        $course  = Course::with(['category','subCategory','courseGoals','courseSections.courseLectures','instructor'])->find($id);
        $totalLectures = $course->courseSections->sum(function($section) {
            return $section->courseLectures->count();
        });

        $categories = Category::latest()->get();
        $coursesByCategory = Course::with(['instructor'])->where([['category_id','=',$course->category_id],['id','!=',$course->id]])->take(3)->get();
        $instructorId = $course->instructor->id;
        $instructorCourses = Course::where('instructor_id', $instructorId)->orderBy('id','DESC')->get();
        return view('frontend.course.course_details',compact('course','totalLectures','instructorCourses','categories','coursesByCategory'));
    }
    //end method

    public function CategoryCourse($id, $slug) {
        $courses = Course::with(['instructor'])->where([['category_id','=',$id],['status','=',1]])->get();
        $category = Category::where('id',$id)->first();
        $categories = Category::latest()->get();
        return view('frontend.category.category_all',compact('courses','category','categories'));
    }
    //end method

    public function SubCategoryCourse($id, $slug) {
        $courses = Course::with(['instructor'])->where([['subcategory_id','=',$id],['status','=',1]])->get();
        $subcategory = SubCategory::where('id',$id)->first();
        $categories = Category::latest()->get();
        return view('frontend.category.subcategory_all',compact('courses','subcategory','categories'));
    } //end method
}
