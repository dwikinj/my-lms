<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Course;
use Illuminate\Http\Request;
use Carbon\Carbon; // Tambahkan untuk manipulasi tanggal
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function AllCoupon()
    {
        $coupon = Coupon::latest()->get();
        return view('admin.backend.coupon.coupon_all', compact('coupon'));
    } //end method

    public function AddCoupon()
    {
        return view('admin.backend.coupon.add_coupon');
    } //end method

    public function StoreCoupon(Request $request)
    {
        $request->validate([
            'coupon_name' => 'required|min:3|unique:coupons,coupon_name',
            'coupon_discount' => 'required|integer|min:0|max:100',
            'coupon_validty' => 'required|date|after_or_equal:today', // Validasi tanggal setelah hari ini atau hari ini
        ], [
            'coupon_name.required' => 'Coupon Name is required.',
            'coupon_name.min' => 'Coupon Name must be at least 3 characters.',
            'coupon_name.unique' => 'Coupon Name is already taken.',
            'coupon_discount.required' => 'Coupon Discount is required.',
            'coupon_discount.integer' => 'Coupon Discount must be an integer.',
            'coupon_discount.min' => 'Coupon Discount must be at least 0%.',
            'coupon_discount.max' => 'Coupon Discount cannot exceed 100%.',
            'coupon_validty.required' => 'Coupon Validity Date is required.',
            'coupon_validty.date' => 'Coupon Validity Date format is invalid.',
            'coupon_validty.after_or_equal' => 'Coupon Validity Date must be today or later.',
        ]);

        Coupon::insert([
            'coupon_name' => strtoupper($request->coupon_name), // Simpan dalam huruf besar
            'coupon_discount' => $request->coupon_discount,
            'coupon_validty' => $request->coupon_validty,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Coupon Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.coupon')->with($notification);
    } // End Method

    public function EditCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.backend.coupon.edit_coupon', compact('coupon'));
    } // End Method

    public function UpdateCoupon(Request $request)
    {
        $coupon_id = $request->id;
        $request->validate([
            'coupon_name' => 'required|min:3|unique:coupons,coupon_name,' . $coupon_id, // Abaikan validasi unik untuk data sendiri
            'coupon_discount' => 'required|integer|min:0|max:100',
            'coupon_validty' => 'required|date|after_or_equal:today',
        ], [
            'coupon_name.required' => 'Coupon Name is required.',
            'coupon_name.min' => 'Coupon Name must be at least 3 characters.',
            'coupon_name.unique' => 'Coupon Name is already taken.',
            'coupon_discount.required' => 'Coupon Discount is required.',
            'coupon_discount.integer' => 'Coupon Discount must be an integer.',
            'coupon_discount.min' => 'Coupon Discount must be at least 0%.',
            'coupon_discount.max' => 'Coupon Discount cannot exceed 100%.',
            'coupon_validty.required' => 'Coupon Validity Date is required.',
            'coupon_validty.date' => 'Coupon Validity Date format is invalid.',
            'coupon_validty.after_or_equal' => 'Coupon Validity Date must be today or later.',
        ]);

        Coupon::findOrFail($coupon_id)->update([
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_validty' => $request->coupon_validty,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Coupon Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.coupon')->with($notification);
    } // End Method

    public function DeleteCoupon($id)
    {
        Coupon::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Coupon Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method

    public function InstructorAllCoupon()
    {
        $id = Auth::id();
        $coupon = Coupon::with(['course'])->where('instructor_id', $id)->latest()->get();
        // dd($coupon);
        return view('instructor.coupon.coupon_all', compact('coupon'));
    } //end method

    public function InstructorAddCoupon()
    {
        $id = Auth::id();
        $courses = Course::where('instructor_id', $id)->orderBy('course_name', 'ASC')->get();
        return view('instructor.coupon.add_coupon', compact('courses'));
    } //end method

    public function InstructorStoreCoupon(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'coupon_name' => 'required|min:3|unique:coupons,coupon_name',
            'coupon_discount' => 'required|integer|min:0|max:100',
            'coupon_validty' => 'required|date|after_or_equal:today',
        ], [
            'course_id.required' => 'Course is required.',
            'course_id.exists' => 'Course not found.',
            'coupon_name.required' => 'Coupon Name is required.',
            'coupon_name.min' => 'Coupon Name must be at least 3 characters.',
            'coupon_name.unique' => 'Coupon Name is already taken.',
            'coupon_discount.required' => 'Coupon Discount is required.',
            'coupon_discount.integer' => 'Coupon Discount must be an integer.',
            'coupon_discount.min' => 'Coupon Discount must be at least 0%.',
            'coupon_discount.max' => 'Coupon Discount cannot exceed 100%.',
            'coupon_validty.required' => 'Coupon Validity Date is required.',
            'coupon_validty.date' => 'Coupon Validity Date format is invalid.',
            'coupon_validty.after_or_equal' => 'Coupon Validity Date must be today or later.',
        ]);

        $id = Auth::id();
        Coupon::create([
            'course_id' => $request->course_id,
            'instructor_id' => $id,
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_status' => 1,
            'coupon_validty' => $request->coupon_validty,
        ]);


        $notification = array(
            'message' => 'Coupon Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('instructor.all.coupon')->with($notification);
    } // End Method

    public function InstructorEditCoupon($id)
    {
        $instructorId = Auth::id();
        $coupon = Coupon::findOrFail($id);
        $courses = Course::where('instructor_id', $instructorId)->orderBy('course_name', 'ASC')->get();
        return view('instructor.coupon.edit_coupon', compact('coupon', 'courses'));
    } //end method

    public function InstructorUpdateCoupon(Request $request)
    {

        $request->validate([
            'id' => 'required|integer|exists:coupons,id',
            'course_id' => 'nullable|integer|exists:courses,id',
            'coupon_name' => [
                'required',
                'min:3',
                Rule::unique('coupons', 'coupon_name')->ignore($request->id),
            ],
            'coupon_discount' => 'required|integer|min:0|max:100',
            'coupon_validty' => 'required|date|after_or_equal:today',
            'coupon_status' => 'required|integer|in:0,1',
        ], [
            // 'course_id.required' => 'Course is required if not for all courses.',
            'course_id.exists' => 'Selected course not found.',
            'coupon_name.required' => 'Coupon Name is required.',
            'coupon_name.min' => 'Coupon Name must be at least 3 characters.',
            'coupon_name.unique' => 'Coupon Name is already taken.',
            'coupon_discount.required' => 'Coupon Discount is required.',
            'coupon_discount.integer' => 'Coupon Discount must be an integer.',
            'coupon_discount.min' => 'Coupon Discount must be at least 0%.',
            'coupon_discount.max' => 'Coupon Discount cannot exceed 100%.',
            'coupon_validty.required' => 'Coupon Validity Date is required.',
            'coupon_validty.date' => 'Coupon Validity Date format is invalid.',
            'coupon_validty.after_or_equal' => 'Coupon Validity Date must be today or later.',
            'coupon_status.required' => 'Status is required.',
            'coupon_status.boolean' => 'Invalid status value.',
        ]);

        $id = $request->id;
        $coupon = Coupon::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();

        $coupon->update([
            'course_id' => $request->course_id ?: null,
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_validty' => $request->coupon_validty,
            'coupon_status' => $request->coupon_status,
        ]);

        $notification = array(
            'message' => 'Coupon Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('instructor.all.coupon')->with($notification);
    }

    public function InstructorDeleteCoupon($id)
    {
        $coupon = Coupon::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();
        $coupon->delete();

        $notification = array(
            'message' => 'Coupon Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('instructor.all.coupon')->with($notification); // Redirect kembali ke halaman sebelumnya
    }
}
