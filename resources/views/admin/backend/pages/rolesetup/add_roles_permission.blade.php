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
                        <li class="breadcrumb-item active" aria-current="page">Add Role In Permission</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <form method="post" id="myForm" action="{{ route('store.roles.permission') }}" class="row g-3">
                    @csrf


                    <div class="form-group">
                        <div class="mb-3 col-md-6">
                            <label for="role_id" class="form-label">Roles Name</label>
                            <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id">
                                <option value="" selected disabled>Choose Role</option>

                                @if (isset($roles) && $roles->count() > 0)
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('roles') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No roles available</option>
                                @endif
                            </select>
                            @error('role_id')
                                <span class="text-danger mt-1">{{ $message }}</span>
                            @enderror


                            <div class="mt-3 form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckAll">
                                <label class="form-check-label" for="flexCheckAll">
                                    Permission All
                                </label>
                                @error('permission')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>

                        <hr>

                        @foreach ($permission_groups as $group)
                            <div class="row mb-2 p-2 rounded {{ $loop->odd ? 'bg-light' : '' }}">
                                <div class="col-3">
                                    <div class="form-check">
                                        <input class="form-check-input group-checkbox" type="checkbox"
                                            id="group_permission_{{ $group->id }}">
                                        <label class="form-check-label"
                                            for="group_permission_{{ $group->id }}">{{ $group->group_name }}</label>
                                    </div>
                                </div>

                                <div class="col-9">
                                    @php
                                        $permissions = App\Models\User::getpermissionByGroupName($group->group_name);
                                    @endphp

                                    @foreach ($permissions as $permission)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                id="permission{{ $permission->id }}" name="permission[]"
                                                value="{{ $permission->id }}">
                                            <label class="form-check-label"
                                                for="permission{{ $permission->id }}">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach





                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4">Save Change</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // --- Fungsi untuk "Permission All" ---
            $('#flexCheckAll').on('click', function() {
                // Cek apakah #flexCheckAll sedang dicentang atau tidak
                var isChecked = $(this).prop('checked');
                
                // Set semua checkbox lain (yang memiliki name 'permission[]' atau class 'group-checkbox')
                // sesuai dengan status #flexCheckAll
                $('input[type="checkbox"]').prop('checked', isChecked);
            });

            // --- Fungsi untuk checkbox per grup (Fitur Bonus) ---
            $('.group-checkbox').on('click', function() {
                // Cek status centang dari checkbox grup yang diklik
                var isChecked = $(this).prop('checked');
                
                // Cari semua input checkbox di dalam div.col-9 yang satu baris dengan grup ini,
                // lalu set status centangnya.
                $(this).closest('.row').find('.col-9 input[type="checkbox"]').prop('checked', isChecked);

                // Panggil fungsi untuk memeriksa apakah "Permission All" perlu dicentang/dihilangkan centangnya
                checkIfAllAreChecked();
            });

            // --- Fungsi untuk memantau checkbox individual ---
            // Jika satu saja permission tidak dicentang, maka "Permission All" tidak boleh tercentang.
            $('input[name="permission[]"]').on('click', function(){
                checkIfAllAreChecked();
            });


            // --- Fungsi Pembantu untuk sinkronisasi "Permission All" ---
            function checkIfAllAreChecked() {
                var totalCheckboxes = $('input[name="permission[]"]').length + $('.group-checkbox').length;
                // Hitung jumlah yang tercentang
                var checkedCheckboxes = $('input[name="permission[]"]:checked').length + $('.group-checkbox:checked').length;
                
                if (totalCheckboxes === checkedCheckboxes) {
                    $('#flexCheckAll').prop('checked', true);
                } else {
                    $('#flexCheckAll').prop('checked', false);
                }
            }

        });
    </script>

@endsection
