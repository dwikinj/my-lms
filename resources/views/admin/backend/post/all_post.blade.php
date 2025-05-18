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
                        <li class="breadcrumb-item active" aria-current="page">All Blog Post</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{route('blog.post.add')}}" class="btn btn-primary px-5">Add Blog Post</a>
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
                                <th>Post Title</th>
                                <th>Blog Category</th>
                                <th>Blog Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($post as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->post_title }}</td>
                                    <td>{{ $item->category->category_name }}</td>
                                    <td><img src="{{ asset($item->post_image) }}" alt="blog post" style="width:70px;height:70px">
                                    </td>
                                    <td>
                                        <a href="{{ route('blog.post.edit', $item->id) }}" class="btn btn-info px-5"
                                            >Edit</a>

                                        <button type="button" class="btn btn-danger delete-post-btn"
                                            data-delete-url="{{ route('blog.post.delete', $item->id) }}">Delete
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


    <script type="text/javascript">
        // Handle DELETE Blog Post
        $(document).on('click', '.delete-post-btn', function(e) {
            e.preventDefault();
            var deleteButton = $(this);
            var deleteUrl = deleteButton.data('delete-url'); 

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
                        }
                    });
                }
            });
        });
    </script>
@endsection
