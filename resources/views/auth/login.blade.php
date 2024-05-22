@extends('layouts.app')

@section('pageTitle', 'Login')

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
                        <h1>{{ __('Welcome in USER LOGIN') }}</h1>
                        <p class="mt-1 mb-2 text-muted">If you have an account, sign in with your email address.</p>
                        <div class="@if (session()->has('success'))  alert alert-success @endif @if (session()->has('error') || session()->has('reset'))  alert alert-danger @endif">{{Session::get('success')}}{{Session::get('error')}}{{Session::get('reset')}}

                            @if (session()->has('reset'))
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#resetModal">
                                Reset link
                            </button>
                            @endif

                        </div>

                        <form method="POST" action="{{ route('login') }} " class="@if (session()->has('errortype')) hide @endif">
                            @csrf

                            <div class="mb-2 form-group">
                                <label for="emailaddress">Email address</label>
                                <input class="form-control" type="email" id="emailaddress" required="" placeholder="Enter your email" name="email" value="{{ old('email') }}">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-2 form-group">
                                <a href="pages-recoverpw.html" class="float-right text-muted"><small></small></a>
                                <label for="password">{{ __('Password') }}</label>
                                <input class="form-control @error('password') is-invalid @enderror" type="password" required="" id="password" placeholder="Enter your password" name="password" required autocomplete="current-password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-2 form-group d-flex justify-content-between">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="remember" id="checkbox-signin" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="checkbox-signin">{{ __('Remember Me') }}</label>
                                </div>
                                <div class="forgotlink">
                                    <a href="/forget-password" class="text-muted">{{ __('Forgot Password?') }}</a>
                                </div>
                            </div>

                            <div class="text-center form-group">
                                <button class="btn btn-primary btn-block" type="submit"> {{ __('Sign In') }} </button>
                            </div>
                            <!-- <div class="mb-0 text-center form-group">
                                <p class="mb-0">
                                    Don't have an account ? <a href="/register/" class="fw-semibold text-dark text-decoration-underline">
                                        <strong>{{ __('Sign Up') }}</strong></a>
                                </p>
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{url('resetlink')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reset Link</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Please enter you email id.</p>
                    <p><input type="text" name="email"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection