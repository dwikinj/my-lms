@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>My Reviews</h3>
                    </div>
                    <div class="card-body">
                        <table class="table generic-table">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $review)
                                    <tr>
                                        <td><a
                                                href="{{ route('course.details', ['id' => $review->course->id, 'slug' => $review->course->course_name_slug]) }}">{{ $review->course->course_name }}</a>
                                        </td>
                                        <td class="review-stars">{{ $review->rating }} <span class="la la-star"></span></td>
                                        <td>{{ $review->comment }}</td>
                                        <td>{{ $review->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
