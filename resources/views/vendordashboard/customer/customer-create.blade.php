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
        <h4 class="page-title">{{ __('Create Customer') }}</h4>
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
          <h4 class="header-title">{{ __('Customer data') }}</h4>
          <p class="sub-header">
            {{ __('All field required.') }}
          </p>

          <form method="POST" action="{{ route('vendor.customer.save') }}" enctype="multipart/form-data" class="form-horizontal">
            {{ csrf_field() }}
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="name">{{ __('Customer Name') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Name" required>
                @if($errors->has('name'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                  </span>
                @endif
              </div>
            </div>


            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="email">{{ __('Customer Email') }}</label>
              <div class="col-lg-10">
                <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                @if($errors->has('email'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="mobile_number">{{ __('Customer Mobile') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Mobile" value="{{ old('mobile_number') }}">
                @if($errors->has('mobile_number'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('mobile_number') }}</strong>
                  </span>
                @endif
              </div>
            </div>

            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="password">{{ __('Customer Password') }}</label>
              <div class="col-lg-10">
                <input type="password" class="form-control" minlength="8" id="password" name="password" value="{{$pass}}" placeholder="Password" required>
                <div id="password_err" class="invalid-feedback"></div>
                @if($errors->has('password'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
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