@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <a href="{{ route('export.permission') }}" class="btn btn-warning px-5">Download Permission(xlsx)</a>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Import Permission</h5>
                <form method="post" id="myForm" action="{{ route('import.xlsxpermission') }}" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group col-md-6">
                        <div class="mb-3">
                            <label for="import_file" class="form-label">Xlsx Sheet File Import</label>
                            <input type="file" class="form-control" name="import_file" id="import_file">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
@endsection
