@extends('layouts.customer-layout')

@section('title', __('Cart'))

@section('content')
@php
$shopslug=Helper::getShopslug(Auth::user()->vendor_id);
@endphp
<div class="shopping-cart container mx-auto lg:py-14 py-8 px-3">
  <h1 class="pb-6 lg:text-5xl md:text-4xl text-3xl font-futura">{{ __('Cart') }}</h1>
  <div class="flex flex-wrap">
    <div class="lg:w-9/12 w-full lg:pr-8 table-responsive">
      @if(count($cartdata)>0)      
      <table class="table w-full table-fixed text-left">
        <thead>
          <tr>
            <th class="product_removed">&nbsp;</th>
            <th class="product_image">{{ __('Product') }}</th>
            <th class="product_name">&nbsp;</th>
            <th class="product_price">{{ __('Price') }}</th>
            <th class="product_qty">{{ __('Quantity') }}</th>
            <th class="product_total">{{ __('Total') }}<br><small>{{ __('(Including GST)')}}</small></th>
          </tr>
        </thead>
        <tbody>
          @php $carttotal=0; @endphp
          @foreach($cartdata as $cart_data)
          @php
            $attribute=unserialize($cart_data->attribute);
            $carttotal=$carttotal+$cart_data->pro_att_price*$cart_data->pro_qty;
          @endphp
            <tr>
              <td class="product_removed">
                <button class="removeproduct" onclick="deleteItem(this)" data-id="{{$cart_data->id}}">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </td>
              @php $fimage = Helper::getFeaturedImage($cart_data->product_id); @endphp
              <td class="product_image"><img src="{{ asset('storage/'.$fimage) }}" alt="" class="product_img mr-3"></td>
              <td class="product_name">
                @if(isset($cart_data->getCartProduct->name))
                {{$cart_data->getCartProduct->name}}
                @endif
                <div class="content_{{$cart_data->id}} hide view_attribute" style="display:none;">
                  @if($cart_data->vehicle_make != '')
                  <p class="w-full text-gray-500 text-sm">
                    <b>{{ __('Vehicle Make') }}-</b> {{$cart_data->vehicle_make}}
                  </p>
                  @endif
                  @if($cart_data->vehicle_model != '')
                  <p class="w-full text-gray-500 text-sm">
                    <b>{{ __('Vehicle Model') }}-</b> {{$cart_data->vehicle_model}}
                  </p>
                  @endif
                  @if($cart_data->vehicle_colour != '')
                  <p class="w-full text-gray-500 text-sm">
                    <b>{{ __('Vehicle Colour') }}-</b> {{$cart_data->vehicle_colour}}
                  </p>
                  @endif
                  @if($cart_data->vehicle_year != '')
                  <p class="w-full text-gray-500 text-sm">
                    <b>{{ __('Vehicle Year') }}-</b> {{$cart_data->vehicle_year}}
                  </p>
                  @endif
                  @if($cart_data->vehicle_rego != '')
                  <p class="w-full text-gray-500 text-sm">
                    <b>{{ __('Vehicle Rego') }}-</b> {{$cart_data->vehicle_rego}}
                  </p>
                  @endif
                  @if($cart_data->franchise_territory != '')
                  <p class="w-full text-gray-500 text-sm">
                    <b>{{ __('Franchise Territory') }}- </b>{{$cart_data->franchise_territory }}
                  </p>
                  @endif
                  @if($cart_data->franchise_name != '')
                  <p class="w-full text-gray-500 text-sm">
                    <b>{{ __('Franchise Name') }}-</b> {{$cart_data->franchise_name }}
                  </p>
                  @endif 

                  
                  @php 
                  $product_text_attr = [];
                  if($cart_data->product_text_attr != '') {
                    $product_text_attr = @unserialize($cart_data->product_text_attr);
                  }
                  @endphp
                  

                  @if(!empty($attribute) || !empty($product_text_attr))
                  <p class="product-title lg:text-base text-gray-900">
                    <b>{{ __('Product Attribute') }}</b>
                  </p>

                  @if($product_text_attr)
                    @foreach($product_text_attr as $id => $attr_val)
                    @php $attr_option_name = Helper::getTextAttributeById($id); @endphp
                    <p class="w-full text-gray-500 text-sm">
                      <b>{{$attr_option_name}}-</b>{{$attr_val}}
                    </p>
                    @endforeach
                  @endif
                  

                  @if(!empty($attribute))
                  @foreach($attribute as $value)
                  
                  @if(!empty($value))
                  @php
                  $value1 = explode('-', $value);
                  $arrtibute_option=Helper::getProAttributeById($value1[0]);
                  if($arrtibute_option['option']->options=="Yes/No"){
                  $option_name=$value1[1];
                  }else{
                  $option_name=$arrtibute_option['option']->options;
                  }
                  @endphp
                  @if(!empty($value1))
                  <p class="w-full text-gray-500 text-sm">
                    <b>{{$arrtibute_option['attribute']->name}}-</b>{{$option_name}}
                  </p>
                  @endif
                  @endif
                  @endforeach
                  @endif 
                  @endif
                </div>
                <div class="show_hide_{{$cart_data->id}} text-sm text-gray-700" style="cursor: grab;" data-id="{{$cart_data->id}}" onClick="showMore(this)" data-content="toggle-text">Show More</div>
              </td>
              <td class="product_price">{{setting('payment-setting.currency')}}{{number_format($cart_data->pro_att_price, 2)}}</td>
              <td class="product_qty">
                <input type="number" value="{{$cart_data->pro_qty}}" min="1" data-id="{{$cart_data->id}}" data-price="{{$cart_data->pro_att_price}}" onClick="updateItem(this)" onchange="updateItem(this)">
              </td>
              <td class="product_total">
                @php
                  $total_gst=(($cart_data->pro_att_price*$cart_data->pro_qty)*setting('tax-setting.gst'))/100;
                  $total_gst = round($total_gst);
                @endphp
                <div class="totalsvalue cart_total" id="cart-total_{{$cart_data->id}}">
                  {{setting('payment-setting.currency')}}{{number_format(($cart_data->pro_att_price*$cart_data->pro_qty)+$total_gst, 2)}}
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="lg:w-3/12 w-full lg:p-4 p-3 bg-gray-100 rounded mt-4 lg:mt-0">
      <h4 class="lg:text-xl mb-3">{{ __('Cart totals') }}</h4>
      <div class="totals">
        <div class="totals-item flex flex-wrap justify-between mb-3">
          <div class="">
            <p>{{ __('Sub Total') }}</p>
          </div>
          <div class="totalsvalue" id="cart-subtotal">{{setting('payment-setting.currency')}}{{number_format($carttotal, 2)}}</div>
        </div>
        @php
        $gst=($carttotal*setting('tax-setting.gst'))/100;
        $gst = round($gst);
        @endphp
        <div class="totals-item flex flex-wrap justify-between mb-3">
          <div class="">
            <p>GST ({{setting('tax-setting.gst')}}%)</p>
          </div>
          <div class="totalsvalue" id="cart-tax">{{setting('payment-setting.currency')}}{{number_format($gst, 2)}}</div>
        </div>
        <!-- <div class="totals-item flex flex-wrap justify-between mb-3">
          <div class=""><p>Shipping</p></div>
          <div class="totals-value" id="cart-shipping">15.00</div>
        </div> -->
        <div class="totals-item totals-item-total flex flex-wrap justify-between mb-3">
          <div class="">
            <p>{{ __('Grand Total') }}</p>
          </div>
          <div class="totalsvalue" id="order-total">{{setting('payment-setting.currency')}}{{number_format($gst+$carttotal, 2)}}</div>
        </div>
      </div>
      <a href="{{ route('cartCheckout', ['vendor_name' => $shopslug ])}}" class="green_btn block text-center"><span>{{ __('Proceed to checkout') }}</span></a>
    </div>
  </div>
   
  @else
  <div class="w-full" role="alert">
    <div class="flex items-center p-4 bg-gray-100 rounded-md justify-between">
      <div class="flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-green-600">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
        </svg>
        <p>{{ __('Your cart is currently empty.') }}</p>
      </div>
      <a href="{{ route('shop', ['vendor_name' => $shopslug ])}}" class="green_btn block text-center">{{ __('Shop Now') }}</a>
    </div>
  </div>
  @endif

</div>


<script type="text/javascript">
  function updateItem(e) {
    let cartid = $(e).attr('data-id');
    let price = $(e).attr('data-price');
    let qty = $(e).val();
    var action = "{{url('updatecart')}}";
    if (qty > 0) {
      updateService(cartid, qty, price, action);
    }
  }

  function updateService(cartid, qty, price, action) {
    $.ajax({
      type: 'POST',
      url: action,
      data: {
        cartid: cartid,
        qty: qty,
        price: price,
        _token: "{{ csrf_token() }}",
      },
      dataType: 'JSON',
      success: function(data) {
        if (data.success == 1) {
          $("#cart-total_" + cartid).text(data.item_total);
          $("#cart-subtotal").text(data.cart_total);
          $("#cart-tax").text(data.gst);
          $("#order-total").text(data.total);
        }
      }
    });
  }


  function showMore(e) {
    let cartid = $(e).attr('data-id');
    var txt = $(".content_" + cartid).is(':visible') ? 'Show More' : 'Show Less';
    $(".show_hide_" + cartid).text(txt);
    $('.content_' + cartid).slideToggle(200);
  }

  function deleteItem(e) {
    let cartid = $(e).attr('data-id');
    var action = "{{url('deletecart')}}";
    swal({
        title: `Are you sure you want to delete this record?`,
        text: "If you delete this, it will be gone forever.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          publishService(cartid, action);
        }
      });

    function publishService(cartid, action) {
      $.ajax({
        type: 'POST',
        url: action,
        data: {
          cartid: cartid,
          _token: "{{ csrf_token() }}",
        },
        success: function(r) {

          location.reload();
        }
      });
    }
  }
</script>

@endsection