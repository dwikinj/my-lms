@php
    $setting = App\Models\SiteSetting::find(1);
@endphp
<div class="row align-items-center dashboard-copyright-content pb-4">
        <ul class="generic-list-item d-flex  align-items-center fs-14 justify-content-end">
            <li class="mx-2 mr-2 text-nowrap"><p class="copy-desc">{{ $setting->copyright }}</p></li>
            <li class="mx-2 text-nowrap"><a href="#">Terms & Conditions</a></li>
            <li class="mx-2 text-nowrap"><a href="#">Privacy Policy</a></li>
        </ul>
    </div><!-- end col-lg-6 -->
</div><!-- end row -->