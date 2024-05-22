@php
$vendata=Helper::getShopData(Auth::id());
@endphp
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                {{ date('Y') }} &copy; <a href="/">{{ $vendata['shop_name'] }}</a> - All rights Reserved
            </div>
            <!-- <div class="col-md-6">
                <div class="text-md-right footer-links d-none d-sm-block">
                    <a href="#">About Us</a>
                    <a href="#">Help</a>
                    <a href="#">Contact Us</a>
                </div>
            </div> -->
        </div>
    </div>
</footer>
<!-- end Footer -->