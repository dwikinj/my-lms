<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\Orderconfirm;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\OrderComplete;
use App\Services\MidtransService;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function AddToCart(Request $request, $id)
    {
        $course = Course::find($id);
        $existingOrder = Order::where('user_id', Auth::id())
            ->where('course_id', $id)
            ->first();

        $cartItem = Cart::search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id === $id;
        });

        if ($cartItem->isNotEmpty()) {
            return response()->json(['error' => 'Course is already in your cart']);
        }

        if ($existingOrder) {
            return response()->json(['error' => 'You already bought this course']);
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

    public function BuyToCart(Request $request, $id)
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

        Session::put('coupon', [
            'coupon_name' => $coupon->coupon_name,
            'coupon_discount' => $coupon->coupon_discount,
            'discount_amount' => round(Cart::total() * $coupon->coupon_discount / 100),
            'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount / 100),

        ]);

        return response()->json([
            'validity' => true,
            'success' => 'Coupon Applied Successfully',
        ]);
    } // End Method

    public function ApplyInstructorCoupon(Request $request)
    {

        $validatedData = $request->validate([
            'coupon_name' => 'required|string|max:255',
            'course_id' => 'nullable|integer|exists:courses,id',
            'instructor_id' => 'nullable|integer|exists:users,id',
        ]);

        $coupon_name = $validatedData['coupon_name'];
        $course_id = $validatedData['course_id'] ?? null;
        $instructor_id = $validatedData['instructor_id'] ?? null;

        $today = Carbon::now()->toDateString();
        $coupon = null;

        //universal coupon
        $coupon = Coupon::where('coupon_name', $coupon_name)
            ->whereNull('course_id')
            ->whereNull('instructor_id')
            ->where('coupon_validty', '>=', $today)
            ->first();
        //end universal coupon

        if (!$coupon && $course_id && $instructor_id) {
            $coupon = Coupon::where('coupon_name', $coupon_name)
                ->where('course_id', $course_id)
                ->where('instructor_id', $instructor_id)
                ->where('coupon_validty', '>=', $today)
                ->first();
        }

        if (!$coupon) {
            return response()->json(['error' => 'Invalid or Expired Coupon Code']);
        }

        Session::put('coupon', [
            'coupon_name' => $coupon->coupon_name,
            'coupon_discount' => $coupon->coupon_discount,
            'discount_amount' => round(Cart::total() * $coupon->coupon_discount / 100),
            'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount / 100),
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
        } else {
            return response()->json(array(
                'total' => Cart::total()
            ));
        }
    } // End Method

    //method remove coupon
    public function CouponRemove()
    {
        Session::forget('coupon'); // Hapus session 'coupon'
        return response()->json(['success' => 'Coupon Successfully Removed']);
    } // End Method

    public function CheckoutCreate()
    {
        if (Auth::check()) {
            if (Cart::total() > 0) {
                $carts = Cart::content();
                $cartTotal = Cart::total();
                $cartQty = Cart::count();
                $couponData = session()->get('coupon');


                return view('frontend.checkout.checkout_view', compact('carts', 'cartTotal', 'cartQty', 'couponData'));
            } else {
                $notification = [
                    'message' => 'Please, Select at Least One Course',
                    'alert-type' => 'error'
                ];
                return redirect()->route('index')->with($notification);
            }
        } else {
            $notification = [
                'message' => 'Login first',
                'alert-type' => 'error'
            ];
            return redirect()->route('login')->with($notification);
        }
    } // End Method

    //Payment Method
    public function Payment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'total' => 'required|numeric|min:0',
        ]);

        $cartTotal = Cart::total();
        $subTotal = Cart::total();
        $carts = Cart::content();
        $userId = Auth::id();

        //user instructor
        $user = User::where('role', 'instructor')->get();
        //end user instructor

        $totalAmount = (Session::has('coupon'))
            ? session()->get('coupon')['total_amount']
            : $cartTotal;


        $data = array();
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['address'] = $request->address;
        $data['course_title'] = $request->course_title;


        if ($request->cash_delivery == 'midtrans') {
            $midtransService = new MidtransService();

            $orderId = 'EOS' . mt_rand(10000000, 99999999);
            // Prepare items for Midtrans
            $items = [];
            foreach ($carts as $cart) {
                $price = $cart->price;

                // Jika ada coupon di session, hitung diskon
                if (session()->has('coupon')) {
                    $discountPercent = session('coupon')['coupon_discount'];
                    $price = $price - ($price * ($discountPercent / 100));
                }

                $items[] = [
                    'id' => $cart->id,
                    'price' => ceil($price),
                    'quantity' => 1,
                    'name' => $cart->name,
                ];
            }

            $transactionParams = [
                'order_id' => $orderId,
                'total_amount' => (float) str_replace(',', '', $totalAmount),
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'items' => $items,
            ];
            // Get Snap Token
            $snapToken = $midtransService->createTransaction($transactionParams);

            // Store order info in session
            Session::put('order_data', $data);
            Session::put('total_amount', $totalAmount);
            Session::put('order_id', $orderId);

            return view('frontend.payment.midtrans', compact('data', 'totalAmount', 'carts', 'snapToken', 'subTotal'));
        } elseif ($request->cash_delivery == 'cod') {

            $payment = new Payment();
            $payment->name = $request->name;
            $payment->email = $request->email;
            $payment->phone = $request->phone;
            $payment->address = $request->address;
            $payment->cash_delivery = $request->cash_delivery;
            $payment->total_amount = $totalAmount;
            $payment->payment_type = 'Direct Payment';
            $payment->invoice_no = 'EOS' . mt_rand(10000000, 99999999);
            $payment->order_date = Carbon::now()->format('d F Y');
            $payment->order_month = Carbon::now()->format('F');
            $payment->order_year = Carbon::now()->format('Y');
            $payment->status = 'pending';

            $payment->save();

            $carts = Cart::content();
            $user_id = Auth::id();

            foreach ($carts as $cart) {

                $order = new Order();
                $order->payment_id = $payment->id;
                $order->user_id = $user_id;
                $order->instructor_id = $cart->options->instructor_id;
                $order->course_id = $cart->id;
                $order->course_title = $cart->name;
                $order->price = $cart->price;
                $order->save();

                //Send notification
                $orderData = [
                    'message' => 'New COD Enrollment In Course',
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'course_title' => $order->course_title,
                    'price' => $order->price,
                ];
                Notification::send($user, new OrderComplete($orderData));
                //End send notification;
            }

            Cart::destroy();
            if (Session::has('coupon')) {
                Session::forget('coupon');
            }

            //Start send email to student
            $data = [
                'invoice_no' => $payment->invoice_no,
                'amount' => $payment->total_amount,
                'name' => $payment->name,
                'email' => $payment->email,
            ];

            Mail::to($payment->email)->send(new Orderconfirm($data));
            //End send email to student




            $notification = [
                'message' => 'COD Payment Successful. Thank you for your purchase!',
                'alert-type' => 'success'
            ];
            return redirect()->route('index')->with($notification);
        }
    }
    //End Method

    public function midtransSuccess(Request $request)
    {
        $orderId = $request->order_id;
        $midtransService = new MidtransService();
        $status = $midtransService->verifyPayment($orderId);

        if (!$status ||  $status->transaction_status != 'capture') {
            // Clear cart and coupon
            Cart::destroy();
            if (Session::has('coupon')) {
                Session::forget('coupon');
            }
            Session::forget('order_data');
            Session::forget('order_id');
            Session::forget('total_amount');

            $notification = [
                'message' => 'Payment failed or pending. Please try again',
                'alert-type' => 'error'
            ];
            return redirect()->route('index')->with($notification);
        }

        // Get order data from session
        $data = Session::get('order_data');
        $carts = Cart::content();
        $cartTotal = Session::get('total_amount');;
        $orderId = Session::get('order_id');
        $userId = Auth::id();


        // Create payment record
        $payment = new Payment();
        $payment->name = $data['name'];
        $payment->email = $data['email'];
        $payment->phone = $data['phone'];
        $payment->address = $data['address'];
        $payment->cash_delivery = 'midtrans';
        $payment->total_amount = $cartTotal;
        $payment->payment_type = 'Midtrans';
        $payment->invoice_no = $orderId;
        $payment->order_date = Carbon::now()->format('d F Y');
        $payment->order_month = Carbon::now()->format('F');
        $payment->order_year = Carbon::now()->format('Y');
        $payment->status = 'completed';
        $payment->save();

        //save order
        foreach ($carts as $cart) {

            $order = new Order();
            $order->payment_id = $payment->id;
            $order->user_id = $userId;
            $order->instructor_id = $cart->options['instructor_id'];
            $order->course_id = $cart->id;
            $order->course_title = $cart->name;
            $order->price = $cart->price;
            $order->save();
        }

        // Clear cart and coupon
        Cart::destroy();
        if (Session::has('coupon')) {
            Session::forget('coupon');
        }
        Session::forget('order_data');
        Session::forget('order_id');
        Session::forget('total_amount');

        $notification = [
            'message' => 'Your payment was successful. Your order has been placed.',
            'alert-type' => 'success'
        ];
        return redirect()->route('index')->with($notification);
    } //end method

}
