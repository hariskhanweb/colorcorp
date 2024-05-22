@component('mail::message')
<p>
    <!-- <img src="{{ url('img/email/user-reset-password.png') }}" width="100%" class="hero" alt="Colorcorp Logo"> -->
    <img src="http://www.colorcorp.com.au/wp-content/uploads/user-reset-password.png" width="100%" class="hero" alt="Colorcorp Logo">
</p>
<h2 class="heading" style="text-align: center;">Reset Password</h2>
<p style="text-align: center;">Hello {{ $details['name'] }}</p>
<p style="text-align: center;">Thanks for the update, but please do not share password with anyone as this is security breach.</p>
<p></p>

@endcomponent