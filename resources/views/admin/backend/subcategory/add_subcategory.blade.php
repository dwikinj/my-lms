@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>


    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add SubCategory</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Add New SubCategory</h5>
                <form method="post" id="myForm"  action="{{ route('store.subcategory') }}"
                    class="row g-3">
                    @csrf
                    <div class="form-group col-md-6">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category Name</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Choose Category</option>
                                @foreach ($category as $item)
                                    <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="mb-3">
                            <label for="subcategory_name" class="form-label">SubCategory Name</label>
                            <input type="text" class="form-control" name="subcategory_name" id="subcategory_name"
                                placeholder="SubCategory Name">
                        </div>
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
        $(document).ready(function() {
            $('#myForm').validate({
                rules: {
                    subcategory_name: {
                        required: true,
                    },
                    category_id: {
                        required: true,
                    },

                },
                messages: {
                    subcategory_name: {
                        required: 'Please Enter SubCategory Name',
                    },
                    category_id: {
                        required: 'Please Select Category Name',
                    },


                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });
    </script>
@endsection
