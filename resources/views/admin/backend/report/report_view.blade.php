@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Report</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4">
                        <form method="post" id="dateForm" action="{{ route('search.by.date') }}" class="row g-3">
                            @csrf
                            <div class="form-group col-md-12">
                                <label for="date" class="form-label">Search By Date</label>
                                <input type="date" class="form-control" name="date" id="date">
                            </div>

                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form method="post" id="monthForm" action="{{ route('search.by.month') }}" class="row g-3">
                            @csrf
                            <div class="form-group col-md-6">
                                <label class="form-label">Search By Month</label>
                                <select name="month" class="form-select mb-3 @error('month') is-invalid @enderror"
                                    aria-label="Month selection">
                                    <option value="" selected disabled>Select a month</option>
                                    <option value="January" @selected(old('month') == 'January')>January</option>
                                    <option value="February" @selected(old('month') == 'February')>February</option>
                                    <option value="March" @selected(old('month') == 'March')>March</option>
                                    <option value="April" @selected(old('month') == 'April')>April</option>
                                    <option value="May" @selected(old('month') == 'May')>May</option>
                                    <option value="June" @selected(old('month') == 'June')>June</option>
                                    <option value="July" @selected(old('month') == 'July')>July</option>
                                    <option value="August" @selected(old('month') == 'August')>August</option>
                                    <option value="September" @selected(old('month') == 'September')>September</option>
                                    <option value="October" @selected(old('month') == 'October')>October</option>
                                    <option value="November" @selected(old('month') == 'November')>November</option>
                                    <option value="December" @selected(old('month') == 'December')>December</option>
                                </select>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label">Search By Year</label>
                                <select name="year" class="form-select mb-3 @error('year') is-invalid @enderror"
                                    aria-label="Year selection">
                                    <option value="" selected disabled>Select a year</option>
                                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                        <option value="{{ $i }}" @selected(old('year') == $i)>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form method="post" id="yearForm" action="{{ route('search.by.year') }}" class="row g-3">
                            @csrf
                            <div class="form-group col-md-12">
                                <label class="form-label">Search By Year</label>
                                <select name="year_name" class="form-select mb-3 @error('year_name') is-invalid @enderror"
                                    aria-label="Year selection">
                                    <option value="" selected disabled>Select a year</option>
                                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                        <option value="{{ $i }}" @selected(old('year_name') == $i)>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                                @error('year_name')
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
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // Date form validation
            $('#dateForm').validate({
                rules: {
                    date: {
                        required: true,
                        date: true
                    }
                },
                messages: {
                    date: {
                        required: 'Please select a date',
                        date: 'Please enter a valid date'
                    }
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
                }
            });

            // Month form validation
            $('#monthForm').validate({
                rules: {
                    month: {
                        required: true
                    },
                    year: {
                        required: true
                    }
                },
                messages: {
                    month: {
                        required: 'Please select a month'
                    },
                    year: {
                        required: 'Please select a year'
                    }
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
                }
            });

            // Year form validation
            $('#yearForm').validate({
                rules: {
                    year_name: {
                        required: true
                    }
                },
                messages: {
                    year_name: {
                        required: 'Please select a year'
                    }
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
                }
            });
        });
    </script>
@endsection
