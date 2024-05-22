@extends('layouts.vendor-layout')
@section('pageTitle', 'Customer Management')
@section('content')
@php
$vendata=Helper::getShopData(Auth::id());
$shopslug = $vendata['shop_url_slug'];
@endphp
<!-- Start Content-->
<div class="container-fluid">

  <!-- start page title -->
  <div class="row align-items-center">
    <div class="col-6">
      <div class="page-title-box">
        <h4 class="page-title">{{ __('Customer Management') }}</h4>
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
                  <th>{{ __('SR.No') }}</th>
                  <th>{{ __('Name') }}</th>
                  <th>{{ __('Email') }}</th>
                  <th>{{ __('Mobile') }}</th>
                  <th>{{ __('Created Date') }}</th>
                </tr>
              </thead>
              <tbody>

                @foreach($data as $key => $cusdata)
                <tr>
                  <th scope="row">{{$key+1}}</th>
                  <td>{{$cusdata->name}}</td>
                  <td>{{$cusdata->email}}</td>
                  <td>{{$cusdata->mobile_number}}</td>
                  <td>{{$cusdata->created_at->format('d/m/Y')}}</td>
                </tr>
                @endforeach
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