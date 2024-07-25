<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseGoal;
use App\Models\CourseSection;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class CourseController extends Controller
{
    public function AllCourse()
    {
        $id = Auth::user()->id;
        $courses = Course::with('category')->where('instructor_id', $id)->orderBy('id', 'desc')->get();

        return view('instructor.courses.all_course', compact('courses'));
    } //endmethod

    public function AddCourse()
    {
        $categories = Category::latest()->get();
        return view('instructor.courses.add_course', compact('categories'));
    } //endmethod

    public function GetSubCategory($categoryId)
    {
        $categories = Category::findOrFail($categoryId);
        $subcategories = $categories->subCategories;

        return response()->json($subcategories);
    } //endmethod

    public function StoreCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_name' => 'required|string|max:255|unique:courses,course_name',
            'course_title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'required|mimetypes:video/mp4,video/webm|max:20240',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'description' => 'required|string',
            'label' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'resources' => 'required|string|max:255',
            'certificate' => 'required|string|max:255',
            'selling_price' => 'required|numeric|between:0,999999.99',
            'discount_price' => 'nullable|numeric|between:0,999999.99',
            'prerequisites' => 'required|string',
            'bestseller' => 'nullable|string|max:255',
            'featured' => 'nullable|string|max:255',
            'highestarted' => 'nullable|string|max:255',
            'course_goals.*' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            $notification = [
                'message' => 'Course insert failed. ' . $validator->errors()->first(),
                'alert-type' => 'error'
            ];

            return back()->with($notification);
        }

        //image
        $image = $request->file('image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        $resizedImage = Image::read($image->getRealPath())->resize(370, 246);
        $resizedImage->save('upload/course/thumbnail/' . $name_gen);
        $save_url = 'upload/course/thumbnail/' . $name_gen;

        //video
        $video = $request->file('video');
        $videoname = time().'.' . $video->getClientOriginalExtension();
        $video->move(public_path('upload/course/video/'),$videoname);
        $save_video = 'upload/course/video/' . $videoname;

        $course = Course::create([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'instructor_id' => Auth::user()->id,
            'course_image' => $save_url,
            'course_title' => $request->course_title,
            'course_name' => $request->course_name,
            'course_name_slug' => Str::slug($request->course_name),
            'description' => $request->description,
            'video' => $save_video,

            'label' => $request->label,
            'duration' => $request->duration,
            'resources' => $request->resources,
            'certificate' => $request->certificate,
            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'prerequisites' => $request->prerequisites,

            'bestseller' => $request->bestseller,
            'featured' => $request->featured,
            'highestarted' => $request->highestarted,
            'status' => 1,
        ]);

        //course goals  and form

        foreach ($request->course_goals as $goal) {
            CourseGoal::create([
                'course_id' => $course->id,
                'goal_name' => $goal
            ]);
        }

        $notification = [
            'message' => 'Course Inserted Succesfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.course')->with($notification);  
    } //endmethod

    public function EditCourse($id) {
        $course = Course::with(['category','courseGoals'])->find($id);
        $categories = Category::latest()->get();
        return view('instructor.courses.edit_course',compact('course','categories'));
    }
    //endmethod

    public function UpdateCourse(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:courses,id',
            'course_name' => 'required|string|max:255|unique:courses,course_name,' . $request->id,
            'course_title' => 'required|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'sometimes|mimetypes:video/mp4,video/webm|max:20240',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'description' => 'required|string',

            'label' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'resources' => 'required|string|max:255',
            'certificate' => 'required|string|max:255',
            'selling_price' => 'required|numeric|between:0,999999.99',
            'discount_price' => 'nullable|numeric|between:0,999999.99',

            'prerequisites' => 'required|string',
            'bestseller' => 'nullable|string|max:255',
            'featured' => 'nullable|string|max:255',
            'highestarted' => 'nullable|string|max:255',
            'course_goals.*' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            $notification = [
                'message' => 'Course insert failed. ' . $validator->errors()->first(),
                'alert-type' => 'error'
            ];

            return back()->with($notification);
        }

        $course = Course::findOrFail($request->id);
        if (!$course) {
            $notification = [
                'message' => 'Course not found',
                'alert-type' => 'error'
            ];               
    
            return back()->with($notification);
        }

        //image
        if ($request->hasFile('image')) {
            if ($course->course_image && file_exists(public_path($course->course_image))) {
                unlink(public_path($course->course_image));
            }

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $resizedImage = Image::read($image->getRealPath())->resize(370, 246);
            $resizedImage->save('upload/course/thumbnail/' . $name_gen);
            $save_url = 'upload/course/thumbnail/' . $name_gen;
            $course->course_image = $save_url;
        }

        //video
       if ($request->hasFile('video')) {
        if ($course->video && file_exists(public_path($course->video))) {
            unlink(public_path($course->video));
        }

        $video = $request->file('video');
        $videoname = time().'.' . $video->getClientOriginalExtension();
        $video->move(public_path('upload/course/video/'),$videoname);
        $save_video = 'upload/course/video/' . $videoname;
        $course->video = $save_video;
       }
        
        $course->update([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'instructor_id' => Auth::user()->id,
            'course_title' => $request->course_title,
            'course_name' => $request->course_name,
            'course_name_slug' => Str::slug($request->course_name),
            'description' => $request->description,

            'label' => $request->label,
            'duration' => $request->duration,
            'resources' => $request->resources,
            'certificate' => $request->certificate,
            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'prerequisites' => $request->prerequisites,

            'bestseller' => $request->bestseller,
            'featured' => $request->featured,
            'highestarted' => $request->highestarted,
            'status' => 1,
        ]);

        //course goals  and form
        if ($request->has('course_goals')) {
            $course->courseGoals()->delete();
            foreach ($request->course_goals as $goal) {
                CourseGoal::create([
                    'course_id' => $course->id,
                    'goal_name' => $goal
                ]);
            }
        }
        
        $notification = [
            'message' => 'Course Updated Succesfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.course')->with($notification); 
    }
    //endmethod

    public function DeleteCourse($id)
    {
        $course = Course::find($id);

        if ($course) {
            // Hapus gambar jika ada
            if (file_exists(public_path($course->course_image))) {
                unlink(public_path($course->course_image));
            }
            if (file_exists(public_path($course->video))) {
                unlink(public_path($course->video));
            }

            $course->delete();

            $notification = [
                'message' => 'course Deleted Successfully',
                'alert-type' => 'success'
            ];
        } else {
            $notification = [
                'message' => 'course not found.',
                'alert-type' => 'error'
            ];
        }

        return redirect()->route('all.course')->with($notification);
    } //end method

    public function AddCourseLecture($id) {
        $course = Course::with('courseSections')->find($id);
        return view('instructor.courses.section.add_course_lecture', compact('course'));
    }//end method

    public function AddCourseSection(Request $request) {
        $validator = Validator::make($request->all(),[
            'id' => 'required|numeric',
            'section_title' => 'required|string|max:2555',
        ]);

        if ($validator->fails()) {
            $notification = [
                'message' => 'Course section insert failed. ' . $validator->errors()->first(),
                'alert-type' => 'error'
            ];

            return redirect()->back()->with($notification);
        }

        CourseSection::create([
            'course_id' => $request->id,
            'section_title' => $request->section_title,
        ]);
        $notification = [
            'message' => 'Course section insert succesfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);

    }//end method

}
