@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>


<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Smtp Setting</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Smtp Setting</h5>
            <form method="post" enctype="multipart/form-data" action="{{route('smtp.update')}}" class="row g-3">
               @csrf
                <input type="hidden" name="id" value="{{$smtp->id}}" >
                 <div class="col-md-6">
                        <div class="mb-3">
                            <label for="mailer" class="form-label">Mailer</label>
                            <input type="text" class="form-control" name="mailer" id="mailer" value="{{$smtp->mailer}}">
                       </div>
                       
                    </div>
                 <div class="col-md-6">
                        <div class="mb-3">
                            <label for="host" class="form-label">Host</label>
                            <input type="text" class="form-control" name="host" id="host" value="{{$smtp->host}}">
                       </div>
                       
                    </div>
                 <div class="col-md-6">
                        <div class="mb-3">
                            <label for="port" class="form-label">Port</label>
                            <input type="text" class="form-control" name="port" id="port" value="{{$smtp->port}}">
                       </div>
                       
                    </div>
                 <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="username" value="{{$smtp->username}}">
                       </div>
                       
                    </div>
                 <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="text" class="form-control" name="password" id="password" value="{{$smtp->password}}">
                       </div>
                       
                    </div>
                 <div class="col-md-6">
                        <div class="mb-3">
                            <label for="encryption" class="form-label">Encryption</label>
                            <input type="text" class="form-control" name="encryption" id="encryption" value="{{$smtp->encryption}}">
                       </div>
                       
                    </div>
                 <div class="col-md-6">
                        <div class="mb-3">
                            <label for="from_address" class="form-label">From Address</label>
                            <input type="text" class="form-control" name="from_address" id="from_address" value="{{$smtp->from_address}}">
                       </div>
                       
                    </div>
               
                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
   
    
</div>



@endsection