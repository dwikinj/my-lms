<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function CourseDetails($id, $slug)
    {
        $course  = Course::with(['category', 'subCategory', 'courseGoals', 'courseSections.courseLectures', 'instructor'])->find($id);
        $totalLectures = $course->courseSections->sum(function ($section) {
            return $section->courseLectures->count();
        });

        $categories = Category::latest()->get();
        $coursesByCategory = Course::with(['instructor'])->where([['category_id', '=', $course->category_id], ['id', '!=', $course->id]])->take(3)->get();
        $instructorId = $course->instructor->id;
        $instructorCourses = Course::where('instructor_id', $instructorId)->orderBy('id', 'DESC')->get();
        return view('frontend.course.course_details', compact('course', 'totalLectures', 'instructorCourses', 'categories', 'coursesByCategory'));
    }
    //end method

    public function CategoryCourse($id, $slug)
    {
        $courses = Course::with(['instructor'])->where([['category_id', '=', $id], ['status', '=', 1]])->get();
        $category = Category::where('id', $id)->first();
        $categories = Category::latest()->get();
        return view('frontend.category.category_all', compact('courses', 'category', 'categories'));
    }
    //end method

    public function SubCategoryCourse($id, $slug)
    {
        $courses = Course::with(['instructor'])->where([['subcategory_id', '=', $id], ['status', '=', 1]])->get();
        $subcategory = SubCategory::where('id', $id)->first();
        $categories = Category::latest()->get();
        return view('frontend.category.subcategory_all', compact('courses', 'subcategory', 'categories'));
    } //end method

    public function InstructorDetails($id)
    {
        $instructor = User::find($id);
        $courses  = Course::where('instructor_id', $id)->get();
        return view('frontend.instructor.instructor_details', compact('instructor', 'courses'));
    } //end method

    public function AllCourses()
    {
        $courses = Course::where('status', 1)
                        ->with('instructor') // Eager load instructor
                        ->withCount('reviews') // Untuk total review
                        ->withAvg('reviews', 'rating') // Untuk rata-rata rating
                        ->orderBy('created_at', 'DESC') // Mengurutkan berdasarkan terbaru
                        ->paginate(3);
        $categories = Category::orderBy('category_name', 'ASC')->get();
        $course_levels = Course::where('status', 1)->distinct()->pluck('label')->filter()->sort()->values();
        return view('frontend.course.course_all',compact('courses', 'categories', 'course_levels'));
    } //end method

    public function filterCoursesByRating(Request $request)
    {
        $query = Course::where('status', 1)
                        ->with('instructor') // Eager load instructor
                        ->withCount('reviews') // Untuk total review
                        ->withAvg('reviews', 'rating') // Untuk rata-rata rating (menghasilkan 'reviews_avg_rating')
                        ->orderBy('created_at', 'DESC');

        // Apply category filter
        if ($request->filled('category_ids') && is_array($request->category_ids)) {
            $categoryIds = array_filter($request->category_ids, 'is_numeric'); // Sanitize: ensure all are numeric
            if (!empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Apply level filter
        if ($request->filled('level_labels') && is_array($request->level_labels)) {
            $levelLabels = array_filter($request->level_labels, 'is_string'); // Sanitize: ensure all are strings
            if (!empty($levelLabels)) {
                $query->whereIn('label', $levelLabels);
            }
        }

        if ($request->filled('rating_filter') && $request->rating_filter !== 'all') {
            $ratingRange = explode('-', $request->rating_filter);
            if (count($ratingRange) == 2) {
                $minRating = (float)$ratingRange[0];
                $maxRating = (float)$ratingRange[1];

                // Ensure we only consider courses that have an average rating calculated
                $query->havingRaw('reviews_avg_rating IS NOT NULL');

                if ($maxRating == 5) {
                    // Untuk rentang "4-5", berarti rating >= 4.0 DAN <= 5.0
                    $query->having('reviews_avg_rating', '>=', $minRating)
                          ->having('reviews_avg_rating', '<=', $maxRating);
                } else {
                    // Untuk rentang lain, misal "3-4", berarti rating >= 3.0 DAN < 4.0
                    $query->having('reviews_avg_rating', '>=', $minRating)
                          ->having('reviews_avg_rating', '<', $maxRating);
                }
            }
        }

        $courses = $query->paginate(3)->appends($request->except('page')); // 3 item per halaman, tambahkan filter ke paginasi

        if ($request->ajax()) {
            // Kembalikan partial view yang hanya berisi daftar kursus dan paginasi
            // Categories are not needed for the partial, only the filtered courses
            return view('frontend.course.partials.course_list_item', compact('courses'))->render(); 
        }

        // Untuk request non-AJAX (seharusnya tidak terjadi jika JS aktif)
        // Anda bisa mengarahkan kembali atau memuat view penuh jika diperlukan.
        // Namun, fokus kita adalah AJAX.
        $categories = Category::orderBy('category_name', 'ASC')->get();
        $course_levels = Course::where('status', 1)->distinct()->pluck('label')->filter()->sort()->values();
        return view('frontend.course.course_all', compact('courses', 'categories', 'course_levels'));
    }
}
