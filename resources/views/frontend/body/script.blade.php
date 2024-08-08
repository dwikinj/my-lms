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
                    timer: 6000
                })
                if ($.isEmptyObject(data.error)) {

                    Toast.fire({
                        type: 'success',
                        title: data.success,
                    })

                } else {

                    Toast.fire({
                        type: 'error',
                        icon:'warning',
                        title: data.error,
                    })
                }

                // End Message   
            },

        })
    }
</script>


{{-- End wishlist Add Option  --}}
