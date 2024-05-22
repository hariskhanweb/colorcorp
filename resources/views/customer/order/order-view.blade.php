@extends('layouts.customer-layout')
@section('title', __('My Order'))
@section('content')
<style>
.user_details td img{ display: inline-block; margin-right: 5px; }
</style>   
 <section class="user_details lg:py-14 md:py-10 py-8">
  <div class="container mx-auto px-4">
    <div class="row flex flex-wrap">
      @include('customer.account-left')
         
      <div class="lg:w-3/4 w-full lg:pl-12 pl-0 lg:pt-0 pt-6">
        <h2 class="lg:text-2xl md:text-1xl text-xl font-futura flex lg:flex-row flex-col justify-between lg:items-center mb-6 border-b pb-4">
          <span>{{__('Order Summary')}}</span>
          <a href="{{ route('invoice.order', ['id'=> $data->id] )}}" class="inline-flex items-center green_btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>

            <span>{{__('Download Invoice')}}</span>
          </a>
          @if($ICdata)
            @if($ICdata->status==2)
            <a href="{{ route('invoice.installation.pdf', ['id'=> $ICdata->id] )}}" class="inline-flex items-center green_btn">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
              </svg>

              <span>{{__('Download Installation Invoice')}}</span>
            </a>
            @endif
          @endif
        </h2>
        <div class="flex flex-wrap pb-4 mb-3">
          <div class="lg:w-3/12 w-4/12 flex items-center">
            <label >{{ __('Order Number:-') }}</label>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center">
             <span>{{ $data->order_number}}</span>
          </div>           
          <div class="lg:w-3/12 w-4/12 flex items-center">
            <label >{{ __('Subtotal:-') }}</label>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center">  
              <span>{{setting('payment-setting.currency')."".number_format($data->subtotal,2) }}</span>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center">  
            <label >{{ __('Order Status:-') }}</label>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center">    
            <span><b></b> 
              <?php  if($data->status == 2) { 
                  echo "<b style='color:green;'>Completed</b>";
              } else if($data->status == 0) { 
                  echo "<b style='color:red;'>Trash</b>";
              } else { 
                  echo "<b style='color:red;'>Pending</b>";
              } ?>
            </span>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center">  
            <label >{{ __('Tax:-') }}</label>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center"> 
            <span>{{$data->tax}}%</span>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center">  
            <label >{{ __('Transaction Id:-') }}</label>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center"> 
            <span>{{$data->transaction_id ??'NA'}}</span>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center">  
            <label >{{ __('GST Amount:-') }}</label>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center"> 
            <span>{{ setting('payment-setting.currency')."".number_format($data->gst,2) }}</span>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center">  
            <label >{{ __('Payment Status:-') }}</label>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center"> 
            <span><b></b> 
              <?php  if($data->status == 2) { 
                echo "<b style='color:green;'>Completed</b>";
              } else if($data->status == 0) { 
                echo "<b style='color:red;'>Trash</b>";
              } else { 
                echo "<b style='color:red;'>Pending</b>";
              } ?>
            </span>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center">  
            <label >{{ __('Total Amount:-') }}</label>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center"> 
            <span>{{setting('payment-setting.currency')."".number_format($data->total_amount,2) }}</span>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center">  
            <label >{{ __('Installation Invoice Number:-') }}</label>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center"> 
            <span>{{ $ICdata->inv_number??"NA" }}</span>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center">  
            <label >{{ __('Total Installation Charge:-') }}</label>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center"> 
            <span>{{ $ICdata?setting('payment-setting.currency')."".number_format($ICdata->total_charges,2):"NA" }}</span>
          </div>
           <div class="lg:w-3/12 w-4/12 flex items-center">  
            <label >{{ __('Payment Method:-') }}</label>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center"> 
          <span>
             <?php  if(empty($data->transaction_id)) { 
               echo "<b style='color:green;'>Purchase Order Number</b>";
             }else{
              echo "<b style='color:green;'>CC</b>";
             }?>
          </span>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center">  
            <label >{{ __('Installation Charges Payment Status:-') }}</label>
          </div>
          <div class="lg:w-3/12 w-4/12 flex items-center"> 
            <span><b></b> 
              <?php  
              if(!empty($ICdata)){
                if($ICdata->status == 2) { 
                  echo "<b style='color:green;'>Completed</b>";
                } else if($ICdata->status == 0) { 
                  echo "<b style='color:red;'>Trash</b>";
                } else { 
                  echo "<b style='color:red;'>Pending</b>";
                }
              }else{
                echo "NA";
              } ?>
            </span>
          </div>
        </div>

        <h2 class="lg:text-2xl md:text-1xl text-xl font-futura flex lg:flex-row flex-col justify-between lg:items-center mb-6 border-b border-t py-4">
          <span>{{__('Product Information')}}</span>
        </h2>
        @if(!empty($data['orderItems']) && count($data['orderItems'])>0)
        <div class="table-responsive w-full text-left">
          <div class="w-full text-left bg-gray-100 p-6 rounded-lg">
            <table class="table">
              <thead>
                <tr>
                  <th>{{ __('Product Name') }}</th>
                  <th>{{ __('Product Specification') }}</th>
                  <th>{{ __('Price') }}</th>  
                  <th>{{ __('Quantity') }}</th> 
                  <th>{{ __('Total') }}</th>
                  <th>{{ __('Installation Charges') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($data['orderItems'] as $key => $value)
                  @php
                    $fimage = App\Helpers\Helpers::getFeaturedImage($value->product_id);
                    $catId = $value->parent_cat_id;
                    $division = App\Helpers\Helpers::getCategoryName($catId);
                  @endphp
                  <tr>
                    <td>
                      <img src="{{ asset('storage/'.$fimage) }}" class="img-responsive" width="80" height="80"> <span>{{$value->name}}</span></td>
                    <td>
                      @if($value['vehicle_make'] || $value['vehicle_model'] || $value['vehicle_colour'] || $value['vehicle_year'] || $value['vehicle_rego'] || $value['franchise_name'] || $value['franchise_territory'])
                      <p><b>{{__('Vehicle Make')}} :-</b>{{$value->vehicle_make}}</p>
                      <p><b>{{__('Vehicle Model')}} :-</b>{{$value->vehicle_model}}</p>
                      <p><b>{{__('Vehicle Colour')}} :-</b>{{$value->vehicle_colour}}</p>
                      <p><b>{{__('Vehicle Year')}} :-</b>{{$value->vehicle_year}}</p>
                      <p><b>{{__('Vehicle Rego')}} :-</b>{{$value->vehicle_rego}}</p>
                      <p><b>{{__('Franchise Territory')}} :-</b>{{$value->franchise_territory}}</p>
                      <p><b>{{__('Franchise Name')}} :-</b>{{$value->franchise_name}}</p>
                      @endif

                      <p><b>{{__('Decal Removel')}} :-</b>{{$value->decal_removel}}</p>
                      <p><b>{{__('Re Scheduling Fee ')}} :-</b>{{$value->re_scheduling_fee}}</p>
                      <p><b>{{__('Preparation Fee')}} :-</b>{{$value->preparation_fee}}</p>
                      
                      <p><b>{{__('Division')}} :-</b>{{ $division }}</p>
                      <p><b>{{__('Comment')}} :-</b>{{$value->comment}}</p>

                      <div class="content_{{$value->id}}" style="display:none;">
                    
                      @if(!empty($value->orderItemsAttribute) && count($value->orderItemsAttribute)>0)
                          <p class="product-title lg:text-base text-gray-900">
                            <b>{{ __('Product Attribute') }}</b>
                          </p>
                          @foreach($value->orderItemsAttribute as $attribute)
                              @if(!empty($attribute))
                                  <p class="w-full text-gray-500 text-sm">
                                  <b>{{$attribute->name}}-{{$attribute->type}}</b>-{{$attribute->type_value}}
                                  </p>
                              @endif
                          @endforeach   
                      @endif
                    </div> 
                    <div class="show_hide_{{$value->id}} text-sm text-gray-700" style="cursor: grab;color:green;" data-id="{{$value->id}}" onclick="showMore(this)" data-content="toggle-text">Show More</div>
                      
                    </td>
                    <td>{{setting('payment-setting.currency')."".number_format($value->pro_att_price,2) }}</td>
                    <td>{{$value->quantity}}</td>
                    <td>{{setting('payment-setting.currency')."".number_format(($value->quantity*$value->pro_att_price),2)}}</td>
                    <td>{{$value->charges?setting('payment-setting.currency')."".number_format(($value->charges),2):"NA"}}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        @endif
              
        <div class="w-full mt-6">
          <h2 class="lg:text-2xl md:text-1xl text-xl font-futura flex lg:flex-row flex-col justify-between lg:items-center mb-6 border-b border-t py-4">
            <span>{{__('Shipping Address')}}</span>
          </h2>
          <div class="flex flex-wrap pb-4 mb-3">
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('Name')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> {{$data->shippingAddress['name']??'NA'}} </span>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('Address')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> {{$data->shippingAddress['address']??'NA'}}</span>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('City')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> {{$data->shippingAddress['city']??"NA"}} </span>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('State')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> {{$data->shippingAddress->stateName->name??"NA"}}</span>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('Country')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> {{$data->shippingAddress->countryName->name??"NA"}} </span>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('Mobile')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> +{{$data->shippingAddress['mobile_number']??"NA"}}</span>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('PostCode')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> {{$data->shippingAddress['postcode']??"NA"}}</span>
            </div>
          </div>  
        </div>

        <div class="w-full" >
          <h2 class="lg:text-2xl md:text-1xl text-xl font-futura flex lg:flex-row flex-col justify-between lg:items-center mb-6 border-b border-t py-4">
            <span>{{__('Billing Address')}}</span>
          </h2>
          <div class="flex flex-wrap pb-4 mb-3">
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('Name')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> {{$data->billingAddress['name']??'NA'}} </span>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('Address')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> {{$data->billingAddress['address']??'NA'}}</span>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('City')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> {{$data->billingAddress['city']??"NA"}} </span>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('State')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> {{$data->billingAddress->stateName->name??"NA"}}</span>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('Country')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> {{$data->billingAddress->countryName->name??"NA"}} </span>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('Mobile')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> +{{$data->billingAddress['mobile_number']??"NA"}}</span>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <label>{{__('PostCode')}}</label>
            </div>
            <div class="lg:w-3/12 w-4/12 flex items-center">
              <span><b>:</b> {{$data->billingAddress['postcode']??"NA"}}</span>
            </div>
          </div>  
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  function showMore(e) {
    let cartid = $(e).attr('data-id');
    var txt = $(".content_" + cartid).is(':visible') ? 'Show More' : 'Show Less';
    $(".show_hide_" + cartid).text(txt);
    $('.content_' + cartid).slideToggle(200);
  }
</script>
@stop