@php
    $id = Auth::user()->id;
    $instructorId = App\Models\User::find($id);
    $status = $instructorId->status;
@endphp

<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('backend/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Instructor</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('instructor.dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        @if ($status === '1')
            <li class="menu-label">Course Manage</li>

            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-cart'></i>
                    </div>
                    <div class="menu-title">Course Manage</div>
                </a>
                <ul>
                    <li> <a href="{{ route('all.course') }}"><i class='bx bx-radio-circle'></i>All Course</a>
                    </li>


                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class='bx bx-receipt'></i>
                    </div>
                    <div class="menu-title">All Orders</div>
                </a>
                <ul>
                    <li> <a href="{{ route('instructor.all.order') }}"><i class='bx bx-radio-circle'></i>All Orders</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class='bx bx-help-circle'></i>
                    </div>
                    <div class="menu-title">All Question</div>
                </a>
                <ul>
                    <li> <a href="{{ route('instructor.all.question') }}"><i class='bx bx-radio-circle'></i>All
                            Question</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class='bx bxs-discount'></i>
                    </div>
                    <div class="menu-title">Manage Coupon</div>
                </a>
                <ul>
                    <li> <a href="{{ route('instructor.all.coupon') }}"><i class='bx bx-radio-circle'></i>All
                            Coupon</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class='bx bx-comment-detail'></i>
                    </div>
                    <div class="menu-title">Manage Review</div>
                </a>
                <ul>
                    <li> <a href="{{ route('instructor.active.review') }}"><i class='bx bx-radio-circle'></i>All
                            Review</a>
                    </li>
                </ul>
            </li>


            <li class="menu-label">Support</li>
        @endif

        <li>
            <a href="#" target="_blank">
                <div class="parent-icon"><i class="bx bx-support"></i>
                </div>
                <div class="menu-title">Support</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>
