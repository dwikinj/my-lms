@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Course Details</div>
            <div class="ms-auto">

            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset($course->course_image) }}" class="rounded-circle p-1 border" width="90"
                            height="90" alt="{{ $course->course_title }}">
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mt-0">{{ $course->course_name }}</h5>
                            <p class="mb-0">{{ $course->course_title }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table mb-0">

                                    <tbody>
                                        <tr>
                                            <td>Category</td>
                                            <td>:</td>
                                            <td><strong>{{ $course->category->category_name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>SubCategory</td>
                                            <td>:</td>
                                            <td><strong>{{ $course->subCategory->subcategory_name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Instructor</td>
                                            <td>:</td>
                                            <td><strong>{{ $course->instructor->name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Label</td>
                                            <td>:</td>
                                            <td><strong>{{ $course->label }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Duration</td>
                                            <td>:</td>
                                            <td><strong>{{ $course->duration }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Video</td>
                                            <td>:</td>
                                            <td>
                                                <video width="300" height="200" controls>
                                                    <source src="{{ asset($course->video) }}" type="video/mp4">
                                                </video>
                                            </td>
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table mb-0">

                                    <tbody>
                                        <tr>
                                            <td>Resources</td>
                                            <td>:</td>
                                            <td><strong>{{ $course->resources }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Certificate</td>
                                            <td>:</td>
                                            <td><strong>{{ $course->certificate }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Selling Price</td>
                                            <td>:</td>
                                            <td><strong>{{ $course->selling_price }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Discount Price</td>
                                            <td>:</td>
                                            <td><strong>{{ $course->discount_price }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>:</td>
                                            <td>
                                                @if ($course->status == 1)
                                                    <span class="badge bg-primary">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>



                                    </tbody>
                                </table>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
