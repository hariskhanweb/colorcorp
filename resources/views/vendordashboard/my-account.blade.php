@extends('layouts.vendor-layout')
@section('pageTitle', 'Create Customer')
@section('content')
<style type="text/css">
  .invalid-feedback {
    display: block !important;
  }
</style>
<!-- Start Content-->
<div class="container-fluid">
  <!-- start page title -->
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <h4 class="page-title"> {{ __('My Account') }}</h4>
      </div>
    </div>
  </div>
  @if(Session::has('message'))
  <div class="alert alert-success text-center">
    {{Session::get('message')}}
  </div>
  @endif
  <!-- end page title -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h4 class="header-title">{{ __('Account data') }}</h4>
          <p class="sub-header">
            {{ __('All field required.') }}
          </p>

          <form method="POST" action="{{ route('myaccount.update') }}" enctype="multipart/form-data" class="form-horizontal">
            {{ csrf_field() }}
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="name">{{ __('Name') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" placeholder="Name" required>
                @if($errors->has('name'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                  </span>
                @endif
              </div>
            </div>


            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="email">{{ __('Email') }}</label>
              <div class="col-lg-10">
                <input type="email" id="email" name="email" disabled class="form-control" placeholder="Email" value="{{ $user->email }}">
                @if($errors->has('email'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="mobile_number">{{ __('Mobile') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Mobile" value="{{ $user->mobile_number }}">
                @if($errors->has('mobile_number'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('mobile_number') }}</strong>
                  </span>
                @endif
              </div>
            </div>           

            <div class="form-group mb-0 justify-content-end row">
              <div class="col-10">
                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
              </div>
            </div>

          </form>

        </div> <!-- end card-box -->
      </div> <!-- end card-->
    </div><!-- end col -->
  </div>
  <!-- end row-->
</div> <!-- container-fluid -->
<!-- datatable js -->

@endsection