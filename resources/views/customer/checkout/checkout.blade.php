@extends('layouts.customer-layout')

@section('title', __('Checkout'))

@section('content')
@php
header('Access-Control-Allow-Origin: *');
$fullname=Auth::user()->name;
$user_id = Auth::user()->id;
$shopslug=Helper::getShopslug(Auth::user()->vendor_id);
$vendata=Helper::getShopData(Auth::user()->vendor_id);
$paymentMethod = json_decode($vendata->pay_by);

if($vendata->pay_by == '') {
  $paymentMethod = array('0'=>'cc','1'=>'po');
}

@endphp

<section class="cart_page lg:py-14 py-8 px-3">
  <div class="container mx-auto">
    <form id="checkout-form" method="post" class="w-full" action="{{ route('place.order', ['vendor_name' => $shopslug ]) }}">
      {{ csrf_field() }}
      <div class="row flex flex-wrap">
        <div class="lg:w-3/5 w-full" data-animation="slideInRight" data-animation-delay=".1s">
          <h4 class="text-3xl text-black font-futura-med lg:mb-6 mb-4">{{ __('Billing details') }}</h4> 

          {{-- Billing Address --}}
          @if(count($cusbilldata)>0)
          <section id="billingaddress-1">
            <div class="max-w-screen-lg mx-auto">
              <div class="jm_product_outer_pg flex flex-wrap lg:pb-2 md:pb-4 pb-2" id="billingaddress">
                @foreach($cusbilldata as $cusadd)
                <div class="jm_product_single xl:w-1/2 lg:w-1/2 md:w-1/2 w-full mb-5 text-center">
                  <div class="jm_product_outer product_card mx-3 @if($cusadd['default_address'] == '1') active @endif ">
                    <div class="edit">
                      <!--  <a style="margin-right:10px" href="http://127.0.0.1:8000/account/addresses/edit/2"><i class="fa fa-pencil"></i></a> 
                      <a style="margin-right:10px" class="delete show_confirm" data-id="2"><i class="fa fa-trash"></i></a> -->
                    </div>
                    @php
                    $state=Helper::getStateName($cusadd['state_id']);
                    $country=Helper::getCountryName($cusadd['country_id']);
                    $state_name=(!empty($state))?$state->name:"";
                    $country_name=(!empty($country))?$country->name:"";
                    if($cusadd['default_address']==1){
                      $addchrck='checked';
                    }else{
                      $addchrck='';
                    }
                  
                    @endphp
                    <div class="porduct_card_content address_card">
                      <div class="address_card_name">{{$fullname}}</div>
                      <div class="address_card_street billadd1" >{{$cusadd['address']}}</div>
                      <div class="address_card_country">
                        <span class="billcity1">{{$cusadd['city']}}</span>, 
                        <span class="billstate1" data-state="{{$cusadd['state_id']}}">{{$state_name}}</span>,
                        <span class="postcode1">{{$cusadd['postcode']}}</span> 
                        <!-- <span class="hide-d" data-country="{{$cusadd['country_id']}}">{{$cusadd['country_id']}} -->
                      </div>
                      <div class="address_card_country billcountry" data-country="{{$cusadd['country_id']}}">{{$country_name}}</div>
                      <div class="address_card_mobile bmobile1">{{$cusadd['mobile_number']}}</div>
                      <div class="address-select"><input class="billingaddress" onclick="selectAdd(this)" type="radio" {{$addchrck}} name="billingaddress" data-id="{{ $cusadd->id }}" value="{{ $cusadd->id }}" ><span>Selected</span></div>
                    </div>
                  </div>
                </div>
                @endforeach                            
              </div>
              <input type="hidden" name="billing_adddress_id" id="billing_adddress_id" value="{{ $defBillingAddId }}" />
            </div>
          </section>
          @endif

          <div class="mb-6">
            <input type="checkbox" id="newbilling" name="newbilling" onclick="addBilling(this)" value="1" @if(count($cusbilldata) == 0) checked @endif >
            <label for="newbilling" class="cursor-pointer">{{ __('Add billing address') }}</label>
          </div>

          <div class="flex flex-wrap -mx-3 mb-6 billform @if(count($cusbilldata)>0) hide-d @endif">  
            <div class="w-full  px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                {{ __('Full Name') }}
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="b-first-name" value="{{$fullname}}" name="bfirstname" type="text" placeholder="">
              <p id="b-first-name-err" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>
            </div>                         
            <div class="w-full px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                  {{ __('Address') }}
              </label>
              <input class="mb-2 appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="baddress" id="baddress" type="text" placeholder="House number and street name">
              <p id="baddress-err" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>
            </div>
            <div class="lg:w-1/3 md:w-1/2 w-full px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                  {{ __('Town / City') }}
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="b-city" name="bcity" type="text" placeholder="">  
              <p id="b-city-err" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>                      
            </div>
            <div class="w-full lg:w-1/3 md:w-1/2 px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                  {{ __('Country') }}
              </label>
              <select  name="bcountry" id="country-dd" class="bcountry appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                <option value="">{{ __('Select Country') }}</option>
                @foreach ($countries as $value)
                <option selected value="{{$value->id}}">
                    {{$value->name}}
                </option>
                @endforeach
              </select>    
              <p id="country-dd-err" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>                    
            </div>
            <div class="lg:w-1/3 md:w-1/2 w-full px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                  {{ __('State') }}
              </label>
              <div class="relative">
                <select id="state-dd" name="bstate" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                  @foreach ($states as $statesvalue)
                    <option value="{{$statesvalue->id}}">
                        {{$statesvalue->name}}
                    </option>
                    @endforeach
              </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
              </div>
              <p id="state-dd-err" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>  
            </div>
            <div class="lg:w-1/3 md:w-1/2 w-full px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                  {{ __('Postcode') }}
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="b-postcode" name="bpostcode" type="text" placeholder="">
              <p id="b-postcode-err" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>
            </div>
            <div class="lg:w-1/3 md:w-1/2 w-full px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                  {{ __('Mobile Number') }}
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="b-mobile" name="bmobile" type="number" placeholder="">
              <p id="b-mobile-err" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>
            </div>
          </div>

          {{-- Shipping Address --}}
          <div class="flex flex-wrap -mx-3 mb-6">  
            <div class="w-full px-3 mb-6">
              <h4 class="text-3xl text-black font-futura-med pt-4 mt-4 border-t border-solid border-gray-200">{{ __('Shipping details') }}</h4>
            </div>
            <div class="w-full px-3 mb-6">
              <input type="checkbox" id="samebilling" name="samebilling" onclick="addSamebilling(this)">
              <label for="samebilling" class="cursor-pointer">{{ __('Same as my billing address') }}</label>
            </div>
          </div>

          @if(count($cusshipdata)>0)
            <section id="shippingadd-1">
              <div class="max-w-screen-lg mx-auto">
                <div class="jm_product_outer_pg flex flex-wrap lg:pb-2 md:pb-4 pb-2" id="shippingadd">
                  @foreach($cusshipdata as $cusshipadd)
                    <div class="jm_product_single xl:w-1/2 lg:w-1/2 md:w-1/2 w-full mb-5 text-center">
                      <div class="jm_product_outer product_card mx-3 @if($cusshipadd['default_address'] == '1') active @endif ">
                        <div class="edit">
                           <!--  <a style="margin-right:10px" href="http://127.0.0.1:8000/account/addresses/edit/2"><i class="fa fa-pencil"></i></a> 
                            <a style="margin-right:10px" class="delete show_confirm" data-id="2"><i class="fa fa-trash"></i></a> -->
                        </div>
                        @php
                        $state=Helper::getStateName($cusshipadd['state_id']);
                        $countryship=Helper::getCountryName($cusshipadd['country_id']);
                        $state_shipname=(!empty($state))?$state->name:"";
                        $country_shipname=(!empty($countryship))?$countryship->name:"";
                        if($cusshipadd['default_address']==1){
                          $addcheck='checked';
                        }else{
                          $addcheck='';
                        }
                      
                        @endphp
                        <div class="porduct_card_content address_card">
                          <div class="address_card_name">{{$fullname}}</div>
                          <div class="address_card_street billadd1" >{{$cusshipadd['address']}}</div>
                          <div class="address_card_country">
                            <span class="billcity1">{{$cusshipadd['city']}}</span>, 
                            <span class="billstate1" data-state="{{$cusshipadd['state_id']}}">{{$state_shipname}}</span>,
                            <span class="postcode1">{{$cusshipadd['postcode']}}</span>
                          </div>
                          <div class="address_card_country billcountry" data-country="{{$cusshipadd['country_id']}}">{{$country_shipname}}</div>
                          <div class="address_card_mobile bmobile1">{{$cusshipadd['mobile_number']}}</div>
                          <div class="address-select"><input class="shippingadd" onclick="selectShipAdd(this)" type="radio" {{$addcheck}} name="shippingadd" data-id="{{ $cusshipadd->id }}" value="{{ $cusshipadd->id }}"><span>Selected</span></div>
                        </div>
                      </div>
                    </div>
                  @endforeach                            
                </div>
                <input type="hidden" name="shipping_adddress_id" id="shipping_adddress_id" value="{{ $defShippingAddId }}" />
              </div>
            </section>
          @endif
            
          <div class="mb-6 newshipping">
            <input type="checkbox" id="newshipping" name="newshipping" onclick="addAsipping(this)" value="1">
            <label for="newshipping" class="cursor-pointer">{{ __('Add shipping address') }}</label>
          </div>

          <div class="flex flex-wrap -mx-3 mb-6 shipform @if(count($cusshipdata)>0) hide-d @endif">
            <div class="w-full px-3 mb-6 md:mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="s-first-name">
                {{ __('Name') }}
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" value="{{$fullname}}" name="sfirstname" id="s-first-name" type="text" placeholder="">
              <p id="sfirstname" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>
            </div>                                       
            <div class="w-full px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                {{ __('Shipping Address') }}
              </label>
              <input class="mb-2 appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="saddress" id="saddress" type="text" placeholder="House number and street name">
              <p id="saddress-err" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>                                         
            </div>
            <div class="lg:w-1/3 md:w-1/2 w-full px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                {{ __('Town / City') }}
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="scity" id="s-city" type="text" placeholder="">   
              <p id="scity-err" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>                      
            </div>
            <div class="w-full lg:w-1/3 px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                {{ __('Country') }}
              </label>
              <select  name="scountry" id="countrydd" onclick="addCountry(this)"  class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                <option value="">Select Country</option>
                @foreach ($countries as $value)
                <option selected value="{{$value->id}}">
                  {{$value->name}}
                </option>
                @endforeach
              </select>  
              <p id="countrydd-err" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>                                     
            </div>
            <div class="lg:w-1/3 md:w-1/2 w-full px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                {{ __('State') }}
              </label>
              <div class="relative">
                <select id="statedd" name="sstate" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                  @foreach ($states as $statesvalue)
                    <option value="{{$statesvalue->id}}">
                      {{$statesvalue->name}}
                    </option>
                  @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
              </div>
              <p id="statedd-err" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>  
            </div>
            <div class="lg:w-1/3 md:w-1/2 w-full px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                {{ __('Postcode') }}
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="spostcode" id="spostcode" type="text" placeholder="">
              <p id="spostcode-err" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>
            </div>
            <div class="lg:w-1/3 md:w-1/2 w-full px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                {{ __('Mobile Number') }}
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="smobile" id="smobile" type="number" placeholder="">
              <p id="smobile-err" class="text-red-500 text-xs italic hide-d">Please fill out this field.</p>
            </div>
          </div>

          <div class="flex flex-wrap -mx-3 mb-6">                
            <div class="w-full px-3">
              <div class="flex flex-wrap">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                    {{ __('Additional information') }}
                </label>
                <div class="relative mb-0 w-full" data-te-input-wrapper-init>
                  <textarea class="peer block min-h-[auto] w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="additional-information" rows="5" placeholder="Notes about your order, e.g. special notes for delivery." name="addition_infomation"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="lg:w-2/5 w-full lg:pl-3 pl-0 lg:mt-0 mt-5 lg:p-10" data-animation="slideInLeft" data-animation-delay=".1s">
          <h4 class="text-3xl text-black font-futura-med lg:mb-6 mb-4">{{ __('Your order') }}</h4>
          <div class="p-4 bg-gray-100">
            <!-- Product Row -->
            <div class="flex flex-wrap mb-2">
              <div class="w-1/2">
                <p><strong>{{ __('Product') }}</strong></p>

              </div>
              <div class="w-1/2 flex justify-end">
                <p><strong>{{ __('Quantity') }}</strong></p>
              </div>
            </div>
            
            <div class="flex flex-wrap mb-2 pb-2 border-b border-solid border-gray-400">
              @php $carttotal=0; @endphp
              @foreach($cartdata as $cart_data)
              @php
              $carttotal=$carttotal+$cart_data->pro_att_price*$cart_data->pro_qty;
              $fimage = Helper::getFeaturedImage($cart_data->product_id);
              @endphp
              <div class="w-1/2 flex flex-wrap items-center">
                <img src="{{ asset('storage/'.$fimage) }}" alt="" class="inline mr-2 product_img"> <span>@if(isset($cart_data->getCartProduct->name))
                {{$cart_data->getCartProduct->name}}
                @endif</span>
                 <div class="moadl-btn" style="color:#007934;text-align: center;width: 100%;"> 
                  <a class="py-2 px-4 showm" onclick="toggleModal('{{$cart_data->id}}')">Show More</a></div> 
              </div>
              <div class="w-1/2 flex flex-wrap items-center justify-end">
                {{$cart_data->pro_qty}}
              </div>
              @endforeach

            </div>
            <!-- End Product Row -->

            <div class="flex flex-wrap mb-2">
              <div class="w-1/2 flex flex-wrap items-center">
                <p>{{ __('Sub Total') }}</p>
              </div>
              <div class="w-1/2 flex flex-wrap items-center justify-end">
                <p>{{setting('payment-setting.currency')}} {{number_format($carttotal, 2)}}</p>
              </div>
            </div>
              @php
                $gst=($carttotal*setting('tax-setting.gst'))/100;
                $gst = round($gst);
              @endphp
            <div class="flex flex-wrap mb-2">
              <div class="w-1/2 flex flex-wrap items-center">
                <p>GST ({{setting('tax-setting.gst')}}%)</p>
              </div>
              <div class="w-1/2 flex flex-wrap items-center justify-end">
                <p>{{setting('payment-setting.currency')}} {{number_format($gst, 2)}}</p>
              </div>
            </div>

            <div class="flex flex-wrap mb-2">
              <div class="w-1/2 flex flex-wrap items-center">
                <p><strong>{{ __('Grand Total') }}</strong></p>
              </div>
              <div class="w-1/2 flex flex-wrap items-center justify-end">
                <p><strong>{{setting('payment-setting.currency')}} {{number_format($gst+$carttotal, 2)}}</strong></p>
              </div>
            </div>
          </div>

          <p class="text-black text-base p-4 bg-gray-100 rounded-md ml-0 mt-3">{{ __('Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our privacy policy.') }}</p>
          
          <div class="flex flex-wrap mb-2 pb-2 bg-gray-100">

            @if(!empty($paymentMethod) && in_array('po', $paymentMethod))
              <div class="w-full px-3 mb-6">
                <input type="radio" name="purchase_order" onclick="addPurchaseOrderNumber(1)" value="1" {{ !in_array('cc', $paymentMethod) ? 'checked' : '' }}>
                <label for="purchase_order" class="cursor-pointer">{{ __('Pay By Purchase order number') }}</label>
              </div> 

              <div class="w-full px-3 mb-6 {{ !in_array('cc', $paymentMethod) ? '' : 'hide-d' }}" id="purchase_order_wrapper">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                  {{ __('Purchase Order Number') }}
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mb-6" name="purchase_order_number"
                 id="purchase_order_number" type="text" placeholder="">
                <span id="porder-num-err" class="hide-d">{{ __('Please Enter Your Purchase Order Number') }}</span>
              </div>
            @endif 
                
                <!-- <button id="plord" type="button" onClick="onPlacePurcaseOrderNumberClick();" class="green_btn py-4 px-12" ><span>{{ __('Place order') }}</span></button> -->
              

              @if(!empty($paymentMethod) && in_array('cc', $paymentMethod)) 
                <div class="w-full px-3 mb-6">
                  <input type="radio" name="purchase_order" id="credit_card" onclick="addPurchaseOrderNumber(0)" value="1" checked>
                  <label for="credit_card" class="cursor-pointer">{{ __('CREDIT CARD / DEBIT CARD') }}</label>
                </div> 
                  
                <div class="card-wrapper-div w-full px-3 mb-6">
                  <div class="p-4 card-wrapper">
                    <div id="securepay-ui-container"></div>
                    <input id="securepayapi-token" name="securePayApiToken" type="hidden">
                    <input name="order_number" id="order_number" type="hidden" value="{{$nextAutoIncrementId}}" /> 
                    <input name="transaction_id" id="transaction_id" type="hidden" value="" /> 
                  </div>
                </div>
              @endif

              @php
              $get_onclick = ( !empty($paymentMethod) && in_array('cc', $paymentMethod) ? 'onPlaceOrderClick()' : 'onPlacePurcaseOrderNumberClick()');
              @endphp
                
              <div class="w-full px-3 mb-6">
                <button id="plord" type="button" onClick="{{$get_onclick}}" class="green_btn py-4 px-12" ><span>{{ __('Place order') }}</span></button>  
                <button type="button" class="reset_btn {{ !empty($paymentMethod) && !in_array('cc', $paymentMethod) ? 'hide-d' : '' }}" onclick="mySecurePayUI.reset();">Reset</button>
              </div>
               
                
          </div>

          

          
        </div>
      </div>
    </form>
  </div>

  <!----modal-->

  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closeModal()"><i class="fa fa-times"></i> </button>
      </div>
      <div class="modal-body">
        <div id="cart-details">
        </div>
      </div>
    </div>
  </div>
</div>
{{-- <div class="fixed z-10 overflow-y-auto top-0 w-full left-0 hidden" id="modal">
    <div class="flex items-center justify-center min-height-100vh pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity">
        <div class="absolute inset-0 bg-gray-900 opacity-75" />
      </div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
      <div class="inline-block align-center bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
        <div class="bg-gray-200 px-4 py-3 text-right">
          <button type="button" class="py-2 px-4 bg-gray-500 text-white rounded hover:bg-gray-700 mr-2" onclick="closeModal()"><i class="fa fa-times"></i> 
          </button>
        </div>
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 text-center" id="cart-details">  
        </div>
      </div>
    </div>
  </div> --}}
  <!---modal end-->
</section>
<div class="processing_loader">
  <div class="inner_loader">
    <div class="process_message">
      <div class="process_icon"><i class="fa fa-spinner fa-spin"></i></div>
      We are processing your payment.<br/>
      Please wait....<br/>
      Don't close browser until payment completed.
    </div>
  </div>
</div>

<script id="securepay-ui-js" src="https://payments-stest.npe.auspost.zone/v3/ui/client/securepay-ui.min.js"></script>
<script type="text/javascript">
  /*$(document).ready(function(){
    @if(count($cusbilldata)>0)
      $('.billform').addClass('hide-d');
    @endif
  }); 
*/


function addPurchaseOrderNumber(e){
  // if($(e).prop("checked") == true) {
  if(e == 1) {
    $('.reset_btn').addClass('hide-d');
    $('.card-wrapper-div').addClass('hide-d');
    $('#purchase_order_wrapper').removeClass('hide-d');
    $("#purchase_order_number").attr('required', 'required'); 
    $("#plord").attr('onclick', 'onPlacePurcaseOrderNumberClick()'); 
  // } else if($(e).prop("checked") == false) {
  } else if(e == 0) {
    $('#porder-num-err').addClass('hide-d');
    $('.reset_btn').click();
    $('.reset_btn').removeClass('hide-d');
    $('.card-wrapper-div').removeClass('hide-d');
    $('#purchase_order_wrapper').addClass('hide-d');
    $("#purchase_order_number").removeAttr('required'); 
    $("#purchase_order_number").val('');
    $("#plord").attr('onclick', 'onPlaceOrderClick()'); 
  }
}

function onPlacePurcaseOrderNumberClick(){
  var isBillingChecked = $('#newbilling')[0].checked;
  var isShippingChecked = $('#newshipping')[0].checked;
  var isvalidateBilling = isvalidateShipping = true;
  var purchase_order_number=$('#purchase_order_number').val();

  if(purchase_order_number!=""){
      $('#porder-num-err').addClass('hide-d');
      $("#purchase_order_number").removeAttr('required'); 
      if(isBillingChecked) {
        isvalidateBilling = validateBilling(); 
      }
      if(isShippingChecked) {
        isvalidateShipping = validateShipping();
      }
      if(isvalidateBilling && isvalidateShipping){
        $(".processing_loader").addClass("active"); 
        $("#transaction_id").val(purchase_order_number);
         setTimeout(function(){
            // $(".processing_loader").removeClass("active"); 
            $("#checkout-form").submit();
          }, 1000);
      }
  }else{
    $('#porder-num-err').removeClass('hide-d');
    $("#purchase_order_number").attr('required', 'required'); 
  }    
}

  function toggleModal(id) {
    getProductDetails(id);
  }

  function closeModal(){
    $("#modal").hide();
  }

  function getProductDetails(cartid) {
    var action = "{{url('get-cart-product')}}";
    $.ajax({
      type: 'POST',
      url: action,
      data: {
        cartid: cartid,
        _token: "{{ csrf_token() }}",
      },
      dataType: 'JSON',
      success: function(data) {
        if (data.success == 1) {
          $('#cart-details').html(data.result);
          $("#modal").show();
        }
      }
    });
  }

  function selectAdd(e){    
    $('.billingaddress').prop('checked', false);
    $('#billingaddress .jm_product_outer.active').removeClass('active');
    $(e).prop('checked', true);
    $(e).parent().parent().parent().addClass('active');

    if($('#samebilling').prop("checked") == true) {
      $('.shipform').addClass('hide-d');
      $('#shippingadd-1').removeClass('hide-d');
      $('#samebilling').prop('checked', false);
    }
    $('.newshipping').removeClass('hide-d');
    var billAddressId = $(e).attr('data-id');
    $("#billing_adddress_id").val(billAddressId);
  }

  function addBilling(e){ 
    if($(e).prop("checked") == true) {
      $('.billform').removeClass('hide-d');
      $('#billingaddress-1').addClass('hide-d'); 
      $("#billing_adddress_id").val(0);  

      $("#b-first-name").attr('required', 'required');
      $("#baddress").attr('required', 'required');
      $("#b-city").attr('required', 'required');
      $("#country-dd").attr('required', 'required');
      $("#state-dd").attr('required', 'required');
      $("#b-postcode").attr('required', 'required'); 
      $("#b-mobile").attr('required', 'required'); 

      if($('#samebilling').prop("checked") == true) {
        $('.shipform').addClass('hide-d');
        $('#shippingadd-1').removeClass('hide-d'); 
        $('.newshipping').removeClass('hide-d');
         $('#samebilling').prop('checked', false);
      }  
    } else if($(e).prop("checked") == false) {
      $('.billform').addClass('hide-d');
      $('#billingaddress-1').removeClass('hide-d');
      var billAddressId = $( "#billingaddress-1" ).find( ".active .billingaddress" ).attr('data-id');
      $("#billing_adddress_id").val(billAddressId);

      $("#b-first-name").removeAttr('required', 'required');
      $("#baddress").removeAttr('required', 'required');
      $("#b-city").removeAttr('required', 'required');
      $("#country-dd").removeAttr('required', 'required');
      $("#state-dd").removeAttr('required', 'required');
      $("#b-postcode").removeAttr('required', 'required'); 
      $("#b-mobile").removeAttr('required', 'required');

      $("#b-first-name").val('');
      $("#baddress").val('');
      $("#b-city").val('');
      $("#country-dd").val('');
      $("#state-dd").val('');
      $("#b-postcode").val('');
      $("#b-mobile").val(''); 

      if($('#samebilling').prop("checked") == true) {
        $('.shipform').addClass('hide-d');
        $('#shippingadd-1').removeClass('hide-d'); 
        $('.newshipping').removeClass('hide-d');
         $('#samebilling').prop('checked', false);
      }

    }
  }

  function selectShipAdd(e){    
    $('.shippingadd').prop('checked', false);
    $('#shippingadd .jm_product_outer.active').removeClass('active');
    $(e).prop('checked', true);
    $(e).parent().parent().parent().addClass('active');
    var shipAddressId = $(e).attr('data-id');
    $("#shipping_adddress_id").val(shipAddressId);
  }

  function addAsipping(e){ 
    if($(e).prop("checked") == true) {
      $('.shipform').removeClass('hide-d');
      $('#shippingadd-1').addClass('hide-d'); 
      $("#shipping_adddress_id").val(0);  

      $("#s-first-name").attr('required', 'required');
      $("#saddress").attr('required', 'required');
      $("#s-city").attr('required', 'required');
      $("#countrydd").attr('required', 'required');
      $("#statedd").attr('required', 'required');
      $("#spostcode").attr('required', 'required'); 
      $("#smobile").attr('required', 'required'); 
      
    } else if($(e).prop("checked") == false) {
      $('.shipform').addClass('hide-d');
      $('#shippingadd-1').removeClass('hide-d');

      var shipAddressId = $( "#shippingadd-1" ).find( ".active .shippingadd" ).attr('data-id');
      $("#shipping_adddress_id").val(shipAddressId);

      $("#s-first-name").removeAttr('required');
      $("#saddress").removeAttr('required');
      $("#s-city").removeAttr('required');
      $("#countrydd").removeAttr('required');
      $("#statedd").removeAttr('required');
      $("#spostcode").removeAttr('required'); 
      $("#smobile").removeAttr('required'); 

      $("#s-first-name").val('');
      $("#saddress").val('');
      $("#s-city").val('');
      $("#countrydd").val('');
      $("#statedd").val('');
      $("#spostcode").val('');
      $("#smobile").val('');
    }
  }

function addSamebilling(e){ 
  if($(e).prop("checked") == true) {
    if($('#newbilling').prop("checked") == false) {
      var billadd=$("[name=billingaddress]:checked").parent().parent().find('.billadd1 ').html();
      var billcity=$("[name=billingaddress]:checked").parent().parent().find('.billcity1').html();
      var billstate=$("[name=billingaddress]:checked").parent().parent().find('.billstate1').data('state');
      var billcountry=$("[name=billingaddress]:checked").parent().parent().find('.billcountry').data('country');
      var postcode=$("[name=billingaddress]:checked").parent().parent().find('.postcode1').html();
      var mobile=$("[name=billingaddress]:checked").parent().parent().find('.bmobile1').html();
      var firstname='{{$fullname}}';
    }else{
      var firstname=$('#b-first-name').val(); 
      var billadd=$('#baddress').val();
      var billcity=$('#b-city').val();
      var billstate=$('#state-dd').val();
      var billcountry=$('#country-dd option:selected').val();
      var postcode=$('#b-postcode').val();
      var mobile = $('#b-mobile').val();
    }
    //alert(billstate);
    $('.shipform').removeClass('hide-d');
    $('#shippingadd-1').addClass('hide-d');
    $('#newshipping').prop('checked', false);
    $('#s-first-name').val(firstname);
    $('#saddress').val(billadd);    
    //$('#countrydd option[value="'+billcountry+'"]').attr("selected", "selected").trigger("change");
    $("#countrydd").val(billcountry).change();
    //$('#statedd option[value="'+billstate+'"]').attr("selected", "selected").trigger("change");
    $("#statedd").val(billstate).change();
    $('#s-city').val(billcity);
    $('#spostcode').val(postcode);
    $('#smobile').val(mobile);
    $('.newshipping').addClass('hide-d');
    var billAddressId = $("#billing_adddress_id").val();
    $("#shipping_adddress_id").val(billAddressId);
    $(".shipform :input").attr("readonly", true);

    $("#s-first-name").attr('required', 'required');
    $("#saddress").attr('required', 'required');
    $("#s-city").attr('required', 'required');
    $("#countrydd").attr('required', 'required');
    $("#statedd").attr('required', 'required');
    $("#spostcode").attr('required', 'required'); 
    $("#smobile").attr('required', 'required'); 

  }else{
    $('.shipform').addClass('hide-d');
    $('#shippingadd-1').removeClass('hide-d');
    $('.newshipping').removeClass('hide-d');
    var shipid=$("[name=shippingadd]:checked").val();
    $("#shipping_adddress_id").val(shipid);
    $(".shipform :input").attr("readonly", false);

    $("#s-first-name").removeAttr('required');
    $("#saddress").removeAttr('required');
    $("#s-city").removeAttr('required');
    $("#countrydd").removeAttr('required');
    $("#statedd").removeAttr('required');
    $("#spostcode").removeAttr('required'); 
    $("#smobile").removeAttr('required'); 

    $("#s-first-name").val('');
    $("#saddress").val('');
    $("#s-city").val('');
    $("#countrydd").val('');
    $("#statedd").val('');
    $("#spostcode").val('');
    $("#smobile").val('');
  }
}

function addCountry(e){
  var idCountry = $(e).val();
  $("#statedd").html('');
  $.ajax({
    url: "{{url('api/fetch-states')}}",
    type: "POST",
    data: {
        country_id: idCountry,
        _token: '{{csrf_token()}}'
    },
    dataType: 'json',
    success: function (result) {
        $('#statedd').html('<option value="">Select State</option>');
        $.each(result.states, function (key, value) {
            $("#statedd").append('<option value="' + value
                .id + '">' + value.name + '</option>');
        });
        $('#city-dd').html('<option value="">Select City</option>');
    }
  });
};

function validateShipping(){
  var sfirstname = $("#s-first-name").val();
  if( sfirstname == "") {
    $( "#sfirstname" ).removeClass('hide-d');
  } else {
    $( "#sfirstname" ).addClass('hide-d');
  }
  var saddress = $("#saddress").val();
  if( saddress == "") {
    $( "#saddress-err" ).removeClass('hide-d');
  } else {
    $( "#saddress-err" ).addClass('hide-d');
  }
  var scity = $("#s-city").val();
  if( scity == "") {
    $( "#scity-err" ).removeClass('hide-d');
  } else {
    $( "#scity-err" ).addClass('hide-d');
  }

  var countrydd = $("#countrydd").val();
  if( countrydd == "") {
    $( "#countrydd-err" ).removeClass('hide-d');
  } else {
    $( "#countrydd-err" ).addClass('hide-d');
  }
  var statedd = $("#statedd").val();
  if( statedd == "") {
    $( "#statedd-err" ).removeClass('hide-d');
  } else {
    $( "#statedd-err" ).addClass('hide-d');
  }

  var spostcode = $("#spostcode").val();
  if( spostcode == "") {
    $( "#spostcode-err" ).removeClass('hide-d');
  } else {
    $( "#spostcode-err" ).addClass('hide-d');
  }
  var smobile = $("#smobile").val();
  if( smobile == "") {
    $( "#smobile-err" ).removeClass('hide-d');
  } else {
    $( "#smobile-err" ).addClass('hide-d');
  }

  if($("#s-first-name").val() == "" || $("#saddress").val() == "" || $("#s-city").val() == "" || $("#spostcode").val() == "" || $("#smobile").val() == "" || $("#countrydd").val() == "" || $("#statedd").val() == "") {
    return false; 
  } else {
    return true;
  }
} 
  
function validateBilling(){
  var bfirstname = $("#b-first-name").val();
  if( bfirstname == "") {
    $( "#b-first-name-err" ).removeClass('hide-d');
  } else {
    $( "#b-first-name-err" ).addClass('hide-d');
  }
  var baddress = $("#baddress").val();
  if( baddress == "") {
    $( "#baddress-err" ).removeClass('hide-d');
  } else {
    $( "#baddress-err" ).addClass('hide-d');
  }
  var bcity = $("#b-city").val();
  if( bcity == "") {
    $( "#b-city-err" ).removeClass('hide-d');
  } else {
    $( "#b-city-err" ).addClass('hide-d');
  }
  var bcountry = $("#country-dd").val();
  if( bcountry == "") {
    $( "#country-dd-err" ).removeClass('hide-d');
  } else {
    $( "#country-dd-err" ).addClass('hide-d');
  }
  var bstate = $("#state-dd").val();
  if( bstate == "") {
    $( "#state-dd-err" ).removeClass('hide-d');
  } else {
    $( "#state-dd-err" ).addClass('hide-d');
  }
  var bpostcode = $("#b-postcode").val();
  if( bpostcode == "") {
    $( "#b-postcode-err" ).removeClass('hide-d');
  } else {
    $( "#b-postcode-err" ).addClass('hide-d');
  }
  var bmobile = $("#b-mobile").val();
  if( bmobile == "") {
    $( "#b-mobile-err" ).removeClass('hide-d');
  } else {
    $( "#b-mobile-err" ).addClass('hide-d');
  }

  if($("#b-first-name").val() == "" || $("#baddress").val() == "" || $("#b-city").val() == "" || $("#b-postcode").val() == "" || $("#b-mobile").val() == "" || $("#country-dd").val() == "" || $("#state-dd").val() == "") {
    return false;
  } else {
    return true;
  }
}

function onPlaceOrderClick(){
  var isBillingChecked = $('#newbilling')[0].checked;
  var isShippingChecked = $('#newshipping')[0].checked;
  var isvalidateBilling = isvalidateShipping = true;

  // alert(isBillingChecked);
  if(isBillingChecked) {
    isvalidateBilling = validateBilling(); 
  }
  if(isShippingChecked) {
    isvalidateShipping = validateShipping();
  }
  if(isvalidateBilling && isvalidateShipping){
    mySecurePayUI.tokenise();
  }
}

var mySecurePayUI = new securePayUI.init({
  containerId: 'securepay-ui-container',
  scriptId: 'securepay-ui-js',
  mode: 'dcc',
  checkoutInfo: {
    orderToken: '123456789012587412369857410dghdgdgdgdgd'
  },    
  clientId: "{{setting('payment-setting.client_id')}}",
  merchantCode: "{{setting('payment-setting.merchant_code')}}",
  card: {
      allowedCardTypes: ['visa', 'mastercard'],
      showCardIcons: false,
      onCardTypeChange: function(cardType) {
        // card type has changed
        // alert("card change");
      },
      onBINChange: function(cardBIN) {
        // card BIN has changed
      },
      onFormValidityChange: function(valid) {
        // validateFormFields();
      },
      onDCCQuoteSuccess: function(quote) {
        // dynamic currency conversion quote was retrieved 
      },
      onDCCQuoteError: function(errors) {
        // quote retrieval failed  
      },
      onTokeniseSuccess: function(tokenisedCard) {
        $("#securepayapi-token").val(tokenisedCard.token);
        jQuery.ajax({
            url: '/api/customer-payment',
            data: {
              securePayApiToken: tokenisedCard.token,
              saveCard: 0,
              user_id: @php echo $user_id; @endphp,
              _token: "{{ csrf_token() }}"
            },
            dataType: 'json',
            method: 'post',
            beforeSend: function () {
              $(".processing_loader").addClass("active"); 
            },
            complete: function () {
              // $(".processing_loader").removeClass("active"); 
            },
            success: function (json) {
              // console.log(json)
              if (json['success'] && json['orderId']) {
                $("#order_number").val(json['orderId']);
                $("#transaction_id").val(json['bankTransactionId']);
                setTimeout(function(){
                  // $(".processing_loader").removeClass("active"); 
                  $("#checkout-form").submit();
                }, 1000);
              } else {
                $(".processing_loader").removeClass("active"); 
                if (json['message']) {
                  $( ".card-wrapper" ).after( '<div id="transaction-error">'+json['message']+'</div>' );
                } else {
                  $( ".card-wrapper" ).after( '<div id="transaction-error">NOT Working</div>' );
                }
              }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
        // card was successfully tokenised or saved card was successfully retrieved 
      },
      onTokeniseError: function(errors) {
        // tokenization failed
      }
  },
  style: {
    // backgroundColor: 'rgba(135, 206, 250, 0.1)',
    // backgroundColor: 'rgb(232 246 253)',
    padding: '20px',
    label: {
      font: {
          family: 'Arial, Helvetica, sans-serif',
          size: '1.1rem',
          color: 'darkblue'
      }
    },
    input: {
     font: {
         family: 'Arial, Helvetica, sans-serif',
         size: '1.1rem',
         color: 'darkblue'
     }
   }  
  },
  onLoadComplete: function () {
    // the UI Component has successfully loaded and is ready to be interacted with
  }
});

</script>

<style type="text/css">
  /*.card-wrapper{ background: rgb(232 246 253); margin: 15px 0px; }*/
  .card-wrapper{ background: #fff; margin: 15px 0px; }
  #securepay-ui-container{ margin: 15px 0px; }
  #securepay-ui-container iframe{ width:100%; }
  #porder-num-err {
    color: #bc111e;
    font-family: Helvetica, sans-serif;
    font-size: 12px;
    font-weight: 400;
    margin: 4px 0 0;
    padding: 0;
  }
  .ph-form-element.cc-expiry {
    flex-basis: auto;
  }
  input#purchase_order_number {
    margin-bottom: 5px;
  }
  .reset_btn {
    background-color: #fff;
    color: #474747;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 16px;
    line-height: 19px;
    font-family: 'futura-med';
    border: 1px solid #474747;
    transition: all 0.3s;
    font-weight: 400;
    display: inline-block;
    overflow: hidden;
    position: relative;
    transition: all 0.3s;
    float: right;
  }
  #transaction-error{
    border: solid 1px #cfcfcf;
    padding: 10px;
    text-align: center;
    margin-bottom: 20px;
    color: red;
  }
  .processing_loader {
    display: none;
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    z-index: 99991;
    background-color: rgba(255, 255, 255, 0.5);
  }
  .processing_loader.active {
    display: block;
  }
  .inner_loader {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100vw;
    height: 100vh;
  }
  .process_message {
      background-color: #fff;
      padding: 30px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      gap: 15px;
      border-radius: 30px;
      box-shadow: 0px 0px 30px 0px #00000026;
      border: 1px solid #ddd;
      font-size: 24px;
      width: 100%;
      max-width: 545px;
      text-align: center;
      color: #5c5c5c;
  }
  .process_icon i{ font-size:40px; color: #007934; }
  span.data-label {
    display: inline-block;
    width: 140px;
  }
  #modal{ height: auto; }
  #modal .modal-header{ text-align: right; }
  #cart-details p.w-full{ margin-bottom: 7px; }
</style>
@endsection
