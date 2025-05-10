@extends('frontend.master')
@section('home')
    <!-- ================================
                    START BREADCRUMB AREA
                ================================= -->
    <section class="breadcrumb-area section-padding img-bg-2">
        <div class="overlay"></div>
        <div class="container">
            <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
                <div class="section-heading">
                    <h2 class="section__title text-white">Midtrans Payment</h2>
                </div>
                <ul
                    class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                    <li><a href="{{ route('index') }}">Home</a></li>
                    <li>Payment</li>
                    <li>Midtrans Checkout</li>
                </ul>
            </div><!-- end breadcrumb-content -->
        </div><!-- end container -->
    </section><!-- end breadcrumb-area -->
    <!-- ================================
                    END BREADCRUMB AREA
                ================================= -->

    <!-- ================================
                       START PAYMENT AREA
                ================================= -->
    <section class="payment-area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-22 pb-3">Complete Your Payment</h3>
                            <div class="divider"><span></span></div>
                            
                            <div class="payment-details">
                                <div class="payment-summary mb-4">
                                    <h4 class="fs-18 font-weight-semi-bold pb-2">Order Summary</h4>
                                    <ul class="generic-list-item generic-list-item-flash fs-15">
                                        <li class="d-flex align-items-center justify-content-between">
                                            <span>Subtotal:</span>
                                            <span>${{ $subTotal }}</span>
                                        </li>
                                        @if (Session::has('coupon'))
                                            <li class="d-flex align-items-center justify-content-between">
                                                <span>Coupon discounts:</span>
                                                <span>-${{ session()->get('coupon')['discount_amount'] }}</span>
                                            </li>
                                            <li class="d-flex align-items-center justify-content-between">
                                                <span>Coupon Name:</span>
                                                <span>{{ session()->get('coupon')['coupon_name'] }}
                                                    ({{ session()->get('coupon')['coupon_discount'] }}%)</span>
                                            </li>
                                            <li class="d-flex align-items-center justify-content-between font-weight-bold">
                                                <span>Total:</span>
                                                <span>${{ session()->get('coupon')['total_amount'] }}</span>
                                            </li>
                                        @else
                                            <li class="d-flex align-items-center justify-content-between font-weight-bold">
                                                <span>Total:</span>
                                                <span>${{ $totalAmount }}</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                
                                <div class="payment-method-area mb-4">
                                    <h4 class="fs-18 font-weight-semi-bold pb-2">Payment via Midtrans</h4>
                                    <p class="pb-3">Please click the button below to proceed with your payment through Midtrans secure payment gateway.</p>
                                    
                                    <div class="text-center">
                                        <button id="pay-button" class="btn theme-btn">Pay Now <i class="la la-credit-card ml-1"></i></button>
                                    </div>
                                </div>

                                <div class="secure-payment-info">
                                    <p class="pb-1 text-black-50"><i class="la la-lock fs-24 mr-1"></i>Secure Payment</p>
                                    <p class="fs-14">Your payment information is securely processed by Midtrans.</p>
                                </div>
                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div><!-- end col-lg-8 -->
            </div><!-- end row -->
        </div><!-- end container -->
    </section>
    <!-- ================================
                       END PAYMENT AREA
                ================================= -->

    <!-- Midtrans JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <script>
        document.getElementById('pay-button').onclick = function() {
            // Trigger snap popup
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = '{{ route("midtrans.success") }}?order_id=' + result.order_id;
                },
                onPending: function(result) {
                    toastr.info('Payment pending. Please complete your payment!');
                },
                onError: function(result) {
                    toastr.error('Payment failed!');
                },
                onClose: function() {
                    toastr.warning('You closed the popup without finishing the payment');
                }
            });
        };
    </script>
@endsection