@component('mail::message')
<p>
  <!-- <img src="{{ url('img/email/user-reset-password.png') }}" width="100%" class="hero" alt="Colorcorp Logo"> -->
  <img src="http://www.colorcorp.com.au/wp-content/uploads/order.png" width="100%" class="hero" alt="Colorcorp Logo">
</p>
<p>Hello {{ $details['username'] }},</p>
<h1>Thanks for shopping with us</h1>
<p>Your recent order on {{ env('APP_NAME') }} has been {{$details['status']}}. Your order details are shown below for your reference:</p>
<h2>Order: {{ $details['data1']->order_number}}</h2>
<p><b>Transaction ID # {{ $details['data1']->transaction_id}}</b></p>
<table class="order-table">
  <thead>
    <tr>
      <th>{{ __('Product Name') }}</th>
      <th>{{ __('Product Specification') }}</th>
      <th>{{ __('Price') }}</th>
      <th>{{ __('QTY') }}</th>
      <th>{{ __('Total') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($details['data1']['orderItems'] as $key => $value)
    @php
    $fimage = App\Helpers\Helpers::getFeaturedImage($value->product_id);
    @endphp
    <tr>
      <td class="product-image">
        <img src="{{ asset('storage/'.$fimage) }}" class="img-responsive" width="40" height="40" />
        {{$value->name}}
      </td>
      <td class="product-attribute">
        <p><strong>{{__('Product')}} :</strong> {{$value->name}}</p>
        @if($value->vehicle_make != '')
        <p><strong>{{__('Vehicle Make')}} :</strong> {{$value->vehicle_make}}</p>
        @endif
        @if($value->vehicle_model != '')
        <p><strong>{{__('Vehicle Model')}} :</strong> {{$value->vehicle_model}}</p>
        @endif
        @if($value->vehicle_colour != '')
        <p><strong>{{__('Vehicle Colour')}} :</strong> {{$value->vehicle_colour}}</p>
        @endif
        @if($value->vehicle_year != '')
        <p><strong>{{__('Vehicle Year')}} :</strong> {{$value->vehicle_year}}</p>
        @endif
        @if($value->vehicle_rego != '')
        <p><strong>{{__('Vehicle Rego')}} :</strong> {{$value->vehicle_rego}}</p>
        @endif
        @if($value->franchise_territory != '')
        <p><strong>{{__('Franchise Territory')}} :</strong> {{$value->franchise_territory}}</p>
        @endif
        @if($value->franchise_name != '')
        <p><strong>{{__('Franchise Name')}} :</strong> {{$value->franchise_name}}</p>
        @endif
        <p><strong>{{__('Decal Removel')}} :</strong> {{$value->decal_removel}}</p>
        <p><strong>{{__('Re Scheduling Fee')}} :</strong> {{$value->re_scheduling_fee}}</p>
        <p><strong>{{__('Preparation Fee')}} :</strong> {{$value->preparation_fee}}</p>
        <p><strong>{{__('Comment')}} :</strong> {{$value->comment}}</p>
        @if(!empty($value->orderItemsAttribute) && count($value->orderItemsAttribute)>0)
        <p class="product-title"><b>{{ __('Product Attribute') }}</b></p>
        @foreach($value->orderItemsAttribute as $attribute)
        @if(!empty($attribute))
        <p><b>{{$attribute->name}}-{{$attribute->type}}-</b>{{$attribute->type_value}}</p>
        @endif
        @endforeach
        @endif
      </td>
      <td>{{setting('payment-setting.currency')." ".number_format($value->pro_att_price,2) }}</td>
      <td>{{$value->quantity}}</td>
      <td>{{setting('payment-setting.currency')." ".number_format(($value->quantity*$value->pro_att_price),2)}}</td>
    </tr>
    @endforeach
    <tr>
      <td style="text-align: right;" colspan="3">{{ __('Subtotal:') }}</td>
      <td style="text-align: right;" colspan="2">
        {{setting('payment-setting.currency')." ".number_format($details['data1']->subtotal,2) }}
      </td>
    </tr>
    <tr>
      <td style="text-align: right;" colspan="3">{{ __('GST Tax:') }}</td>
      <td style="text-align: right;" colspan="2">{{$details['data1']->tax}}%</td>
    </tr>
    <tr>
      <td style="text-align: right;" colspan="3">{{ __('GST Tax Amount:') }}</td>
      <td style="text-align: right;" colspan="2">{{setting('payment-setting.currency')." ".number_format($details['data1']->gst,2) }}</td>
    </tr>
    <tr>
      <td style="text-align: right;" colspan="3">{{ __('total:') }}</td>
      <td style="text-align: right;" colspan="2">{{setting('payment-setting.currency')." ".number_format($details['data1']->total_amount,2) }}</td>
    </tr>
  </tbody>
</table>
<table class="order-table layout-fixed order-table-info" style="margin-top: 30px;">
  <thead>
    <tr>
      <th>{{__('Shipping Address')}}</th>
      <th>{{__('Billing Address')}}</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><b>{{__('Name')}} :</b> <span>{{$details['data1']->shippingAddress['name']??'NA'}}</span></td>
      <td><b>{{__('Name')}} :</b> <span>{{$details['data1']->billingAddress['name']??'NA'}}</span></td>
    </tr>
    <tr>
      <td><b>{{__('Address')}} :</b> <span>{{$details['data1']->shippingAddress['address']??'NA'}}</span></td>
      <td><b>{{__('Address')}} :</b> <span>{{$details['data1']->billingAddress['address']??'NA'}}</span></td>
    </tr>
    <tr>
      <td><b>{{__('City')}} :</b> <span>{{$details['data1']->shippingAddress['city']??"NA"}}</span></td>
      <td><b>{{__('City')}} :</b> <span>{{$details['data1']->billingAddress['city']??"NA"}}</span></td>
    </tr>
    <tr>
      <td><b>{{__('State')}} :</b> <span>{{$details['data1']->shippingAddress->stateName->name??"NA"}}</span></td>
      <td><b>{{__('State')}} :</b> <span>{{$details['data1']->billingAddress->stateName->name??"NA"}}</span></td>
    </tr>
    <tr>
      <td><b>{{__('Country')}} :</b> <span>{{$details['data1']->shippingAddress->countryName->name??"NA"}}</span></td>
      <td><b>{{__('Country')}} :</b> <span>{{$details['data1']->billingAddress->countryName->name??"NA"}}</span></td>
    </tr>
    <tr>
      <td><b>{{__('Mobile')}} :</b> <span>+{{$details['data1']->shippingAddress['mobile_number']??"NA"}}</span></td>
      <td><b>{{__('Mobile')}} :</b> <span>+{{$details['data1']->billingAddress['mobile_number']??"NA"}}</span></td>
    </tr>
    <tr>
      <td><b>{{__('PostCode')}} :</b> <span>{{$details['data1']->shippingAddress['postcode']??"NA"}}</span></td>
      <td><b>{{__('PostCode')}} :</b> <span>{{$details['data1']->billingAddress['postcode']??"NA"}}</span></td>
    </tr>
  </tbody>
</table>

@if(!empty($details['note']))
<p>Note:- {{ $details['note'] }}</p>
@endif
@endcomponent