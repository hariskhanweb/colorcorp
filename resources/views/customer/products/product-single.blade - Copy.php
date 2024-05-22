
@php 
   if(Route::is('product.single')){
        $cart_data=Helper::getExistCartData(Auth::id(),$productrecord->id);
        //dd($cart_data);
    }
    $countries = Helper::getCountryData(Auth::id(),$productrecord->id);
@endphp

@extends('layouts.customer-layout')

@section('title', __('Single Product'))

@section('content')

<section class="product_section lg:py-14 py-9 overflow-x-hidden">
    <div class="container px-5 mx-auto">
        <div class="row flex flex-wrap">

            <div class="lg:w-2/4 md:w-full" data-animation="slideInRight" data-animation-delay=".1s">
                <div class="sv-slider">
                    <div class="owl-carousel pr-7" data-slider-id="1">
                        @if(count($prodmedia)>0)
                        @foreach($prodmedia as $prodimglist)
                        <div class="sv-slider-item">
                            <img src="{{ asset('storage/'.$prodimglist->url) }}" alt="{{ $productrecord->name }}">
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="owl-thumbs mt-4" data-slider-id="1">
                    </div>
                </div>
            </div>

            <div class="lg:w-2/4 md:w-full lg:pl-8 pl-0 lg:pt-0 pt-6" data-animation="slideInLeft" data-animation-delay=".1s">
                @if(!empty($cart_data))
                <div class="warning text-green-600">
                    {{ __('This Product is already added in cart if you added it will replace the previous one.') }}
                </div>
                @endif
                <h1 class="lg:text-5xl md:text-4xl text-3xl font-futura-med text-black mb-6 lg:px-0 md:px-0">{{ $productrecord->name }}</h1>
                <div class="text-gray-600 mb-3 text-sm">{!! $productrecord->short_description !!}</div>
                @php 
                  $attributeprice = 0;
                @endphp
                @if(count($prodattribes) == 1)
                  @foreach($prodattribes as $prodattrlist)
                    @if($prodattrlist->attrtype == 'select')
                      @php $optionlist = Helper::getProdSelOptions($prodattrlist->attrid, $productrecord->id); @endphp
                      @if(count($optionlist) == 1)
                        @foreach($optionlist as $optionrows)
                          @php $attributeprice = $attributeprice + $optionrows->variableprice; @endphp
                        @endforeach
                      @endif
                    @endif
                  @endforeach
                @endif

                @php $productprice = $productrecord->price +  $attributeprice; @endphp
                <p class="mb-6 text-2xl text-green-600" id="pro-sinlprice" data-price="{{$productrecord->price}}" >
                  {{setting('payment-setting.currency')."".number_format($productprice,2) }}</p>

                  <input type="hidden" id="actual_hidden_price" value="0">

                <form class="w-full lg:px-0 md:px-0" method="POST" action="{{ route('add.cart') }}">
                    {{ csrf_field() }}
                    <div class="flex flex-wrap -mx-3 mb-6">

                        @if( count($prodattribes)>0 && $productrecord->has_variation != 0 )
                        @foreach($prodattribes as $prodattrlist)

                              @if($prodattrlist->attrtype == 'text')
                              <div class="w-full md:w-1/2 pl-3">
                                <div class="relative">
                                  <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" type="text" placeholder="{{ $prodattrlist->attribname }}" name="product_text_attr[{{$prodattrlist->attrid}}]" required>
                                </div>
                              </div>
                              @endif
                              @if($prodattrlist->attrtype == 'select')
                              <div class="w-full md:w-1/2 pl-3">
                                <div class="relative">
                                  <select name="proatt[{{$prodattrlist->attrid}}]" class="block appearance-none block w-full bg-white text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 attr_pro" onchange="getval('select',$(this).find(':selected').attr('data-price'),`{{$productrecord->price}}`,`{{$prodattrlist->attrid}}`,`{{$prodattrlist->is_price}}`);" id="proatt_{{$prodattrlist->attrid}}" required>
                                      <option class="text-gray-700" value="" data-price="0" data-isprice="0">-- Select {{ $prodattrlist->attribname }} --</option>
                                      
                                    @php $optionlist = Helper::getProdSelOptions($prodattrlist->attrid, $productrecord->id); @endphp
                                    @foreach($optionlist as $optionrows)
                                      <option class="text-gray-700" value="{{$optionrows->productattributes_id}}-{{$optionrows->variableprice}}" data-price="{{$optionrows->variableprice}}" data-isprice="{{$prodattrlist->is_price}}" @php if(count($optionlist) == 1 && count($prodattribes) == 1) echo "selected"; @endphp >{{$optionrows->optionname}}</option>
                                    @endforeach                         
                                  </select>

                                  <input type="hidden" id="actual_hidden_data_price_{{$prodattrlist->attrid}}" value="0">

                                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                  </div>

                                </div>
                              </div>
                              @endif
                              @if($prodattrlist->attrtype == 'yesno')
                              <div class="w-full md:w-1/2 pl-3">
                                <div class="relative">
                                  <select name="proatt[{{$prodattrlist->attrid}}]" class="block appearance-none block w-full bg-white text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 attr_pro" onchange="getval('yesno',$(this).find(':selected').attr('data-price'),`{{$productrecord->price}}`,`{{$prodattrlist->attrid}}`,`{{$prodattrlist->is_price}}`);" id="proatt_{{$prodattrlist->attrid}}" required>
                                    <option class="text-gray-700" value="" data-price="0" data-isprice="0">-- Select {{ $prodattrlist->attribname }} --</option>
                                    @php $productattributesid = Helper::getProAttribute($productrecord->id,$prodattrlist->attrid); @endphp
                                    <option class="text-gray-700" value="{{$productattributesid->id}}-Yes" data-price="0" data-isprice="0">Yes</option>
                                    <option class="text-gray-700" value="{{$productattributesid->id}}-No" data-price="0" data-isprice="0">No</option>
                                  </select>


                                  <input type="hidden" id="actual_hidden_data_price_{{$prodattrlist->attrid}}" value="0">

                                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                  </div>
                                </div>
                              </div>
                              @endif  
                        @endforeach
                        @endif

                        <div class="w-full pl-3">
                          <div class="relative">
                            <select class="block appearance-none block w-full bg-white text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="installation_address" name="installation_address">
                                <option class="text-gray-700">Installation Required</option>
                                <option class="text-gray-700" value="1">Yes</option>
                                <option class="text-gray-700" value="0">No</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                          </div>
                        </div>

                        <div class="w-full">
                          <div id="address_wrapper" class="size_chart pl-3" style="display: none;">
                            <h2 class="w-full pb-6 lg:text-3xl text-2xl font-futura pt-4 mt-4 border-t border-solid border-gray-200">Install Location</h2>
                            <!--<div class="w-full mb-6">
                              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                                ADDRESS
                              </label>
                              <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="install-add" name="install_add" type="text" placeholder="House number and street name">
                            </div>--->
                              <!-- start address-->
                               <div class="flex flex-wrap -mx-3 mb-6">
                                      <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                                       {{ __('Country') }}
                                      </label>
                                         <select  name="country" id="country-dd" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" >
                                            <option value="">{{ __('Select Country') }}</option>
                                            @foreach ($countries as $value)
                                            <option value="{{$value->id}}">
                                                {{$value->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                        
                                      </div>
                                      <div class="w-full md:w-1/2 px-3 mb-6">
                                      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                        {{ __('State') }}
                                      </label>

                                      <select name="state"  id="state-dd" 
                                      class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" >
                                      </select>
                                      </div>

                                      <div class="w-full md:w-1/2 px-3 mb-6">
                                      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                        {{ __('City') }}
                                      </label>
                                      <input id="city" type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="city" value="" >
                                      </div>

                                       <div class="w-full md:w-1/2 px-3 mb-6">
                                      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                        {{ __('Address') }}
                                      </label>
                                       <input id="address" type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="address" value="" >
                                      </div>

                                      <div class="w-full md:w-1/2 px-3 mb-6">
                                      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                        {{ __('Postcode') }}
                                      </label>
                                       <input id="postcode" type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="postcode" value="">
                                      </div>

                                      <div class="w-full md:w-1/2 px-3 mb-6">
                                      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                        {{ __('Mobile Number') }}
                                      </label>
                                       <input id="mobile_number" type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="mobile_number" value="">
                                      </div>

                                  </div>
                              <!--end address-->                          
                            </div>
                        </div>


                        @php
                          $categories = App\Helpers\Helpers::getParentCategoriesByProductId($productrecord->id);
                        @endphp
                        @if(!empty($categories)) 
                        <div class="w-full pl-3">
                          <div class="relative">
                            <select name="division" class="block appearance-none block w-full bg-white text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"" id="grid-state" required>
                              <option value="">-- Select Divison --</option>
                              @foreach($categories as $category)
                              <option class="text-gray-700" value="{{ $category->id }}" @if(request()->get('division') == $category->slug) selected @endif >{{ $category->name }}</option>
                              @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                          </div>
                        </div>
                        @endif

                        <div class="w-full pl-3 mb-3">
                            <p class="text-gray-400 text-sm">{{ __('Decal Removel Required - Fees Apply') }}</p>
                            <div class="flex">
                            <div class="flex items-center">
                                <input id="bordered-radio-1" type="radio" value="yes" name="decal_removel" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" name="decal_removel" required>
                                <label for="bordered-radio-1" class="w-full py-2 ml-2 text-sm font-medium text-gray-400 dark:text-gray-300">{{ __('Yes') }}</label>
                            </div>
                            <div class="flex items-center pl-4">
                                <input id="bordered-radio-2" type="radio" value="no" name="decal_removel" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" name="decal_removel" required>
                                <label for="bordered-radio-2" class="w-full py-2 ml-2 text-sm font-medium text-gray-400 dark:text-gray-300">{{ __('No') }}</label>
                            </div>
                            </div>
                        </div>
                        <div class="w-full pl-3">
                            <div class="flex justify-center">
                                <div class="relative mb-0 w-full">
                                    <textarea class="peer block min-h-[auto] w-full bg-white resize-none text-gray-700 border border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 rounded"
                                        id="additional-information" rows="5" placeholder="Additional Information" name="pro_info"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="w-full pl-3">
                            <p class="text-gray-600 mb-3 text-sm"><strong>{{ __('Please Note:') }}</strong> {{ __('Your vehicle must be washed prior to drop off. If the vehicle is dropped off without being washed satisfactorily then a cleaning charge will apply and separately invoiced. The car wash invoice will be required to be paid prior to pick up.') }}</p>
                            <p class="text-gray-600 mb-3 text-sm"><strong>Re-scheduling Fee</strong></p>
                            <div class="flex mb-5">
                            <input id="link-checkbox" type="checkbox" value="yes" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" name="re_scheduling_fee" required>
                            <label for="link-checkbox" class="ml-2 text-sm font-medium text-gray-600 dark:text-gray-300">{{ __('I Understand That a Re-Scheduling Fee of $250 Will Be Charged if Notice Isn’t Provided 48HRS Prior to the Appointment.') }}</Label>
                            </div>
                            <p class="text-gray-600 mb-3 text-sm"><strong>{{ __('Car Wash & Preparation Fee') }}</strong></p>
                            <div class="flex mb-5">
                            <input id="cwpf-checkbox" type="checkbox" value="yes" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" name="preparation_fee" required>
                            <label for="cwpf-checkbox" class="ml-2 text-sm font-medium text-gray-600 dark:text-gray-300">{{ __('I Understand That the Vehicle Must Be Cleaned Satisfactorily Prior to Drop Off. If the Vehicle Is Not Cleaned Satisfactorily a $100 Car Wash & Preparation Fee Will Apply. Note: It Is Not Sufficient to Run the Car Through a Automatic Car Wash.') }}</label>
                            </div>
                        </div>
                    </div>
                     <!-- Product Info -->
                        <input type="hidden" name="sproductid" value="{{ $productrecord->id }}">
                        <input type="hidden" name="sprodvendorid" value="{{ $productrecord->vendor_id }}">
                        <input type="hidden" name="sproductcategry" value="{{ $catgryid }}">
                        <input type="hidden" name="userid" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="pro_price" value="{{$productrecord->price}}">
                        <!-- Product Info Ends -->
                        <button type="submit" class="green_btn py-4 px-12">
                          <span>{{ __('Add to Cart') }} <i class="fa fa-shopping-cart"></i></span>
                        </button>  

                </form>
            </div>

        </div>
        <div class="row flex flex-wrap">
            <div class="md:w-full">
                <h3 class="font-futura-med text-black mb-5 lg:px-0 md:px-0">{{ __('More Information:') }}</h3>
                <div class="text-gray-600 mb-3 text-sm">
                    {!! $productrecord->long_description !!}
                </div>
            </div>
        </div>      
    </div>
</section>
<script type="text/javascript">
function getval(type,sel,price,attrId,is_price) {   
  var actual_hidden_price = $('#actual_hidden_price').val();
  var amt = parseFloat(sel);
  var total = 0;
  if(is_price == 1 && type == 'select'){
    if(amt!=0 || amt!=''){
      var actual_hidden_data_price = parseInt($('#actual_hidden_data_price_'+attrId).val());
      if(actual_hidden_data_price!=0){
        var act_amt = parseFloat(actual_hidden_price)-parseFloat(actual_hidden_data_price);
        total=(parseFloat(act_amt)+parseFloat(amt)).toFixed(2);
      }else{
        if(actual_hidden_price!=0){
          total=(parseFloat(actual_hidden_price)+parseFloat(amt)).toFixed(2);
        }else{
          total=(parseFloat(price)+parseFloat(amt)).toFixed(2);
        }        
      }
      $('#actual_hidden_data_price_'+attrId).val(amt);
    }else{
      var hidden_actual_amt = $('#actual_hidden_data_price_'+attrId).val();
      var new_amt = parseFloat(hidden_actual_amt);
      total=(parseFloat(actual_hidden_price)-parseFloat(new_amt)).toFixed(2);
    }    
    $('#pro-sinlprice').html('$'+total);
    $('#actual_hidden_price').val(total);
  }
}
</script>

@endsection