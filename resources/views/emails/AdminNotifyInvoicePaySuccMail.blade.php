@component('mail::message')
<p>
  <!-- <img src="{{ url('img/email/user-reset-password.png') }}" width="100%" class="hero" alt="Colorcorp Logo"> -->
  <img src="http://www.colorcorp.com.au/wp-content/uploads/order.png" width="100%" class="hero" alt="Colorcorp Logo">
</p>
<p>Hello {{ $details['name'] }},</p>
<p>The installation invoice payment of {{ $details['username']??'NA'}} on {{ env('APP_NAME') }} has been recieved. The invoice details are shown below</p>
<h2>Order Number: {{ $details['order_number'] }}</h2>
<p><b>Invoice Number: {{ $details['inv_number'] }}</b></p>
<p><b>Installation Invoice Status: {{ $details['status'] }}</b></p>
<p><b>Total Installation Charge: {{ $details['amount'] }}</b></p>
<p><b>Transaction ID # {{ $details['transaction_id'] }}</b></p>
@endcomponent