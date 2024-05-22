<!DOCTYPE html>
<html lang="en">
<head>
  <title>Order Invoice</title>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  
  <style>
    /** Define the margins of your page **/
    @page {
      margin: 20px;
    }
    .page-break {
      page-break-after: always;
    }
    *{
      box-sizing: border-box;
    }
    body {
      font-family: 'Open Sans', sans-serif;
      margin: 0px; 
      color: #333333;
      line-height: normal;
      font-size: 14px;
    }

    header {
      position: relative;
      font-size: 20px !important;
      text-align: center;
      text-align: center;
    }
    .logo {
      display: inline-block;
      max-width: 250px;
    }

    .top-heading {
      background-color: #007934;
      color: #fff;
      padding: 5px 15px;
      margin-bottom: 25px;
    }
    .top-heading  h2 {
      margin: 0px;
    }
    .description {
      color: #333333;
    }
    .wrapper {
      display: inline-block;
      width: 100%;
      padding: 0px;
    }
    .heading {
      width: 100%;
      display: inline-block;
      margin-top: 25px;
      margin-bottom: 0px;
      border-bottom: 1px solid #ddd;
      font-size: 18px;
      padding: 10px 0px;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }
    table thead tr th,
    table tbody tr td {
      padding: 5px;
      vertical-align: top;
    }
    table tbody tr td.label {
      font-weight: 700;
    }
    .table thead tr th {
      background-color: #333;
      color: #fff;
      border-right: 1px solid #fff;
    }
    table tfoot {
      border-top: 1px solid #e1e1e1;
      border-bottom: 1px solid #e1e1e1;
      padding-bottom: 5px;
    }
    footer {
      position: relative;
      font-size: 14px !important;
      text-align: center;
      padding: 5px 10px;
      background-color: #ccc;
    }
  </style>
</head>
<body>
  <!-- Define header and footer blocks before your content -->
  <header>
    <img class="logo" src="https://adbuzzdemo2.com.au/jimsgroup/images/logo.png" />
  </header>
  <!-- Wrap the content of your PDF inside a main tag -->
  <main class="pdf-content">
    <div class="top-heading">
      <h2>Order Invoice</h2>
    </div>
    <div class="wrapper">
      <div class="description">
        <strong>Hello {{$data['shipping_address']['name']??'NA'}},</strong><br/><br/>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.
      </div>
      <h3 class="heading">{{__('Order Summary')}}</h3>
      <table class="order_summary">
        <tbody>
          <tr>
            <td><b>{{ __('Order Number: ') }}</b>&nbsp;&nbsp;{{ $data['order_number'] }}</td>
          </tr>
          <tr>
            <td>
              <b>{{ __('Order Status: ') }}</b>&nbsp;&nbsp; 
              <?php if($data['status'] == 2){ 
                echo "<span style='color:green;'>Completed</span>";
                } else if($data['status'] == 0) { 
                echo "<span style='color:red;'>Trash</span>";
                } else { 
                echo "<span style='color:red;'>Pending</span>";
                }
              ?>
            </td>
          </tr>
          <tr>
            <td><b>{{ __('Transaction Id: ') }}</b>&nbsp;&nbsp;{{$data['transaction_id']}}</td>
          </tr>
          <tr>
            <td><b>{{ __('Total Amount: ') }}</b>&nbsp;&nbsp;{{setting('payment-setting.currency')."".number_format($data['total_amount'],2) }}</td>
          </tr>
        </tbody>
      </table>
      <h3 class="heading" style="border-width: 0px;">{{__('Product Information')}}</h3>
      @if(!empty($data['order_items']) && count($data['order_items'])>0)
      <table class="table">
        <thead>
          <tr>
            <th style="width:30%; text-align:left;">{{ __('Product Name') }}</th>
            <th style="width:40%; text-align:left;">{{ __('Product Specification') }}</th>
            <th style="width:10%; text-align:left;">{{ __('Price') }}</th>  
            <th style="width:10%; text-align:left;">{{ __('Quantity') }}</th> 
            <th style="width:10%; text-align:right;">{{ __('Total') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data['order_items'] as $key => $value)
          <tr>
            <td>
              @php
                $fimage = App\Helpers\Helpers::getFeaturedImage($value['product_id']);
                $catId = $value['parent_cat_id'];
                $division = App\Helpers\Helpers::getCategoryName($catId);
              @endphp 
              {{ $value['name'] }}
            </td>
            <td>
              
              @if($value['vehicle_make'] != '')
              <b>{{__('Vehicle Make')}} : </b>{{ ucfirst($value['vehicle_make']) }}<br/>
              @endif
              @if($value['vehicle_model'] != '')
              <b>{{__('Vehicle Model')}} : </b>{{ ucfirst($value['vehicle_model']) }}<br/>
              @endif
              @if($value['vehicle_colour'] != '')
              <b>{{__('Vehicle Colour')}} : </b>{{ ucfirst($value['vehicle_colour']) }}<br/>
              @endif
              @if($value['vehicle_year'] != '')
              <b>{{__('Vehicle Year')}} : </b>{{ ucfirst($value['vehicle_year']) }}<br/>
              @endif
              @if($value['vehicle_rego'] != '')
              <b>{{__('Vehicle Rego')}} : </b>{{ ucfirst($value['vehicle_rego']) }}<br/>
              @endif
              @if($value['franchise_name'] != '')
              <b>{{__('Franchise Name')}} : </b>{{ ucfirst($value['franchise_name']) }}<br/>
              @endif
              @if($value['franchise_territory'] != '')
              <b>{{__('Franchise Territory')}} : </b>{{ ucfirst($value['franchise_territory']) }}<br/>
              @endif

              <b>{{__('Decal Removel')}} : </b>{{ ucfirst($value['decal_removel']) }}<br/>
              <b>{{__('Re Scheduling Fee ')}} : </b>{{ ucfirst($value['re_scheduling_fee']) }}<br/>
              <b>{{__('Preparation Fee')}} : </b>{{ ucfirst($value['preparation_fee']) }}<br/>
              
              

              <b>{{__('Division')}} : </b>{{ $division }}<br/>
              <b>{{__('Comment')}} : </b>{{ ucfirst($value['comment']) }}
            </td>
            <td>{{setting('payment-setting.currency')."".number_format($value['pro_att_price'],2) }}</td>
            <td>{{ $value['quantity'] }}</td>
            <td style="text-align:right;">{{setting('payment-setting.currency')."".number_format(($value['quantity']*$value['pro_att_price']),2)}}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4" style="text-align:right;">{{ __('Subtotal: ') }}</td>
            <td style="text-align:right;">{{setting('payment-setting.currency')."".number_format($data['subtotal'],2) }}</td>
          </tr>
          <tr>
            <td colspan="4" style="text-align:right;">
              GST ({{$data['tax']}}%) Amount:
            </td>
            <td style="text-align:right;">
              {{ setting('payment-setting.currency')."".number_format($data['gst'],2) }}
            </td>
          </tr>
          <tr>
            <td colspan="4" style="text-align:right;">
              Total Amount:
            </td>
            <td style="text-align:right;">
              {{setting('payment-setting.currency')."".number_format($data['total_amount'],2) }}
            </td>
          </tr>
        </tfoot>
      </table>
      @endif
      
      <h3 class="heading" style="border-bottom: 0px">{{__('Addresses')}}</h3>
      <table style="width:100%; border:none!important;" class="table">
      <thead>
        <tr>
          <th style="text-align:left;">{{__('Shipping Address')}}</th>
          <th style="text-align:left;">{{__('Billing Address')}}</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div>
              <div><label>{{__('Name')}}</label><span><b>:</b> {{$data['shipping_address']['name']??'NA'}} </span></div>
              <div><label>{{__('Address')}}</label><span><b>:</b> {{$data['shipping_address']['address']??'NA'}}</span></div>
              <div><label>{{__('City')}}</label><span><b>:</b> {{$data['shipping_address']['city']??"NA"}} </span></div>
              <div><label>{{__('State')}}</label><span><b>:</b> {{$datashipstate->name??"NA"}}</span></div>
              <div><label>{{__('Country')}}</label><span><b>:</b> {{$datashipcontry->name??"NA"}} </span></div>
              <div><label>{{__('Mobile')}}</label><span><b>:</b> +{{$data['shipping_address']['mobile_number']??"NA"}}</span></div>
              <div><label>{{__('PostCode')}}</label><span><b>:</b> {{$data['shipping_address']['postcode']??"NA"}}</span></div>
            </div>
          </td>
          <td>
            <div>
              <div><label>{{__('Name')}}</label><span><b>:</b> {{$data['billing_address']['name']??'NA'}} </span></div>
              <div><label>{{__('Address')}}</label><span><b>:</b> {{$data['billing_address']['address']??'NA'}}</span></div>
              <div><label>{{__('City')}}</label><span><b>:</b> {{$data['billing_address']['city']??"NA"}} </span></div>
              <div><label>{{__('State')}}</label><span><b>:</b> {{$databillstate->name??"NA"}}</span></div>
              <div><label>{{__('Country')}}</label><span><b>:</b> {{$databillcontry->name??"NA"}} </span></div>
              <div><label>{{__('Mobile')}}</label><span><b>:</b> +{{$data['billing_address']['mobile_number']??"NA"}}</span></div>
              <div><label>{{__('PostCode')}}</label><span><b>:</b> {{$data['billing_address']['postcode']??"NA"}}</span></div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</main>
<footer style="margin-top: 30px;">Copyright © <?php echo date("Y");?> | {{setting('general.title')}}</footer>
</body>
</html>