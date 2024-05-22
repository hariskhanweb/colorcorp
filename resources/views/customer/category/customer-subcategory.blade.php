@extends('layouts.customer-layout')

@section('title') {{ _('Vehicles') }} @endsection

@section('content')

@php
$shopslug = Helper::getShopslug(Auth::user()->vendor_id);
$vendata=Helper::getShopData(Auth::user()->vendor_id);
@endphp
<!-- <section class="lg:py-14 md:py-10 py-8 jim-banner-sec jim-inner-banner-sec" style="background-color: #F6F6F6;">
    <div class="container mx-auto">
        <div class="flex flex-col items-center justify-between">
            @php
              $image = Helper::getCategoryImage($categoryslug);
            @endphp
            <img src="{{ asset('storage/'.$image) }}" data-animation="zoomIn" data-animation-delay=".1s">        
            <ul class="flex flex-wrap justify-center space-x-5 pt-12" data-animation="slideInUp" data-animation-delay=".2s">
                <li><a href="{{ url()->previous() }}" class="green_btn lg:px-14 px-9"><span>Back</span></a></li>
                <li><a href="javascript:;" onclick="openSearchModal('vehicle')" class="comun_btn lg:px-14 px-9"><span>Vehicles</span></a></li>
                {{-- <li><a href="#" class="comun_btn lg:px-14 px-9"><span>Trailers</span></a></li> --}}
            </ul>
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

<section class="jm_section_i lg:py-14 md:py-10 py-8">
  <div class="container mx-auto px-3">
    <h1 class="text-center lg:text-5xl md:text-4xl text-3xl font-futura mb-10" data-animation="zoomIn" data-animation-delay=".1s">Select Your <span class="font-futura-bold">Vehicle</span> Type</h1>
    @if(count($records)>0)
      <div class="xl:columns-4 lg:columns-3 md:columns-2 columns-1 gap-3 mx-auto space-y-3 pb-28" data-animation="slideInUp" data-animation-delay=".2s">
        @foreach($records as $record)
        <a href="{{ route('product.list', ['vendor_name' => $shopslug, 'parent_category'=> $categoryslug, 'category' => $record->slug]) }}" class="block">
          <div class="break-inside-avoid text-center px-6 lg:py-8 md:py-8 mowing_box">          
              <img src="{{ asset('storage/'.$record->image) }}" alt="" class="mx-auto py-4">
              <h4 class="service_title py-8">{{ $record->name }}</h4>          
          </div>
        </a>
        @endforeach
      </div>  
    @else
      <div class="w-full" role="alert">
        <div class="flex items-center p-4 bg-gray-100 rounded-md space-x-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-green-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <p>{{ __('No record found.') }}</p>
        </div>
      </div>
    @endif  
  </div>
</section>
@endsection