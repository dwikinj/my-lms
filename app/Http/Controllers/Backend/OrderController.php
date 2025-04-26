<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

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
}
