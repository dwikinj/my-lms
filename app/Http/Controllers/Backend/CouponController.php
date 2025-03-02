<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon; // Tambahkan untuk manipulasi tanggal

class CouponController extends Controller
{
    public function AllCoupon()
    {
        $coupon = Coupon::latest()->get();
        return view('admin.backend.coupon.coupon_all',compact('coupon'));
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
    
}