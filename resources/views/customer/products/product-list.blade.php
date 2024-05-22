@extends('layouts.customer-layout')

@section('title', __('Product List'))

@section('content')

<section class="lg:py-14 md:py-10 py-8" data-animation="slideInUp" data-animation-delay=".2s">
    <div class="max-w-screen-lg mx-auto">
        @if(count($productlist)>0)
            <div class="jm_product_outer_pg flex flex-wrap lg:pb-10 md:pb-8 pb-6">
                <!-- Product List Start -->
                @foreach($productlist as $prodlist)
                @php $fimage = App\Helpers\Helpers::getFeaturedImage($prodlist->id); @endphp
                <div class="jm_product_single xl:w-1/4 lg:w-1/3 md:w-1/2 w-full mb-5 text-center">
                    <div class="jm_product_outer product_card mx-3">
                        <a href="{{ route('product.single', ['vendor_name' => $shopslug,'division' => $parentcatslug, 'category' => $catslug,'prodslug'=>$prodlist->slug]) }}" class="border-b border-gray-200 border-solid inline-block w-full">
                            <!-- <img src="{{ asset('storage/'.$fimage) }}" alt="" class="w-full"> -->
                            <div class="w-full aspect-image-square" style="background-image: url('{{ asset('storage/'.$fimage) }}');"></div>
                        </a>
                        <div class="porduct_card_content lg:py-8 lg:px-6 md:py-4 md:px-4 p-8">
                            <a href="{{ route('product.single', ['vendor_name' => $shopslug, 'division' => $parentcatslug, 'category' => $catslug,'prodslug'=>$prodlist->slug]) }}">
                                <p class="text-base mb-2 font-futura">{{ $prodlist->name }}</p>
                            </a>
                            <p class="text-base mb-5 pb-5 font-futura-med">{{ setting('payment-setting.currency')."".number_format($prodlist->price,2) }}</p>
                        </div>
                        <a href="{{ route('product.single', ['vendor_name' => $shopslug, 'division' => $parentcatslug, 'category' => $catslug,'prodslug'=>$prodlist->slug]) }}" class="block uppercase text-sm green_btn lg:px-10 md:px-4"><span>{{ __('Detail') }} </span></a>
                    </div>
                </div>
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
        <!-- Product List Ends -->

        {!! $productlist->links() !!}

    </div>
</section>

@endsection