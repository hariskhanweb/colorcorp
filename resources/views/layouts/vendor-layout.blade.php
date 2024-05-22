<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('pageTitle') - {{ config('app.name', 'Laravel') }}</title>

  <!-- App favicon -->
  <link rel="shortcut icon" href="assets/images/favicon.ico">

  <!-- Styles -->
  <link href="{{ url('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ url('css/select2.min.css') }}" rel="stylesheet">
  <link href="{{ url('css/select2-bootstrap') }}" rel="stylesheet">
  <link href="{{ url('css/app.min.css') }}" rel="stylesheet">
  <link href="{{ url('css/icons.min.css') }}" rel="stylesheet">
  <link href="{{ url('css/custom.css') }}" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
  <link href="{{ url('libs/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ url('libs/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
  @include('layouts.style')
</head>

<body>
  @php
  $vendata=Helper::getShopData(Auth::id());
  @endphp
  <div id="wrapper">
    @include('layouts.vendor-header')

    <!-- ========== Left Sidebar Start ========== -->
    <div class="left-side-menu">

      <div class="sidebar-toggle">
        <span class="sidebar-open">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="30" class="svg-icon-md">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
          </svg>
        </span>
      </div>

      <div class="slimscroll-menu">

        <!-- LOGO -->
        <a href="{{ route('vendor.dashboard', ['vendor_name' => $vendata['shop_url_slug']]) }}" class="mb-4 text-center logo">
          <span class="logo-lg">
            <img src="{{ asset('img/logo.png') }}" alt="Colocorp Logo" />
          </span>
          <span class="logo-sm">
            <img src="{{ asset('img/logo.png') }}" alt="Colocorp Logo" />
          </span>
        </a>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

          <ul class="metismenu" id="side-menu">

            <li>
              <a href="{{ route('vendor.dashboard', ['vendor_name' => $vendata['shop_url_slug']]) }}">
                <i class="fe-airplay"></i>
                <!-- <span class="float-right badge badge-success">01</span> -->
                <span> {{__('Dashboard')}} </span>
              </a>
            </li>

            {{--<li>
              <a href="{{ route('vendor.category', ['vendor_name' => $vendata['shop_url_slug']]) }}">
                <i class="fe-briefcase"></i>
                <span> {{__('Category Management')}} </span>
              </a>
            </li>--}}

            <li>
              <a href="{{ route('vendor.product', ['vendor_name' => $vendata['shop_url_slug']]) }}">
                <i class="fe-grid"></i>
                <span> {{__('Product Management')}} </span>
              </a>
            </li>
            <li>
              <a href="{{ route('vendor.customer', ['vendor_name' => $vendata['shop_url_slug']]) }}">
                <i class="fe-users"></i>
                <span> {{__('Customer Management')}}</span>
              </a>
            </li>
            <li>
              <a href="{{ route('vendor.order', ['vendor_name' => $vendata['shop_url_slug']]) }}">
                <i class="fe-users"></i>
                <span> {{__('Order Management')}}</span>
              </a>
            </li>
          </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

      </div>
      <!-- Sidebar -left -->

    </div>
    <!-- Left Sidebar End -->
    <div class="content-page">
      <div class="content">
        @yield('content')
      </div>
      @include('layouts.vendor-footer')
      <!-- end Footer -->
    </div>
    <div class="right-bar">
      <div class="rightbar-title">
        <a href="javascript:void(0);" class="float-right right-bar-toggle">
          <i class="mdi mdi-close"></i>
        </a>
        <h5 class="m-0 text-white">Settings</h5>
      </div>

      <div class="jmScrollDiv">
        @php
        $vendata=Helper::getShopData(Auth::id());
        @endphp
        <form method="POST" action="{{ route('colorSetting.update', ['id' => $vendata['id']]) }}" enctype="multipart/form-data" class="form-horizontal">
          {{ csrf_field() }}
          <div class="form-group jm-color-setting">
            <span>Theme Color 1</span>
            <label for="shop_primary_color">
              <input type="color" id="shop_primary_color" name="shop_primary_color" class="form-control" placeholder="color" value="{{ $vendata['shop_primary_color'] }}" required>
            </label>
          </div>
          <div class="form-group jm-color-setting">
            <span>Theme Color 2</span>
            <label for="shop_secondary_color">
              <input type="color" id="shop_secondary_color" name="shop_secondary_color" class="form-control" placeholder="color" value="{{ $vendata['shop_secondary_color'] }}" required>
            </label>
          </div>

          <div class="form-group jm-color-setting">
            <span>Theme Color 3</span>
            <label for="shop_body_color">
              <input type="color" id="shop_body_color" name="shop_body_color" class="form-control" placeholder="color" value="{{ $vendata['shop_body_color'] }}" required>
            </label>
          </div>
          <div class="form-group jm-color-setting">
            <span>Theme Color 4</span>
            <label for="shop_heading_color">
              <input type="color" id="shop_heading_color" name="shop_heading_color" class="form-control" placeholder="color" value="{{ $vendata['shop_heading_color'] }}" required>
            </label>
          </div>
          <div class="form-group jm-color-setting">
            <span>Theme Color 5</span>
            <label for="shop_third_color">
              <input type="color" id="shop_third_color" name="shop_third_color" class="form-control" placeholder="color" value="{{ $vendata['shop_third_color'] }}" required>
            </label>
          </div>
          <div class="form-group jm-color-setting">
            <span>Theme Color 6</span>
            <label for="shop_forth_color">
              <input type="color" id="shop_forth_color" name="shop_forth_color" class="form-control" placeholder="color" value="{{ $vendata['shop_forth_color'] }}" required>
            </label>
          </div>
          <div class="form-group jm-color-setting">
            <span>Theme Color 7</span>
            <label for="shop_fifth_color">
              <input type="color" id="shop_fifth_color" name="shop_fifth_color" class="form-control" placeholder="color" value="{{ $vendata['shop_fifth_color'] }}" required>
            </label>
          </div>

          <!-- For Menus -->
          <div class="form-group jm-color-setting">
            <span>Menu Background Color</span>
            <label for="menu_bg_color">
              <input type="color" id="menu_bg_color" name="menu_bg_color" class="form-control" placeholder="color" value="{{ $vendata['menu_bg_color'] }}" required>
            </label>
          </div>
          <div class="form-group jm-color-setting">
            <span>Active Menu Background Color</span>
            <label for="active_menu_bg_color">
              <input type="color" id="active_menu_bg_color" name="active_menu_bg_color" class="form-control" placeholder="color" value="{{ $vendata['active_menu_bg_color'] }}" required>
            </label>
          </div>
          <div class="form-group jm-color-setting">
            <span>Menu Text Color</span>
            <label for="menu_text_color">
              <input type="color" id="menu_text_color" name="menu_text_color" class="form-control" placeholder="color" value="{{ $vendata['menu_text_color'] }}" required>
            </label>
          </div>
          <div class="form-group jm-color-setting">
            <span>Active Menu Text Color</span>
            <label for="active_menu_text_color">
              <input type="color" id="active_menu_text_color" name="active_menu_text_color" class="form-control" placeholder="color" value="{{ $vendata['active_menu_text_color'] }}" required>
            </label>
          </div>

          <div class="form-group mb-0 text-center">
            <button type="submit" class="btn btn-primary btn-block">Save Settings</button>
          </div>
        </form>
      </div> <!-- end slimscroll-menu-->
    </div>

  </div>
  <div class="rightbar-overlay"></div>

  <!-- App js -->
  <script src="{{ url('js/vendor.min.js') }}"></script>
  <script src="{{ url('libs/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ url('libs/datatables/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ url('js/select2.full.js') }}"></script>
  <script src="{{ url('js/chart.js') }}"></script>
  <script src="{{ url('js/app.min.js') }}"></script>
  <!-- Editor Js -->
  <script src="{{ url('libs/ckeditor/ckeditor.js') }}"></script>
  <!-- Custom JS -->
  <script src="{{ url('js/custom.js') }}"></script>
  <script src="{{ url('js/sweetalert.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('.select2').select2({
        closeOnSelect: false,
      });
    });
    $(document).ready(function() {
      // Default Datatable
      $('#basic-datatable').DataTable({
        "pageLength": 8,
        "lengthMenu": [
          [8, 15, 25, 50, -1],
          [8, 15, 25, 50, "All"]
        ],
        "language": {
          "paginate": {
            "previous": "<i class='mdi mdi-chevron-left'>",
            "next": "<i class='mdi mdi-chevron-right'>"
          }
        },
        "drawCallback": function() {
          $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
        }
      });
    });
    $("input[name=mobile_number]").keypress(function(event) {
      return /\d/.test(String.fromCharCode(event.keyCode));
    });

    $("#password").keyup(function(event) {
      var pswd = $(this).val();
      regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#\$%\^\&*\)\(+=._-])[A-Za-z\d!@#\$%\^\&*\)\(+=._-]{8,}$/;
      if (regex.exec(pswd) == null) {
        $("#password_err").html("The password must be at least 8 characters & it should contain at least one capital letter, one digit & one special character.");
        $("#password").focus();
        return false;
      } else {
        $("#password_err").html("");
        return true;
      }

    });
  </script>
  <script>
    var allCkEditors = [];
    var allEditors = document.querySelectorAll('.editor');
    for (var i = 0; i < allEditors.length; ++i) {
      ClassicEditor.create(allEditors[i], {
          toolbar: ['heading', '|',
            'bold', 'italic', '|',
            'link', '|',
            'bulletedList', 'numberedList', '|',
            'insertTable', '|',
            'blockQuote', '|',
            'undo', 'redo'
          ],
          height: "140px"
        })
        .then(editor => {
          allCkEditors.push(editor);
        });
    }

    function ckEditor(name) {
      for (var i = 0; i < allCkEditors.length; i++) {
        if (allCkEditors[i].sourceElement.id === name) return allCkEditors[i];
      }
      return null;
    }

    document.querySelectorAll('input[type=color]').forEach(function(picker) {
      var targetLabel = document.querySelector('label[for="' + picker.id + '"]'),
        codeArea = document.createElement('span');

      codeArea.innerHTML = picker.value;
      targetLabel.appendChild(codeArea);

      picker.addEventListener('change', function() {
        codeArea.innerHTML = picker.value;
        targetLabel.appendChild(codeArea);
      });
    });
  </script>

</body>


</html>