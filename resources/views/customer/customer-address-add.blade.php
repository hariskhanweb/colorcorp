@extends('layouts.customer-layout')

@section('title', __('Add Address'))

@section('content')


    <section class="user_details lg:py-14 md:py-10 py-8">
  <div class="container mx-auto px-4">
    <div class="row flex flex-wrap">
    @include('customer.account-left')

    <div class="lg:w-3/4 w-full">
      <div class="lg:pl-12 pl-0 lg:pt-0 pt-6">
          <h2 class="pb-6 lg:text-5xl md:text-4xl text-3xl font-futura">
         {{ __('Addresses') }}
        </h2>
          <form class="w-full"  method="POST" action="{{ route('addresses.create') }}" enctype="multipart/form-data" >
            {{ csrf_field() }}
            <div class="flex flex-wrap -mx-3 mb-6">
              <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                 {{ __('Country') }}
                </label>
                 <select  name="country" id="country-dd" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                    <option value="">Select Country</option>
                    @foreach ($countries as $value)
                    <option value="{{$value->id}}">
                        {{$value->name}}
                    </option>
                    @endforeach
                </select>
                @if($errors->has('country'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('country') }}</strong>
                  </span>
                @endif
              </div>
              <div class="w-full md:w-1/2 px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                  {{ __('State') }}
                </label>
                <select id="state-dd" name="state" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </select>
                @if($errors->has('state'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('state') }}</strong>
                  </span>
                @endif
              </div>

              <div class="w-full md:w-1/2 px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                  {{ __('City') }}
                </label>
                <input type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="city" value="" required> 
                @if($errors->has('city'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('city') }}</strong>
                  </span>
                @endif
              </div>

              <div class="w-full md:w-1/2 px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                  {{ __('Address') }}
                </label>
                <input type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="address" value="" required>
                @if($errors->has('address'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('address') }}</strong>
                  </span>
                @endif
              </div>

              <div class="w-full md:w-1/2 px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                  {{ __('Postcode') }}
                </label>
                <input type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="postcode" value="" required>
                @if($errors->has('postcode'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('postcode') }}</strong>
                  </span>
                @endif
              </div>

              <div class="w-full md:w-1/2 px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                  {{ __('Mobile Number') }}
                </label>
                <input type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="mobile_number" value="" required>
                @if($errors->has('mobile_number'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('mobile_number') }}</strong>
                  </span>
                @endif
              </div>

              <div class="w-full md:w-1/2 px-3 mb-6">
                <label class="custom-control-label flex bg-gray-100 py-2 px-3 space-x-2 rounded-md cursor-pointer" for="default_address">
                  <input type="checkbox" name="default_address" class="custom-control-input" id="default_address">
                  <span>{{ __('Default Address!') }}</span>
                </label>
                @if($errors->has('default_address'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('default_address') }}</strong>
                  </span>
                @endif
              </div>

              <div class="w-full md:w-1/2 px-3 mb-6 flex items-center lg:flex-row flex-col space-x-4">
                
                <label class="custom-control-label flex bg-gray-100 py-2 px-3 space-x-2 rounded-md cursor-pointer" for="address_shipping">
                  <input type="radio"  value="0" name="address_type" class="custom-control-input" id="address_shipping" checked>
                  <span>{{ __('Shipping Address!') }}</span>
                </label>

                <label class="custom-control-label flex bg-gray-100 py-2 px-3 space-x-2 rounded-md cursor-pointer" for="address_billing">
                  <input type="radio"  value="1" name="address_type" class="custom-control-input" id="address_billing">
                  <span>{{ __('Billing Address!') }}</span>
                </label>

                @if($errors->has('address_type'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('address_type') }}</strong>
                </span>
                @endif
              </div>
            </div>
          <button type="submit" class="green_btn py-4 px-12"><span>{{ __('Save changes')}}</span></button>  
      </form>
      
      </div>
  </div>

  </div>
    </div>
</section>

@endsection
