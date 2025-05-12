@extends('instructor.instructor_dashboard')
@section('instructor')

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('instructor.all.coupon') }}">All Coupons</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Coupon</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Edit Coupon</h5>
            <form method="post" id="myForm" action="{{ route('instructor.update.coupon', $coupon->id) }}" class="row g-3">
               @csrf
               @method('PUT') 
               <input type="hidden" name="id" value="{{ $coupon->id }}">
                <div class="form-group col-md-6">
                    <label for="coupon_name" class="form-label">Coupon Name</label>
                    <input type="text" class="form-control" name="coupon_name" id="coupon_name"
                           placeholder="Coupon Name" value="{{ old('coupon_name', $coupon->coupon_name) }}">
                    @error('coupon_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="course_id" class="form-label">Courses</label>
                    <select name="course_id" class="form-select mb-3 @error('course_id') is-invalid @enderror"
                         aria-label="course selection">
                        <option value="" {{ is_null(old('course_id', $coupon->course_id)) ? 'selected' : '' }} >For All Courses (Optional)</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $coupon->course_id) == $course->id ? 'selected' : '' }}>
                                {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="coupon_discount" class="form-label">Coupon Discount (%)</label>
                    <input type="number" class="form-control" name="coupon_discount" id="coupon_discount"
                           min="0" max="100" step="1" placeholder="0-100"
                           value="{{ old('coupon_discount', $coupon->coupon_discount) }}">
                     @error('coupon_discount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="coupon_validty" class="form-label">Coupon Validity</label>
                    <input type="datetime-local" class="form-control" name="coupon_validty" id="coupon_validty"
                           value="{{ old('coupon_validty', $coupon->coupon_validty ? \Carbon\Carbon::parse($coupon->coupon_validty)->format('Y-m-d\TH:i') : '') }}">
                     @error('coupon_validty')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="coupon_status" class="form-label">Status</label>
                    <select name="coupon_status" class="form-select mb-3 @error('coupon_status') is-invalid @enderror">
                        <option value="1" {{ old('coupon_status', $coupon->coupon_status) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('coupon_status', $coupon->coupon_status) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('coupon_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4">Update Coupon</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                coupon_name: {
                    required: true,
                    minlength: 3
                },
                coupon_discount: {
                    required: true,
                    min: 0,
                    max: 100
                },
                coupon_validty: {
                    required: true
                },
                coupon_status: {
                    required: true
                }
            },
            messages: {
                coupon_name: {
                    required: 'Please Enter Coupon Name',
                    minlength: 'Coupon name must be at least 3 characters'
                },
                coupon_discount: {
                    required: 'Please Enter Discount Percentage',
                    min: 'Discount must be at least 0%',
                    max: 'Discount cannot exceed 100%'
                },
                coupon_validty: {
                    required: 'Please Select Validity Date/Time'
                },
                coupon_status: {
                    required: 'Please select a status'
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                // Handle select elements differently for error placement
                if (element.is('select')) {
                    error.insertAfter(element);
                } else {
                    element.closest('.form-group').append(error);
                }
            },
            highlight: function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>

@endsection