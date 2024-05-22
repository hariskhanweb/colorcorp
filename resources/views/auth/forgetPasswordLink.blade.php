@extends('layouts.app')

@section('pageTitle', 'Reset Password')

@section('content')
<style type="text/css">
    .hide,
    nav.navbar {
        display: none;
    }

    body {
        padding-bottom: 0px;
    }

    body main.py-4 {
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
</style>
<div class="account-pages auth-pages">
    <div class="container">
        <div class="row justify-content-center">
            <div class="auth-card">
                <div class="auth-card-body">
                    <div class="col-lg-6 p-3 p-md-5 auth-card-left">
                        <div class="bg-overlay"></div>
                        <a href="/">
                            <img src="{{asset('img/colorcorp-logo.png')}}" alt="" height="120">
                        </a>
                    </div>
                    <div class="col-lg-6 p-3 p-md-5 auth-card-right">
                        <h1>{{ __('Reset Password') }}</h1>
                        <div class="alert alert-warning mb-2 mt-2 font-13">
                            Enter your email address and we'll send you an email with instructions to reset your password.
                        </div>
                        @if (session('status'))
                        <div class="alert alert-success mb-2" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif

                        <form method="POST" action="{{ route('reset.password.post') }}">
                            @csrf
                          <input type="hidden" name="token" value="{{ $token }}">
  
                          <div class="mb-2 form-group">
                              <label for="email_address" >E-Mail Address</label>
                              
                                  <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                                  @if ($errors->has('email'))
                                      <span class="text-danger">{{ $errors->first('email') }}</span>
                                  @endif
                              
                          </div>
  
                          <div class="mb-2 form-group">
                              <label for="password">Password</label>
                              
                                  <input type="password" id="password" class="form-control" name="password" required autofocus>
                                  @if ($errors->has('password'))
                                      <span class="text-danger">{{ $errors->first('password') }}</span>
                                  @endif
                              
                          </div>
  
                          <div class="mb-2 form-group">
                              <label for="password-confirm">Confirm Password</label>
                             
                                  <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required autofocus>
                                  @if ($errors->has('password_confirmation'))
                                      <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                  @endif
                             
                          </div>
  
                          <div class="mt-1 text-center form-group">
                              <button type="submit" class="btn btn-primary">
                                  Reset Password
                              </button>
                          </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection