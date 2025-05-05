<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CourseSection;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Question;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function AdminPendingOrder()
    {
        $payment = Payment::where('status', 'pending')->orderBy('id', 'desc')->get();
        return view('admin.backend.order.pending_order', compact('payment'));;
    } //end method

    public function AdminConfirmOrder()
    {
        $payment = Payment::where('status', 'confirm')->orderBy('id', 'desc')->get();
        return view('admin.backend.order.confirm_order', compact('payment'));;
    } //end method

    public function AdminOrderDetail($id)
    {
        $payment = Payment::findOrFail($id);
        $orderItem = Order::where('payment_id', $payment->id)->orderBy('id', 'desc')->get();

        return view('admin.backend.order.order_details', compact('payment', 'orderItem'));
    } //end method

    public function AdminOrderConfirmAction($payment_id)
    {
        $payment = Payment::findOrFail($payment_id);
        $payment->status = 'confirm';
        $payment->save();

        $notification = [
            'message' => 'Order Confirmed Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.confirm.order')->with($notification);
    } //end method

    public function InstructorAllOrder()
    {
        $id = Auth::user()->id;
        // Get latest order IDs for each payment
        $latestOrderIds = Order::where('instructor_id', $id)
            ->selectRaw('MAX(id) as id')
            ->groupBy('payment_id')
            ->pluck('id');

        // Get full order details for latest orders
        $ordersItem = Order::whereIn('id', $latestOrderIds)
            ->orderBy('id', 'DESC')
            ->get();

        return view('instructor.orders.all_orders', compact('ordersItem'));
    } //end method
    public function InstructorOrderDetail($payment_id)
    {
        $payment = Payment::findOrFail($payment_id);
        $orderItem = Order::where('payment_id', $payment->id)->orderBy('id', 'desc')->get();

        return view('instructor.orders.order_details', compact('payment', 'orderItem'));
    } //end method
    public function InstructorOrderInvoice($payment_id)
    {
        $payment = Payment::findOrFail($payment_id);
        $orderItem = Order::where('payment_id', $payment->id)->orderBy('id', 'desc')->get();

        $pdf = Pdf::loadView('instructor.orders.order_pdf', compact('payment', 'orderItem'))->setPaper('a4')->setOption([
            'tempDir' => public_path(),
            'chroot' => public_path(),
        ]);

        return $pdf->download('invoice.pdf');
    } //end method
    public function InstructorOrderConfirmAction($payment_id)
    {
        $payment = Payment::findOrFail($payment_id);
        $payment->status = 'confirm';
        $payment->save();

        $notification = [
            'message' => 'Order Confirmed Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    } //end method

    //User Courses
    public function MyCourse()
    {
        $id = Auth::user()->id;
        $latestOrderIds = Order::where('user_id', $id)
            ->selectRaw('MAX(id) as id')
            ->groupBy('course_id')
            ->pluck('id');

        $myCourses = Order::whereIn('id', $latestOrderIds)
            ->orderBy('id', 'DESC')
            ->get();

        return view('frontend.mycourse.all_my_course', compact('myCourses'));
    } //end method
    public function CourseView($course_id)
    {
        $id = Auth::user()->id;

        $course = Order::where('user_id', $id)->where('course_id', $course_id)->first();
        $sections = CourseSection::where('course_id', $course_id)->orderBy('id', 'asc')->get();
        $questions = Question::where('course_id', $course_id)->where('user_id', $id)->whereNull('parent_id')->orderBy('id', 'desc')->get();

        return view('frontend.mycourse.course_view', compact('course', 'sections', 'questions'));
    } //end method
}
