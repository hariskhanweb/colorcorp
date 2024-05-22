@extends('layouts.app')
@section('content')
<style type="text/css">
    .hide,
    nav.navbar,
    .home-btn {
        display: none !important;
    }

    body {
        padding-bottom: 0px;
    }

    body main.py-4 {
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
</style>
<div class="account-pages auth-pages">
  <div class="container">
    <div class="row justify-content-center">
      <div class="auth-card p-4">
        <div class="col-lg-12 mb-3">
          <h3>{{$data['title']}}</h3>
          <div class="@if (session()->has('success'))  alert alert-success @endif @if (session()->has('error') || session()->has('reset'))  alert alert-danger @endif">{{Session::get('success')}}{{Session::get('error')}}{{Session::get('reset')}}

            @if (session()->has('reset'))  
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#resetModal">
                Reset link
              </button>
            @endif

          </div>
        </div>
        <form method="POST" action="{{ route('shopSetting.create') }}" enctype="multipart/form-data" class="form-horizontal" id="shop-form">
          {{ csrf_field() }}
          <div class="auth-card-body">

            <div class="col-lg-6">
              <div class="mb-2 form-group">
                  <label for="shopname">{{ __('Shop Name') }}</label>
                  <input type="text" class="form-control" id="shop_name" name="shop_name" placeholder="Shop Name" value="" required>
                  @error('shop_name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
              </div>
            </div>

            {{-- <div class="col-lg-6">
              <div class="mb-2 form-group">
                <label for="shoplogo">{{ __('Shop Logo') }}</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="shop_logo" name="shop_logo" accept="image/png, image/jpg, image/jpeg" required="required" />
                    <label class="custom-file-label" for="shop_logo">Choose file</label>
                      <div class="col-lg-12">
                        @error('shop_logo')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                  </div>
                </div>
              </div>
            </div> --}}

            <div class="col-lg-6">
              <div class="mb-2 form-group">
                <label for="shopbanner">{{ __('Shop Banner') }}</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="shop_banner" name="shop_banner" accept="image/png, image/jpg, image/jpeg" required="required" />
                    <label class="custom-file-label" for="shop_banner">Choose file</label>
                    <div class="col-lg-12">
                    @error('shop_banner')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {{-- <div class="col-lg-6">
              <div class="mb-2 form-group">
                  <label for="shop_email">{{ __('Shop Email') }}</label>
                  <input type="email" id="shop_email" name="shop_email" class="form-control" placeholder="Email" required>
                  @error('shop_email')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
              </div>
            </div>

            <div class="col-lg-6">
              <div class="mb-2 form-group">
                  <label for="shop_mobile">{{ __('Shop Mobile') }}</label>
                  <input type="text" class="form-control" id="shop_mobile" name="shop_mobile" placeholder="Mobile number" value="" required>
                  @error('shop_mobile')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
              </div>
            </div> --}}

            <div class="col-lg-6">
              <div class="mb-2 form-group">
                <label for="shop_url_slug">{{ __('Shop URL Slug') }}</label>
                <input type="text" class="form-control" id="shop_url_slug" name="shop_url_slug" placeholder="Shop URL Slug" value="" required>
                @error('shop_url_slug')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="col-lg-12">
              <div class="mb-2 form-group">
                <label for="shop_theme_color">{{ __('Theme Colors') }}</label>
                <div class="shop_theme_colors">
                  <div class="form-group mb-2">
                    <label for="shop_primary_color">Color 1</label>
                    <input type="color" class="form-control" id="shop_primary_color" name="shop_primary_color" value="#609966" required>
                  </div>
                  <div class="form-group mb-2">
                    <label for="shop_secondary_color">Color 2</label>
                    <input type="color" class="form-control" id="shop_secondary_color" name="shop_secondary_color" value="#40513B" required>
                  </div>
                  <div class="form-group mb-2">
                    <label for="shop_body_color">Color 3</label>
                    <input type="color" class="form-control" id="shop_body_color" name="shop_body_color" value="#6c757d" required>
                  </div>
                  <div class="form-group mb-2">
                    <label for="shop_heading_color">Color 4</label>
                    <input type="color" class="form-control" id="shop_heading_color" name="shop_heading_color" value="#323a46" required>
                  </div>
                  <div class="form-group mb-2">
                    <label for="shop_third_color">Color 5</label>
                    <input type="color" class="form-control" id="shop_third_color" name="shop_third_color" value="#9DC08B" required>
                  </div>
                  <div class="form-group mb-2">
                    <label for="shop_forth_color">Color 6</label>
                    <input type="color" class="form-control" id="shop_forth_color" name="shop_forth_color" value="#EDF1D6" required>
                  </div>
                  <div class="form-group mb-2">
                    <label for="shop_fifth_color">Color 7</label>
                    <input type="color" class="form-control" id="shop_fifth_color" name="shop_fifth_color" value="#698269" required>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="mb-2 form-group">
                <label for="country">{{ __('Country') }}</label>
                <select  name="country" id="country-dd" class="form-control" required>
                  <option value="">Select Country</option>
                  @foreach ($data['countries'] as $data)
                  <option value="{{$data->id}}">
                    {{$data->name}}
                  </option>
                  @endforeach
                </select>
                @error('country')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
              </div>
            </div>

            <div class="col-lg-6">
              <div class="mb-2 form-group">
                <label for="state">{{ __('State') }}</label>
                <select id="state-dd" name="state" class="form-control" required></select>
                @error('state')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="col-lg-6">
              <div class="mb-2 form-group">
                <label for="city">{{ __('City') }}</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="City" value="" required>
                @error('city')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="col-lg-6">
              <div class="mb-2 form-group">
                <label for="postcode">{{ __('Postcode') }}</label>
                <input type="text"  class="form-control" id="postcode" name="postcode" placeholder="Postcode" value="" required>
                @error('postcode')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="col-lg-12">
              <div class="mb-2 form-group">
                <label for="address">{{ __('Address') }}</label>
                <input type="text"  class="form-control" id="address" name="address" placeholder="Address" value="" required>
                @error('address')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>


            <div class="col-lg-6">
              <div class="mb-2 form-group custom-checkbox">
                <label for="pay_by">{{ __('Payment Method') }}</label>
                <div class="mb-2 form-group">
                  <input type="checkbox" class="" name="pay_by[]" value="cc" checked> Credit Card &nbsp;&nbsp;
                  <input type="checkbox" class="" name="pay_by[]" value="po"> Purchase Order
                </div>
                @error('pay_by')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <p id="errorMessage" style="color: red; display: none;">{{ __('Please select at least one Payment method.') }}</p>
            </div>

            <div class="col-lg-12">
              <div class="form-group mb-0 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{url('resetlink')}}">  
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reset Link</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p>Please enter you email id.</p>
        <p><input type="text" name="email"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
     </form>
    </div>
  </div>
</div>
<script type="text/javascript">

$("input[name=shop_mobile]").keypress(function(event) {
  return /\d/.test(String.fromCharCode(event.keyCode));
});


$('.custom-file-input').on('change', function(){
    // console.log("I am inside upload event");
    files = $(this)[0].files; 
    name = ''; 
    for(var i = 0; i < files.length; i++)
    {
        name += '\"' + files[i].name + '\"' + (i != files.length-1 ? ", " : ""); 
    } 
    $(this).parent().find(".custom-file-label").html(name);
})

$(document).ready(function () {
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
});




const form = document.getElementById('shop-form');
form.addEventListener('submit', function(event) {
  const checkboxes = document.querySelectorAll('input[name="pay_by[]"]');
  console.log('checkboxes = '+checkboxes);
  let isAnyChecked = false;
  for (const checkbox of checkboxes) {
    if (checkbox.checked) {
      isAnyChecked = true;
      break;
    }
  }
  if (!isAnyChecked) {
    const errorMessage = document.getElementById('errorMessage');
    errorMessage.style.display = 'block';
    event.preventDefault();
  } else {
    const errorMessage = document.getElementById('errorMessage');
    errorMessage.style.display = 'none';
  }
});



</script>
@endsection