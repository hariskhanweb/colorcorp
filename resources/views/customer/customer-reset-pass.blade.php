@extends('layouts.customer-layout')

@section('title', __('Reset Password'))

@section('content')
   
 <section class="user_details lg:py-14 md:py-10 py-8">
  <div class="container mx-auto px-4">
    <div class="row flex flex-wrap">
    @include('customer.account-left')

    <div class="lg:w-3/4 w-full">
      <div class="lg:pl-12 pl-0 lg:pt-0 pt-6">
          <h2 class="pb-6 lg:text-5xl md:text-4xl text-3xl font-futura">
          {{ __('Reset Password') }}
        </h2>
          <form class="w-full"  method="POST" action="{{ route('reset.password') }}">
            {{ csrf_field() }}
             @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{Session::get('error')}}
                </div>
            @endif
           
          <div class="flex flex-wrap -mx-3 mb-6">
              <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                {{ __('Password') }}
              </label>
              <input id="password" type="password" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500
               @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
               @error('password')
                <span class="invalidfeedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="w-full md:w-1/2 px-3 mb-6">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                {{ __('Confirm Password') }}
              </label>
              <input id="password-confirm" type="password" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="password_confirmation" required autocomplete="new-password"> 
              @error('password_confirmation')
              <span class="invalidfeedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
              @enderror
              </div>
              
          </div>
          <button type="submit" class="green_btn py-4 px-12"><span>{{ __('Reset Password') }}</span></button>  
      </form>
      
      </div>
  </div>

  </div>
    </div>
</section>

@endsection
