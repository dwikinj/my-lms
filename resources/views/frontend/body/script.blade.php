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



                if ($.isEmptyObject(data.error)) {
                    toastr.success(
                        data.success

                    )

                } else {
                    toastr.error(
                        data.error

                    )
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

                    

                    $('#wishlist').html(rows);
                    $('#empty-wishlist').hide();
                    if (response.wishlist.length === 0) {
                        $('#empty-wishlist').show();
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


{{--  Start Mini Cart   --}}
<script type="text/javascript">
    function miniCart() {
        $.ajax({
            type: 'GET',
            url: '/course/mini/cart',
            dataType: 'json',
            success: function(response) {
                let miniCart = "";

                $.each(response.carts, function(key, value) {
                    miniCart += `
                         <li class="media media-card">
                                                <a href="/mycart" class="media-img">
                                                    <img src="/${value.options.image}" alt="${value.name}">
                                                </a>
                                                <div class="media-body">
                                                    <h5><a href="/course/details/${value.id}/${value.options.slug}}">${value.name}</a></h5>
                                                    <span class="d-block lh-18 py-1">${value.options.instructor}</span>
                                                    <p class="text-black font-weight-semi-bold lh-18">$${value.price}</p>
                                                    <button class="btn btn-link p-0 m-0" id="${value.rowId}" onclick="miniCartRemove(this.id)"><i class="la la-times"></i></button>
                                                </div>
                        </li>
                    `
                });

                $('#miniCart').html(miniCart);
                $('#cartSubTotal').text(`$${response.cartTotal}`);
                $('#cartQty').text(`${response.cartQty}`);

            }
        })
    }
    miniCart();

    //start my cart
    function cart() {
        $.ajax({
            type: "GET",
            url: "/get-cart-course",
            dataType: "json",
            success: function(response) {
                var rows = "";
                $.each(response.carts, function(key, value) {
                    rows += `
                    <tr>
                        <th scope="row">
                            <div class="media media-card">
                                <a href="/course/details/${value.id}/${value.options.slug}" class="media-img mr-0">
                                    <img src="/${value.options.image}" alt="${value.name}">
                                </a>
                            </div>
                        </th>
                        <td>
                            <a href="/course/details/${value.id}/${value.options.slug}" class="text-black font-weight-semi-bold">${value.name}</a>
                            <p class="fs-14 text-gray lh-20">By <a href="/instructor/details/${value.options.instructor_id}" class="text-color hover-underline">${value.options.instructor}</a>
                        </td>
                        <td>
                            <ul class="generic-list-item font-weight-semi-bold">
                                <li class="text-black lh-18">$${value.price}</li>
                            </ul>
                        </td>
                        <td>
                            <button type="button" id="${value.rowId}" onclick="miniCartRemove(this.id)" class="icon-element icon-element-xs shadow-sm border-0" data-toggle="tooltip" data-placement="top" title="Remove">
                                <i class="la la-times"></i>
                            </button>
                        </td>
                    </tr>
                    `
                });

                $('#cartTbody').html(rows);
                $('span[id="cartSubTotal"]').html(`$${response.cartTotal}`);
            }
        })
    }
    cart();
    //end my cart

    //add to minicart
    function addToCart(courseId, courseName, instructorId, slug) {
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                _token: '{{ csrf_token() }}',
                course_name: courseName,
                course_name_slug: slug,
                instructor: instructorId,
            },

            url: "/cart/data/store/" + courseId,
            success: function(data) {
                // Start Message 

                if ($.isEmptyObject(data.error)) {
                    toastr.success(
                        data.success
                    )
                    miniCart();

                } else {
                    toastr.error(
                        data.error
                    )
                }
                // End Message   
            }
        })
    }
    //end to minicart

    //buy now
    function buyCourse(courseId, courseName, instructorId, slug) {
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                _token: '{{ csrf_token() }}',
                course_name: courseName,
                course_name_slug: slug,
                instructor: instructorId,
            },

            url: "/buy/data/store/" + courseId,
            success: function(data) {
                // Start Message 

                if ($.isEmptyObject(data.error)) {
                    toastr.success(
                        data.success
                    )
                    miniCart();
                    window.location.href = '/checkout';

                } else {
                    toastr.error(
                        data.error
                    )
                }
                // End Message   
            }
        })
    }
    //end method


    //remove minicart
    function miniCartRemove(rowId) {
        $.ajax({
            type: "DELETE",
            url: "/course/mini/cart/remove/" + rowId,
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    toastr.success(
                        data.success
                    )
                    miniCart();
                    cart();
                    couponCalc();

                } else {
                    toastr.error(
                        data.error
                    )
                }
            }
        })
    }
    //end remove minicart

    //apply coupon code
    function applyCoupon() {
        var coupon_name = $('#coupon_name').val();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: {
                coupon_name: coupon_name
            },
            url: "{{ route('coupon.apply') }}", // Define this route next
            success: function(data) {
                if (data.validity == true) {
                    $('#couponField').hide();
                    couponCalc();
                    toastr.success(data.success);
                } else {
                    toastr.error(data.error);
                }
            }
        })
    }
    //end coupon code

    //coupon calculation
    function couponCalc() {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: "{{ route('coupon.calculation') }}", // Define this route next
            success: function(data) {
                if (data.total) {
                    $('#couponCalField').html(`
                        <h3 class="fs-18 font-weight-bold pb-3">Cart Totals</h3>
                        <div class="divider"><span></span></div>
                        <ul class="generic-list-item pb-4">
                            <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                                <span class="text-black">Subtotal:</span>
                                <span>$${data.total}</span>
                            </li>
                            <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                                <span class="text-black">Grand Total:</span>
                                <span>$${data.total}</span>
                            </li>
                        </ul>
                    `);
                } else {
                    $('#couponCalField').html(`
                        <h3 class="fs-18 font-weight-bold pb-3">Cart Totals</h3>
                        <div class="divider"><span></span></div>
                        <ul class="generic-list-item pb-4">
                            <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                                <span class="text-black">Subtotal:</span>
                                <span>$${data.subtotal}</span>
                            </li>
                            <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                                <span class="text-black">Coupon Name:</span>
                                <span>${data.coupon_name} <button type="button" class="icon-element icon-element-xs shadow-sm border-0" data-toggle="tooltip" data-placement="top" onclick="couponRemove()">
                                    <i class="la la-times"></i>
                                </button></span>
                            </li>
                            <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                                <span class="text-black">Coupon Discount:</span>
                                <span>${data.coupon_discount}%</span>
                            </li>
                            <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                                <span class="text-black">Discount Amount:</span>
                                <span>$${data.discount_amount}</span>
                            </li>
                            <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                                <span class="text-black">Grand Total:</span>
                                <span>$${data.total_amount}</span>
                            </li>
                        </ul>
                        
                    `);
                }
            }
        })
    }
    couponCalc();
    //end coupon code

    //start coupon remove
    //remove coupon code
    function couponRemove() {
        $.ajax({
            type: 'GET', // Bisa juga menggunakan DELETE, tergantung konfigurasi route di backend
            dataType: 'json',
            url: "{{ route('coupon.remove') }}", // Route yang akan kita definisikan di Laravel
            success: function(data) {
                if (data.success) {
                    $('#couponField').show(); // Tampilkan kembali form kupon
                    couponCalc(); // Update tampilan kalkulasi cart
                    toastr.success(data.success); // Tampilkan pesan sukses
                }
            }
        });
    }
    //end coupon remove

    //start instructor apply coupon code
    function applyInsCoupon() {
        var couponName = $('#coupon_name').val();
        var courseId = $('#course_id').val();
        var instructorId = $('#instructor_id').val();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: {
                coupon_name: couponName,
                course_id: courseId,
                instructor_id: instructorId,
            },
            url: "{{ route('coupon.instructor.apply') }}", // Define this route next
            success: function(data) {
                if (data.validity == true) {
                    $('#couponField').hide();
                    couponCalc();
                    toastr.success(data.success);
                    miniCart();
                } else {
                    toastr.error(data.error);
                }
            }
        })
    }
    //end instructor apply coupon code
</script>
{{-- End Mini Cart --}}

{{-- Review Ajax --}}
<script>
    $(document).ready(function() {
        $('#reviewForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah submit form tradisional

            var form = $(this);
            var submitButton = $('#submitReviewBtn');
            var originalButtonText = submitButton.html();
            var formData = form.serialize(); // Mengambil data form
            var url = form.attr('action');

            // Reset pesan error sebelumnya
            $('#review-messages').html('');
            $('#rating_error').text('');
            $('#comment_error').text('');
            $('.form-control').removeClass('is-invalid'); // Hapus kelas error jika ada

            // Tampilkan loading state (opsional)
            submitButton.html('Submitting... <i class="fas fa-spinner fa-spin"></i>').prop('disabled',
                true);

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json', // Berharap respons JSON dari server
                success: function(response) {
                    if (response.success) {
                        // Tampilkan pesan sukses
                        $('#review-messages').html('<div class="alert alert-success">' +
                            response.message + '</div>');
                        // Kosongkan form
                        form[0].reset();
                        // Anda mungkin ingin melakukan hal lain, seperti me-reload bagian review di halaman
                        // atau menambahkan review baru ke daftar secara dinamis.
                        // Contoh: setTimeout(function(){ location.reload(); }, 2000); // Reload halaman setelah 2 detik
                    }
                    // (Teorinya, blok ini tidak akan tercapai jika ada validasi error karena server akan return 422)
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) { // Error validasi
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            if (errors.rating) {
                                $('#rating_error').text(errors.rating[0]);
                            }
                            if (errors.comment) {
                                $('#comment_error').text(errors.comment[0]);
                                $('#comment_textarea').addClass('is-invalid');
                            }
                            // Tambahkan penanganan untuk error lain jika ada
                            // Misalnya, tampilkan pesan error umum jika ada error lain selain field
                            var generalErrorMessages = [];
                            $.each(errors, function(key, value) {
                                if (key !== 'rating' && key !==
                                    'comment') { // Contoh jika ada error lain
                                    generalErrorMessages.push(value[0]);
                                }
                            });
                            if (generalErrorMessages.length > 0) {
                                $('#review-messages').html(
                                    '<div class="alert alert-danger"><ul>' +
                                    generalErrorMessages.map(msg => `<li>${msg}</li>`)
                                    .join('') + '</ul></div>');
                            }
                        } else {
                            $('#review-messages').html(
                                '<div class="alert alert-danger">Validation error, but no specific messages returned.</div>'
                                );
                        }
                    } else {
                        // Error server lain
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                            xhr.responseJSON.message :
                            'An unexpected error occurred. Please try again.';
                        $('#review-messages').html('<div class="alert alert-danger">' +
                            errorMessage + '</div>');
                    }
                },
                complete: function() {
                    // Kembalikan tombol ke state normal
                    submitButton.html(originalButtonText).prop('disabled', false);
                }
            });
        });
    });
</script>
{{-- End Review Ajax --}}
