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
                    <h2 class="section__title text-white">Checkout</h2>
                </div>
                <ul
                    class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                    <li><a href="{{ route('index') }}">Home</a></li>
                    <li>Pages</li>
                    <li>Checkout</li>
                </ul>
            </div><!-- end breadcrumb-content -->
        </div><!-- end container -->
    </section><!-- end breadcrumb-area -->
    <!-- ================================
            END BREADCRUMB AREA
        ================================= -->

    <!-- ================================
               START CONTACT AREA
        ================================= -->
    <section class="cart-area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-22 pb-3">Billing Details</h3>
                            <div class="divider"><span></span></div>
                            <form method="post" class="row" action="{{ route('payment') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="input-box col-lg-6">
                                    <label class="label-text">Name</label>
                                    <div class="form-group">
                                        <input class="form-control form--control" type="text" name="name"
                                            value="{{ Auth::user()->name }}">
                                        <span class="la la-user input-icon"></span>
                                    </div>
                                </div><!-- end input-box -->
                                <div class="input-box col-lg-12">
                                    <label class="label-text">Email Address</label>
                                    <div class="form-group">
                                        <input class="form-control form--control" type="email" name="email"
                                            value="{{ Auth::user()->email }}">
                                        <span class="la la-envelope input-icon"></span>
                                    </div>
                                </div><!-- end input-box -->
                                <div class="input-box col-lg-12">
                                    <label class="label-text">Phone Number</label>
                                    <div class="form-group">
                                        <input id="phone" class="form-control form--control" type="tel"
                                            name="phone" value="{{ Auth::user()->phone }}">
                                        <span class="la la-phone input-icon"></span>
                                    </div>
                                </div><!-- end input-box -->
                                <div class="input-box col-lg-12">
                                    <label class="label-text">Address</label>
                                    <div class="form-group">
                                        <input class="form-control form--control" type="text" name="address"
                                            value="{{ Auth::user()->address }}">
                                        <span class="la la-map-marker input-icon"></span>
                                    </div>
                                </div><!-- end input-box -->

                                <div class="btn-box col-lg-12">
                                    <div class="custom-control custom-checkbox mb-4 fs-15">
                                        <input type="checkbox" class="custom-control-input" id="agreeCheckbox" required>
                                        <label class="custom-control-label custom--control-label" for="agreeCheckbox">I
                                            agree to the
                                            <a href="#" class="text-color hover-underline">terms and
                                                conditions</a> and
                                            <a href="#" class="text-color hover-underline">privacy
                                                policy</a>
                                        </label>
                                    </div><!-- end custom-control -->
                                    <p class="pb-1 text-black-50"><i class="la la-lock fs-24 mr-1"></i>Secure Connection</p>
                                    <p class="fs-14">Your information is safe with us!</p>
                                </div><!-- end btn-box -->
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-22 pb-3">Select Payment Method</h3>
                            <div class="divider"><span></span></div>
                            <div class="payment-option-wrap">
                                <div class="payment-tab is-active">
                                    <div class="payment-tab-toggle">
                                        <input id="directPayment" name="cash_delivery" type="radio" value="cod"
                                            checked>
                                        <label for="directPayment">Direct Payment</label>
                                    </div>
                                    <div class="payment-tab-toggle">
                                        <input id="stripe" name="stripe" type="radio" value="stripe">
                                        <label for="stripe">Stripe Payment</label>
                                    </div>
                                </div><!-- end payment-tab -->


                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div><!-- end col-lg-7 -->
                <div class="col-lg-5">
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-22 pb-3">Order Details</h3>
                            <div class="divider"><span></span></div>
                            <div class="order-details-lists">
                                @foreach ($carts as $cart)
                                    <input type="hidden" name="slug[]" value="{{ $cart->options->slug }}">
                                    <input type="hidden" name="course_id[]" value="{{ $cart->id }}">
                                    <input type="hidden" name="course_title[]" value="{{ $cart->name }}">
                                    <input type="hidden" name="price[]" value="{{ $cart->price }}">
                                    <input type="hidden" name="instructor_id[]"
                                        value="{{ $cart->options->instructor_id }}">


                                    <div class="media media-card border-bottom border-bottom-gray pb-3 mb-3">
                                        <a href="{{ route('course.details', ['id' => $cart->id, 'slug' => $cart->options->slug]) }}"
                                            class="media-img">
                                            <img src="{{ asset($cart->options->image) }}" alt="{{ $cart->name }}">
                                        </a>
                                        <div class="media-body">
                                            <h5 class="fs-15 pb-2"><a
                                                    href="{{ route('course.details', ['id' => $cart->id, 'slug' => $cart->options->slug]) }}">{{ $cart->name }}</a>
                                            </h5>
                                            <p class="text-black font-weight-semi-bold lh-18">${{ $cart->price }}
                                                @if ($cart->options->discount)
                                                    <span
                                                        class="before-price fs-14">${{ $cart->options->oldPrice }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div><!-- end media -->
                                @endforeach
                            </div><!-- end order-details-lists -->
                            <a href="{{ route('mycart') }}" class="btn-text"><i class="la la-edit mr-1"></i>Edit</a>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title fs-22 pb-3">Order Summary</h3>
                            <div class="divider"><span></span></div>
                            <ul class="generic-list-item generic-list-item-flash fs-15">
                                <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                                    <span class="text-black">Subtotal:</span>
                                    <span>${{ $cartTotal }}</span>
                                </li>
                                @if (Session::has('coupon'))
                                    <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                                        <span class="text-black">Coupon discounts:</span>
                                        <span>-${{ $couponData['discount_amount'] }}</span>
                                    </li>
                                    <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                                        <span class="text-black">Coupon Name:</span>
                                        <span>{{ $couponData['coupon_name'] }}
                                            ({{ $couponData['coupon_discount'] }}%)</span>
                                    </li>
                                    <li class="d-flex align-items-center justify-content-between font-weight-bold">
                                        <span class="text-black">Total:</span>
                                        <span>${{ $couponData['total_amount'] }}</span>
                                    </li>
                                    <input type="hidden" name="total" value="{{ $cartTotal }}">
                                @else
                                    <li class="d-flex align-items-center justify-content-between font-weight-bold">
                                        <span class="text-black">Total:</span>
                                        <span>${{ $cartTotal }}</span>
                                    </li>
                                    <input type="hidden" name="total" value="{{ $cartTotal }}">
                                @endif
                            </ul>
                            <div class="btn-box border-top border-top-gray pt-3">
                                <p class="fs-14 lh-22 mb-2">Aduca is required by law to collect applicable transaction
                                    taxes for purchases made in certain tax jurisdictions.</p>
                                <p class="fs-14 lh-22 mb-3">By completing your purchase you agree to these <a
                                        href="#" class="text-color hover-underline">Terms of Service.</a></p>
                                <button type="submit" class="btn theme-btn w-100">Proceed <i
                                        class="la la-arrow-right icon ml-1"></i></button>
                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div><!-- end col-lg-5 -->
            </div><!-- end row -->
        </div><!-- end container -->
        </form>

    </section>
    <!-- ================================
               END CONTACT AREA
        ================================= -->
@endsection
