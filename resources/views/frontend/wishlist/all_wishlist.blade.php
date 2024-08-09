@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')
    <div class="section-block mb-5"></div>
    <div class="dashboard-heading mb-5">
        <h3 class="fs-22 font-weight-semi-bold">My Bookmarks</h3>
    </div>

    <h3 id="empty-wishlist">You doesn't have wishlisted courses</h3>

    <div class="row" id="wishlist">


    </div><!-- end row -->
    <div class="text-center py-3" id="pagination-wishlist">

    </div>
@endsection
