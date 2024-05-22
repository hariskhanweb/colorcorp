@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'view' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
<div class="container-fluid">
    <div class="bread-header">
        <h1 class="page-title">
            <i class="voyager-categories"></i>
            {{ __('voyager::generic.'.($edit ? 'view' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
        </h1>
        <div class="bread-buttons">
            <a href="{{ route('installation-invoice') }}" class="btn btn-warning">
                <i class="glyphicon glyphicon-list"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.return_to_list') }}</span>
            </a>
        </div>
        @include('voyager::multilingual.language-selector')
    </div>
</div>

<style type="text/css">
        .heading{
            color: #191e4f;
            font-weight: 600;
            font-size: 1.7rem;
        }
        .space{
            padding: 15px;
        }
        .row>[class*=col-] {
            margin-bottom: 0px!important;
        }
        b{
            font-weight: 700;
        }
        .voyager .panel {box-shadow: 0 10px 14px rgb(0 0 0 / 5%);}
        .scroll{
            overflow: auto;
            white-space: nowrap;
        }
    </style>
@stop

@section('content')

    <!-- End Delete File Modal -->
    <div class="col-md-12">
        <div class="panel panel-form panel-bordered space">
            <fieldset >
                <legend class="font-weight-bold mb-0 mr-5 heading">{{__('Order Summary')}}</legend>
                <div class="panel-body p-0">
                <div class="row">
                    <div class="col-md-3"><label>{{__('Order Number')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> {{$dataTypeContent->order_number}}</span></div>
                    <div class="col-md-3"><label>{{__('Subtotal')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> {{setting('payment-setting.currency')." ".number_format($dataTypeContent->subtotal,2) }}
                    </span></div>
                </div>
               
                <div class="row">
                    <div class="col-md-3"><label>{{__('Order Status')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> 
                        <?php  if($dataTypeContent->status == 2) { 
                            echo "<b style='color:brown;'>Completed</b>";
                        } else if($dataTypeContent->status == 0) { 
                            echo "<b style='color:red;'>Trash</b>";
                        } else { 
                            echo "<b style='color:#191e4f;'>Pending</b>";
                        } ?></span></div>
                    <div class="col-md-3"><label>{{__('Tax')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> {{$dataTypeContent->tax}}%</span></div>
                    
                </div>
                <div class="row">
                    <div class="col-md-3"><label>{{__('Transaction ID')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> {{$dataTypeContent->transaction_id}}</span></div>
                    <div class="col-md-3"><label>{{__('GST Amount')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> {{setting('payment-setting.currency')." ".number_format($dataTypeContent->gst,2) }}</span></div>
                </div>
                <div class="row">
                    <div class="col-md-3"><label>{{__('Payment Status')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> 
                        <?php  if($dataTypeContent->status == 2) { 
                            echo "<b style='color:brown;'>Completed</b>";
                        } else if($dataTypeContent->status == 0) { 
                            echo "<b style='color:red;'>Trash</b>";
                        } else { 
                            echo "<b style='color:#191e4f;'>Pending</b>";
                        } ?>
                    </span></div>
                   <div class="col-md-3"><label>{{__('Total Amount')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> {{setting('payment-setting.currency')." ".number_format($dataTypeContent->total_amount,2) }}</span></div>
                </div>
                <div class="row">
                    <div class="col-md-3 clearfix">Installation Invoice Number</div>
                    <div class="col-md-3 clearfix"><b>:</b>
                        {{ $ICdata->inv_number }}
                    </div>
                    <div class="col-md-3"><label>{{__('Total Installation Charge')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> {{setting('payment-setting.currency')." ".number_format($ICdata->total_charges,2) }}</span></div>
                </div>
                <div class="row">
                    <div class="col-md-3 clearfix">Installation Charges Payment Status</div>
                    <div class="col-md-3 clearfix"><b>:</b>
                        <?php  if($ICdata->status == 2) { 
                            echo "<b style='color:brown;'>Completed</b>";
                        } else if($ICdata->status == 0) { 
                            echo "<b style='color:red;'>Trash</b>";
                        } else { 
                            echo "<b style='color:#191e4f;'>Pending</b>";
                        } ?>
                    </div>
                    <div class="col-md-3">&nbsp;</div>
                    <div class="col-md-3">&nbsp;</div>
                </div>
                
                </div>
            </fieldset>
        </div>
    </div>

    <div class="col-md-12">
        <div class="panel panel-form panel-bordered space">
            <fieldset >
                <legend class="font-weight-bold mb-0 mr-5 heading">{{__('User Information')}}</legend>
                <div class="panel-body p-0">
                <div class="row">
                    <div class="col-md-3"><label>{{__('User Name')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> {{$dataTypeContent->userOrder->name??'NA'}} {{$getUserInfo->last_name??''}}</span></div>
                    <div class="col-md-3"><label>{{__('Email')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> {{$dataTypeContent->userOrder->email??"NA"}}</span></div>
                </div>
                
                <div class="row">
                    <div class="col-md-3"><label>{{__('Role Name')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> {{$dataTypeContent->userOrder->role->name??"NA"}}</span></div>
                    <div class="col-md-3"><label>{{__('Vendor Name')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> {{$dataTypeContent->vendorName->name??"NA"}}</span></div>
                </div>
                <div class="row">
                    <div class="col-md-3"><label>{{__('Mobile')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> +{{$dataTypeContent->userOrder->mobile_number??"NA"}}</span></div>
                    
                    </div></div>
                
            </fieldset>
        </div>
    </div>

     <div class="col-md-12">
        <div class="panel panel-form panel-bordered space">
            <fieldset >
                <legend class="font-weight-bold mb-0 mr-5 heading">{{__('Product Information')}}</legend>
                <div class="panel-body p-0">
                <div class="row">
                    @if(!empty($dataTypeContent->orderItems) && count($dataTypeContent->orderItems)>0)          
                  <table  class="table  dt-responsive nowrap table-centered border">
                      <thead>
                        <tr>
                        <th>{{ __('Product Name') }}</th>
                        <th>{{ __('Product Specification') }}</th>
                        <th>{{ __('Price') }}</th>  
                        <th>{{ __('Quantity') }}</th> 
                        <th>{{ __('Total') }}</th> 
                        <th class="text-right">{{ __('Installation Charges') }}</th> 
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($dataTypeContent->orderItems as $key => $value)
                        @php
                            $fimage = App\Helpers\Helpers::getFeaturedImage($value->product_id);
                        @endphp
                          
                           <tr>
                          <td> <img src="{{ asset('storage/'.$fimage) }}" class="img-responsive" width="80" height="80"> 
                            {{$value->name}}</td>
                         <td>
                            @if($value['vehicle_make'] || $value['vehicle_model'] || $value['vehicle_colour'] || $value['vehicle_year'] || $value['vehicle_rego'] || $value['franchise_name'] || $value['franchise_territory'])

                            <p>{{__('Vehicle Make')}} :-{{$value->vehicle_make}}</p>
                            <p>{{__('Vehicle Model')}} :-{{$value->vehicle_model}}</p>
                            <p>{{__('Vehicle Colour')}} :-{{$value->vehicle_colour}}</p>
                            <p>{{__('Vehicle Year')}} :-{{$value->vehicle_year}}</p>
                            <p>{{__('Vehicle Rego')}} :-{{$value->vehicle_rego}}</p>
                            <p>{{__('Franchise Territory')}} :-{{$value->franchise_territory}}</p>
                            <p>{{__('Franchise Name')}} :-{{$value->franchise_name}}</p>
                            @endif

                            <p>{{__('Decal Removel')}} :-{{$value->decal_removel}}</p>
                            <p>{{__('Re Scheduling Fee ')}} :-{{$value->re_scheduling_fee}}</p>
                            <p>{{__('Preparation Fee')}} :-{{$value->preparation_fee}}</p>
                            
                            <p>{{__('Comment')}} :-{{$value->comment}}</p>

                            <div class="content_{{$value->id}}" style="display:none;">
                           
                            @if(!empty($value->orderItemsAttribute) && count($value->orderItemsAttribute)>0)
                                <p class="product-title lg:text-base text-gray-900">
                                  {{ __('Product Attribute') }}
                                </p>
                                @foreach($value->orderItemsAttribute as $attribute)
                                    @if(!empty($attribute))
                                        <p class="w-full text-gray-500 text-sm">
                                        {{$attribute->name}}-{{$attribute->type}}-{{$attribute->type_value}}
                                        </p>
                                    @endif
                                @endforeach   
                            @endif
                           </div> 
                           <div class="show_hide_{{$value->id}} text-sm text-gray-700" style="cursor: grab;color:red;" data-id="{{$value->id}}" onclick="showMore(this)" data-content="toggle-text">Read More</div>
                        </td>
                          <td>{{setting('payment-setting.currency')." ".number_format($value->pro_att_price,2) }}</td>
                         <td>{{$value->quantity}}</td>
                          <td>{{setting('payment-setting.currency')." ".number_format(($value->quantity*$value->pro_att_price),2)}}</td>
                          <td class="text-right">{{setting('payment-setting.currency')." ".number_format(($value->charges),2)}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                 @endif
                 </div></div>
            </fieldset>
        </div>
    </div>   
@stop

@section('javascript')
    <script>
    function showMore(e) {
    let cartid = $(e).attr('data-id');
    var txt = $(".content_" + cartid).is(':visible') ? 'Read More' : 'Read Less';
    $(".show_hide_" + cartid).text(txt);
    $('.content_' + cartid).slideToggle(200);
  }
 </script>   
@stop