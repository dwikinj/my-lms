<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('backend/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Admin</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        <li class="menu-label">Menus</li>

        @if (Auth::user()->can('category.menu'))
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-category'></i></div>
                <div class="menu-title">Manage Category</div>
            </a>
            <ul>
                @if (Auth::user()->can('category.all'))
                <li><a href="{{ route('all.category') }}"><i class='bx bx-radio-circle'></i>All Category</a></li>
                @endif
                @if (Auth::user()->can('subcategory.all'))
                <li><a href="{{ route('all.subcategory') }}"><i class='bx bx-radio-circle'></i>All SubCategory</a></li>
                @endif
            </ul>
        </li>
        @endif

        @if (Auth::user()->can('coupon.menu'))
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-purchase-tag-alt'></i></div>
                <div class="menu-title">Manage Coupon</div>
            </a>
            <ul>
                @if (Auth::user()->can('coupon.all'))
                <li><a href="{{ route('all.coupon') }}"><i class='bx bx-radio-circle'></i>All Coupon</a></li>
                @endif
            </ul>
        </li>
        @endif

        @if (Auth::user()->can('all.user.menu'))
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-group'></i></div>
                <div class="menu-title">Manage Users</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.all.user') }}"><i class='bx bx-radio-circle'></i>All Users</a></li>
                <li><a href="{{ route('admin.all.instructor') }}"><i class='bx bx-radio-circle'></i>All Instructors</a></li>
            </ul>
        </li>
        @endif

        @if (Auth::user()->can('blog.menu'))
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-news'></i></div>
                <div class="menu-title">Manage Blog</div>
            </a>
            <ul>
                <li><a href="{{ route('blog.category') }}"><i class='bx bx-radio-circle'></i>Blog Category</a></li>
                <li><a href="{{ route('blog.post') }}"><i class='bx bx-radio-circle'></i>Blog Post</a></li>
            </ul>
        </li>
        @endif
        
        @if (Auth::user()->can('order.menu'))
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-receipt'></i></div>
                <div class="menu-title">Manage Orders</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.pending.order') }}"><i class='bx bx-radio-circle'></i>Pending orders</a></li>
                <li><a href="{{ route('admin.confirm.order') }}"><i class='bx bx-radio-circle'></i>Confirm orders</a></li>
            </ul>
        </li>
        @endif

        @if (Auth::user()->can('review.menu'))
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-star'></i></div>
                <div class="menu-title">Manage Review</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.pending.review') }}"><i class='bx bx-radio-circle'></i>Pending Review</a></li>
                <li><a href="{{ route('admin.active.review') }}"><i class='bx bx-radio-circle'></i>Active Review</a></li>
            </ul>
        </li>
        @endif
        
        @if (Auth::user()->can('report.menu'))
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bar-chart-alt-2'></i></div>
                <div class="menu-title">Manage Report</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.report.view') }}"><i class='bx bx-radio-circle'></i>Report View</a></li>
            </ul>
        </li>
        @endif
        
        @if (Auth::user()->can('rolepermission.menu'))
        <li class="menu-label">Roles & Permissions</li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-shield-quarter"></i></div>
                <div class="menu-title">Roles & Permissions</div>
            </a>
            <ul>
                <li><a href="{{ route('all.permission') }}"><i class='bx bx-radio-circle'></i>All Permission</a></li>
                <li><a href="{{ route('all.roles') }}"><i class='bx bx-radio-circle'></i>All Roles</a></li>
                <li><a href="{{ route('add.roles.permission') }}"><i class='bx bx-radio-circle'></i>Role In Permission</a></li>
                <li><a href="{{ route('all.roles.permission') }}"><i class='bx bx-radio-circle'></i>All Role In Permission</a></li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-user-check"></i></div>
                <div class="menu-title">Manage Admin</div>
            </a>
            <ul>
                <li><a href="{{ route('all.admin') }}"><i class='bx bx-radio-circle'></i>All Admin</a></li>
            </ul>
        </li>
        @endif

        @if (Auth::user()->can('setting.menu'))
        <li class="menu-label">Settings</li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-cog'></i></div>
                <div class="menu-title">Manage Setting</div>
            </a>
            <ul>
                <li><a href="{{ route('site.setting') }}"><i class='bx bx-radio-circle'></i>Site Setting</a></li>
                <li><a href="{{ route('smtp.setting') }}"><i class='bx bx-radio-circle'></i>SMTP Setting</a></li>
            </ul>
        </li>
        @endif

        <li>
            <a href="https://themeforest.net/user/codervent" target="_blank">
                <div class="parent-icon"><i class="bx bx-support"></i></div>
                <div class="menu-title">Support</div>
            </a>
        </li>
    </ul>
</div>