@extends('instructor.instructor_dashboard')
@section('instructor')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content" x-data="courseManager()">

        <div class="row">
            <div class="col-12 ">
                <h6 class="mb-0 text-uppercase">Basic Media Object</h6>
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset($course->course_image) }}" class="rounded-circle p-1 border" width="90"
                                height="90" alt="...">
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mt-0">{{ $course->course_name }}</h5>
                                <p class="mb-0">{{ $course->course_title }}</p>
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">Add Section</button>

                        </div>
                    </div>
                </div>

                {{-- Add section and lecture --}}
                @foreach ($course->courseSections as $key => $section)
                    <div class="container">
                        <div class="main-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body p-4 d-flex justify-content-between">
                                            <h6>{{ $section->section_title }}</h6>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a class="btn btn-primary ms-auto" title="Add Lecture"
                                                    @click="addLecture({{ $course->id }}, {{ $section->id }}, {{ $key }})">
                                                    <i class="fadeIn animated bx bx-folder-plus"></i>
                                                </a>&nbsp;
                                                <button type="submit" class="btn btn-danger  ms-auto"
                                                    title="Delete Section"><i
                                                        class="fadeIn animated bx bx-trash"></i></button>
                                            </div>
                                        </div>

                                        <div class="courseHide" id="lectureContainer{{ $key }}">
                                            <div class="container">
                                                @foreach ($section->courseLectures as $lecture)
                                                    <div
                                                        class="lectureDiv mb-3 d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <strong>{{ $loop->iteration }} .
                                                                {{ $lecture->lecture_title }}</strong>
                                                        </div>
                                                        <div class="btn-group">
                                                            <a href="" class="btn btn-sm btn-primary">Edit</a>&nbsp;
                                                            <a href="" class="btn btn-sm btn-danger">Delete</a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div x-show="isFormVisible({{ $key }})" class="container mb-3">
                                            <h6>Lecture Title</h6>
                                            <input type="text" name="lecture_title" x-model="newLecture.title"
                                                class="form-control mb-3" placeholder="Enter Lecture Title">

                                            <h6>Lecture Content</h6>
                                            <textarea name="content" id="description" x-model="newLecture.content" class="form-control mt-2" cols="30"
                                                rows="10"></textarea>

                                            <h6 class="mt-3">Add Video Url</h6>
                                            <input type="text" name="url" x-model="newLecture.url"
                                                class="form-control" placeholder="Add Url">

                                            <button class="btn btn-primary mt-3"
                                                x-on:click="saveLecture({{ $course->id }}, {{ $section->id }}, {{ $key }})">Save
                                                Lecture</button>
                                            <button class="btn btn-secondary mt-3"
                                                @click="cancelLecture({{ $key }})">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach



                {{-- End section and lecture --}}



            </div>
        </div>
    </div>
    {{-- modal --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('add.course.section') }}" method="post">
                        @csrf

                        <input type="hidden" name="id" value="{{ $course->id }}">

                        <div class="form-group ">
                            <div class="mb-3">
                                <label for="section_title" class="form-label">Course Section</label>
                                <input type="text" class="form-control" name="section_title" id="section_title"
                                    placeholder="e.q. Section 1: Project Setup...">
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    {{-- modal end --}}



    <script>
        function courseManager() {
            return {
                showLectureForm: {

                },
                newLecture: {
                    title: '',
                    content: '',
                    url: ''
                },
                saveLectureRoute: '{{ route('save.course.lecture') }}',

                isFormVisible(index) {
                    return this.showLectureForm[index] === true;
                },

                addLecture(courseId, sectionId, index) {
                    this.showLectureForm[index] = true;

                },

                async saveLecture(courseId, sectionId, index) {
                    if (tinymce.get('description')) {
                        this.newLecture.content = tinymce.get('description').getContent();
                    }

                    const lectureData = {
                        course_id: courseId,
                        course_section_id: sectionId,
                        lecture_title: this.newLecture.title,
                        url: this.newLecture.url,
                        content: this.newLecture.content,
                    }

                    try {
                        const response = await fetch(this.saveLectureRoute, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(lectureData)
                        });

                        const result = await response.json();
                        if (result.status === 'success') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: result.message,
                                showConfirmButton: false,
                                timer: 3000
                            });

                            this.cancelLecture(index);

                            window.location.reload();
                        } else {
                            throw new Error(result.message || 'An error occurred');
                        }
                    } catch (error) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: error.message,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }

                    this.cancelLecture(index);
                },

                cancelLecture(index) {
                    this.showLectureForm[index] = false;
                    this.newLecture = {
                        title: '',
                        content: '',
                        url: ''
                    };
                    tinymce.get('description').setContent('');
                }
            }
        }
    </script>
@endsection
