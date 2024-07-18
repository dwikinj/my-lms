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
                        <li class="breadcrumb-item active" aria-current="page">Edit SubCategory</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Edit SubCategory</h5>
                <form method="post" enctype="multipart/form-data" action="{{ route('update.subcategory') }}"
                    class="row g-3">
                    @csrf
                    <input type="hidden" name="id" value="{{ $subcategory->id }}">

                    <div class="form-group col-md-6">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category Name</label>
                            <select class="form-select" id="category_id" name="category_id">
                                @foreach ($category as $item)
                                    <option @if ($item->id == $subcategory->category_id) selected @endif value="{{ $item->id }}">
                                        {{ $item->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="mb-3">
                            <label for="subcategory_name" class="form-label">SubCategory Name</label>
                            <input type="text" class="form-control" name="subcategory_name" id="subcategory_name"
                                value="{{ $subcategory->subcategory_name }}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
@endsection
