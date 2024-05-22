@extends('layouts.app')

@section('pageTitle', 'Registration')

@section('content')
<style type="text/css">
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
                        <h1>Create New Account</h1>
                        <p class="mt-1 mb-2 text-muted">Don't have an account? Create free account</p>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-2 form-group">
                                <label for="emailaddress">{{ __('Name') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-2 form-group">
                                <label for="emailaddress">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email address" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-2 form-group">
                                <label for="emailaddress">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" name="password" required autocomplete="new-password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-2 form-group">
                                <label for="emailaddress">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control @error('password-confirm') is-invalid @enderror" placeholder="Enter confirm password" name="password_confirmation" required autocomplete="new-password">
                                @error('password-confirm')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-2 form-group d-flex justify-content-between">
                                <div class="custom-control custom-checkbox">
                                    <input id="checkbox-terms" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label text-muted" for="checkbox-terms">{{ __('I accept Terms and Conditions') }}</label>
                                </div>
                            </div>

                            <div class="text-center form-group">
                                <button class="btn btn-primary btn-block" type="submit"> {{ __('Register') }} </button>
                            </div>

                            <div class="mb-0 text-center form-group">
                                <p class="mb-0 text-muted">
                                    Already have account? <a href="/login/" class="fw-semibold text-dark text-decoration-underline">
                                        <strong>{{ __('Log In') }}</strong></a>
                                </p>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection