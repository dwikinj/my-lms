<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
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
}
