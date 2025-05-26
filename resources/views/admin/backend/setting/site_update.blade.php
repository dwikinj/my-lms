@extends('admin.admin_dashboard')
@section('admin')

{{-- Pastikan jQuery sudah di-load sebelum script custom --}}
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> --}}
{{-- Jika jQuery sudah ada di admin_dashboard, baris di atas mungkin tidak perlu --}}

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Site Setting</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Site Setting</h5>
            {{-- Tambahkan ID pada form --}}
            <form id="siteSettingForm" method="post" enctype="multipart/form-data" action="{{route('site.setting.update')}}" class="row g-3">
               @csrf
               @method('PUT') {{-- Tetap diperlukan untuk routing Laravel --}}

                <input type="hidden" name="id" value="{{$setting->id}}" >

                 <div class="col-md-6">
                    <div class="mb-3">
                        <img id="logoPreview"
                             src="{{ !empty($setting->logo) ? asset('upload/admin_images/' . $setting->logo) : asset('upload/no_image.jpg') }}"
                             alt="Logo Preview"
                             class="img-thumbnail"
                             style="max-width: 200px; max-height: 150px; margin-top: 10px; {{ empty($setting->logo) ? 'display:none;' : '' }}"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control" name="logo" id="logo" accept="image/*">
                        <span class="text-danger error-text" id="error-logo"></span>
                    </div>
                </div>

                 <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="phone" value="{{$setting->phone}}">
                            <span class="text-danger error-text" id="error-phone"></span>
                       </div>
                    </div>
                 <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" value="{{$setting->email}}">
                            <span class="text-danger error-text" id="error-email"></span>
                       </div>
                    </div>
                 <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" id="address" value="{{$setting->address}}">
                            <span class="text-danger error-text" id="error-address"></span>
                       </div>
                    </div>
                 <div class="col-md-6">
                        <div class="mb-3">
                            <label for="facebook" class="form-label">Facebook</label>
                            <input type="text" class="form-control" name="facebook" id="facebook" value="{{$setting->facebook}}">
                            <span class="text-danger error-text" id="error-facebook"></span>
                       </div>
                    </div>
                 <div class="col-md-6">
                        <div class="mb-3">
                            <label for="twitter" class="form-label">Twitter</label>
                            <input type="text" class="form-control" name="twitter" id="twitter" value="{{$setting->twitter}}">
                            <span class="text-danger error-text" id="error-twitter"></span>
                       </div>
                    </div>
                 <div class="col-md-6">
                        <div class="mb-3">
                            <label for="copyright" class="form-label">Copyright</label>
                            <input type="text" class="form-control" name="copyright" id="copyright" value="{{$setting->copyright}}">
                            <span class="text-danger error-text" id="error-copyright"></span>
                       </div>
                    </div>

                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4" id="saveChangesBtn">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script jQuery untuk preview gambar dan AJAX submit --}}
<script type="text/javascript">
    $(document).ready(function(){
        // Script preview logo (sudah ada dan baik)
        $('#logo').change(function(e){
            if (e.target.files && e.target.files[0]) {
                var reader = new FileReader();
                reader.onload = function(event){
                    $('#logoPreview').attr('src', event.target.result).show();
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // AJAX Form Submission
        $('#siteSettingForm').on('submit', function(e){
            e.preventDefault(); // Mencegah submit form standar

            var formData = new FormData(this);
            var submitButton = $('#saveChangesBtn');
            var originalButtonText = submitButton.html();

            // Reset pesan error sebelumnya
            $('.error-text').text('');
            $('.form-control').removeClass('is-invalid'); // Hapus kelas error jika ada

            submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST', 
                data: formData,
                processData: false, // Penting untuk FormData
                contentType: false, // Penting untuk FormData
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Jika ada meta csrf, jika tidak FormData sudah bawa _token
                },
                success: function(response){
                    if(response.success){
                        toastr.success(response.message);
                        // Opsional: Jika logo berubah, update src preview dari respons server
                        if(response.new_logo_url){
                            $('#logoPreview').attr('src', response.new_logo_url);
                        }
                        $('#logo').val(''); // Bersihkan input file setelah sukses
                    } else {
                        toastr.error(response.message || 'An unexpected error occurred.');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    if (jqXHR.status === 422) { // Error validasi dari Laravel
                        var errors = jqXHR.responseJSON.errors;
                        $.each(errors, function(key, value){
                            $('#error-' + key).text(value[0]); // Tampilkan pesan error pertama
                            $('#' + key).addClass('is-invalid'); // Tambah kelas error pada input
                        });
                        toastr.error(jqXHR.responseJSON.message || 'Validation failed!');
                    } else {
                        toastr.error('Error: ' + (jqXHR.responseJSON.message || errorThrown));
                    }
                },
                complete: function(){
                    submitButton.prop('disabled', false).html(originalButtonText);
                }
            });
        });
    });
</script>
@endsection