@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Role In Permission</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                {{-- 1. Perbarui action form --}}
                <form method="post" id="myForm" action="{{ route('update.roles.permission', $selected_role->id) }}" class="row g-3">
                    @csrf

                    <div class="form-group">
                        <div class="mb-3 col-md-6">
                            {{-- 2. Tampilkan nama role, tidak perlu dropdown --}}
                            <label class="form-label">Role Name</label>
                            <h4>{{ $selected_role->name }}</h4>
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <div class="mt-3 form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckAll">
                                <label class="form-check-label" for="flexCheckAll">Permission All</label>
                            </div>
                        </div>

                        <hr>

                        @foreach ($permission_groups as $group)
                            <div class="row mb-2 p-2 rounded {{ $loop->odd ? 'bg-light' : '' }}">
                                <div class="col-3">
                                    @php
                                        // 3a. Cek apakah semua permission dalam grup ini sudah dimiliki role
                                        $permissionsInGroup = App\Models\User::getpermissionByGroupName($group->group_name);
                                        $groupHasAllPermissions = $permissionsInGroup->every(function ($permission) use ($rolePermissions) {
                                            return in_array($permission->id, $rolePermissions);
                                        });
                                    @endphp
                                    <div class="form-check">
                                        <input class="form-check-input group-checkbox" type="checkbox" id="group_permission_{{ $group->id }}" {{ $groupHasAllPermissions ? 'checked' : '' }}>
                                        <label class="form-check-label" for="group_permission_{{ $group->id }}">{{ $group->group_name }}</label>
                                    </div>
                                </div>

                                <div class="col-9">
                                    @foreach ($permissionsInGroup as $permission)
                                        <div class="form-check">
                                            {{-- 3b. Cek permission individual --}}
                                            <input class="form-check-input" type="checkbox" id="permission{{ $permission->id }}" name="permission[]" value="{{ $permission->id }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission{{ $permission->id }}">{{$permission->name}}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        @error('permission')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror

                        <div class="col-md-12 mt-3">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 4. Perbarui JavaScript --}}
    <script type="text/javascript">
        // Fungsi untuk memeriksa semua checkbox dan menyinkronkan "Permission All"
        function checkIfAllAreChecked() {
            var totalCheckboxes = $('input[name="permission[]"]').length;
            var checkedCheckboxes = $('input[name="permission[]"]:checked').length;
            
            if (totalCheckboxes === checkedCheckboxes) {
                $('#flexCheckAll').prop('checked', true);
            } else {
                $('#flexCheckAll').prop('checked', false);
            }
        }

        $(document).ready(function() {
            // Jalankan pengecekan saat halaman selesai dimuat
            checkIfAllAreChecked();

            // Fungsi untuk "Permission All"
            $('#flexCheckAll').on('click', function() {
                var isChecked = $(this).prop('checked');
                $('input[type="checkbox"]').prop('checked', isChecked);
            });

            // Fungsi untuk checkbox per grup
            $('.group-checkbox').on('click', function() {
                var isChecked = $(this).prop('checked');
                $(this).closest('.row').find('.col-9 input[type="checkbox"]').prop('checked', isChecked);
                checkIfAllAreChecked();
            });

            // Fungsi untuk memantau checkbox individual
            $('input[name="permission[]"]').on('click', function(){
                // Cek status grup
                var row = $(this).closest('.row');
                var totalInGroup = row.find('.col-9 input[type="checkbox"]').length;
                var checkedInGroup = row.find('.col-9 input[type="checkbox"]:checked').length;
                
                if(totalInGroup === checkedInGroup){
                    row.find('.group-checkbox').prop('checked', true);
                } else {
                    row.find('.group-checkbox').prop('checked', false);
                }

                // Cek status "Permission All"
                checkIfAllAreChecked();
            });
        });
    </script>
@endsection