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
        <h4 class="page-title"> {{ __('Reset Password') }}</h4>
      </div>
    </div>
  </div>
  @if (session()->has('error'))
  <div class="alert alert-danger text-center">
     {{Session::get('error')}}
  </div>
  @endif
  <!-- end page title -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h4 class="header-title">{{ __('Reset Password') }}</h4>
          <p class="sub-header">
            {{ __('All field required.') }}
          </p>

          <form method="POST" action="{{ route('reset.password') }}" enctype="multipart/form-data" class="form-horizontal">
            {{ csrf_field() }}
            @if (session()->has('error'))
                        <div class="alert alert-danger">
                            <strong>{{Session::get('error')}}</strong>
                        </div>
                    @endif
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="name">{{ __('Password') }}</label>
              <div class="col-lg-10">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>


            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="email">{{ __('Confirm Password') }}</label>
              <div class="col-lg-10">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                  @error('password_confirmation')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                  @enderror
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