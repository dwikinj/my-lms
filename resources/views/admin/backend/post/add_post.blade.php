@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Blog Post</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Add Blog Post</h5>
            {{-- Perhatikan perubahan action dan penambahan id --}}
            <form method="POST" id="myForm" enctype="multipart/form-data" action="{{ route('blog.post.store') }}"
                class="row g-3">
                @csrf

                <div class="col-md-6">
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="blog_category_id" class="form-label">Blog Category</label>
                            <select name="blog_category_id" id="blog_category_id" class="form-select mb-3" aria-label="Default select example">
                                <option selected="" value="">Select Blog Category</option>
                                @foreach ($blogCategory as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" id="blog_category_id_error"></span> 
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="post_title" class="form-label">Post Title</label>
                            <input class="form-control" name="post_title" type="text" id="post_title" >
                            <span class="invalid-feedback" id="post_title_error"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <div class="mb-3">
                        <label for="long_description_editor" class="form-label">Post Long Description</label>
                        <div id="long_description_editor" style="height: 300px;">
                        </div>
                        <input type="hidden" name="long_description" id="long_description">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="post_tags" class="form-label">Post Tags</label>
                            <input type="text" name="post_tags" id="post_tags" class="form-control" data-role="tagsinput" value="Jquery">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="mb-3">
                            <label for="post_image" class="form-label">Post Image</label>
                            <input class="form-control" name="post_image" type="file" id="post_image">
                            <span class="invalid-feedback" id="post_image_error"></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <img id="imagePreview" src="{{url('upload/no_image.jpg') }}" alt="Post Preview" class="rounded p-1 bg-primary"
                        width="110" height="110">
                </div>


                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function() {
    // --- QuillJS Image Handler ---
    function imageHandler() {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = function() {
            var file = input.files[0];
            if (file) {
                var formData = new FormData();
                formData.append('image', file);

                $.ajax({
                    url: "{{ route('blog.post.upload_image_quill') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success && response.url) {
                            var range = quill.getSelection(true);
                            quill.insertEmbed(range.index, 'image', response.url);
                            quill.setSelection(range.index + 1);
                        } else {
                            toastr.warm('Image upload failed: ' + (response.error || 'Unknown error'));
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error('Image upload error: ' + errorThrown);
                    }
                });
            }
        };
    }
    // --- End QuillJS Image Handler ---


    // Inisialisasi Quill editor
    var quill = new Quill('#long_description_editor', {
        theme: 'snow',
        modules: {
            toolbar: {
                container: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'font': [] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ],
                handlers: {
                    'image': imageHandler
                }
            }
        },
        placeholder: 'Write your long description here...'
    });

    // Fungsi untuk membersihkan error messages
    function clearValidationErrors() {
        $('.form-control').removeClass('is-invalid');
        $('.form-select').removeClass('is-invalid');
        $('.invalid-feedback').text('').hide();
    }

    // AJAX Form Submission
    $('#myForm').on('submit', function(e) {
        e.preventDefault(); 

        // Ambil konten Quill dan masukkan ke hidden input
        var htmlContent = quill.root.innerHTML;
        if (htmlContent === '<p><br></p>') { // Jika hanya ada paragraf kosong
            htmlContent = '';
        }
        $('#long_description').val(htmlContent);

        var formData = new FormData(this); // 'this' mengacu ke form
        var actionUrl = $(this).attr('action'); // Ambil URL dari atribut action form


        $.ajax({
            url: actionUrl,
            method: 'POST', 
            data: formData,
            processData: false, 
            contentType: false, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                clearValidationErrors();
                // Anda bisa tambahkan loading spinner di sini
                $('button[type="submit"]').prop('disabled', true).text('Submiting...');
            },
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message, 'Success');

                    // Redirect ke halaman yang ditentukan server

                   setTimeout(function() {
                        window.location.href = response.redirect_url;
                    }, 800);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('button[type="submit"]').prop('disabled', false).text('Update');
                if (jqXHR.status === 422) { // Error validasi dari Laravel
                    var errors = jqXHR.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key).addClass('is-invalid');
                        $('#' + key + '_error').text(value[0]).show(); // Tampilkan error pertama untuk field tsb

                        // Khusus untuk Quill
                        if (key === 'long_description') {
                            $('#long_description_editor').addClass('is-invalid');
                        }
                    });
                    toastr.error('Please check the form for errors.', 'Validation Error');
                } else {
                    toastr.error('An unexpected error occurred: ' + errorThrown, 'Error');
                }
            }
        });
    });


    // Preview image (untuk Feature Image)
    $('#post_image').change(function(e){
        if (e.target.files && e.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e_onLoad){
                $('#imagePreview').attr('src', e_onLoad.target.result);
            }
            reader.readAsDataURL(e.target.files[0]);
        } else {
            $('#imagePreview').attr('src', '{{ url("upload/no_image.jpg") }}');
        }
    });

});
</script>
@endsection