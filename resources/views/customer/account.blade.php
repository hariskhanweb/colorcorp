@extends('layouts.customer-layout')

@section('title', __('My Account'))

@section('content')
  <section class="user_details lg:py-14 md:py-10 py-8">
  <div class="container mx-auto px-4">
    <div class="row flex flex-wrap">
    @include('customer.account-left')

    <div class="lg:w-3/4 w-full">
      <div class="lg:pl-12 pl-0 lg:pt-0 pt-6">
          <h2 class="pb-6 lg:text-5xl md:text-4xl text-3xl font-futura">
          {{ __('My Account') }}
        </h2>
          <form class="w-full" method="POST"  action="{{ route('account.update') }}" >
            {{ csrf_field() }}
            <div class="flex flex-wrap -mx-3 mb-6">
              <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                  {{ __('Name') }}
                </label>
                <input type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="name" value="{{$data->name}}" required>
                @if($errors->has('name'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                  </span>
                @endif
              </div>
              <div class="w-full md:w-1/2 px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                   {{ __('E-mail') }}
                </label>
                <input type="email" disabled class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="email" value="{{$data->email}}" required readonly="" />
              </div>
              <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                    {{ __('Mobile number') }}
                </label>
                <input type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="mobile_number" value="{{$data->mobile_number}}" required>
                @if($errors->has('mobile_number'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('mobile_number') }}</strong>
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
