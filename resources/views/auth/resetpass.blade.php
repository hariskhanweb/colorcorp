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
                        <p></p>
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                <strong>{{Session::get('error')}}</strong>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('reset.password') }}">
                            @csrf

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
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-2 form-group">
                                <button type="submit" class="btn btn-primary">{{ __('Reset Password') }}</button>
                                <a href="{{ route('reset.skip') }}" class="btn btn-secondary">Skip</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
@endsection
