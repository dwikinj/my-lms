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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard')}}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Active Reviews</li>
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
                                <th>Course</th>
                                <th>User</th>
                                <th>Comment</th>
                                <th>Rating</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reviews as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->course->course_name }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->comment }}</td>
                                    <td>
                                        <div class="cursor-pointer rating-stars">
                                          @for ($i = 1; $i <= 5; $i++)
                                              @if ($item->rating >= $i)
                                                  <i class="bx bxs-star text-warning"></i>
                                              @else
                                                  <i class="bx bxs-star text-secondary"></i>
                                              @endif
                                          @endfor
                                        </div>
                                      </td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('D, d F Y H:i') }}</td>
                                    
                                    <td>
                                        <div class="form-check form-switch form-check-danger ">
                                            <input class="form-check-input large-checkbox status-toggle" type="checkbox"
                                                id="flexSwitchCheckDanger" data-review-id="{{ $item->id }}"
                                                {{ $item->status ? 'checked' : '' }}>
                                            <label class="form-check-label" for="flexSwitchCheckDanger"> </label>
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
                let reviewId = $(this).data('review-id');
                let isChecked = $(this).is(':checked');
                let currentCheckbox = $(this); 

                    $.ajax({
                    url: "{{ route('update.review.status') }}", 
                    method: "POST",
                    data: {
                        review_id: reviewId,
                        status: isChecked ? 1 : 0,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.alertType === 'success') {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message || 'An unknown error occurred.');
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Failed to update status.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                        currentCheckbox.prop('checked', !isChecked);
                    }
                });
            });
        });
    </script>
@endsection
