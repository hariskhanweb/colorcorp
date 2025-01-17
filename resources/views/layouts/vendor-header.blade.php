<!-- Topbar Start -->
@php
$vendata=Helper::getShopData(Auth::id());
@endphp
<div class="navbar-custom">
    <ul class="float-right mb-0 list-unstyled topnav-menu">
        <li class="dropdown notification-list">
            <a class="mr-0 nav-link dropdown-toggle nav-user waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                @php
                $sessingpath=$vendata['shop_logo'];
                @endphp
                <i class="fe-user noti-icon"></i>
                <span class="ml-1 pro-user-name">
                    {{ $vendata['shop_name'] }} <i class="mdi mdi-chevron-down"></i>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                <!-- item-->
                <div class="dropdown-item noti-title">
                    <h6 class="m-0">
                        Welcome !
                    </h6>
                </div>

                <!-- item-->
                <a href="{{ route('vendor.myaccount', ['vendor_name' => $vendata['shop_url_slug']]) }}" class="dropdown-item notify-item">
                    <i class="dripicons-user"></i>
                    <span>My Account</span>
                </a>

                <!-- item-->
                <a href="{{ route('vendor.resetpassword', ['vendor_name' => $vendata['shop_url_slug']]) }}" class="dropdown-item notify-item">
                    <i class="dripicons-lock"></i>
                    <span>Change Password</span>
                </a>


                <!-- item-->

                <a href="{{ route('vendor.shopSetting.edit', ['vendor_name' => $vendata['shop_url_slug']]) }}" class="dropdown-item notify-item">
                    <i class="dripicons-gear"></i>
                    <span>Settings</span>
                </a>

                

                <div class="dropdown-divider"></div>

                <!-- item-->
                <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                    <i class="dripicons-power"></i>
                    <span>Logout</span>
                </a>

            </div>
        </li>

        <li class="dropdown notification-list">
            <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                <i class="fe-settings noti-icon"></i>
            </a>
        </li>

    </ul>    
    <ul class="mb-0 list-unstyled menu-left">
        <li class="float-left">
            <button class="button-menu-mobile open-left disable-btn">
                <i class="fe-menu"></i>
            </button>
        </li>        
    </ul>
</div>
<!-- end Topbar -->