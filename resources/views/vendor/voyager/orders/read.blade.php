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
            <a href="{{ route('voyager.'.$dataType->slug.'.index') }}" class="btn btn-warning">
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

    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-form panel-bordered space">
                <legend class="font-weight-bold mb-0 mr-5 heading">{{ __('Update Order Status') }}</legend>

                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if($edit)
                            {{ method_field("PUT") }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body p-0">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                            @endphp

                            @foreach($dataTypeRows as $row)
                                <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $display_options = $row->details->display ?? NULL;
                                    if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                    }
                                @endphp
                                @if (isset($row->details->legend) && isset($row->details->legend->text))
                                    <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                @endif

                                <div class="form-group order-item-field @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                    {{ $row->slugify }}
                                    <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                    @include('voyager::multilingual.input-hidden-bread-edit-add')
                                    @if (isset($row->details->view))
                                        <p>fff</p>
                                        @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
                                    @elseif ($row->type == 'relationship')
                                        <p>fff ggg</p>
                                        @include('voyager::formfields.relationship', ['options' => $row->details])
                                    @else

                                        @if ($row->id == 98 && $dataTypeContent->order_status == 1)
                                            <p>Success</p>
                                            <input type="hidden" name="order_status" value='1' /> 
                                        @else
                                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}        
                                        @endif
                                        
                                    @endif

                                    @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                        {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                    @endforeach
                                    @if ($errors->has($row->field))
                                        @foreach ($errors->get($row->field) as $error)
                                            <span class="help-block">{{ $error }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach

                        <div class="form-group  col-md-12 ">
                            <label class="control-label" for="name">{{ __('Comment') }}</label>
                             <textarea class="form-control" rows="5" name="order_status_comment" placeholder="Enter Order Status Comment">{{ ($OrderComments != '') ? $OrderComments->note : '' }}</textarea>
                        </div>  

                        <div class="panel-footer border-top p-30 justify-content-between d-flex">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                        </div>

</div><!-- panel-body -->
                    </form>

                </div>
            </div>
        </div>
    </div>

   
    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

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
                        {{ $ICdata->inv_number??"NA" }}
                    </div>
                    <div class="col-md-3"><label>{{__('Total Installation Charge')}}</label></div>
                    <div class="col-md-3"><span><b>:</b> {{ $ICdata?setting('payment-setting.currency')." ".number_format($ICdata->total_charges,2):"NA" }}</span></div>
                </div>
                <div class="row">
                    <div class="col-md-3 clearfix">Installation Charges Payment Status</div>
                    <div class="col-md-3 clearfix"><b>:</b>
                        <?php  
                        if(!empty($ICdata)){
                            if($ICdata->status == 2) { 
                                echo "<b style='color:brown;'>Completed</b>";
                            } else if($ICdata->status == 0) { 
                                echo "<b style='color:red;'>Trash</b>";
                            } else { 
                                echo "<b style='color:#191e4f;'>Pending</b>";
                            } 
                        }else{
                            echo "NA";
                        } ?>
                    </div>
                    <div class="col-md-3">{{ __('Payment Method') }}</div>
                    <div class="col-md-3"><b>:</b> <span>
                   <?php  if(empty($dataTypeContent->transaction_id)) { 
                     echo "<b style='color:green;'>Purchase Order Number</b>";
                   }else{
                    echo "<b style='color:green;'>CC</b>";
                   }?>
                </span></div>
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
                          <td class="text-right">{{$value->charges?setting('payment-setting.currency')." ".number_format(($value->charges),2):"NA"}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                 @endif
                 </div></div>
            </fieldset>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-form panel-bordered space">
            <fieldset >
                <legend class="font-weight-bold mb-0 mr-5 heading">{{__('Shipping Address')}}</legend>
                <div class="panel-body p-0">
                <div class="row">
                    <div class="col-md-2"><label>{{__('Name')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$dataTypeContent->shippingAddress['name']??'NA'}} </span></div>
                    <div class="col-md-2"><label>{{__('Address')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$dataTypeContent->shippingAddress['address']??'NA'}}</span></div>
                </div>
                
                <div class="row">
                    <div class="col-md-2"><label>{{__('City')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$dataTypeContent->shippingAddress['city']??"NA"}}</span></div>
                    <div class="col-md-2"><label>{{__('State')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$dataTypeContent->shippingAddress->stateName->name??"NA"}}</span></div>
                </div>
                <div class="row">
                    <div class="col-md-2"><label>{{__('Country')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$dataTypeContent->shippingAddress->countryName->name??"NA"}}</span></div>
                    <div class="col-md-2"><label>{{__('Mobile')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> +{{$dataTypeContent->shippingAddress['mobile_number']??"NA"}}</span></div>
                </div>
                 <div class="row">
                    <div class="col-md-2"><label>{{__('PostCode')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$dataTypeContent->shippingAddress['postcode']??"NA"}}</span></div>
                    </div> </div>
                
            </fieldset>
        </div>
    </div>
    
     <div class="col-md-6">
        <div class="panel panel-form panel-bordered space">
            <fieldset >
                <legend class="font-weight-bold mb-0 mr-5 heading">{{__('Billing Address')}}</legend>
                <div class="panel-body p-0">
                <div class="row">
                    <div class="col-md-2"><label>{{__('Name')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$dataTypeContent->billingAddress['name']??'NA'}} </span></div>
                    <div class="col-md-2"><label>{{__('Address')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$dataTypeContent->billingAddress['address']??'NA'}}</span></div>
                </div>
                
                <div class="row">
                    <div class="col-md-2"><label>{{__('City')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$dataTypeContent->billingAddress['city']??"NA"}}</span></div>
                    <div class="col-md-2"><label>{{__('State')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$dataTypeContent->billingAddress->stateName->name??"NA"}}</span></div>
                </div>
                <div class="row">
                    <div class="col-md-2"><label>{{__('Country')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$dataTypeContent->billingAddress->countryName->name??"NA"}}</span></div>
                    <div class="col-md-2"><label>{{__('Mobile')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> +{{$dataTypeContent->billingAddress['mobile_number']??"NA"}}</span></div>
                </div>
                 <div class="row">
                    <div class="col-md-2"><label>{{__('PostCode')}}</label></div>
                    <div class="col-md-4"><span><b>:</b> {{$dataTypeContent->billingAddress['postcode']??"NA"}}</span></div>
                    </div>
                    </div>
                
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