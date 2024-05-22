@extends('layouts.customer-layout')
@section('title', __('My Order'))
@section('content')
@php
$shopslug=Helper::getShopslug(Auth::user()->vendor_id);
@endphp

<section class="user_details lg:py-14 md:py-10 py-8">
  <div class="container mx-auto px-4">
    <div class="row flex flex-wrap">
    @include('customer.account-left')
              
    <div class="lg:w-3/4 w-full lg:pl-12 pl-0 lg:pt-0 pt-6">
        <h2 class="lg:pb-12 pb-6 lg:text-5xl md:text-4xl text-3xl font-futura">{{ __('My orders') }}</h2>

        @if(count($data)>0)
          <div class="table-responsive w-full text-left">
            <table class="table order_table">
              <thead>
                <tr>
                  <th>{{ __('Order') }}</th>
                  <th>{{ __('Subtotal') }}</th>
                  <th>{{ __('Tax') }}</th>
                  <th>{{__('GST Amount')}}</th>
                  <th>{{__('Total Amount')}}</th>
                  <th>{{ __('Transaction Id') }}</th>
                  <th>{{ __('Status') }}</th>
                  <th>{{ __('Created At') }}</th>
                  <th>{{ __('Action') }}</th>
                </tr>
              </thead>
              <tbody>
              @if(!empty($data) && count($data)>0)
                @foreach($data as $key => $orderdata)
                <tr>
                  <td>{{$orderdata->order_number}}</td>
                  <td>{{setting('payment-setting.currency')." ".number_format($orderdata->subtotal,2)  }}</td>
                  <td>{{$orderdata->tax}}%</td>
                  <td>{{setting('payment-setting.currency')." ".number_format($orderdata->gst,2)  }}</td>
                  <td>{{setting('payment-setting.currency')." ".number_format($orderdata->total_amount,2)  }}</td>
                  <td>{{$orderdata->transaction_id ??'NA'}}</td>
                  <td>
                    @if($orderdata->status===0)
                        {{__('Trash')}}
                    @elseif($orderdata->status===2)
                        {{__('Completed')}}
                    @else
                        {{__('Pending')}}
                    @endif
                  <td> {{$orderdata->created_at->format('d/m/Y')}}</td>
                  <td>
                    <div class="myorder-action-div">
                      <a href="{{ route('view.order', ['id' => $orderdata->id]) }}"
                        class="green_btn text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                      </a>
                      <form class="w-full lg:px-0 md:px-0" method="POST" action="{{ route('add.cart.to.reorder') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="parent_order_id" value="{{ $orderdata->id }}">
                        <button type="submit" class="green_btn">           
                          <span><i class="fa fa-shopping-cart" title="Re-order" style="font-size: 20px;"></i></span>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
          </div>
          @else
          <div class="w-full" role="alert">
            <div class="flex items-center p-4 bg-gray-100 rounded-md justify-between">
              <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-green-600">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <p>{{ __('No record found.') }}</p>
              </div>
              <a href="{{ route('shop', ['vendor_name' => $shopslug ])}}" class="green_btn block text-center">{{ __('Shop Now') }}</a>
            </div>
          </div>
        @endif
              
        </div>
    </div>
  </div>
</section>
@stop