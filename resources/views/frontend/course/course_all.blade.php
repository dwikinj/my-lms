@extends('frontend.master')
@section('home')

<!-- ================================
    START BREADCRUMB AREA
================================= -->
<section class="breadcrumb-area section-padding img-bg-2">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
            <div class="section-heading">
                <h2 class="section__title text-white">All Courses</h2>
            </div>
            <ul class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                <li><a href="/">Home</a></li>
                <li>All Courses</li>
            </ul>
        </div><!-- end breadcrumb-content -->
    </div><!-- end container -->
</section><!-- end breadcrumb-area -->
<!-- ================================
    END BREADCRUMB AREA
================================= -->

<!--======================================
        START COURSE AREA
======================================-->
<section class="course-area section-padding">
    <div class="container">
        <div class="filter-bar mb-4">
            <div class="filter-bar-inner d-flex flex-wrap align-items-center justify-content-between">
                <p class="fs-14">We found <span class="text-black">{{ $courses->total() }}</span> courses available for you</p>
                <div class="d-flex flex-wrap align-items-center">
                    <a class="btn theme-btn theme-btn-sm theme-btn-white lh-28 collapse-btn" data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseFilter">
                        Filters <i class="la la-angle-down ml-1 collapse-btn-hide"></i>
                        <i class="la la-angle-up ml-1 collapse-btn-show"></i>
                    </a>
                </div>
            </div><!-- end filter-bar-inner -->
            <div class="collapse pt-4" id="collapseFilter">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="widget-panel">
                            <h3 class="fs-18 font-weight-semi-bold pb-3">Ratings</h3>
                            <div class="custom-control custom-radio mb-1 fs-15">
                                <input type="radio" class="custom-control-input rating-filter" id="ratingAll" name="rating_filter" value="all" checked>
                                <label class="custom-control-label custom--control-label" for="ratingAll">All Ratings</label>
                            </div>
                            <div class="custom-control custom-radio mb-1 fs-15">
                                <input type="radio" class="custom-control-input rating-filter" id="rating4to5" name="rating_filter" value="4-5">
                                <label class="custom-control-label custom--control-label" for="rating4to5">
                                          <span class="rating-wrap d-flex align-items-center">
                                              <span class="review-stars">
                                                  <span class="la la-star"></span>
                                                  <span class="la la-star"></span>
                                                  <span class="la la-star"></span>
                                                  <span class="la la-star"></span>
                                                  <span class="la la-star-half-alt"></span> <!-- Representasi 4.0 ke atas -->
                                              </span>
                                              <span class="rating-total pl-1"><span class="mr-1 text-black">4.0 & up</span></span>
                                          </span>
                                </label>
                            </div>
                            <div class="custom-control custom-radio mb-1 fs-15">
                                <input type="radio" class="custom-control-input rating-filter" id="rating3to4" name="rating_filter" value="3-4">
                                <label class="custom-control-label custom--control-label" for="rating3to4">
                                          <span class="rating-wrap d-flex align-items-center">
                                              <span class="review-stars">
                                                  <span class="la la-star"></span>
                                                  <span class="la la-star"></span>
                                                  <span class="la la-star"></span>
                                                  <span class="la la-star-o"></span>
                                                  <span class="la la-star-o"></span>
                                              </span>
                                              <span class="rating-total pl-1"><span class="mr-1 text-black">3.0 - 3.9</span></span>
                                          </span>
                                </label>
                            </div>
                            <div class="custom-control custom-radio mb-1 fs-15">
                                <input type="radio" class="custom-control-input rating-filter" id="rating2to3" name="rating_filter" value="2-3">
                                <label class="custom-control-label custom--control-label" for="rating2to3">
                                          <span class="rating-wrap d-flex align-items-center">
                                              <span class="review-stars">
                                                  <span class="la la-star"></span>
                                                  <span class="la la-star"></span>
                                                  <span class="la la-star-o"></span>
                                                  <span class="la la-star-o"></span>
                                                  <span class="la la-star-o"></span>
                                              </span>
                                              <span class="rating-total pl-1"><span class="mr-1 text-black">2.0 - 2.9</span></span>
                                          </span>
                                </label>
                            </div>
                            <div class="custom-control custom-radio mb-1 fs-15">
                                <input type="radio" class="custom-control-input rating-filter" id="rating1to2" name="rating_filter" value="1-2">
                                <label class="custom-control-label custom--control-label" for="rating1to2">
                                    <span class="rating-wrap d-flex align-items-center">
                                        <span class="review-stars">
                                            <span class="la la-star"></span>
                                            <span class="la la-star-o"></span>
                                            <span class="la la-star-o"></span>
                                            <span class="la la-star-o"></span>
                                            <span class="la la-star-o"></span>
                                        </span>
                                        <span class="rating-total pl-1"><span class="mr-1 text-black">1.0 - 1.9</span></span>
                                    </span>
                                </label>
                            </div>
                        </div><!-- end widget-panel -->
                    </div><!-- end col-lg-3 -->
                    <div class="col-lg-3">
                        <div class="widget-panel">
                            <h3 class="fs-18 font-weight-semi-bold pb-3">Categories</h3>
                            <div id="category-filter-list">
                                @if(isset($categories) && $categories->count() > 0)
                                    @foreach ($categories->take(5) as $category) {{-- Show first 5 initially --}}
                                        <div class="custom-control custom-checkbox mb-1 fs-15">
                                            <input type="checkbox" class="custom-control-input category-filter" name="category_filter[]" id="catCheckbox{{ $category->id }}" value="{{ $category->id }}">
                                            <label class="custom-control-label custom--control-label text-black" for="catCheckbox{{ $category->id }}">
                                                {{ $category->category_name }}
                                                {{-- <span class="ml-1 text-gray">({{ $category->courses_count ?? 0 }})</span> --}}
                                                {{-- Dynamic count can be added later if needed --}}
                                            </label>
                                        </div><!-- end custom-control -->
                                    @endforeach
                                    @if($categories->count() > 5)
                                        <a class="collapse-btn collapse--btn fs-15" data-toggle="collapse" href="#collapseMoreCategories" role="button" aria-expanded="false" aria-controls="collapseMoreCategories">
                                            <span class="collapse-btn-hide">Show more<i class="la la-angle-down ml-1 fs-14"></i></span>
                                            <span class="collapse-btn-show">Show less<i class="la la-angle-up ml-1 fs-14"></i></span>
                                        </a>
                                        <div class="collapse" id="collapseMoreCategories">
                                            @foreach ($categories->skip(5) as $category)
                                                <div class="custom-control custom-checkbox mb-1 fs-15">
                                                    <input type="checkbox" class="custom-control-input category-filter" name="category_filter[]" id="catCheckbox{{ $category->id }}" value="{{ $category->id }}">
                                                    <label class="custom-control-label custom--control-label text-black" for="catCheckbox{{ $category->id }}">
                                                        {{ $category->category_name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <p>No categories available.</p>
                                @endif
                            </div>
                        </div><!-- end widget-panel -->
                    </div><!-- end col-lg-3 -->
                 
                    <div class="col-lg-3">
                        <div class="widget-panel">
                            <h3 class="fs-18 font-weight-semi-bold pb-3">Level</h3>
                            <div id="level-filter-list">
                                @if(isset($course_levels) && $course_levels->count() > 0)
                                    @foreach ($course_levels as $level)
                                        <div class="custom-control custom-checkbox mb-1 fs-15">
                                            <input type="checkbox" class="custom-control-input level-filter" name="level_filter[]" id="levelCheckbox{{ Str::slug($level) }}" value="{{ $level }}">
                                            <label class="custom-control-label custom--control-label text-black" for="levelCheckbox{{ Str::slug($level) }}">
                                                {{ $level }}
                                            </label>
                                        </div><!-- end custom-control -->
                                    @endforeach
                                @else
                                    <p>No levels defined.</p>
                                @endif
                            </div>
                        </div><!-- end widget-panel -->
                    </div><!-- end col-lg-3 -->
              
                </div><!-- end row -->
            </div><!-- end collapse -->
        </div><!-- end filter-bar -->
        <div id="course-area-content"> {{-- Wrapper untuk update AJAX --}}
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
                                    @if ($course->highestrated == 1)
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
                            </div>
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
                        {{ $courses->links('vendor.pagination.custom-pagination') }} 
                    </nav>
                @endif
                @if ($courses->total() > 0)
                    <p class="fs-14 pt-2">Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} results</p>
                @else
                    <p class="fs-14 pt-2">No results found</p>
                @endif
            </div>
        </div> {{-- End #course-area-content --}}
    </div><!-- end container -->
</section><!-- end courses-area -->
<!--======================================
        END COURSE AREA
======================================-->

<!-- tooltip_templates -->
<div class="tooltip_templates">
    @foreach ($courses as $course)
        <div id="tooltip_content_{{ $course->id }}">
            <div class="card card-item">
                <div class="card-body">
                    <p class="card-text pb-2">By <a href="{{ $course->instructor ? route('instructor.details', ['id' => $course->instructor->id]) : '#' }}">{{ $course->instructor->name ?? 'N/A' }}</a></p>
                    <h5 class="card-title pb-1"><a href="{{ route('course.details', ['id' => $course->id, 'slug' => $course->course_name_slug]) }}">{{ $course->course_name }}</a></h5>
                    <div class="d-flex align-items-center pb-1">
                        @if ($course->bestseller == 1)
                            <h6 class="ribbon fs-14 mr-1">Bestseller</h6>
                        @endif
                        <p class="text-success fs-14 font-weight-medium">Updated<span class="font-weight-bold pl-1">{{ $course->updated_at->format('F Y') }}</span></p>
                    </div>
                    <ul class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center fs-14">
                        <li>{{ $course->duration }} total hours</li>
                        <li>{{ $course->label }}</li>
                    </ul>
                    <p class="card-text pt-1 fs-14 lh-22">{{ Str::limit($course->prerequisites, 100) }}</p>
                    <ul class="generic-list-item fs-14 py-3">
                        @forelse($course->courseGoals->take(3) as $goal)
                            <li><i class="la la-check mr-1 text-black"></i> {{ $goal->goal_name }}</li>
                        @empty
                            <li>No course goals specified.</li>
                        @endforelse
                    </ul>
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn theme-btn flex-grow-1 mr-3" onclick="addToCart({{ $course->id }},'{{ $course->course_name }}','{{ $course->instructor_id }}','{{ $course->course_name_slug }}')"><i class="la la-shopping-cart mr-1 fs-18"></i> Add to Cart</button>
                        <div>
                            <div class="icon-element icon-element-sm shadow-sm cursor-pointer" title="Add to Wishlist" onclick="addToWishList({{ $course->id }})">
                                <i class="la la-heart-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    @endforeach
</div><!-- end tooltip_templates -->

@push('scripts')
<script>
$(document).ready(function() {
    const courseAreaContent = $('#course-area-content'); // Target div wrapper

    function fetchFilteredCourses(page = 1) { // Default ke halaman 1
        // Tampilkan status loading
        courseAreaContent.html('<div class="text-center p-5"><i class="la la-spinner la-spin la-3x"></i><p>Loading courses...</p></div>');

        var selectedRating = $('input[name="rating_filter"]:checked').val();
        var selectedCategories = $('input.category-filter:checked').map(function() {
            return $(this).val();
        }).get();
        var selectedLevels = $('input.level-filter:checked').map(function() {
            return $(this).val();
        }).get();

        $.ajax({
            url: "{{ route('courses.filterByRating') }}", // Route akan kita definisikan
            type: 'GET',
            data: {
                rating_filter: selectedRating,
                category_ids: selectedCategories,
                level_labels: selectedLevels,
                page: page // Kirim halaman saat ini untuk paginasi
            },
            success: function(response) {
                courseAreaContent.html(response);
                // Re-initialize lazy loading for the new images
                // Make sure the selector targets images within the updated content
                if ($.fn.lazy) { // Check if lazy load plugin is available
                    courseAreaContent.find('img.lazy').lazy();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching filtered courses:", xhr.responseText);
                courseAreaContent.html('<p class="text-center text-danger p-5">Could not load courses. Please try again.</p>');
            }
        });
    }

    // Combined filter change handler
    $('input[name="rating_filter"], input.category-filter, input.level-filter').on('change', function() {
        fetchFilteredCourses(1); // Reset ke halaman 1 saat filter baru dipilih
    });

    // Handle paginasi AJAX
    $(document).on('click', '#course-area-content .pagination-box .pagination a', function(event) {
        event.preventDefault();
        var pageUrl = $(this).attr('href');
        if (!pageUrl || pageUrl === '#') return;

        var url = new URL(pageUrl);
        var page = url.searchParams.get("page");

        fetchFilteredCourses(page); // Pass the new page, other filters are picked up inside the function
    });
});
</script>
@endpush
@endsection