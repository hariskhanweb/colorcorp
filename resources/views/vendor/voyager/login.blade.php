@extends('voyager::auth.master')

@section('content')
<div class="login-container">

  <h1>{{__('Admin Panel Login')}}</h1>

  <form action="{{ route('voyager.login') }}" method="POST">
    {{ csrf_field() }}
    <div class="form-group" id="emailGroup">
      <label>Email</label>
      <div class="controls">
        <input type="text" name="email" id="email" value="{{ old('email') }}" placeholder="{{ __('voyager::generic.email') }}" class="form-control" >
      </div>
    </div>

    <div class="form-group" id="passwordGroup">
      <label>Password</label>
      <div class="controls">
        <input type="password" name="password" placeholder="{{ __('voyager::generic.password') }}" class="form-control" >
      </div>
    </div>

    <div class="form-group" id="rememberMeGroup">
      <div class="controls custom-checkbox">
        <input type="checkbox" name="remember" id="remember" value="1"><label for="remember" class="remember-me-text">{{ __('voyager::generic.remember_me') }}</label>
      </div>
    </div>

    <button type="submit" class="btn btn-block btn-primary">
      <span class="signingin hidden"><span class="voyager-refresh"></span> {{ __('voyager::login.loggingin') }}...</span>
      <span class="signin">{{ __('voyager::generic.login') }}</span>
    </button>

  </form>

  <div style="clear:both"></div>

  @if(!$errors->isEmpty())
  <div class="alert alert-red">
    <ul class="list-unstyled">
      @foreach($errors->all() as $err)
      <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
  @endif

</div> <!-- .login-container -->
@endsection

@section('post_js')

<script>
  var btn = document.querySelector('button[type="submit"]');
  var form = document.forms[0];
  var email = document.querySelector('[name="email"]');
  var password = document.querySelector('[name="password"]');
  btn.addEventListener('click', function(ev) {
    if (form.checkValidity()) {
      btn.querySelector('.signingin').className = 'signingin';
      btn.querySelector('.signin').className = 'signin hidden';
    } else {
      ev.preventDefault();
    }
  });
  email.focus();
  document.getElementById('emailGroup').classList.add("focused");

  // Focus events for email and password fields
  email.addEventListener('focusin', function(e) {
    document.getElementById('emailGroup').classList.add("focused");
  });
  email.addEventListener('focusout', function(e) {
    document.getElementById('emailGroup').classList.remove("focused");
  });

  password.addEventListener('focusin', function(e) {
    document.getElementById('passwordGroup').classList.add("focused");
  });
  password.addEventListener('focusout', function(e) {
    document.getElementById('passwordGroup').classList.remove("focused");
  });
</script>
@endsection