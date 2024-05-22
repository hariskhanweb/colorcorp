@php
    $vendata=Helper::getShopData(Auth::user()->vendor_id);
@endphp

<!-- <section class="lg:py-16 md:py-14 py-10 jim-banner-sec" style="background-color: #F6F6F6;">
    <div class="container mx-auto px-4">
        <div class="flex flex-col items-center justify-between">
            <div class="w-3/5">
                @if( !empty($vendata['shop_banner']) )
            		<img src="{{ asset('uploads/vendors/'.$vendata['shop_banner'])}}" class="mx-auto" data-animation="zoomIn" data-animation-delay=".1s">
            	@elseif(Route::is('product.list')) 
            		@php
				    	$currentpath = request()->path();
				    	$parent_cat_slug = explode('/', $currentpath);
				    	$image = Helper::getCategoryImage($parent_cat_slug[1]);
				    @endphp
					<img src="{{ asset('storage/'.$image)}}" class="mx-auto" data-animation="zoomIn" data-animation-delay=".1s">
            	@else
            		@if(request()->get('division'))
            			@php $image = Helper::getCategoryImage(request()->get('division')); @endphp
            			<img src="{{ asset('storage/'.$image)}}" class="mx-auto" data-animation="zoomIn" data-animation-delay=".1s">
            		@else
            			<img src="{{ asset('img/BGSR-JIMS-MOWING.png')}}" class="mx-auto" data-animation="zoomIn" data-animation-delay=".1s">
            		@endif
            	@endif
            </div>
        </div>
    </div>
</section> -->

<section class="jim-banner-sec">
  <div class="banner--sec" style="background-color: #F6F6F6;">
    @if(!empty($vendata['shop_banner']))
    <img src="{{ asset('uploads/vendors/'.$vendata['shop_banner'])}}" class="mx-auto" data-animation="zoomIn" data-animation-delay=".1s">
    @else
    <img src="{{ asset('img/logo.png') }}" class="mx-auto" alt="" data-animation="zoomIn" data-animation-delay=".1s">
    @endif
  </div>
</section>

<!-- end Topbar