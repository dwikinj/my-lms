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
                    <h2 class="section__title text-white">Sign Up</h2>
                </div>
                <ul
                    class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                    <li><a href="index.html">Home</a></li>
                    <li>Pages</li>
                    <li>Sign Up</li>
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
    <section class="contact-area section--padding position-relative">
        <span class="ring-shape ring-shape-1"></span>
        <span class="ring-shape ring-shape-2"></span>
        <span class="ring-shape ring-shape-3"></span>
        <span class="ring-shape ring-shape-4"></span>
        <span class="ring-shape ring-shape-5"></span>
        <span class="ring-shape ring-shape-6"></span>
        <span class="ring-shape ring-shape-7"></span>
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto">
                    <div class="card card-item">
                        <div class="card-body">
                            <h3 class="card-title text-center fs-24 lh-35 pb-4">Create an Account and <br> Start Learning!
                            </h3>
                            <div class="section-block"></div>
                            <form method="post" action="{{ route('register') }}">
                                @csrf

                                <div class="input-box">
                                    <label class="label-text">Name</label>
                                    <div class="form-group">
                                        <input
                                            class="form-control form--control @error('name')
                                            is-invalid
                                        @enderror"
                                            type="text" id="name" name="name" placeholder="Name"
                                            value="{{ old('name') }}">
                                        <span class="la la-user input-icon"></span>
                                    </div>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="input-box">
                                    <label class="label-text">Email</label>
                                    <div class="form-group">
                                        <input class="@error('email') is-invalid @enderror form-control form--control"
                                            type="email" id="email" name="email" placeholder="Enter email address"
                                            value="{{ old('email') }}">
                                        <span class="la la-envelope input-icon"></span>
                                    </div>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="input-box ">
                                    <label class="label-text">Password</label>
                                    <div class="input-group @error('password') mb-0 @else mb-3 @enderror">
                                        <span class="la la-lock input-icon"></span>
                                        <input class="form-control form--control @error('password') is-invalid @enderror"
                                            id="password" type="password" name="password" placeholder="Password">
                                    </div>
                                    @error('password')
                                        <small class="text-danger mb-3">{{ $message }}</small>
                                    @enderror

                                </div>

                                <div class="input-box">
                                    <label class="label-text">Confirm Password</label>
                                    <div class="input-group ">
                                        <span class="la la-lock input-icon"></span>
                                        <input  class="@error('password_cofirmation') is-invalid  @enderror
                                            form-control form--control password-field"
                                            id="password_confirmation" type="password" name="password_confirmation"
                                            placeholder="Confirm Password">
                                    </div>
                                  
                                </div>

                                <!-- Checkbox dan button submit -->
                                <div class="btn-box">
                                    <div class="custom-control custom-checkbox mb-2 fs-15">
                                        <input type="checkbox" class="custom-control-input" id="receiveCheckbox"
                                            name="receive_emails" {{ old('receive_emails') ? 'checked' : '' }}>
                                        <label class="custom-control-label custom--control-label lh-20"
                                            for="receiveCheckbox">
                                            Yes! I want to get the most out of Aduca by receiving emails with exclusive
                                            deals,
                                            personal recommendations and learning tips!
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox mb-4 fs-15">
                                        <input type="checkbox" class="custom-control-input" id="agreeCheckbox"
                                            name="agree_terms" {{ old('agree_terms') ? 'checked' : '' }} required>
                                        <label class="custom-control-label custom--control-label" for="agreeCheckbox">
                                            By signing I agree to the
                                            <a href="terms-and-conditions.html" class="text-color hover-underline">terms and
                                                conditions</a> and
                                            <a href="privacy-policy.html" class="text-color hover-underline">privacy
                                                policy</a>
                                        </label>
                                    </div>

                                    <button class="btn theme-btn" type="submit">Register Account <i
                                            class="la la-arrow-right icon ml-1"></i></button>
                                    <p class="fs-14 pt-2">Already have an account? <a href="{{ route('login') }}"
                                            class="text-color hover-underline">Log in</a></p>
                                </div>
                            </form>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div><!-- end col-lg-7 -->
            </div><!-- end row -->
        </div><!-- end container -->
    </section><!-- end contact-area -->
    <!-- ================================
                           END CONTACT AREA
                    ================================= -->
@endsection
