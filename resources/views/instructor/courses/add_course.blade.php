@extends('instructor.instructor_dashboard')
@section('instructor')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Course</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Add New Course</h5>
                <form method="post" id="myForm" enctype="multipart/form-data" action="{{ route('store.course') }}"
                    class="row g-3">
                    @csrf
                    <div x-data="{
                        imageUrl: '{{ url('upload/no_image.jpg') }}',
                        fileChosen: fileChosen,
                    }">
                        <div class="form-group col-md-6">
                            <div class="mb-3">
                                <label for="course_name" class="form-label">Course Name</label>
                                <input type="text" class="form-control" name="course_name" id="course_name"
                                    placeholder="Course Name">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="mb-3">
                                <label for="course_title" class="form-label">Course Title</label>
                                <input type="text" class="form-control" name="course_title" id="course_title"
                                    placeholder="Course Title">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="mb-3">
                                <label for="image" class="form-label">Course Image</label>
                                <input class="form-control" name="image" type="file" id="image"
                                    @change="fileChosen">
                            </div>
                        </div>

                        <div class="col-md-12 mt-1">
                            <img :src="imageUrl" alt="Category Preview" class="rounded-circle p-1 bg-primary"
                                width="110">
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="mb-3">
                            <label for="video" class="form-label">Course Intro Video</label>
                            <input type="file" class="form-control" name="video" id="video"
                                accept="video/mp4,video/webm">
                        </div>
                    </div>



                    <div class="row" x-data="categorySelection()">
                        <div class="form-group col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Course Category</label>
                                <select class="form-select" id="category_id" name="category_id" x-model="selectedCategory"
                                    @change="loadSubcategories()">
                                    <option value="">Choose Category</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="mb-3">
                                <label for="subcategory_id" class="form-label">Course Subcategory</label>
                                <select class="form-select" id="subcategory_id" name="subcategory_id"
                                    x-model="selectedSubcategory">
                                    <template x-for="subcategory in subcategories" :key="subcategory.id">
                                        <option x-bind:value="subcategory.id" x-text="subcategory.subcategory_name">
                                        </option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="mb-3">
                            <label for="certificate" class="form-label">Certificate Avaible</label>
                            <select class="form-select" id="certificate" name="certificate">
                                <option selected disabled>Open this select menu</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="mb-3">
                            <label for="label" class="form-label">Course Label</label>
                            <select class="form-select" id="label" name="label">
                                <option value="Begginer">Begginer</option>
                                <option value="Middle">Middle</option>
                                <option value="Advance">Advance</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <div class="mb-3">
                            <label for="selling_price" class="form-label">Course Price</label>
                            <input type="text" class="form-control" name="selling_price" id="selling_price">
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <div class="mb-3">
                            <label for="discount_price" class="form-label">Discount Price</label>
                            <input type="text" class="form-control" name="discount_price" id="discount_price">
                        </div>
                    </div>


                    <div class="form-group col-md-3">
                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration</label>
                            <input type="text" class="form-control" name="duration" id="duration">
                        </div>
                    </div>


                    <div class="form-group col-md-3">
                        <div class="mb-3">
                            <label for="resources" class="form-label">Resources</label>
                            <input type="text" class="form-control" name="resources" id="resources">
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="mb-3">
                            <label for="prerequisites" class="form-label">Course Prerequisites</label>
                            <textarea class="form-control" name="prerequisites" id="prerequisites" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Course Description</label>
                            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                        </div>
                    </div>

                    <p>Course Goals</p>


                    <!--   //////////// Goal Option /////////////// -->

                    <div x-data="goalManager()">
                        <div class="row add_item">
                            <template x-for="(goal, index) in goals" :key="index">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label :for="'goals_' + index" class="form-label">Goals</label>
                                            <input type="text" :name="'course_goals[]'" :id="'goals_' + index" class="form-control" placeholder="Goals" x-model="goal.value">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6" style="padding-top: 30px;">
                                        <button type="button" class="btn btn-success" @click="addGoal" x-show="index === goals.length - 1">
                                            <i class="fa fa-plus-circle"></i> Add More..
                                        </button>
                                        <button type="button" class="btn btn-danger" @click="removeGoal(index)" x-show="goals.length > 1">
                                            <i class="fa fa-minus-circle"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!--   ////////////End Goal Option /////////////// -->


                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bestseller" value="1"
                                    id="bestseller">
                                <label class="form-check-label" for="bestseller">Best Seller</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="featured" value="1"
                                    id="featured">
                                <label class="form-check-label" for="featured">Featured</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" name="highestarted" type="checkbox" value="1"
                                    id="highestarted">
                                <label class="form-check-label" for="highestarted">Highest Rated</label>
                            </div>
                        </div>
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

    <script>
        function goalManager() {
            return {
                goals: [{ value: '' }],
                addGoal() {
                    this.goals.push({ value: '' });
                },
                removeGoal(index) {
                    this.goals.splice(index, 1);
                },
                
            }
        }
    </script>

    <script type="text/javascript">
        function categorySelection() {
            return {
                selectedCategory: '',
                selectedSubcategory: '',
                subcategories: [],
                loadSubcategories() {
                    if (this.selectedCategory) {
                        fetch(`{{ url('/subcategory/ajax') }}/${this.selectedCategory}`)
                            .then(response => response.json())
                            .then(data => {
                                this.subcategories = data;
                                this.selectedSubcategory = '';
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    } else {
                        this.subcategories = [];
                        this.selectedSubcategory = '';
                    }
                }
            }
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#myForm').validate({
                rules: {
                    course_name: {
                        required: true,
                    },
                    course_title: {
                        required: true,
                    },
                    video:{
                        required: true
                    },
                    category_id:{
                        required: true
                    },
                    subcategory_id:{
                        required: true
                    },
                    label:{
                        required: true
                    },
                    selling_price:{
                        required: true
                    },
                   

                },
                messages: {
                    course_name: {
                        required: 'Please Enter Course Name',
                    },
                    course_title: {
                        required: 'Please Enter Course Title',
                    },
                    video: {
                        required: 'Please Input Course Video intro',
                    },
                    category_id: {
                        required: 'Please Input Course Category',
                    },
                    subcategory_id: {
                        required: 'Please Input Course SubCategory',
                    },
                    label: {
                        required: 'Please Input Course Label',
                    },
                    selling_price: {
                        required: 'Please Input Course Price',
                    },
                   


                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });

        function fileChosen(event) {
            if (event.target.files.length > 0) {
                const file = event.target.files[0];
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
