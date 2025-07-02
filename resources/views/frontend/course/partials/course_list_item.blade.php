<div class="row">
    @forelse ($courses as $course)
        <div class="col-lg-4 responsive-column-half">
            <div class="card card-item card-preview" data-tooltip-content="#tooltip_content_{{ $course->id }}">
                <div class="card-image">
                    <a href="{{ route('course.details', ['id' => $course->id, 'slug' => $course->course_name_slug]) }}" class="d-block">
                        <img class="card-img-top lazy" src="{{ asset('frontend/images/img-loading.png') }}" data-src="{{ asset($course->course_image) }}" alt="Card image cap">
                    </a>
                    <div class="course-badge-labels">
                        @if ($course->bestseller == 1)
                            <div class="course-badge">Bestseller</div>
                        @endif
                        @if ($course->highestrated == 1) {{-- Pastikan nama field ini benar di database Anda --}}
                            <div class="course-badge sky-blue">Highest Rated</div>
                        @endif
                        @if ($course->featured == 1)
                            <div class="course-badge red">Featured</div>
                        @endif
                        @if ($course->discount_percentage > 0)
                            <div class="course-badge blue">-{{ $course->discount_percentage }}%</div>
                        @endif
                    </div>
                </div><!-- end card-image -->
                <div class="card-body">
                    <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">{{ $course->label }}</h6>
                    <h5 class="card-title">
                        <a href="{{ route('course.details', ['id' => $course->id, 'slug' => $course->course_name_slug]) }}">{{ Str::limit($course->course_name, 45) }}</a>
                    </h5>
                    <p class="card-text"><a href="{{ $course->instructor ? route('instructor.details', ['id' => $course->instructor->id]) : '#' }}">{{ $course->instructor->name ?? 'N/A' }}</a></p>
                    @php
                        $averageRating = $course->reviews_avg_rating ?? 0;
                        $totalReviews = $course->reviews_count ?? 0;
                        $fullStars = floor($averageRating);
                        $hasHalfStar = ($averageRating - $fullStars) >= 0.5 && ($averageRating - $fullStars) < 1;
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
                    <div class="d-flex justify-content-between align-items-center">
                        @if ($course->discount_percentage > 0)
                            <p class="card-price text-black font-weight-bold">
                                ${{ $course->discount_price }}
                                <span class="before-price font-weight-medium">${{ $course->selling_price }}</span>
                            </p>
                        @else
                            <p class="card-price text-black font-weight-bold">
                                ${{ $course->selling_price }}
                            </p>
                        @endif
                        <div class="icon-element icon-element-sm shadow-sm cursor-pointer" title="Add to Wishlist" onclick="addToWishList({{ $course->id }})">
                            <i class="la la-heart-o"></i>
                        </div>
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col-lg-4 -->
    @empty
        <div class="col-lg-12">
            <p class="text-center fs-18">No courses found matching your criteria.</p>
        </div>
    @endforelse
</div><!-- end row -->

<div class="text-center pt-3">
    @if ($courses->hasPages())
        <nav aria-label="Page navigation example" class="pagination-box">
            {{-- Gunakan view paginasi kustom Anda --}}
            {{ $courses->links('vendor.pagination.custom-pagination') }}
        </nav>
    @endif
    @if ($courses->total() > 0)
        <p class="fs-14 pt-2">Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} results</p>
    @else
        <p class="fs-14 pt-2">No results found</p>
    @endif
</div>
