@extends('admin.admin_dashboard')
@section('admin')
    <!doctype html>
    <html lang="en">



    <body>
        <!--wrapper-->

        <!--start page wrapper -->
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">User Profile</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">User Profilep</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">

                </div>
            </div>
            <!--end breadcrumb-->
            <div class="container">
                <div class="main-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="{{ !empty($profileData->photo) ? url('upload/admin_images/' . $profileData->photo) : url('upload/no_image.jpg') }}"
                                            alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
                                        <div class="mt-3">
                                            <h4>{{ $profileData->name }}</h4>
                                            <p class="text-secondary mb-1">{{ $profileData->username }}</p>
                                            <p class="text-muted font-size-sm">{{ $profileData->email }}</p>
                                            <button class="btn btn-primary">Follow</button>
                                            <button class="btn btn-outline-primary">Message</button>
                                        </div>
                                    </div>
                                    <hr class="my-4" />
                                    <ul class="list-group list-group-flush">
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-globe me-2 icon-inline">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="2" y1="12" x2="22" y2="12">
                                                    </line>
                                                    <path
                                                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                                    </path>
                                                </svg>Website</h6>
                                            <span class="text-secondary">https://codervent.com</span>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <form action="{{ route('admin.profile.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Name</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="name" class="form-control "
                                                value="{{ $profileData->name }}" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">User Name</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="username" class="form-control"
                                                value="{{ $profileData->username }}" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Email</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="email" class="form-control"
                                                value="{{ $profileData->email }}" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Phone</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ $profileData->phone }}" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Address</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" name="address" class="form-control"
                                                value="{{ $profileData->address }}" />
                                        </div>
                                    </div>

                                    <div x-data="{ imagePreview: '{{ !empty($profileData->photo) ? url('upload/admin_images/' . $profileData->photo) : url('upload/no_image.jpg') }}' }">
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Profile Image</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="file" name="photo" class="form-control"
                                                    x-on:change="
                                                           const file = $event.target.files[0];
                                                           const reader = new FileReader();
                                                           reader.onload = (e) => {
                                                               imagePreview = e.target.result;
                                                           };

                                                           reader.readAsDataURL(file);
                                                       " />
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0"> </h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <img :src="imagePreview" alt="Admin"
                                                    class="rounded-circle p-1 bg-primary" width="80">
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="submit" class="btn btn-primary px-4" value="Save Changes" />
                                        </div>
                                    </div>
                                </div>
                                </form> 
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i
                class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © 2021. All right reserved.</p>
        </footer>
        </div>
        <!--end wrapper-->

        <!-- search modal -->
        <div class="modal" id="SearchModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
                <div class="modal-content">
                    <div class="modal-header gap-2">
                        <div class="position-relative popup-search w-100">
                            <input class="form-control form-control-lg ps-5 border border-3 border-primary" type="search"
                                placeholder="Search">
                            <span
                                class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-4"><i
                                    class='bx bx-search'></i></span>
                        </div>
                        <button type="button" class="btn-close d-md-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="search-list">
                            <p class="mb-1">Html Templates</p>
                            <div class="list-group">
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action active align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-angular fs-4'></i>Best Html Templates</a>
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-vuejs fs-4'></i>Html5 Templates</a>
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-magento fs-4'></i>Responsive Html5 Templates</a>
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-shopify fs-4'></i>eCommerce Html Templates</a>
                            </div>
                            <p class="mb-1 mt-3">Web Designe Company</p>
                            <div class="list-group">
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-windows fs-4'></i>Best Html Templates</a>
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-dropbox fs-4'></i>Html5 Templates</a>
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-opera fs-4'></i>Responsive Html5 Templates</a>
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-wordpress fs-4'></i>eCommerce Html Templates</a>
                            </div>
                            <p class="mb-1 mt-3">Software Development</p>
                            <div class="list-group">
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-mailchimp fs-4'></i>Best Html Templates</a>
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-zoom fs-4'></i>Html5 Templates</a>
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-sass fs-4'></i>Responsive Html5 Templates</a>
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-vk fs-4'></i>eCommerce Html Templates</a>
                            </div>
                            <p class="mb-1 mt-3">Online Shoping Portals</p>
                            <div class="list-group">
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-slack fs-4'></i>Best Html Templates</a>
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-skype fs-4'></i>Html5 Templates</a>
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-twitter fs-4'></i>Responsive Html5 Templates</a>
                                <a href="javascript:;"
                                    class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i
                                        class='bx bxl-vimeo fs-4'></i>eCommerce Html Templates</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end search modal -->




        <!-- Bootstrap JS -->
        <script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>
        <!--plugins-->
        <script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('backend/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
        <script src="{{ asset('backend/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
        <script src="{{ asset('backend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
        <!--app JS-->
        <script src="{{ asset('backend/assets/js/app.js') }}"></script>
    </body>

    </html>
@endsection
