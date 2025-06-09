@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Admin</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Add New Admin</h5>
                <form method="post" id="myForm" action="{{ route('store.admin') }}" class="row g-3">
                    @csrf
                    {{-- Username --}}
                    <div class="form-group col-md-6">
                        <label for="username" class="form-label">Admin Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Name --}}
                    <div class="form-group col-md-6">
                        <label for="name" class="form-label">Admin Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group col-md-6">
                        <label for="email" class="form-label">Admin Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="form-group col-md-6">
                        <label for="phone" class="form-label">Admin Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="form-group col-md-6">
                        <label for="address" class="form-label">Admin Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group col-md-6">
                        <label for="password" class="form-label">Admin Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Roles --}}
                    <div class="form-group col-md-6">
                        <label for="roles" class="form-label">Role Name</label>
                        <select class="form-select @error('roles') is-invalid @enderror" id="roles" name="roles">
                            <option selected value="" disabled>Choose Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ old('roles') == $role->name ? 'selected' : '' }}>
                                    {{$role->name}}
                                </option>
                            @endforeach
                        </select>
                        @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
@endsection