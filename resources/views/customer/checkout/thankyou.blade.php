<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thank You</title>    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
</head>
<body>
@php
    $customer_email = Auth::user()->email;
@endphp
<section class="bg-white dark:bg-gray-900 cp-tq-page">
    <div class="flex items-center justify-center">
        <div class="container mx-auto">
            <div class="flex flex-col items-center space-y-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="chech--icon text-green-600 w-40 h-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h1 class="text-5xl font-bold">Thank You !</h1>
            <p class="text-lg">{{ __("Your order $order_number has been received!.")}}</p>
            <p class="text-lg">{{ __("We sent an email to ")}} <b>{{ $customer_email }}</b> {{ __("with your order confirmation and order detail.")}}</p>
            
            @if(!empty($orderrecord)) 
            <div class="table-responsive w-full">
                <div class="w-full text-left bg-gray-100 p-6 rounded-lg">
                    <table class="table w-full thank_table">
                        <thead>
                        <tr>
                            <th>{{ __('Order Number') }}</th>
                            <th>{{ __('Subtotal') }}</th>
                            <th>{{__('GST')}}</th>
                            <th>{{__('Total Amount')}}</th>
                            <th>{{ __('Transaction Id') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created At') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{$orderrecord->order_number}}</td>
                            <td>{{setting('payment-setting.currency')." ".number_format($orderrecord->subtotal,2)  }}</td>
                            <td>{{setting('payment-setting.currency')." ".number_format($orderrecord->gst,2)  }}</td>
                            <td>{{setting('payment-setting.currency')." ".number_format($orderrecord->total_amount,2)  }}</td>
                            <td>{{$orderrecord->transaction_id ??'NA'}}</td>
                            <td>
                            @if($orderrecord->status===0)
                                {{__('Trash')}}
                            @elseif($orderrecord->status===2)
                                {{__('Completed')}}
                            @else
                                {{__('Pending')}}
                            @endif
                            <td> {{$orderrecord->created_at->format('d/m/Y')}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <a href="{{route('my.order')}}" class="cp-btn w-1/2 px-6 py-3 text-md flex items-center justify-center gap-x-2 tracking-wide text-white transition-colors duration-200 bg-indigo-500 rounded-lg shrink-0 sm:w-auto hover:bg-indigo-600 dark:hover:bg-indigo-500 dark:bg-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:rotate-180">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                    </svg>
                    <span>Check your Order</span>
                </a>
            </div>
        </div>
        </div>
</section>
<style>
.cp-tq-page {
    padding: 50px 15px;
}
.cp-tq-page h1 {
    font-size: 2.5rem;
}
.cp-tq-page .cp-btn {
    width: auto;
}
@media screen and (max-width: 767px) {
.cp-tq-page .chech--icon {
    width: 8rem;
    height: 8rem;
}    
.cp-tq-page table.table > thead tr th {
    font-size: 14px;
}
}
</style>
</body>
</html>