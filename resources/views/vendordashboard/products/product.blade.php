@extends('layouts.vendor-layout')
@section('pageTitle', 'Product Management')
@section('content')
@php $vendor= App\Helpers\Helpers::getShopslug(Auth::user()->id); @endphp
<!-- Start Content-->
<div class="container-fluid">

  <!-- start page title -->
  <div class="row align-items-center">
    <div class="col-6">
      <div class="page-title-box">
        <h4 class="page-title">{{ __('Product Management') }}</h4>
      </div>
    </div>
  </div>

  <!-- end page title -->
  <div class="row">
    <div class="col-12">
      <div class="card card-box">
        <div class="card-body">
          <div class="table-responsive">
            <table id="basic-datatable" class="table table-hover dt-responsive nowrap table-centered">
              <thead>
                <tr>
                  <th>{{ __('ID') }}</th>
                  <th>{{ __('Image') }}</th>
                  <th>{{ __('Name') }}</th>
                  <th>{{ __('SKU') }}</th>
                  <!-- <th>{{ __('Price') }}</th> -->
                  <th>{{ __('Status') }}</th>
                  <th>{{ __('Created At') }}</th>
                  <th>{{ __('Action') }}</th>
                </tr>
              </thead>

              <tbody>
                @if(count($prodrecord)>0)
                @foreach($prodrecord as $prodlist)
                @php $fimage = App\Helpers\Helpers::getFeaturedImage($prodlist->id); @endphp
                <tr>
                  <td>{{$prodlist->id}}</td>
                  <td><img src="{{ asset('storage/'.$fimage) }}" class="img-responsive" width="80" height="80"></td>
                  <td>{{$prodlist->name}}</td>
                  <td>{{$prodlist->sku}}</td>
                  <!-- <td>{{ setting('payment-setting.currency')." ".number_format($prodlist->price,2) }}</td> -->
                  <td>@if($prodlist->status==1) Enable @else Disable @endif</td>
                  <td>{{ App\Helpers\Helpers::getDateFormatted($prodlist->created_at) }}</td>
                  <td>
                    <a href="{{ route('vendor.product.view', ['vendor_name' => $vendor, 'id' => $prodlist->id]) }}" title="View" class="btn btn-primary waves-effect waves-light"><i class="fe-eye"></i> </a>
                  </td>
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
          </div>
        </div> <!-- end card body-->
      </div> <!-- end card -->
    </div><!-- end col-->
  </div>
  <!-- end row-->
</div> <!-- container-fluid -->

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