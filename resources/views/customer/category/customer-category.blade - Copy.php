@extends('layouts.customer-layout')

@section('title') {{ _('Category') }} @endsection

@section('content')
@php
$shopslug = Helper::getShopslug(Auth::user()->vendor_id);
$vendata=Helper::getShopData(Auth::user()->vendor_id);

$searchable_option = [];
if(isset($page->searchable_option)) {
  $searchable_option = json_decode($page->searchable_option);
}

if(empty($searchable_option)) {
  $searchable_option = array('0'=>'search');
}

@endphp

<section class="lg:py-16 md:py-14 py-10 jim-banner-sec" style="background-color: #F6F6F6;">
  <div class="container mx-auto px-3">
    <div class="flex flex-col items-center justify-between">
      <div class="lg:w-3/5 w-full">
        @if($page)
        <img src="{{ asset('storage/' . $page->image) }}" class="mx-auto" alt="" data-animation="zoomIn" data-animation-delay=".1s">
        @elseif( !empty($vendata['shop_banner']) )
        <img src="{{ asset('uploads/vendors/'.$vendata['shop_banner'])}}" class="mx-auto" data-animation="zoomIn" data-animation-delay=".1s">
        @else
        <img src="{{ asset('img/logo.png') }}" class="mx-auto" alt="" data-animation="zoomIn" data-animation-delay=".1s">
        @endif
        <div class="text-center" data-animation="slideInUp" data-animation-delay=".2s">
          @if($page)
            {!! $page->excerpt !!}
            {!! $page->body !!}
          @else
            <h1 class="pt-6 lg:text-5xl md:text-4xl text-3xl font-futura">
              Need it <span class="font-futura-bold text_blue">Done?</span> <br><span class="font-futura-bold text_blue">Colorcorp</span> the One!
            </h1>
            <p class="lg:py-8 md:py-6 py-4 font-futura">
              Lorem Ipsum proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris.
            </p>
          @endif
          
          <ul class="flex flex-wrap justify-center items-center">
            @if(!empty($searchable_option) && in_array('division', $searchable_option))
            <li class="mx-2 lg:my-0 md:my-0 my-2"><a href="{{ route('shop', ['vendor_name' => $shopslug ]) }}" class="green_btn active-green-btn"><span>{{ __('Search by Division') }}</span></a></li>
            @endif
            @if(!empty($searchable_option) && in_array('vehicle', $searchable_option))
            <li class="mx-2 lg:my-0 md:my-0 my-2"><a href="javascript:;" class="comun_btn common-btn search-vehicle" onclick="openSearchModal('vehicle')"><span>{{ __('Search by Vehicle') }}</span></a></li>
            @endif
            @if(!empty($searchable_option) && in_array('accessories', $searchable_option))
            <li class="mx-2 lg:my-0 md:my-0 my-2"><a href="javascript:;" class="comun_btn common-btn search-vehicle" onclick="openSearchModal('accessories')"><span>{{ __('Accessories') }}</span></a></li>
            @endif
          </ul>

        </div>
      </div>
    </div>
  </div>
</section>

<section class="mansory_layout lg:pt-12 md:pt-8 pt-4 mb-16" data-animation="slideInUp" data-animation-delay=".1s">
  <div class="container mx-auto px-3"> 
    @if( in_array('search', $searchable_option) )
    <form class="search_form flex items-center max-w-lg mx-auto lg:mb-8 md:mb-5">
      {{ csrf_field() }}
      <div class="relative w-full">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <svg aria-hidden="true" class="w-5 h-5 text_gray dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
          </div>
          <input type="text" class="focus:outline-none bg-gray-50 border border-gray-300 text_gray text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-none category-search" placeholder="Search By Your Division Here..." required style="border-color:#007934">
      </div>
      <button type="submit" class="search-btn inline-flex items-center py-2.5 px-3 ml-2 text-sm font-medium comun_btn">
          <span>
            <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
          </span>
      </button>
    </form>
    @endif
    <div class="row flex flex-wrap">
      <div class="w-full loader">
        <div role="status">
          <svg aria-hidden="true" class="inline w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-green-500" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
              <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
          </svg>
          <span class="sr-only">{{ __('Loading...') }}</span>
        </div>
      </div>
      <div class="w-full categories-wrapper">
        <div id="category-wrapper" class="grid js-masonry gap-x-3 js-masonry flex flex-wrap container mx-auto" data-masonry-options='{ "itemSelector": ".grid-item", "columnWidth": 1 }'>
  
          @foreach($records as $record)
          <div class="lg:w-1/4 md:w-1/2 w-full grid-item" data-title="{{ $record->name }}">
            <a href="{{ route('shop.subcategories', ['vendor_name' => $shopslug,'category_slug' => $record->slug]) }}">
              <div class="bg-sky-200 p-4 m-2 h-80 shine-overlay-bg">
                <img src="{{ asset('storage/'.$record->image) }}" alt="" class="mx-auto py-4">
                <h4 class="service_title w-full">{{ $record->name }}</h4>
              </div>
            </a>
          </div>
          @endforeach
       
        </div>
        <!-- @if($records_count>12)
        <div class="text-center my-8">
          <a href="#" class="green_btn px-12" id="load-more-services"><span>Load More Services</span></a>
        </div>
        @endif -->

        {{ $records->links() }}
      </div>
    </div>
  </div>
</section>

@endsection