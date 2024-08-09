{{--  Start wishlist Add Option  --}}
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    function addToWishList(course_id) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "/add-to-wishlist/" + course_id,
            success: function(data) {
                // Start Message 

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                })
                if ($.isEmptyObject(data.error)) {
                    Toast.fire({
                        type: 'success',
                        title: data.success,
                    })

                } else {
                    Toast.fire({
                        type: 'error',
                        icon: 'warning',
                        title: data.error,
                    })
                }

                // End Message   
            },

        })
    }
</script>
{{-- End wishlist Add Option  --}}

{{--  Start load wishlist data   --}}
<script type="text/javascript">
    function wishlist() {
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "/get-wishlist-course",
            success: function(response) {
                if (response.wishlist) {
                    let rows = "";
                    $.each(response.wishlist, function(key, value) {
                        rows += `
                            <div class="col-lg-4 responsive-column-half">
                                <div class="card card-item">
                                    <div class="card-image">
                                        <a href="/course/details/${value.instructor_id}/${value.course_name_slug}" class="d-block">
                                            <img class="card-img-top" src="/${value.course_image}" alt="Card image cap">
                                        </a>
                                        <div class="course-badge-labels">
                                            ${value.instructor.bestseller == 1 ? '<div class="course-badge">Bestseller</div>' : ''}
                                            ${value.instructor.highestarted == 1 ? '<div class="course-badge sky-blue">Highestrated</div>' : ''}
                                            ${value.instructor.featured == 1 ? '<div class="course-badge red">Featured</div>' : ''}
                                            ${value.instructor.discount_percentage > 0 ? '<div class="course-badge blue">-' + value.instructor.discount_percentage + '%</div>' : ''}
                                        </div>
                                    </div><!-- end card-image -->
                                    <div class="card-body">
                                        <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">${value.label}</h6>
                                        <h5 class="card-title">
                                            <a href="/course/details/${value.instructor_id}/${value.course_name_slug}">
                                                ${value.course_name.length > 27 ? value.course_name.slice(0, 25).trim() + '...' : value.course_name}
                                            </a>
                                        </h5>
                                        <p class="card-text"><a href="/instructor/details/${value.instructor_id}">${value.instructor.name}</a></p>
                                        <div class="rating-wrap d-flex align-items-center py-2">
                                            <div class="review-stars">
                                                <span class="rating-number">4.4</span>
                                                <span class="la la-star"></span>
                                                <span class="la la-star"></span>
                                                <span class="la la-star"></span>
                                                <span class="la la-star"></span>
                                                <span class="la la-star-o"></span>
                                            </div>
                                            <span class="rating-total pl-1">(20,230)</span>
                                        </div><!-- end rating-wrap -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            ${value.discount_price < value.selling_price ? `
                                                    <p class="card-price text-black font-weight-bold">
                                                        $${value.discount_price}
                                                        <span class="before-price font-weight-medium">$${value.selling_price}</span>
                                                    </p>
                                                ` : `
                                                    <p class="card-price text-black font-weight-bold">$${value.selling_price}</p>
                                                `}                                          
                                            <div onclick="confirmDeleteWishlist(${value.id})" class="icon-element icon-element-sm shadow-sm cursor-pointer" data-toggle="tooltip" data-placement="top" title="Remove from Wishlist">
                                                <i class="la la-heart"></i></div>
                                        </div>
                                    </div><!-- end card-body -->
                                </div><!-- end card -->
                            </div><!-- end col-lg-4 -->
                        `;
                    });

                    //pagination wishlist
                    let paginationWishlist = `
                        <nav aria-label="Page navigation example" class="pagination-box">
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true"><i class="la la-arrow-left"></i></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true"><i class="la la-arrow-right"></i></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <p class="fs-14 pt-2">Showing 1-4 of 16 results</p>
                    `

                    $('#wishlist').html(rows);
                    $('#pagination-wishlist').html(paginationWishlist);
                    $('#empty-wishlist').hide();
                    if (response.wishlist.length === 0) {
                        $('#empty-wishlist').show();
                        $('#pagination-wishlist').hide();
                    }
                    $('#wishQty').text(response.wishlist.length);


                }
            }
        });
    };

    function confirmDeleteWishlist(courseId) {
        Swal.fire({
            title: "Are you sure?",
            text: "Delete This Course From Wishlist?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                deleteFromWishlist(courseId);
            }
        });
    }

    function deleteFromWishlist(courseId) {
        $.ajax({
            type: "DELETE",
            url: `/delete-from-wishlist/${courseId}`,
            success: function(response) {
                wishlist();
            },
            error: function(xhr, status, error) {
                console.error('Error deleting course from wishlist:', error);
            }
        });
    }

    wishlist();
</script>

{{-- End load wishlist data  --}}
