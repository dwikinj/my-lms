@php
    // Mengambil 6 kursus teratas berdasarkan ID, beserta jumlah review dan rata-rata rating
    // Anda bisa mengubah orderBy atau limit sesuai kebutuhan, misalnya kursus terbaru atau terlaris.
    $courses = App\Models\Course::where('status', 1)
        ->withCount('reviews') // Menghasilkan 'reviews_count'
        ->withAvg('reviews', 'rating') // Menghasilkan 'reviews_avg_rating'
        ->orderBy('id', 'DESC') // Mengambil kursus terbaru
        ->limit(6)
        ->get();
@endphp

<section class="course-area pb-90px">
    <div class="course-wrapper">
        <div class="container">
            <div class="section-heading text-center">
                <h5 class="ribbon ribbon-lg mb-2">Learn on your schedule</h5>
                <h2 class="section__title">Students are viewing</h2>
                <span class="section-divider"></span>
            </div><!-- end section-heading -->
            <div class="course-carousel owl-action-styled owl--action-styled mt-30px">

                {{-- LOOPING MELALUI SETIAP KURSUS --}}
                @foreach ($courses as $course)
                    <div class="card card-item card-preview"
                        data-tooltip-content="#tooltip_content_{{ $course->id }}">
                        <div class="card-image">
                            <a href="{{ route('course.details', ['id' => $course->id, 'slug' => $course->course_name_slug]) }}"
                                class="d-block">
                                <img class="card-img-top lazy" src="{{ asset($course->course_image) }}"
                                    data-src="{{ asset($course->course_image) }}" alt="Card image cap">
                            </a>
                            <div class="course-badge-labels">

                                @if ($course->bestseller == 1)
                                    <div class="course-badge">Bestseller</div>
                                @endif

                                @if ($course->discount_percentage > 0)
                                    <div class="course-badge blue">-{{ $course->discount_percentage }}%</div>
                                @endif

                            </div>
                        </div><!-- end card-image -->
                        <div class="card-body">
                            <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">{{ $course->label }}</h6>
                            <h5 class="card-title" x-data="{ title: '{{ $course->course_name }}' }" x-init="title = title.length > 27 ? title.slice(0, 25).trim() + '...' : title">
                                <a href="{{ route('course.details', ['id' => $course->id, 'slug' => $course->course_name_slug]) }}"
                                    x-text="title"></a>
                            </h5>
                            <p class="card-text"><a
                                    href="{{ route('instructor.details', ['id' => $course->instructor->id]) }}">{{ $course->instructor->name }}</a>
                            </p>
                            <div class="rating-wrap d-flex align-items-center py-2">
                                {{-- AWAL BAGIAN RATING DINAMIS --}}
                                @php
                                    $averageRating = $course->reviews_avg_rating ?? 0;
                                    $totalReviews = $course->reviews_count ?? 0;
                                    $fullStars = floor($averageRating);
                                    $hasHalfStar = $averageRating - $fullStars >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                @endphp
                                <div class="review-stars">
                                    @if ($totalReviews > 0)
                                        <span class="rating-number">{{ number_format($averageRating, 1) }}</span>
                                        @for ($i = 1; $i <= $fullStars; $i++)
                                            <span class="la la-star"></span>
                                        @endfor
                                        @if ($hasHalfStar && !$fullStars == 5)
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
                                {{-- AKHIR BAGIAN RATING DINAMIS --}}
                            </div><!-- end rating-wrap -->
                            <div class="d-flex justify-content-between align-items-center">
                                {{-- AWAL BAGIAN HARGA DINAMIS --}}
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
                                {{-- AKHIR BAGIAN HARGA DINAMIS --}}

                                {{-- Tombol Wishlist dinamis --}}
                                <div>
                                    <div class="icon-element icon-element-sm shadow-sm cursor-pointer"
                                        title="Add to Wishlist" onclick="addToWishList({{ $course->id }})">
                                        <i class="la la-heart-o"></i>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                @endforeach
                {{-- AKHIR LOOPING --}}

            </div><!-- end course-carousel -->
        </div><!-- end container -->
    </div><!-- end course-wrapper -->
</section><!-- end courses-area -->

<!-- ================================
         START TOOLTIP
================================= -->
{{-- Template untuk tooltip yang akan muncul saat mouse hover di atas kartu kursus --}}
<div class="tooltip_templates">
    @foreach ($courses as $course)
        <div id="tooltip_content_{{ $course->id }}">
            <div class="card card-item">
                <div class="card-body">
                    <p class="card-text pb-2">By <a
                            href="{{ route('instructor.details', ['id' => $course->instructor->id]) }}">{{ $course->instructor->name }}</a>
                    </p>
                    <h5 class="card-title pb-1"><a
                            href="{{ route('course.details', ['id' => $course->id, 'slug' => $course->course_name_slug]) }}">{{ $course->course_name }}</a>
                    </h5>
                    <div class="d-flex align-items-center pb-1">
                        @if ($course->bestseller == 1)
                            <h6 class="ribbon fs-14 mr-1">Bestseller</h6>
                        @else
                            <h6 class="ribbon fs-14 mr-1">New</h6>
                        @endif
                        <p class="text-success fs-14 font-weight-medium">Updated<span class="font-weight-bold pl-1">
                                {{ $course->updated_at->format('F Y') }}</span></p>
                    </div>
                    <ul
                        class="generic-list-item generic-list-item-bullet generic-list-item--bullet d-flex align-items-center fs-14">
                        <li>{{ $course->duration }} total hours</li>
                        <li>{{ $course->label }}</li>
                    </ul>
                    <p class="card-text pt-1 fs-14 lh-22">{{ Str::limit($course->prerequisites, 100) }}</p>
                    <ul class="generic-list-item fs-14 py-3">
                        @forelse(collect($course->courseGoals)->take(2) as $goal)
                            <li><i class="la la-check mr-1 text-black"></i> {{ $goal->goal_name }}</li>
                        @empty
                            <li><i class="la la-check mr-1 text-black"></i> No course goals specified.</li>
                        @endforelse
                    </ul>
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn theme-btn flex-grow-1 mr-3"
                            onclick="addToCart({{ $course->id }},'{{ $course->course_name }}','{{ $course->instructor->id }}','{{ $course->course_name_slug }}')">
                            <i class="la la-shopping-cart mr-1 fs-18"></i> Add to Cart
                        </button>
                        <div>
                            <div class="icon-element icon-element-sm shadow-sm cursor-pointer"
                                title="Add to Wishlist" onclick="addToWishList({{ $course->id }})">
                                <i class="la la-heart-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    @endforeach
</div><!-- end tooltip_templates -->
<!-- ================================
          END TOOLTIP
================================= -->