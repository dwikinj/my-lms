@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Blog Category</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addCategoryModal">Add Blog Category</button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Category Name</th>
                                <th>Category Slug</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->category_name }}</td>
                                    <td>{{ $item->category_slug }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info px-5 edit-category-btn"
                                            data-id="{{ $item->id }}" data-bs-toggle="modal"
                                            data-bs-target="#editCategoryModal">Edit</button>

                                        <button type="button" class="btn btn-danger delete-category-btn"
                                            data-id="{{ $item->id }}"
                                            data-delete-url="{{ route('blog.delete.category', $item->id) }}">Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal  Add Blog Category -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Blog Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addCategoryForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required>
                            <div class="invalid-feedback" id="category_name_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveCategoryBtn">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <!-- Modal Edit Blog Category -->
     <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Blog Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryForm">
                    @csrf
                    @method('PUT') 
                    <input type="hidden" id="edit_category_id" name="category_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_category_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="edit_category_name" name="category_name" required>
                            <div class="invalid-feedback" id="edit_category_name_error_frontend"></div>
                            <div class="invalid-feedback" id="edit_category_name_error_backend"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_category_slug" class="form-label">Category Slug</label>
                            <input type="text" class="form-control" id="edit_category_slug" name="category_slug" required>
                            <div class="invalid-feedback" id="edit_category_slug_error_frontend"></div>
                            <div class="invalid-feedback" id="edit_category_slug_error_backend"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="updateCategoryBtn">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Blog Category (Disempurnakan) -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Blog Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryForm">
                    @csrf
                    @method('PUT') {{-- Method spoofing untuk PUT request --}}
                    <input type="hidden" id="edit_category_id" name="category_id"> {{-- Untuk menyimpan ID --}}
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_category_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="edit_category_name" name="category_name" required>
                            {{-- Hanya satu div invalid-feedback per input, akan diisi oleh JS --}}
                            <div class="invalid-feedback" id="edit_category_name_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_category_slug" class="form-label">Category Slug</label>
                            <input type="text" class="form-control" id="edit_category_slug" name="category_slug" required>
                            {{-- Hanya satu div invalid-feedback per input, akan diisi oleh JS --}}
                            <div class="invalid-feedback" id="edit_category_slug_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="updateCategoryBtn">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script jQuery untuk submit form via AJAX dengan Toastr --}}
    <script type="text/javascript">
        $(document).ready(function() {
            // Submit form via AJAX
            $('#addCategoryForm').on('submit', function(e) {
                e.preventDefault(); // Mencegah submit form standar

                // Reset pesan error field sebelumnya
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                var formData = $(this).serialize(); // Mengambil data form

                $.ajax({
                    url: "{{ route('blog.add.category') }}",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        $('#addCategoryModal').modal('hide');
                        $('#addCategoryForm')[0].reset();

                        // Tampilkan pesan sukses menggunakan Toastr
                        if (response.message) {
                            toastr.success(response.message);
                        }

                        // Reload halaman untuk menampilkan data baru
                        setTimeout(function() {
                            location.reload();
                        }, 800);
                    },
                    error: function(xhr) {
                        // Reset error field sebelum menampilkan yang baru
                        $('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').text('');

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) { // Validation errors
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    // Tampilkan setiap error validasi menggunakan Toastr
                                    if (value && value.length > 0) {
                                        toastr.error(value[0]);
                                    }
                                    // Tampilkan error di bawah field input jika ada
                                    if (key === 'category_name') {
                                        $('#' + key + '_error').text(value[0]).show();
                                        $('#' + key).addClass('is-invalid');
                                    }
                                });
                            } else if (xhr.responseJSON
                                .message
                            ) { // General error message from server (misal dari catch block)
                                toastr.error(xhr.responseJSON.message);
                            } else {
                                toastr.error('An unexpected error occurred. Please try again.');
                            }
                        } else {
                            toastr.error(
                                'An server error occurred. Please check console or try again.'
                            );
                        }
                    }
                });
            });

            // Reset form dan error field saat modal ditutup
            $('#addCategoryModal').on('hidden.bs.modal', function() {
                $('#addCategoryForm')[0].reset();
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            });
        });

        // Handle DELETE CATEGORY
        // Handle DELETE CATEGORY (menggunakan tombol dengan data-delete-url)
        $(document).on('click', '.delete-category-btn', function(e) {
            e.preventDefault();
            var deleteButton = $(this);
            var categoryId = deleteButton.data('id'); // Ambil ID dari data-id
            var deleteUrl = deleteButton.data('delete-url'); // Ambil URL dari data-delete-url

            if (!deleteUrl) {
                toastr.error('Could not initiate delete. URL missing.');
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.message) {
                                toastr.success(response.message);
                            }

                            // Reload halaman untuk menampilkan data baru
                            setTimeout(function() {
                                location.reload();
                            }, 800);
                        },
                        error: function(xhr) {
                            var errorMessage = 'An error occurred. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            toastr.error(errorMessage);
                            console.error("Delete error:", xhr);
                        }
                    });
                }
            });
        });

          // --- EDIT CATEGORY (Refactored: No Frontend Validation, Slug handling simplified) ---

            // 1. Saat tombol edit diklik: Fetch data dan isi modal
            $(document).on('click', '.edit-category-btn', function() {
                var categoryId = $(this).data('id');

                // Reset form dan error messages modal edit
                $('#editCategoryForm')[0].reset();
                $('#edit_category_name, #edit_category_slug').removeClass('is-invalid');
                $('#edit_category_name_error, #edit_category_slug_error').text('').hide();

                $.ajax({
                    url: "{{ url('/admin/blog/category') }}/" + categoryId + "/edit-data",
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.category) {
                            $('#edit_category_id').val(response.category.id);
                            $('#edit_category_name').val(response.category.category_name);
                            $('#edit_category_slug').val(response.category.category_slug);
                        } else {
                            toastr.error(response.message || 'Category data not found.');
                            $('#editCategoryModal').modal('hide');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to fetch category data. Please try again.');
                        console.error("Fetch edit data error:", xhr);
                        $('#editCategoryModal').modal('hide');
                    }
                });
            });

            // 2. Submit form EDIT CATEGORY via AJAX (Tanpa Validasi Frontend)
            $('#editCategoryForm').on('submit', function(e) {
                e.preventDefault();

                // Reset error messages modal edit
                $('#edit_category_name, #edit_category_slug').removeClass('is-invalid');
                $('#edit_category_name_error, #edit_category_slug_error').text('').hide();

                var categoryId = $('#edit_category_id').val();
                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ url('/admin/blog/category/update') }}/" + categoryId,
                    type: 'POST', // Karena @method('PUT') di form
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        $('#editCategoryModal').modal('hide');
                        if (response.message) {
                            toastr.success(response.message);
                        }
                        setTimeout(function() {
                            location.reload();
                        }, 800);
                    },
                    error: function(xhr) {
                        // Reset error field sebelum menampilkan yang baru
                        $('#edit_category_name, #edit_category_slug').removeClass('is-invalid');
                        $('#edit_category_name_error, #edit_category_slug_error').text('').hide();

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) { // Validation errors from backend
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    if (value && value.length > 0) {
                                        toastr.error(value[0]); // Tampilkan error umum via Toastr
                                    }
                                    // Tampilkan error spesifik di bawah field input
                                    if (key === 'category_name') {
                                        $('#edit_category_name_error').text(value[0]).show();
                                        $('#edit_category_name').addClass('is-invalid');
                                    }
                                    if (key === 'category_slug') {
                                        $('#edit_category_slug_error').text(value[0]).show();
                                        $('#edit_category_slug').addClass('is-invalid');
                                    }
                                });
                            } else if (xhr.responseJSON.message) {
                                toastr.error(xhr.responseJSON.message);
                            } else {
                                toastr.error('An unexpected error occurred during update.');
                            }
                        } else {
                            toastr.error('A server error occurred during update.');
                        }
                        console.error("Update error:", xhr);
                    }
                });
            });

            // Reset form edit saat modal ditutup
            $('#editCategoryModal').on('hidden.bs.modal', function () {
                $('#editCategoryForm')[0].reset();
                $('#edit_category_name, #edit_category_slug').removeClass('is-invalid');
                $('#edit_category_name_error, #edit_category_slug_error').text('').hide();
            });
    </script>
@endsection
