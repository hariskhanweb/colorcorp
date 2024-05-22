@extends('layouts.vendor-layout')
@section('pageTitle', 'Order Management')
@section('content')
@php
$vendata=Helper::getShopData(Auth::id());
@endphp
<!-- Start Content-->
<div class="container-fluid">

  <!-- start page title -->
  <div class="row align-items-center">
    <div class="col-6">
      <div class="page-title-box">
        <h4 class="page-title">{{ __('Order Management') }}</h4>
      </div>
    </div>
   
  </div>

  <!-- end page title -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">

          <div class="table-responsive">
            <table id="basic-datatable" class="table table-hover dt-responsive nowrap table-centered">
              <thead>
                <tr>
                  <th>{{ __('Order Number') }}</th>
                  <th>{{ __('Full Name') }}</th>  
                  <!-- <th>{{ __('Subtotal') }}</th> -->
                  <!-- <th>{{ __('Tax') }}</th> -->
                  <!-- <th>{{__('GST Amount')}}</th> -->
                  <!-- <th>{{__('Total Amount')}}</th> -->
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
                  <td>{{$orderdata->userOrder["name"]}}</td>
                  <!-- <td>{{setting('payment-setting.currency')." ".number_format($orderdata->subtotal,2) }}</td>
                  <td>{{$orderdata->tax}}%</td>
                  <td>{{setting('payment-setting.currency')." ".number_format($orderdata->gst,2) }}</td>
                  <td>{{setting('payment-setting.currency')." ".number_format($orderdata->total_amount,2) }}</td> -->
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
                    <a href="{{ route('vendor.order.edit', ['vendor_name' => $vendata['shop_url_slug'], 'id' => $orderdata->id]) }}" title="View" class="btn btn-primary waves-effect waves-light"><i class="fe-eye"></i> </a>
                  </td>
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
          </div>

        </div> <!-- end card-body-->
      </div> <!-- end card -->
    </div> <!-- end col -->
  </div>
  <!-- end row-->
</div> <!-- container-fluid -->
<!-- datatable js -->

@if (session()->has('message'))
<script type="text/javascript">
  $(document).ready(function() {
    swal({
      text: "{{ Session::get('message') }}",
      icon: "success",
    });
  });
</script>
@endif
@endsection