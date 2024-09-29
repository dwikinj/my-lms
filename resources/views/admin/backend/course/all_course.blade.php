@extends('admin.admin_dashboard')
@section('admin')
    <style>
        .large-checkbox {
            transform: scale(1.5);
        }
    </style>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Instructor</li>
                    </ol>
                </nav>
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
                                <th>Image</th>
                                <th>Course Name</th>
                                <th>Instructor</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Action</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <img style="max-width: 80px" class="img-thumbnail"
                                            src="{{ asset($item->course_image) }}"
                                            data-src="{{ asset($item->course_image) }}" alt="Card image cap">
                                    </td>
                                    <td>{{ $item->course_name }}</td>
                                    <td>{{ $item->instructor->name }}</td>
                                    <td>{{ $item->category->category_name }}</td>
                                    <td>{{ $item->selling_price }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm text-white"
                                            href="{{ route('admin.course.details', $item->id) }}">
                                            <i class="fadeIn animated bx bx-show"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch form-check-danger">
                                            <input class="form-check-input large-checkbox status-toggle" type="checkbox"
                                                id="switch-status-course-{{ $item->id }}"
                                                data-course-id="{{ $item->id }}" {{ $item->status ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="switch-status-course-{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.status-toggle').on('change', function() {
                let courseId = $(this).data('course-id');
                let isChecked = $(this).is(':checked');
                
                $.ajax({
                    url: "{{ route('update.course.status') }}",
                    method: "PATCH",
                    data: {
                        course_id: courseId,
                        is_checked: isChecked ? 1 : 0,
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        toastr.success(response.message);

                        // Update the status text and class
                        let statusSpan = $(this).closest('tr').find('.status-badge');
                        if (isChecked) {
                            statusSpan.removeClass('btn-danger').addClass('btn-primary').text(
                                'Active');
                        } else {
                            statusSpan.removeClass('btn-primary').addClass('btn-danger').text(
                                'Inactive');
                        }
                    }.bind(this),
                    error: function() {
                        toastr.error('Failed to update status');
                    }
                });
            });
        });
    </script>
@endsection
