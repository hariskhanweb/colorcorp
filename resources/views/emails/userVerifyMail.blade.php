@component('mail::message')
<p>
    <!-- <img src="{{ url('img/email/user-reset-password.png') }}" width="100%" class="hero" alt="Colorcorp Logo"> -->
    <img src="http://www.colorcorp.com.au/wp-content/uploads/user-account.png" width="100%" class="hero" alt="Colorcorp Logo">
</p>
@if(isset($details['role_id']) && $details['role_id'] == 2)
<p><h2 class="heading" style="text-align: center;">Your Account Registered Successfully</h2></p>
<p style="text-align: center;margin-top: 15px;">Thank you for your interest in accessing the Colorcorp.</p>
<p style="text-align: center;">Admin has successfully set up your account, please log in with the following details.</p>
<p style="text-align: center;">Email: {{ $details['email'] }}</p>
<p style="text-align: center;">Password: {{ $details['pass'] }}</p>
@else
<h2 class="heading" style="text-align: center;">Email Verification</h2>
<p style="text-align: center;margin-top: 15px;">Thank you for your interest in accessing the Colorcorp.</p>
<p style="text-align: center;">Click the below link and details to verify yourself for Colorcorp system.</p>
<p style="text-align: center;">Email: {{ $details['email'] }}</p>
<p style="text-align: center;">Password: {{ $details['pass'] }}</p>
@endif
@if($details['url'] != '')
@component('mail::button', ['url' => $details['url']])
Verify email
@endcomponent
@endif
@endcomponent</p>