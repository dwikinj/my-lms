@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')
    <div class="container-fluid">

        <div class="dashboard-heading mb-5">
            <h3 class="fs-22 font-weight-semi-bold">My Courses</h3>
        </div>
        <div class="dashboard-cards mb-5">
            @foreach ($myCourses as $item)
                <div class="card card-item card-item-list-layout">
                    <div class="card-image">
                        <a href="{{ route('course.view', $item->course_id) }}" class="d-block">
                            <img class="card-img-top" src="{{ asset($item->course->course_image) }}" alt="Card image cap">
                        </a>
                    </div><!-- end card-image -->
                    <div class="card-body">
                        <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">{{ $item->course->label }}</h6>
                        <h5 class="card-title"><a
                                href="{{ route('course.view', $item->course_id) }}">{{ $item->course->course_name }}</a>
                        </h5>
                        <p class="card-text"><a
                                href="{{ route('instructor.details', $item->instructor->id) }}">{{ $item->instructor->name }}</a>
                        </p>
                        @php
                            $averageRating = $item->course->reviews_avg_rating ?? 0;
                            $totalReviews = $item->course->reviews_count ?? 0;
                            $fullStars = floor($averageRating);
                            $hasHalfStar = $averageRating - $fullStars >= 0.5 && $averageRating - $fullStars < 1;
                            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                        @endphp
                        <div class="rating-wrap d-flex align-items-center py-2">
                            <div class="review-stars">
                                @if ($totalReviews > 0)
                                    <span class="rating-number">{{ number_format($averageRating, 1) }}</span>
                                    @for ($i = 1; $i <= $fullStars; $i++)
                                        <span class="la la-star"></span>
                                    @endfor
                                    @if ($hasHalfStar)
                                        <span class="la la-star-half-alt"></span>
                                    @endif
                                    @for ($i = 1; $i <= $emptyStars; $i++)
                                        <span class="la la-star-o"></span>
                                    @endfor
                                @else
                                    <span class="rating-number">0.0</span>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="la la-star-o"></span>
                                    @endfor
                                @endif
                            </div>
                            <span class="rating-total pl-1">({{ number_format($totalReviews) }})</span>
                        </div><!-- end rating-wrap -->
                        <ul class="card-duration d-flex align-items-center fs-15 pb-2">
                            <li class="mr-2">
                                <span class="text-black">Duration:</span>
                                <span>{{ $item->course->duration }} hours</span>
                            </li>
                            <li class="mr-2">
                                <span class="text-black">Students:</span>
                                <span>{{ $item->students_count }}</span>
                            </li>
                        </ul>

                    </div><!-- end card-body -->
                </div>
            @endforeach

        </div><!-- end col-lg-12 -->

    </div><!-- end container-fluid -->
@endsection
