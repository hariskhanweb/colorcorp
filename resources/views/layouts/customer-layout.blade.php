<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  
  <head>

      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">

      @php 
      $metaData = ''  ;
      $vendor_id = Auth::user()->vendor_id;
      if (Route::currentRouteName() == 'shop') {
        $metaData = Helper::getMetaTags('page', $vendor_id, '');
      } 
      elseif(Route::currentRouteName() == 'shop.subcategories') {
        $routeParameters = Route::current()->parameters();
        $metaData = Helper::getMetaTags('category', $vendor_id, $routeParameters['category_slug']);
      } 
      elseif(Route::currentRouteName() == 'product.list') {
        $routeParameters = Route::current()->parameters();
        $metaData = Helper::getMetaTags('sub-category', $vendor_id, $routeParameters['category']);
      } 
      elseif(Route::currentRouteName() == 'product.single') {
        $routeParameters = Route::current()->parameters();
        $metaData = Helper::getMetaTags('product', $vendor_id, $routeParameters['prodslug']);
      }
      @endphp
      
      @if($metaData)
      <title>{!! $metaData['metaTitle'] !!}</title>
      <meta name="description" content="{!! $metaData['metaDescription'] !!}">
      <meta name="keywords" content="{!! $metaData['metaKeywords'] !!}">
      @else
      <title>{{ config('app.name') }} - @yield('title')</title>
      @endif

      <!-- App favicon -->
      <link rel="shortcut icon" href="assets/images/favicon.ico">
       <script src="https://cdn.tailwindcss.com"></script>
       <link href="{{ url('css/cs-animation.css') }}" rel="stylesheet" />
      <link rel="stylesheet" href="{{ url('css/style.css') }}">
      <link rel="stylesheet" href="{{ url('css/owl.theme.default.min.css') }}">
      <link rel="stylesheet" href="{{ url('css/owl.carousel.min.css') }}">
      <link href="{{ url('css/cs-animation.css') }}" rel="stylesheet" />
      <link href="{{ url('css/font-awesome.min.css') }}" rel="stylesheet" />
      <link href="{{ url('css/custom.css') }}" rel="stylesheet">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


      @include('layouts.style')
  </head>

  <body>

    @include('layouts.customer-header')

    @yield('content')

    @include('layouts.customer-footer')

    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/owl.carousel.min.js') }}"></script>
    <script src="{{ url('js/owl.carousel2.thumbs.min.js') }}"></script>
    <script src="{{ url('js/main.js') }}"></script>
    <script src="{{ url('js/custom.js') }}"></script>
    <script src="{{ url('js/masonry.pkgd.min.js') }}"></script>
    <script src="{{ asset('js/jm-animation.js') }}"></script>
     <script src="{{ url('js/sweetalert.min.js') }}"></script>
    <script>
      
    $('#country-dd').on('change', function () {
        var idCountry = this.value;
        $("#state-dd").html('');
        $.ajax({
            url: "{{url('api/fetch-states')}}",
            type: "POST",
            data: {
                country_id: idCountry,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function (result) {
                $('#state-dd').html('<option value="">Select State</option>');
                $.each(result.states, function (key, value) {
                    $("#state-dd").append('<option value="' + value
                        .id + '">' + value.name + '</option>');
                });
                $('#city-dd').html('<option value="">Select City</option>');
            }
        });
    });

    $('.show_confirm').click(function(event) {
        var form =  jQuery('#delete_form');
        jQuery('#delete_form')[0].action = '{{ url("/account/addresses/delete") }}';
        jQuery('#address_id').val(jQuery(this).data('id'));
        event.preventDefault();
        swal({
              title: `Are you sure you want to delete this record?`,
              text: "If you delete this, it will be gone forever.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
        });
    });
    </script> 
             
  </body>

</html>

