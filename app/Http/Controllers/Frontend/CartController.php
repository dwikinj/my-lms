<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Course;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function AddToCart(Request $request, $id)
    {
        $course = Course::find($id);

        //is course already exist in cart 
        $cartItem = Cart::search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id === $id;
        });

        if ($cartItem->isNotEmpty()) {
            return response()->json(['error' => 'Course is already in your cart']);
        }

        if ($course->discount_price == NULL) {
            Cart::add([
                'id' => $id,
                'name' => $request->course_name,
                'qty' => 1,
                'price' => $course->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $course->course_image,
                    'slug' => $request->course_name_slug,
                    'instructor' => $course->instructor->name,
                    'instructor_id' => $course->instructor->id,
                ]

            ]);
        } else {
            Cart::add([
                'id' => $id,
                'name' => $request->course_name,
                'qty' => 1,
                'price' => $course->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $course->course_image,
                    'slug' => $request->course_name_slug,
                    'instructor' => $course->instructor->name,
                    'instructor_id' => $course->instructor->id,

                ]

            ]);
        }

        return response()->json(['success' => 'Succesfully Added Course to Your Cart']);
    } //end method

    public function CartData()
    {
        $carts = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json([
            'carts' => $carts,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ]);
    } //end method

    public function AddMiniCart()
    {
        $carts = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json([
            'carts' => $carts,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ]);
    } //end method

    public function RemoveMiniCart($rowId)
    {
        Cart::remove($rowId);

        return response()->json([
            'success' => 'Successfully Deleted Course From Your Cart',
        ]);
    } //end method

    public function MyCart()
    {

        return view('frontend.mycart.view_mycart');
    } //end method

    public function GetCartCourse()
    {
        $carts = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json([
            'carts' => $carts,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ]);
    }

    public function ApplyCoupon(Request $request)
    {
        $coupon_name = $request->coupon_name;
        $coupon = Coupon::where('coupon_name', $coupon_name)->first();

        if ($coupon === null) {
            return response()->json(['error' => 'Invalid Coupon Code']);
        }

        if (Carbon::now()->toDateString() > $coupon->coupon_validty) {
            return response()->json(['error' => 'Coupon Code is expired']);
        }

        Session::put('coupon',[
            'coupon_name' => $coupon->coupon_name,
            'coupon_discount' => $coupon->coupon_discount,
            'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100),
            'total_amount' => round(Cart::total() - Cart::total()* $coupon->coupon_discount/100),

        ]);

        return response()->json([
            'validity' => true,
            'success' => 'Coupon Applied Successfully',
        ]);


    } // End Method
    public function CalculationCoupon()
    {
        if (Session::has('coupon')) {
            return response()->json(array(
                'subtotal' => Cart::total(),
                'coupon_name' => session()->get('coupon')['coupon_name'],
                'coupon_discount' => session()->get('coupon')['coupon_discount'],
                'discount_amount' => session()->get('coupon')['discount_amount'],
                'total_amount' => session()->get('coupon')['total_amount'],
            ));
        }else {
            return response()->json(array(
                'total' => Cart::total()
            ));
        }

    } // End Method

    //method remove coupon
    public function CouponRemove(){
        Session::forget('coupon'); // Hapus session 'coupon'
        return response()->json(['success' => 'Coupon Successfully Removed']); 
    } // End Method

    public function CheckoutCreate() {
        if (Auth::check()) { 
            if(Cart::total() > 0){
                $carts = Cart::content();
                $cartTotal = Cart::total();
                $cartQty = Cart::count();
                $couponData = session()->get('coupon');

    
                return view('frontend.checkout.checkout_view',compact('carts','cartTotal','cartQty','couponData'));
            }else {
                $notification = [
                    'message' => 'Please, Select at Least One Course',
                    'alert-type' => 'error'
                ];
                return redirect()->route('index')->with($notification);
            }
        }else {
            $notification = [
                'message' => 'Login first',
                'alert-type' => 'error'
            ];
            return redirect()->route('login')->with($notification);
        }
    }// End Method

}
