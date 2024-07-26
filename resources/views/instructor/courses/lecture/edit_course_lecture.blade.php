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
                        <li class="breadcrumb-item active" aria-current="page">Edit Lecture</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Edit lecture</h5>
                <form method="POST" x-data="lectureForm()" @submit.prevent="submitForm" action="{{ route('update.course.lecture') }}"
                    class="row g-3">
                    @csrf
                    <input type="hidden" name="id" value="{{ $lecture->id }}">
                    <input type="hidden" name="course_id" value="{{ $lecture->course_id }}">
                    <input type="hidden" name="course_section_id" value="{{ $lecture->course_section_id }}">
                    <div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="lecture_title" class="form-label">Lecture Title</label>
                                <input type="text" class="form-control" name="lecture_title" id="lecture_title"
                                    value="{{ $lecture->lecture_title }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="url" class="form-label">Url</label>
                                <input type="text" class="form-control" name="url" id="url"
                                    value="{{ $lecture->url }}">
                            </div>

                            <div x-data="lectureContent('{{ $lecture->content }}')" x-init="initContent()" class="mb-3">
                                <label for="description" class="form-label">Content</label>
                                <textarea name="content" id="description" x-ref="contentArea" class="form-control mt-2" rows="10"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Submit</button>
                            <a href="{{ route('add.course.lecture', $lecture->course_id) }}" class="btn btn-secondary px-4">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>

    <script>
        function lectureContent(initialContent) {
            return {
                content: initialContent,
                initContent() {
                    this.$nextTick(() => {
                        this.$refs.contentArea.value = this.content;
                        if (tinymce.get('description')) {
                            tinymce.get('description').setContent(this.content);
                        }
                    });
                }
            }
        }

        function lectureForm() {
            return {
                submitForm(event) {
                    // Ambil konten dari TinyMCE
                    const content = tinymce.get('description').getContent();

                    // Tambahkan konten ke form
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'content';
                    hiddenInput.value = content;
                    event.target.appendChild(hiddenInput);

                    // Submit form
                    event.target.submit();
                }
            }
        }
    </script>
@endsection
