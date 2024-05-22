<div class="installation_inv_mail" style="padding: 0px 50px 0px 50px;">
<p>
  <!-- <img src="{{ url('img/email/user-reset-password.png') }}" width="100%" class="hero" alt="Colorcorp Logo"> -->
  <img src="http://www.colorcorp.com.au/wp-content/uploads/order.png" width="100%" class="hero" alt="Colorcorp Logo">
</p>
<p>Hello {{ $username }},</p>
<h1>Thanks for shopping with us</h1>
<p>Your installation invoice is generated from {{ env('APP_NAME') }}. Your invoice is given below</p>
<h2>Order Number: {{ $order_number }}</h2>
<p><b>Invoice Number: {{ $inv_number }}</b></p>
<p><b>Total Installation Charge: {{ $amount }}</b></p>

<table style="width: 100%;">
  <tr>
    <td colspan="1" style="text-align: center;">
      <h5>Pay for this invoice</h5>
    </td>
  <tr>
  <tr>
    <td colspan="1" style="text-align: center;">
      <a href="{{ $url }}" class="m_-7226647909899018863button" rel="noopener" style="box-sizing:border-box;font-family:'Open Sans',sans-serif;border-radius:99px;color:#ffffff;display:inline-block;overflow:hidden;text-decoration:none;padding:12px 35px;text-transform:uppercase;font-weight:600;letter-spacing:1px;background-color:#eb2d90" target="_blank" data-saferedirecturl="https://www.google.com/url?q=http://127.0.0.1:8000/jimsmowing/invoice-checkout/OQ%3D%3D&amp;source=gmail&amp;ust=1686122446275000&amp;usg=AOvVaw0NdIgeUfYzIJPbCKsLS0FC">Pay</a>
    </td>
  <tr>
</table>
</div>