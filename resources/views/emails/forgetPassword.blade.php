@component('mail::message')
<p>
    <!-- <img src="{{ url('img/email/user-reset-password.png') }}" width="100%" class="hero" alt="Colorcorp Logo"> -->
    <img src="http://www.colorcorp.com.au/wp-content/uploads/user-reset-password.png" width="100%" class="hero" alt="Colorcorp Logo">
</p>
<h2 class="heading" style="text-align: center;">Hello, {{ $details['name'] }}</h2>
<p style="text-align: center;">Thank you for your interest in accessing the Colorcorp.</p>
<p style="text-align: center;">Click the below link and details to verify yourself for Colorcorp system.</p>
<p style="text-align: center;">Email: {{ $details['email'] }}</p>
<p style="text-align: center;">Password: {{ $details['pass'] }}</p>
@component('mail::button', ['url' => $details['url']])
Verify email
@endcomponent

@endcomponent</p>