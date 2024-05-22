@extends('layouts.vendor-layout')
@section('pageTitle', 'Dashboard')
@section('content')
@php
$vendata=Helper::getShopData(Auth::id());
$shopslug = $vendata['shop_url_slug'];
@endphp
<div class="container-fluid">
  <!-- start page title -->
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <h4 class="page-title">{{ __('Dashboard') }}</h4>
      </div>
    </div>
  </div>
  <!-- end page title -->
  <div class="row">
    <div class="col-xl-4 col-lg-6">
      <div class="card widget-flat card-box bg-secondary">
        <div class="card-body p-0">
          <div class="p-3 pb-0">
            <div class="float-right">
              <span class="text-white widget-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="35" class="svg-icon-md">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                </svg>
              </span>
            </div>
            <h5 class="text-white font-weight-normal mt-0">{{ __('Total Orders') }}</h5>
            <h3 class="mt-2 text-white mb-0">{{ $totalOrders }}</h3>
          </div>
        </div> <!-- end card-body-->
      </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-4 col-lg-6">
      <div class="card widget-flat card-box bg-primary">
        <div class="card-body p-0">
          <div class="p-3 pb-0">
            <div class="float-right">
              <span class="text-white widget-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="35" class="svg-icon-md">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                </svg>
              </span>
            </div>
            <h5 class="text-white font-weight-normal mt-0">{{ __('Total Products') }}</h5>
            <h3 class="mt-2 text-white mb-0">{{ $totalProducts }}</h3>
          </div>
        </div> <!-- end card-body-->
      </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-4 col-lg-6">
      <div class="card widget-flat card-box bg-fifth">
        <div class="card-body p-0">
          <div class="p-3 pb-0">
            <div class="float-right">
              <span class="text-white widget-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="35" class="svg-icon-md">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
              </span>
            </div>
            <h5 class="text-white font-weight-normal mt-0">{{ __('Total Users') }}</h5>
            <h3 class="mt-2 text-white mb-0">{{ $totalCustomers }}</h3>
          </div>
        </div> <!-- end card-body-->
      </div> <!-- end card-->
    </div> <!-- end col-->

    <!-- <div class="col-xl-3 col-lg-6">
      <div class="card widget-flat card-box bg-third">
        <div class="card-body p-0">
          <div class="p-3 pb-0">
            <div class="float-right">
              <span class="text-white widget-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="35" class="svg-icon-md">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </span>
            </div>
            <h5 class="text-white font-weight-normal mt-0">Total Visits</h5>
            <h3 class="mt-2 text-white mb-0">74,315</h3>
          </div>
        </div>
      </div>
    </div> -->
  </div>

  <div class="row">
    <div class="col-xl-8">
      <div class="card card-box">
        <div class="card-body">
          <h4 class="header-title">{{ __('Sales Analytics') }}</h4>
          <canvas id="analytics" height="379" class="mt-4" width="697" style="display: block; width: 697px; height: 379px;"></canvas>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="card card-box">
        <div class="card-body">
          <h4 class="header-title mb-4">{{ __('Top Products') }}</h4>
          @if(!empty($topSalesProducts))
            <div class="table-responsive">
              <table class="table mb-0">
                <tbody>
                  @foreach($topSalesProducts as $tsproduct)
                  <tr>
                    @php $data = Helper::getProductDataById($tsproduct->product_id); @endphp
                    @if($data)
                    <td colspan="2">{{ $data['name'] }}</td>
                    <!-- <td align="right">{{ setting('payment-setting.currency')." ".number_format($tsproduct->total_price,2) }}</td> -->
                    @endif
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card card-box">
        <div class="card-body">
          <h4 class="header-title">{{ __('Recent Customers') }}</h4>

          <div class="mt-3 table-responsive">
            <table class="table mb-0 table-hover table-centered">
              <thead>
                <tr>
                  <th>{{ __('ID') }}</th>
                  <th>{{ __('Name') }}</th>
                  <th>{{ __('Email') }}</th>
                  <th>{{ __('Phone') }}</th>
                  <th>{{ __('Created Date') }}</th>
                </tr>
              </thead>

              <tbody>
                @foreach($customers as $customer)
                  <tr>
                    <th scope="row">#{{ $customer->id }}</th>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="mr-2 notify-icon bg-primary">
                          <i class="fe-user"></i>
                        </div>
                        <p class="mb-0 font-weight-medium"><a href="javascript: void(0);">{{ $customer->name }}</a></p>
                      </div>
                    </td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->mobile_number }}</td>
                    <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end row -->
</div>

@if (session()->has('message'))
<script type="text/javascript">
  $(document).ready(function() {
    swal({
      text: "{{ Session::get('message') }}",
      icon: "success",
    });
  });
</script>
@endif

<script type="text/javascript">
  var barChartData = {
    labels: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    ],
    datasets: [
        {
            label: "Customers",
            backgroundColor: "#609966",
            borderWidth: 0,
            data: [@php echo $chartData['customers'] @endphp],
        },
        {
            label: "Products",
            backgroundColor: "#40513B",
            borderWidth: 0,
            data: [@php echo $chartData['products'] @endphp],
        },
        {
            label: "Orders",
            backgroundColor: "#9DC08B",
            borderWidth: 0,
            data: [@php echo $chartData['orders'] @endphp],
        },
    ],
  };

  var chartOptions = {
      responsive: true,
      title: {
          display: false,
          text: "Chart.js Bar Chart",
      },
      scales: {
          y: {
              beginAtZero: true,
              max: 10,
          },
      },
      plugins: {
          legend: {
              display: false,
              labels: {
                  color: "rgb(255, 99, 132)",
              },
          },
      },
  };

  window.onload = function () {
    if(document.getElementById("analytics")){
      var ctx = document.getElementById("analytics").getContext("2d");
      window.myBar = new Chart(ctx, {
        type: "bar",
        data: barChartData,
        options: chartOptions,
      });
    }
  };
</script>
@endsection
