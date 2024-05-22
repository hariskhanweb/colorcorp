<!-- <?php
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $protocol = 'https://';
}
else {
  $protocol = 'http://';
}
$notssl = 'http://';
if($protocol==$notssl){
    $url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>
    <script> 
    window.location.href ='<?php echo $url?>';
    </script> 
<?php } ?> -->
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="robots" content="none" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content="admin login">
  <title>@yield('title', 'Admin - '.Voyager::setting("admin.title"))</title>
  <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ url('css/admin.css') }}">
  @if (__('voyager::generic.is_rtl') == 'true')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
  <link rel="stylesheet" href="{{ voyager_asset('css/rtl.css') }}">
  @endif
  <style>
    body {
      background-image:url('{{ Voyager::image( Voyager::setting("admin.bg_image"), voyager_asset("images/bg.jpg") ) }}');

      background-color: {
          {
          Voyager: :setting("admin.bg_color", "#FFFFFF")
        }
      }

      ;
    }

    body.login .login-sidebar {
      border-top:5px solid {
          {
          config('voyager.primary_color', '#22A7F0')
        }
      }

      ;
    }

    @media (max-width: 767px) {
      body.login .login-sidebar {
        border-top: 0px !important;

        border-left:5px solid {
            {
            config('voyager.primary_color', '#22A7F0')
          }
        }

        ;
      }
    }

    body.login .form-group-default.focused {
      border-color: {
          {
          config('voyager.primary_color', '#22A7F0')
        }
      }

      ;
    }

    .login-button,
    .bar:before,
    .bar:after {
      background: {
          {
          config('voyager.primary_color', '#22A7F0')
        }
      }

      ;
    }

    .remember-me-text {
      padding: 0 5px;
    }
  </style>

  @yield('pre_css')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="login">

  <div class="auth-layout">
    <div class="auth-card">
      @yield('content')
    </div>
    <div class="auth-image">
      <div class="auth-caption">
        <?php $admin_logo_img = Voyager::setting('admin.icon_image', ''); ?>
        @if($admin_logo_img == '')
        <img class="img-responsive logo" src="{{ url('img/colorcorp-logo.png') }}" alt="{{ Voyager::setting('admin.title', 'Voyager') }}">
        @else
        <img class="img-responsive logo" src="{{ Voyager::image($admin_logo_img) }}" alt="{{ Voyager::setting('admin.title', 'Voyager') }}">
        @endif
        <div class="copy animated fadeIn" style="display: none;">
          <h1>{{ Voyager::setting('admin.title', 'Voyager') }}</h1>
          <p>{{ Voyager::setting('admin.description', __('voyager::login.welcome')) }}</p>
        </div>
      </div>
    </div>
  </div>
  @yield('post_js')
</body>

</html>