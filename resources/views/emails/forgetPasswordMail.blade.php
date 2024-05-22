@component('mail::message')
@php
$url=route('reset.password.get', $details['token']) ;
@endphp
<p>
    <!-- <img src="{{ url('img/email/user-reset-password.png') }}" width="100%" class="hero" alt="Colorcorp Logo"> -->
    <img src="https://www.colorcorp.com.au/wp-content/uploads/user-reset-password.png" width="100%" class="hero" alt="Colorcorp Logo">
</p>
<h2 class="heading" style="text-align: center;">Forget Password</h2>
<p></p>
<p style="text-align: center;">You can reset password from bellow link:</p>
@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

@endcomponent</p>