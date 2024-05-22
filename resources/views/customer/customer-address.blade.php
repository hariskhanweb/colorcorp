@extends('layouts.customer-layout')

@section('title', __('Addresses'))

@section('content')
   
 <section class="user_details lg:py-14 md:py-10 py-8">
  <div class="container mx-auto px-4">
    <div class="row flex flex-wrap">
      @include('customer.account-left')

      <div class="lg:w-3/4 w-full">
        <div class="lg:pl-12 pl-0 lg:pt-0 pt-6">
          <div class="flex lg:flex-row flex-col lg:justify-between lg:items-center">
            <h2 class="pb-6 lg:text-5xl md:text-4xl text-3xl font-futura">{{ __('Addresses') }}</h2>
            <div class="col-lg-8 address-0" style="text-align: right;">
              <a href="{{ route('addresses.add') }}" class="green_btn py-4 px-12"><span>{{ __('Add Address') }}</span></a>
            </div>
          </div>
          <section class="lg:py-14 md:py-10 py-8">
            <div class="max-w-screen-lg mx-auto">
              <div class="jm_product_outer_pg flex flex-wrap lg:pb-10 md:pb-8 pb-6">
                <div class="lg:w-3/4 w-full">
                  <h4 class="pb-6 lg:text-2xl md:text-3xl text-2xl font-futura">{{ __('Shiping Addresses') }}</h4>
                </div>
                @if(count($cusshipdata)>0)  
                @foreach($cusshipdata as $cusadd)
                <div class="jm_product_single xl:w-1/2 lg:w-1/2 md:w-1/2 w-full mb-5 text-center">
                  <div class="jm_product_outer product_card mx-3 @if($cusadd['default_address'] == '1') active @endif">                    
                    <div class="porduct_card_content address_card editable">
                      <div class="edit" >
                        <a class="editable" href="{{ route('addresses.edit',['id' => $cusadd['id']]) }}">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                          </svg>
                        </a> 
                        <a class="delete show_confirm" data-id="{{ $cusadd['id'] }}">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                          </svg>
                        </a>
                      </div>
                      @php 
                        $state=Helper::getStateName($cusadd['state_id']);
                        $country=Helper::getCountryName($cusadd['country_id']);
                        $state_name=(!empty($state))?$state->name:"";
                        $country_name=(!empty($country))?$country->name:"";
                      @endphp
                      <div class="address_card_street billadd1" >{{$cusadd['address']}}</div>
                      <div class="address_card_country">
                        <span class="billcity1">{{$cusadd['city']}}</span>, 
                        <span class="billstate1" data-state="{{$cusadd['state_id']}}">{{$state_name}}, {{$cusadd['country_id']}}</span>,
                        <span class="postcode1">{{$cusadd['postcode']}}</span>
                      </div>
                      <div class="address_card_country billcountry" data-country="{{$cusadd['country_id']}}">{{$country_name}}</div>
                      <div class="address_card_mobile bmobile1">{{$cusadd['mobile_number']}}</div>
                    </div>
                  </div>
                </div>
                @endforeach
                
                @else
                <div class="w-full px-4" role="alert">
                  <div class="flex item-center p-4 bg-gray-100 rounded-md">
                    <svg class="fill-current h-6 w-6 text-green-700 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                      <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                    </svg>
                    <p>{{ __('No shipping address record found.') }}</p>
                  </div>
                </div>
              </div>
              @endif 


              <div class="lg:w-3/4 w-full">
                <h4 class="pb-6 lg:text-2xl md:text-3xl text-2xl font-futura">{{ __('Billing Addresses') }}</h4>
              </div>
              @if(count($cusbilldata)>0) 
              @foreach($cusbilldata as $cusadd)
              <div class="jm_product_single xl:w-1/2 lg:w-1/2 md:w-1/2 w-full mb-5 text-center">
                <div class="jm_product_outer product_card mx-3 @if($cusadd['default_address'] == '1') active @endif">
                    <div class="porduct_card_content address_card editable">
                        <div class="edit" >
                          <a class="editable" href="{{ route('addresses.edit',['id' => $cusadd['id']]) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                          </a> 
                          <a class="delete show_confirm" data-id="{{ $cusadd['id'] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                          </a>
                        </div>
                        @php 
                          $state=Helper::getStateName($cusadd['state_id']);
                          $country=Helper::getCountryName($cusadd['country_id']);
                          $state_name=(!empty($state))?$state->name:"";
                          $country_name=(!empty($country))?$country->name:"";
                        @endphp
                        <div class="address_card_street billadd1" >{{$cusadd['address']}}</div>
                        <div class="address_card_country">
                          <span class="billcity1">{{$cusadd['city']}}</span>, 
                          <span class="billstate1" data-state="{{$cusadd['state_id']}}">{{$state_name}}, {{$cusadd['country_id']}}</span>,
                          <span class="postcode1">{{$cusadd['postcode']}}</span>
                        </div>
                        <div class="address_card_country billcountry" data-country="{{$cusadd['country_id']}}">{{$country_name}}</div>
                        <div class="address_card_mobile bmobile1">{{$cusadd['mobile_number']}}</div>
                    </div>
                  </div>
                </div>
                @endforeach
                @else
                <div class="w-full px-4" role="alert">
                  <div class="flex item-center p-4 bg-gray-100 rounded-md">
                    <svg class="fill-current h-6 w-6 text-green-700 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                      <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                    </svg>
                    <p>{{ __('No billing address record found.') }}</p>
                  </div>
                </div>
                @endif 
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</section>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog" style="right:10px;">
        <div class="modal-dialog">
            <div class="modal-content">               
                <div class="modal-footer d-flex justify-content-center align-items-center">
                    <form action="#" id="delete_form" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="address_id" id="address_id" value="">
                        <input type="submit" class="btn btn-primary delete-confirm" value="{{ __('Yes, Delete it!') }}" style="margin-top: 15px!important;">
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@section('javascript')
@if (session()->has('message'))
<script type="text/javascript">
    jQuery(document).ready(function(){    
        jQuery('#sucessModal').modal('show');
    });
</script>
@endif

@endsection
@stop