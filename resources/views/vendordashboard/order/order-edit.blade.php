@extends('layouts.vendor-layout')
@section('pageTitle', 'Order Details')
@section('content')

@php
$user = auth()->user();
$vendor= App\Helpers\Helpers::getShopslug(Auth::user()->id);
@endphp
<!-- Start Content-->
<div class="container-fluid">

  <!-- start page title -->
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <h4 class="page-title">{{ __('Order Details') }}</h4>
      </div>
    </div>
  </div>
  <!-- end page title -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <!--hide order status update-->
            <!--<div class="form-group col-12">
                 <form class="form-horizontal" method="POST" action="{{ route('vendor.order.update', ['vendor_name' => $vendor]) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="order_id" value="{{ $data->id }}" />
                     <div class="form-group row">
                      <label class="col-lg-2 col-form-label" for="Comment">{{ __('Comment') }}</label>
                      <div class="col-lg-10">
                        <textarea class="form-control" rows="5" name="order_status_comment" placeholder="Enter Order Status Comment"></textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">{{ __('Order Status') }}</label>
                      <div class="col-lg-4">
                        <select class="form-control" name="status">
                          <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Pending</option>
                          <option value="2" {{ $data->status == 2 ? 'selected' : '' }}>Completed</option>
                        </select>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                  </form>
            </div> -->
           <div class="form-group col-12">
             <legend class="font-weight-bold mb-0 mr-5 heading" 
             style="margin-bottom: 25px !important;border-bottom: 1px solid #e5e5e5;margin-top: 20px;">{{__('Order Summary')}}</legend>
            <div class="form-group row">
              <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Order Number:-') }}</label>
              <div class="col-lg-3">
               <span>{{ $data->order_number}}</span>
              </div>
              
              
              <!-- <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Subtotal:-') }}</label>
              <div class="col-lg-3">   
                <span>{{setting('payment-setting.currency')." ".number_format($data->subtotal,2) }}</span>
              </div> -->
              <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Order Status:-') }}</label>
              <div class="col-lg-3">   
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
              
              <!-- <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Tax:-') }}</label>
              <div class="col-lg-3">   
                <span>{{$data->tax}}%</span>
              </div> -->
              <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Transaction Id:-') }}</label>
              <div class="col-lg-3">   
                <span>{{ $data->transaction_id ??'NA'}}</span>
              </div>
              <!-- <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('GST Amount:-') }}</label>
              <div class="col-lg-3">
               <span>{{ setting('payment-setting.currency')." ".number_format($data->gst,2) }}</span>
              </div> -->
               
               <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Payment Status:-') }}</label>
              <div class="col-lg-3">   
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
              <!-- <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Total Amount:-') }}</label>
              <div class="col-lg-3">   
                <span>{{setting('payment-setting.currency')." ".number_format($data->total_amount,2) }}</span>
              </div> -->
              <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Installation Invoice Number:-') }}</label>
              <div class="col-lg-3">   
                <span>{{ $ICdata->inv_number??"NA" }}</span>
              </div>
              <!-- <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Total Installation Charge:-') }}</label>
              <div class="col-lg-3">   
                <span>{{ $ICdata?setting('payment-setting.currency')." ".number_format($ICdata->total_charges,2):"NA" }}</span>
              </div>
               <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Payment Method:-') }}</label>
              <div class="col-lg-3">   
                 <span>
                   <?php  if(empty($data->transaction_id)) { 
                     echo "<b style='color:green;'>Purchase Order Number</b>";
                   }else{
                    echo "<b style='color:green;'>CC</b>";
                   }?>
                </span>
              </div> -->

              <!-- <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Installation Charges Payment Status:-') }}</label>
              <div class="col-lg-3">   
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
              </div> -->
              
            </div>
          </div>


           <div class="form-group col-12">
             <legend class="font-weight-bold mb-0 mr-5 heading" 
             style="margin-bottom: 25px !important;border-bottom: 1px solid #e5e5e5;margin-top: 20px;">{{__('User Information')}}</legend>
            <div class="form-group row">
              <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('User Name:-') }}</label>
              <div class="col-lg-3">
               <span>{{ $data->userOrder->name??'NA'}}</span>
              </div>
              
              
              <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Email:-') }}</label>
              <div class="col-lg-3">   
                <span>{{$data->userOrder->email??"NA" }}</span>
              </div>
               <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Role Name:-') }}</label>
              <div class="col-lg-3">   
                <span>{{$data->userOrder->role->name??"NA" }}</span>
              </div>
              
              <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Vendor Name:-') }}</label>
              <div class="col-lg-3">   
                <span>{{$data->vendorName->name??"NA"}}</span>
              </div>
              <label class="col-lg-3 col-form-label" for="simpleinput">{{ __('Mobile:-') }}</label>
              <div class="col-lg-3">   
                <span>+{{ $data->userOrder->mobile_number??"NA"}}</span>
              </div>
              
              
            </div>
          </div>

          <div class="form-group col-12">
             <legend class="font-weight-bold mb-0 mr-5 heading" 
             style="margin-bottom: 25px !important;border-bottom: 1px solid #e5e5e5;margin-top: 20px;">{{__('Product Information')}}</legend>
             @if(!empty($data['orderItems']) && count($data['orderItems'])>0)
            <div class="form-group col-12">
              
              <div class="product_details row">
                  <table  class="table  dt-responsive nowrap table-centered border">
                      <thead>
                        <tr>
                        <th>{{ __('Product Name') }}</th>
                        <th>{{ __('Product Specification') }}</th>
                        <!-- <th>{{ __('Price') }}</th>   -->
                        <th>{{ __('Quantity') }}</th> 
                        <!-- <th>{{ __('Total') }}</th> -->
                        <!-- <th>{{ __('Installation Charges') }}</th> -->
                        </tr>
                      </thead>
                      <tbody>

                        @foreach($data['orderItems'] as $key => $value)
                          @php
                            $fimage = App\Helpers\Helpers::getFeaturedImage($value->product_id);
                          @endphp
                          
                           <tr>
                          <td> <img src="{{ asset('storage/'.$fimage) }}" class="img-responsive" width="80" height="80"> 
                            {{$value->name}}</td>
                          <td>

                            @if($value['vehicle_make'] || $value['vehicle_model'] || $value['vehicle_colour'] || $value['vehicle_year'] || $value['vehicle_rego'] || $value['franchise_name'] || $value['franchise_territory'])

                            <p><b>{{__('Vehicle Make')}} :-</b>
                              {{$value->vehicle_make}}</p>
                            <p><b>{{__('Vehicle Model')}} :-</b>
                              {{$value->vehicle_model}}</p>
                            <p><b>{{__('Vehicle Colour')}} :-</b>
                              {{$value->vehicle_colour}}</p>
                            <p><b>{{__('Vehicle Year')}} :-</b>
                              {{$value->vehicle_year}}</p>
                            <p><b>{{__('Vehicle Rego')}} :-</b>
                              {{$value->vehicle_rego}}</p>
                            <p><b>{{__('Franchise Territory')}} :-</b>
                              {{$value->franchise_territory}}</p>
                            <p><b>{{__('Franchise Name')}} :-</b>
                              {{$value->franchise_name}}</p>
                            @endif
                            
                            <p><b>{{__('Decal Removel')}} :-</b>
                              {{$value->decal_removel}}</p>
                            <p><b>{{__('Re Scheduling Fee ')}} :-</b>
                              {{$value->re_scheduling_fee}}</p>
                           <p><b>{{__('Preparation Fee')}} :-</b>
                            {{$value->preparation_fee}}</p>
                            
                            <p><b>{{__('Comment')}} :-</b>
                              {{$value->comment}}</p>

                            <div class="content_{{$value->id}}" style="display:none;">
                            
                            @if(!empty($value->orderItemsAttribute) && count($value->orderItemsAttribute)>0)
                                <p class="product-title lg:text-base text-gray-900">
                                  <b>{{ __('Product Attribute') }}</b>
                                </p>
                                @foreach($value->orderItemsAttribute as $attribute)
                                    @if(!empty($attribute))
                                        <p class="w-full text-gray-500 text-sm">
                                        <b>{{$attribute->name}}-{{$attribute->type}}-</b>{{$attribute->type_value}}
                                        </p>
                                    @endif
                                @endforeach   
                            @endif
                           </div> 
                           <div class="show_hide_{{$value->id}} text-sm text-gray-700" style="cursor: grab;color:green;" data-id="{{$value->id}}" onclick="showMore(this)" data-content="toggle-text">Show More</div>
                            
                          </td>
                          <!-- <td>{{setting('payment-setting.currency')." ".number_format($value->pro_att_price,2) }}</td> -->
                          <td>{{$value->quantity}}</td>
                          <!-- <td>{{setting('payment-setting.currency')." ".number_format(($value->quantity*$value->pro_att_price),2)}}</td> -->
                          <!-- <td>{{$value->charges?setting('payment-setting.currency')." ".number_format(($value->charges),2):"NA"}}</td> -->
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
              </div>
            </div>
            @endif
            
          </div>
        <div class="row">
        <div class="col-md-6">
        <div class="panel panel-form panel-bordered space">
            <fieldset >
               <legend class="font-weight-bold mb-0 mr-5 heading" 
             style="margin-bottom: 25px !important;border-bottom: 1px solid #e5e5e5;margin-top: 20px;">{{__('Shipping Address')}}</legend>
                <div class="row">
                    <br/>
                    <div class="col-md-2"><label>{{__('Name')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$data->shippingAddress['name']??'NA'}} </span></div>
                    <div class="col-md-2"><label>{{__('Address')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$data->shippingAddress['address']??'NA'}}</span></div>
                </div>
                
                <div class="row">
                    <div class="col-md-2"><label>{{__('City')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$data->shippingAddress['city']??"NA"}}</span></div>
                    <div class="col-md-2"><label>{{__('State')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$data->shippingAddress->stateName->name??"NA"}}</span></div>
                </div>
                <div class="row">
                    <div class="col-md-2"><label>{{__('Country')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$data->shippingAddress->countryName->name??"NA"}}</span></div>
                    <div class="col-md-2"><label>{{__('Mobile')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> +{{$data->shippingAddress['mobile_number']??"NA"}}</span></div>
                </div>
                 <div class="row">
                    <div class="col-md-2"><label>{{__('PostCode')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$data->shippingAddress['postcode']??"NA"}}</span></div>
                </div>
                
            </fieldset>
        </div>
    </div>
    
     <div class="col-md-6">
        <div class="panel panel-form panel-bordered space">
            <fieldset >
                <legend class="font-weight-bold mb-0 mr-5 heading" 
             style="margin-bottom: 25px !important;border-bottom: 1px solid #e5e5e5;margin-top: 20px;">{{__('Billing Address')}}</legend>
                <div class="row">
                    <br/>
                    <div class="col-md-2"><label>{{__('Name')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$data->billingAddress['name']??'NA'}} </span></div>
                    <div class="col-md-2"><label>{{__('Address')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$data->billingAddress['address']??'NA'}}</span></div>
                </div>
                
                <div class="row">
                    <div class="col-md-2"><label>{{__('City')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$data->billingAddress['city']??"NA"}}</span></div>
                    <div class="col-md-2"><label>{{__('State')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$data->billingAddress->stateName->name??"NA"}}</span></div>
                </div>
                <div class="row">
                    <div class="col-md-2"><label>{{__('Country')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$data->billingAddress->countryName->name??"NA"}}</span></div>
                    <div class="col-md-2"><label>{{__('Mobile')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> +{{$data->billingAddress['mobile_number']??"NA"}}</span></div>
                </div>
                 <div class="row">
                    <div class="col-md-2"><label>{{__('PostCode')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$data->billingAddress['postcode']??"NA"}}</span></div>
                </div>
                
            </fieldset>
        </div>
    </div>
    </div>
   

            

         
         <!-- end card body-->
      </div> <!-- end card -->
    </div><!-- end col-->
  </div>
  <!-- end row-->
</div> <!-- container-fluid -->


   <script>
    function showMore(e) {
    let cartid = $(e).attr('data-id');
    var txt = $(".content_" + cartid).is(':visible') ? 'Show More' : 'Show Less';
    $(".show_hide_" + cartid).text(txt);
    $('.content_' + cartid).slideToggle(200);
  }
 </script>

@endsection

