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
      <h2>Installation Invoice</h2>
    </div>
    <div class="wrapper">
      <div class="description">
        <strong>Hello {{$UserData->name??'NA'}},</strong><br/><br/>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.
      </div>
      <h3 class="heading">{{__('Invoice Summary')}}</h3>
      <table class="order_summary">
        <tbody>
          <tr>
            <td><b>{{ __('Order Number: ') }}</b>&nbsp;&nbsp;{{ $OrderData->order_number }}</td>
          </tr>
          <tr>
            <td><b>{{ __('Invoice Number: ') }}</b>&nbsp;&nbsp;{{ $ICdata['inv_number'] }}</td>
          </tr>
          <tr>
            <td>
              <b>{{ __('Invoice Status: ') }}</b>&nbsp;&nbsp; 
              <?php if($ICdata['status'] == 2){ 
                echo "<span style='color:green;'>Completed</span>";
                } else if($ICdata['status'] == 0) { 
                echo "<span style='color:red;'>Trash</span>";
                } else { 
                echo "<span style='color:red;'>Pending</span>";
                }
              ?>
            </td>
          </tr>
          <tr>
            <td><b>{{ __('Total Amount: ') }}</b>&nbsp;&nbsp;{{setting('payment-setting.currency')."".number_format($ICdata['total_charges'],2) }}</td>
          </tr>
        </tbody>
      </table>
      <h3 class="heading" style="border-width: 0px;">{{__('Product Information')}}</h3>
      @if(!empty($ICIdata) && count($ICIdata)>0)
      <table class="table">
        <thead>
          <tr>
            <th style="width:10%; text-align:left;">{{ __('Sr. No.') }}</th>
            <th style="width:60%; text-align:left;">{{ __('Product Name') }}</th>
            <th style="width:30%; text-align:right;">{{ __('Installation Charges') }}</th>
          </tr>
        </thead>
        <tbody>
          @php 
            $num = 1;   
          @endphp   
          @foreach($ICIdata as $key => $value)
          <tr>
            <td>{{ $num }}</td>
            <td>
              {{ $value['order_item'] }}
            </td>
            <td style="text-align:right;">{{setting('payment-setting.currency')."".number_format(($value['charges']),2)}}</td>
          </tr>
          @php 
            $num++;
          @endphp
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2" style="text-align:right;">
              Total Amount:
            </td>
            <td style="text-align:right;">
              {{setting('payment-setting.currency')."".number_format($ICdata->total_charges,2) }}
            </td>
          </tr>
        </tfoot>
      </table>
      @endif      
  </div>
</main>
<footer style="margin-top: 30px;">Copyright © <?php echo date("Y");?> | {{setting('general.title')}}</footer>
</body>
</html>