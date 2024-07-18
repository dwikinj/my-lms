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
                    <li class="breadcrumb-item active" aria-current="page">Edit Category</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Edit Category</h5>
            <form method="post" enctype="multipart/form-data" action="{{route('update.category')}}" class="row g-3">
               @csrf
                <input type="hidden" name="id" value="{{$category->id}}" >

                <div x-data="{
                    imageUrl: '{{ asset($category->image) }}',
                    fileChosen(event) {
                        if (event.target.files.length > 0) {
                            const file = event.target.files[0];
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.imageUrl = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                }">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="category_name" id="category_name" value="{{$category->category_name}}">
                       </div>
                       
                    </div>
                
                    
                
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            <input class="form-control" name="image" type="file" id="image" @change="fileChosen">
                        </div>
                    </div>

                    <div class="col-md-12 mt-1">
                        <img :src="imageUrl" alt="Category Preview" class="rounded-circle p-1 bg-primary" width="110">
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