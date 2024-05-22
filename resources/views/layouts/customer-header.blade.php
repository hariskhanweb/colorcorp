<!-- Topbar Start -->
@php
  $vendata = Helper::getShopData(Auth::user()->vendor_id);
  $cart_count = Helper::getCartData(Auth::id());
  $user_logo = Helper::getVendorLogo(Auth::user()->vendor_id);
 
  $top_menu_option = [];
  if(isset($page->top_menu_option)) {
    $top_menu_option = json_decode($page->top_menu_option);
  }
  if(empty($top_menu_option)) {
    $top_menu_option = array('0'=>'home');
  }
@endphp
<header class="py-5 site-header">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div class="site-logo">
              <a href="{{ route('shop', ['vendor_name' => $vendata['shop_url_slug']]) }}" ><img src="{{ asset('img/logo.png') }}" class="w-60"></a>
              @if(isset($user_logo) && $user_logo != '')
              <a href="{{ route('shop', ['vendor_name' => $vendata['shop_url_slug']]) }}" >
              <img src="{{ asset('/storage/'.$user_logo) }}" alt="Image" width="100px"></a>
              @endif
            </div>            

            <nav class="bg-white border-gray-200 px-2 sm:px-4 py-2.5 rounded dark:bg-gray-900">
              <div class="container flex flex-wrap items-center justify-between mx-auto">
                <ul class="flex space-x-5 md:hidden">
                  <li><a href="{{ route('account') }}" class="text_gray"> <i class="fa fa-user-o"></i> </a></li>
                  <li><a href="{{ route('cart', ['vendor_name' => $vendata['shop_url_slug']]) }}" class="text_gray"> <i class="fa fa-shopping-bag"></i> </a>
                    @if($cart_count>0)  
                      {{$cart_count}}
                    @endif
                  </li>
                </ul>
                <button data-collapse-toggle="navbar-default" type="button" class="open_menu inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
                  <span class="sr-only">{{ __('Open main menu') }}</span>
                  <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                </button>
                <div class="hidden w-full md:block md:w-auto open_menu_mobile" id="navbar-default">

                  <ul class="flex flex-col p-4 mt-4 border border-gray-100 rounded-lg md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0">
                    @if(!empty($top_menu_option) && in_array('home', $top_menu_option))
                    <li class="active">
                      <a href="{{ route('shop', ['vendor_name' => $vendata['shop_url_slug']]) }}" class="text_green font16 font-futura" aria-current="page">{{ __('Home') }}</a>
                    </li>
                    @endif
                    @if(!empty($top_menu_option) && in_array('division', $top_menu_option))
                    <li>
                      <a href="{{ route('shop', ['vendor_name' => $vendata['shop_url_slug']]) }}"  class="text_gray font16 font-futura">{{ __('Choose Division') }}</a>
                    </li>
                    @endif
                    @if(!empty($top_menu_option) && in_array('vehicle', $top_menu_option))
                    <li>
                      <a href="javascript:;" onclick="openSearchModal('vehicle')" class="text_gray font16 font-futura">{{ __('Choose Vehicle') }}</a>
                    </li>
                    @endif
                    @if(!empty($top_menu_option) && in_array('accessories', $top_menu_option))
                    <li>
                      <a href="javascript:;" onclick="openSearchModal('accessories')" class="text_gray font16 font-futura">{{ __('Accessories') }}</a>
                    </li>
                    @endif
                  </ul>

                  <div class="site-account p-4 display_on_mobile">
                    <ul class="flex space-x-5">
                      <li><a href="tel:1800-33-22-57" class="text_gray font16"><i class="fa fa-phone"></i> 1800 33 22 57 </a></li>
               

                      <li><a href="{{ route('account') }}" class="text_gray"> <i class="fa fa-user-o"></i> </a></li>
                      <li><a href="{{ route('cart', ['vendor_name' => $vendata['shop_url_slug']]) }}" class="text_gray"> <i class="fa fa-shopping-bag"></i> </a>
                        @if($cart_count>0)  
                          {{$cart_count}}
                        @endif
                      </li>

                    </ul>
                  </div>
                </div>
              </div>
            </nav>

            <div class="site-account hide_on_mobile">
                <ul class="flex space-x-5">
                    <li><a href="tel:1800-33-22-57" class="text_gray font16"><i class="fa fa-phone"></i> 1800 33 22 57 </a></li>
                    <li><a href="{{ route('account') }}" class="text_gray"> <i class="fa fa-user-o"></i> </a></li>
                    <li><a href="{{ route('cart', ['vendor_name' => $vendata['shop_url_slug']]) }}" class="text_gray"> <i class="fa fa-shopping-bag"></i> </a>  
                    @if($cart_count>0)  
                    {{$cart_count}}
                    @endif
                  </li>                    

                </ul>
            </div>
        </div>
    </div>
</header>

@if(Route::is('shop') || Route::is('shop.subcategories') )

@else
  @include('layouts.customer-header-banner')
@endif
<!-- end Topbar -->