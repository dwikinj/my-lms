@php
    // Mengambil 6 ulasan terbaru yang statusnya disetujui (1)
    // with('user') digunakan untuk eager loading, menghindari query N+1 dan meningkatkan performa
    $reviews = App\Models\Review::where('status', 1)
        ->with('user')
        ->latest() // Mengurutkan berdasarkan 'created_at' dari yang terbaru
        ->limit(6)
        ->get();
@endphp

<section class="testimonial-area section-padding">
    <div class="container">
        <div class="section-heading text-center">
            <h5 class="ribbon ribbon-lg mb-2">Testimonials</h5>
            <h2 class="section__title">Student's Feedback</h2>
            <span class="section-divider"></span>
        </div><!-- end section-heading -->
    </div><!-- end container -->
    <div class="container-fluid">
        <div class="testimonial-carousel owl-action-styled">

            {{-- Looping melalui setiap review yang diambil --}}
            @forelse ($reviews as $review)
                <div class="card card-item">
                    <div class="card-body">
                        <div class="media media-card align-items-center pb-3">
                            <div class="media-img avatar-md">
                                {{-- Logika dinamis untuk menampilkan foto user --}}
                                {{-- Menggunakan logika yang sama dari file referensi Anda untuk menangani role user/instructor --}}
                                <img src="{{ !empty($review->user->photo) ? (($review->user->role === 'instructor' ? url('upload/instructor_images/' . $review->user->photo) : url('upload/user_images/' . $review->user->photo))) : url('upload/no_image.jpg') }}"
                                    alt="Testimonial avatar" class="rounded-full">
                            </div>
                            <div class="media-body">
                                {{-- Menampilkan nama user secara dinamis --}}
                                <h5>{{ $review->user->name }}</h5>
                                <div class="d-flex align-items-center pt-1">
                                    {{-- Menampilkan role user, default 'Student' jika tidak ada --}}
                                    <span class="lh-18 pr-2">{{ Str::ucfirst($review->user->role ?? 'Student') }}</span>
                                    <div class="review-stars">
                                        {{-- Logika dinamis untuk menampilkan rating bintang --}}
                                        @if (isset($review->rating) && is_numeric($review->rating))
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <span class="la la-star"></span>
                                                @else
                                                    <span class="la la-star-o"></span>
                                                @endif
                                            @endfor
                                        @else
                                            {{-- Fallback jika tidak ada rating --}}
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="la la-star-o"></span>
                                            @endfor
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div><!-- end media -->

                        {{-- Menampilkan komentar review secara dinamis --}}
                        <p class="card-text">
                            {{ $review->comment }}
                        </p>
                    </div><!-- end card-body -->
                </div><!-- end card -->

            @empty
                <div class="card card-item">
                    <div class="card-body text-center">
                        <p class="card-text">
                            No student feedback available yet.
                        </p>
                    </div>
                </div>
            @endforelse

        </div><!-- end testimonial-carousel -->
    </div><!-- container-fluid -->
</section><!-- end testimonial-area -->