@extends('layouts.app')

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
                        <p class="mt-1 mb-2 text-muted">Enter your new password.</p>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-2 form-group">
                                <label for="emailaddress">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-2 form-group">
                                <label for="emailaddress">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-2 form-group">
                                <label for="emailaddress">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">

                                @error('password-confirm')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mt-1 text-center form-group">
                                <button class="btn btn-primary btn-block" type="submit">{{ __('Reset Password') }}</button>
                            </div>
                            <div class="mb-0 text-center form-group">
                                <p class="mb-0">
                                    Back to <a href="/login/" class="fw-semibold text-dark text-decoration-underline">
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