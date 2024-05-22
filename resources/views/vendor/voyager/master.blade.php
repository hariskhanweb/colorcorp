<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <title>@yield('page_title', setting('admin.title') . " - " . setting('admin.description'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="assets-path" content="{{ route('voyager.voyager_assets') }}" />

    <!-- Favicon -->
    <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">





    @yield('css')
    @if(__('voyager::generic.is_rtl') == 'true')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="{{ voyager_asset('css/rtl.css') }}">
    @endif
    <link rel="stylesheet" href="{{ url('css/admin.css') }}">

    <!-- Few Dynamic Styles -->
    <style type="text/css">
        .voyager .side-menu .navbar-header {
            background:{{ config('voyager.primary_color','#22A7F0') }};
            border-color:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .widget .btn-primary{
            border-color:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .widget .btn-primary:focus, .widget .btn-primary:hover, .widget .btn-primary:active, .widget .btn-primary.active, .widget .btn-primary:active:focus{
            background:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .voyager .breadcrumb a{
            color:{{ config('voyager.primary_color','#22A7F0') }};
        }
    </style>

    @if(!empty(config('voyager.additional_css')))<!-- Additional CSS -->
        @foreach(config('voyager.additional_css') as $css)
        <link rel="stylesheet" type="text/css" href="{{ asset($css) }}">
        @endforeach
    @endif

    @yield('head')
</head>

<body class="voyager @if(isset($dataType) && isset($dataType->slug)){{ $dataType->slug }}@endif">

    <div id="voyager-loader">
        <?php $admin_loader_img = Voyager::setting('admin.loader', ''); ?>
        @if($admin_loader_img == '')
            <img src="{{ voyager_asset('images/logo-icon.png') }}" alt="Voyager Loader">
        @else
            <img src="{{ Voyager::image($admin_loader_img) }}" alt="Voyager Loader">
        @endif
    </div>

    <?php
    if (\Illuminate\Support\Str::startsWith(Auth::user()->avatar, 'http://') || \Illuminate\Support\Str::startsWith(Auth::user()->avatar, 'https://')) {
        $user_avatar = Auth::user()->avatar;
    } else {
        $user_avatar = Voyager::image(Auth::user()->avatar);
    }
    ?>

    <div class="app-container">
        <div class="fadetoblack visible-xs"></div>
        <div class="row content-container">
            @include('voyager::dashboard.navbar')
            @include('voyager::dashboard.sidebar')
            <script>
                (function() {
                    var appContainer = document.querySelector('.app-container'),
                        sidebar = appContainer.querySelector('.side-menu'),
                        navbar = appContainer.querySelector('nav.navbar.navbar-top'),
                        loader = document.getElementById('voyager-loader'),
                        hamburgerMenu = document.querySelector('.hamburger'),
                        sidebarTransition = sidebar.style.transition,
                        navbarTransition = navbar.style.transition,
                        containerTransition = appContainer.style.transition;

                    sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition =
                        appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition =
                        navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = 'none';

                    if (window.innerWidth > 768 && window.localStorage && window.localStorage['voyager.stickySidebar'] == 'true') {
                        appContainer.className += ' expanded no-animation';
                        loader.style.left = (sidebar.clientWidth / 2) + 'px';
                        hamburgerMenu.className += ' is-active no-animation';
                    }

                    navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = navbarTransition;
                    sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition = sidebarTransition;
                    appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition = containerTransition;
                })();
            </script>
            <!-- Main Content -->
            <div class="container-fluid">
                <div class="side-body padding-top">
                    @yield('page_header')
                    <div id="voyager-notifications"></div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @include('voyager::partials.app-footer')

    <!-- Javascript Libs -->


    <script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>
    <script src="{{ url('js/chart.js') }}"></script>
    <!-- <script src="{{ url('js/admin-custom.js') }}"></script> -->
    <!-- <script src="{{ url('libs/ckeditor/ckeditor4.js') }}"></script> -->
    <!-- <script src="{{ url('libs/ckeditor5/ckeditor.js') }}"></script> -->
    
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>


<script>
    @if(Session::has('alerts'))
        let alerts = {!! json_encode(Session::get('alerts')) !!};
        helpers.displayAlerts(alerts, toastr);
    @endif

    @if(Session::has('message'))

    // TODO: change Controllers to use AlertsMessages trait... then remove this
    var alertType = {!! json_encode(Session::get('alert-type', 'info')) !!};
    var alertMessage = {!! json_encode(Session::get('message')) !!};
    var alerter = toastr[alertType];

    if (alerter) {
        alerter(alertMessage);
    } else {
        toastr.error("toastr alert-type " + alertType + " is unknown");
    }
    @endif



    $(document).ready(function () {
        $('#bulk_delete_btn').hide();

        $('.product-name').keyup(function(){
            let str = this.value;
            var newstr = str.replace('&', 'and');
            newstr = newstr.replace(/[&@\/\\#, +()$~%.'":*?<>{}]/g, '-');
            $('.product-slug').val(newstr.toLowerCase());
        });

        $('.page-name').keyup(function(){
            let str = this.value;
            var newstr = str.replace('&', 'and');
            newstr = newstr.replace(/[&@\/\\#, +()$~%.'":*?<>{}]/g, '-');
            $('.page-slug').val(newstr.toLowerCase());
        });


        $('#vendor_id').change(function() {
            var vendorId = $(this).val();
            $.ajax({
                url: '{{ route('getParentCategories') }}',
                method: 'GET',
                data: { vendor_id: vendorId },
                success: function(response) {
                    $('.parent_id').empty().append(response.options);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });


            $.ajax({
                url: '{{ route('getCategories') }}',
                method: 'GET',
                data: { vendor_id: vendorId },
                success: function(response) {
                    $('#prodcategory').empty().append(response.options);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $('#prodhasvariate').change(function() {
            let varient = $(this).val();
            if(varient=="1") { 
                $("#prodothrsection").css('display', 'block'); 
            } else { 
                $("#prodothrsection").css('display', 'none'); 
            }
        });


         $('.ajaxButton').click(function() {
            var id = $('#gallryimgid').val();
            $.ajax({
                url: '{{ route('removeProdGalaryImg') }}',
                method: 'GET',
                data: {id:id},
                success: function(response) {
                    if(response['data']==1){ 
                        $('#pgalimg'+id).css('display', 'none');                    
                    }
                    $("#cofirmgallModal").modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

    });

function ShowAttributeOpts(parentid){
    let psecid = "prodparentattr"+parentid;
    if($("#"+psecid).prop('checked') == true){
        $("#showattroption"+parentid).css('display', 'block');
    } else {
        $("#showattroption"+parentid).css('display', 'none');
    }
}





function RemoveGalleryImg(imgid){
    $('#gallryimgid').val(imgid);
    $("#cofirmgallModal").modal({backdrop: 'static', keyboard: false});
}



    </script>
    @include('voyager::media.manager')
    @yield('javascript')
    @stack('javascript')
    @if(!empty(config('voyager.additional_js')))<!-- Additional Javascript -->
        @foreach(config('voyager.additional_js') as $js)<script type="text/javascript" src="{{ asset($js) }}"></script>@endforeach
    @endif

</body>

</html>