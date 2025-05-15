@extends('instructor.instructor_dashboard')
@section('instructor')
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
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
