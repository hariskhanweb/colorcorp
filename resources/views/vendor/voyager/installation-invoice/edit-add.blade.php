@extends('voyager::master')

@section('page_title', __('Create Invoice'))

@section('page_header')
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="container-fluid">
        <div class="bread-header">
            <h1 class="page-title">
            <i class="voyager-categories"></i>
                {{ __('Create Invoice') }}
            </h1>
            <div class="bread-buttons">
                <a href="{{ route('installation-invoice') }}" class="btn btn-warning">
                    <i class="glyphicon glyphicon-list"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.return_to_list') }}</span>
                </a>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="page-content edit-add container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-form panel-bordered">
                <form class="form-edit-add" role="form" action="#" method="POST" enctype="multipart/form-data" id="ic_form">
                    @csrf
                    <div class="panel-body">
                        <div class="form-group col-md-12">                                            
                            <div class="invalid-feedback full" style="display:block;"><label class="control-label" for="name">{{ __('Order Id :') }}</label> {{ $order->order_number }}</div>
                            <input type="hidden" name="orderID" value="{{ $order->id }}">
                            <input type="hidden" name="user_id" value="{{ $order->user_id }}">
                        </div>                        
                        <div class="form-group col-md-12 table-responsive">
                            @if(!empty($dataTypeContent->orderItems) && count($dataTypeContent->orderItems)>0)          
                            <table  class="table  dt-responsive nowrap table-centered border">
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
                                    @php 
                                        $num = 1;   
                                    @endphp   
                                    @foreach($dataTypeContent->orderItems as $key => $value)
                                    @php
                                        $fimage = App\Helpers\Helpers::getFeaturedImage($value->product_id);
                                    @endphp
                                      
                                    <tr>
                                        <td> <img src="{{ asset('storage/'.$fimage) }}" class="img-responsive" width="80" height="80"> 
                                        {{$value->name}}<input type="hidden" name="item_name[]" value="{{ $value->name }}"><input type="hidden" name="item_id[]" value="{{ $value->id }}"></td>
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
                                        <td><input type="text" class="form-control" name="charges[]" class="charges" id="charge_{{ $num }}" placeholder="Enter Installation Charges" value="" onkeypress="return only_number(event);" onkeyup="return getTotalCharge(this.value,{{$num}})" autocomplete="off" required></td>
                                    </tr>
                                    @php 
                                        $num++;
                                    @endphp
                                    @endforeach
                                </tbody>
                                <thead>
                                    <tr>
                                        <th colspan="4"></th>
                                        <th>{{ __('Sub Total') }}</th>
                                        <th><b>:</b> {{setting('payment-setting.currency')." ".number_format($dataTypeContent->subtotal,2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4"></th>
                                        <th>{{ __('GST (10%)') }}</th>
                                        <th><b>:</b> {{setting('payment-setting.currency')." ".number_format($dataTypeContent->gst,2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4"></th>
                                        <th>{{ __('Grand Total') }}</th>
                                        <th><b>:</b> {{setting('payment-setting.currency')." ".number_format($dataTypeContent->total_amount,2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4"></th>
                                        <th>{{ __('Total Installation Charges') }}</th>
                                        <th><b>:</b> {{setting('payment-setting.currency')}} <span id="total_ic">0</span><input type="hidden" name="num" id="num" value="{{ count($dataTypeContent->orderItems) }}" /></th>
                                    </tr>
                                </thead>
                            </table>
                            @endif
                        </div>
                    </div>
                    
                    <div class="panel-footer border-top p-30 justify-content-between d-flex  col-md-12">
                        <div class="form-group  col-md-12 ">
                            <button type="submit" class="btn btn-primary save" id="genInv">Generate Invoice</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- confirmation modal --}}
<div class="modal modal-danger fade" tabindex="-1" id="cnfm_modal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="voyager-check"></i> {{ __('Please make sure all the information you have mentioned is correct because once the invoice is generated it\'s not editable!') }}</h4>
            </div>
            <div class="modal-footer">
                <form action="{{url('/admin/installation-invoice/save')}}" id="charge_form" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="orderID" value="{{ $order->id }}">
                    <input type="hidden" name="user_id" value="{{ $order->user_id }}">
                    <input  type="hidden" class="form-control" name="totalIC" id="totalIC" placeholder="Total Charge" value="0">
                    <input type="hidden" name="item_id" id="m_item_id" value="">
                    <input type="hidden" name="item_name" id="m_item_name" value="">
                    <input type="hidden" name="charges" id="m_charges" value="">
                    <input type="submit" id="cnfm_btn" class="btn btn-danger pull-right delete-confirm" value="{{ __('Yes, Generate It!') }}">
                </form>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal" id="cancel">{{ __('voyager::generic.cancel') }}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop

@section('css')
<style type="text/css">
    .form-row { display: flex;}
    .form-row .form-control.code { border-top-right-radius: 0; border-bottom-right-radius: 0px; max-width: 70px;}
    .form-row .form-control.code + .form-control { border-top-left-radius: 0px; border-bottom-left-radius: 0px; border-left: 0px; flex-grow: 1;}
    .ccode{ padding-top: 5px;  font-size: 20px;}
</style>
@stop
@section('javascript')
<script>      
    $("#ic_form").submit(function(e){
        e.preventDefault();
        var item_id     = $("input[name='item_id[]']").map(function(){return $(this).val();}).get();
        var item_name   = $("input[name='item_name[]']").map(function(){return $(this).val();}).get();
        var charges     = $("input[name='charges[]']").map(function(){return $(this).val();}).get();
        $('#m_item_id').val(item_id);
        $('#m_item_name').val(item_name);
        $('#m_charges').val(charges);
        $('#cnfm_modal').modal('show');
        return false;
    });

    $("#cnfm_btn").click(function(){
        setTimeout(function(){ 
            $('#cnfm_modal').modal('hide');
            $('#voyager-loader').css('display','block'); 
            $('#cnfm_btn').prop('disabled',true); 
            $('#cancel').prop('disabled',true); 
        }, 1000);
    });

    function showMore(e) {
        let cartid = $(e).attr('data-id');
        var txt = $(".content_" + cartid).is(':visible') ? 'Read More' : 'Read Less';
        $(".show_hide_" + cartid).text(txt);
        $('.content_' + cartid).slideToggle(200);
    }

    function only_number(e){
        var charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
    }

    function getTotalCharge(val, srno) {
        var sum = 0;
        var num = $('#num').val();
        for (i = 1; i <= num; i++) {
            var val = $('#charge_'+i).val()?$('#charge_'+i).val():0;
            sum = parseInt(sum)+parseInt(val);
        }
        $('#totalIC').val(sum);
        $('#total_ic').text(sum);
    }
</script>    
@stop
