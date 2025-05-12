@extends('instructor.instructor_dashboard')
@section('instructor')

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Coupon</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Add New Coupon</h5>
            <form method="post" id="myForm" action="{{ route('instructor.store.coupon') }}" class="row g-3">
               @csrf
                <div class="form-group col-md-6">
                    <label for="coupon_name" class="form-label">Coupon Name</label>
                    <input type="text" class="form-control" name="coupon_name" id="coupon_name" 
                           placeholder="Coupon Name" value="{{ old('coupon_name') }}">
                    @error('coupon_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="course_id" class="form-label">Courses</label>
                    <select name="course_id" class="form-select mb-3 @error('course_id') is-invalid @enderror"
                         aria-label="course selection">
                        <option value="" selected disabled>Select a course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
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
                           value="{{ old('coupon_discount') }}">
                     @error('coupon_discount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="coupon_validty" class="form-label">Coupon Validity</label>
                    <input type="datetime-local" class="form-control" name="coupon_validty" id="coupon_validty"
                           value="{{ old('coupon_validty') }}">
                     @error('coupon_validty')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4">Submit</button>
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
                course_id: {
                    required: true
                },
                coupon_discount: {
                    required: true,
                    min: 0,
                    max: 100
                },
                coupon_validty: {
                    required: true
                }
            },
            messages: {
                coupon_name: {
                    required: 'Please Enter Coupon Name',
                    minlength: 'Coupon name must be at least 3 characters'
                },
                course_id: {
                    required: 'Please select a course'
                },
                coupon_discount: {
                    required: 'Please Enter Discount Percentage',
                    min: 'Discount must be at least 0%',
                    max: 'Discount cannot exceed 100%'
                },
                coupon_validty: {
                    required: 'Please Select Validity Date/Time'
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
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