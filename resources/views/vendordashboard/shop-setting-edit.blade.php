@extends('layouts.vendor-layout')
@section('pageTitle', 'Edit Settings')
@section('content')

<!-- Start Content-->
<div class="container-fluid">

  <!-- start page title -->
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <h4 class="page-title">{{$data['title']}}</h4>
      </div>
    </div>
  </div>
  
  <!-- end page title -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h4 class="header-title">Update data</h4>
          <p class="sub-header">
            All field required.
          </p>

          <form method="POST" action="{{ route('shopSetting.update', ['id' => $data['id']]) }}" enctype="multipart/form-data" class="form-horizontal" id="shop-form">
            {{ csrf_field() }}
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="shop_name">Shop Name</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="shop_name" name="shop_name" value="{{ $data['vendorInfo']->shop_name }}" required>
              </div>
              <div class="col-lg-12">
                @error('shop_name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            {{--<div class="form-group row row">
              <label class="col-lg-2 col-form-label">Shop Logo</label>
              <div class="col-lg-10">
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="shop_logo" name="shop_logo" style="width: 60%;" accept="image/png, image/jpg, image/jpeg">
                    <label class="custom-file-label" for="shop_logo" style="width:57%">Choose file</label><img src="{{ asset('uploads/vendors/' . $data['vendorInfo']->shop_logo ) }}" width="28px">
                  </div>
                </div>
              </div>
            </div> --}}

            <div class="form-group row row">
              <label class="col-lg-2 col-form-label">Shop Banner</label>
              <div class="col-lg-10">
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="shop_banner" name="shop_banner" style="width: 60%;">
                    <label class="custom-file-label" for="shop_banner" style="width:57%" accept="image/png, image/jpg, image/jpeg">Choose file</label><img src="{{ asset('uploads/vendors/' . $data['vendorInfo']->shop_banner ) }}" width="28px">
                  </div>
                </div>
              </div>
            </div>

            {{-- <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="shop_email">Shop Email</label>
              <div class="col-lg-10">
                <input type="email" id="shop_email" name="shop_email" class="form-control" placeholder="Email" value="{{ $data['vendorInfo']->shop_email }}" required>
              </div>
              <div class="col-lg-12">
                @error('shop_email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="shop_mobile">Shop Mobile</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="shop_mobile" name="shop_mobile" value="{{ $data['vendorInfo']->shop_mobile }}" required>
              </div>
              <div class="col-lg-12">
                @error('shop_mobile')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div> --}}

            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="shop_url_slug">Shop URL Slug</label>
              <div class="col-lg-10">
                <input type="text" disabled class="form-control" id="shop_url_slug" name="shop_url_slug" value="{{ $data['vendorInfo']->shop_url_slug }}" required>
              </div>
              <div class="col-lg-12">
                @error('shop_url_slug')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            @php $checkedValues = json_decode($data['vendorInfo']->pay_by); @endphp
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">{{ __('Payment Method') }}</label>
                <div class="col-lg-10" style="margin-top: 10px;">
                  <input type="checkbox" class="" name="pay_by[]" value="cc" @if(!empty($checkedValues)) {{ in_array('cc', $checkedValues) ? 'checked' : '' }} @endif> Credit Card &nbsp;&nbsp; 
                  <input type="checkbox" class="" name="pay_by[]" value="po" @if(!empty($checkedValues)) {{ in_array('po', $checkedValues) ? 'checked' : '' }} @endif> Purchase Order
                  <p id="errorMessage" style="color: red; display: none;">{{ __('Please select at least one Payment method.') }}</p>
                </div>
                @error('pay_by')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
            </div>

            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="country">Country</label>
              <div class="col-lg-10">

                <select name="country" id="country-dd" class="form-control" required>
                  <option value="">Select Country</option>
                  @foreach ($data['countries'] as $value)
                  <option value="{{$value->id}}" @if($data["vendorInfo"]->country_id == $value->id) selected="selected" @endif
                    >
                    {{$value->name}}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-12">
                @error('country')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="state">State</label>
              <div class="col-lg-10">
                <select name="state" id="state-dd" class="form-control" required>
                  <option value="">Select State</option>
                  @foreach ($data['state'] as $value)
                  <option value="{{$value->id}}" @if($data['vendorInfo']->state_id == $value->id) selected="selected" @endif>
                    {{$value->name}}
                  </option>
                  @endforeach
                </select>

              </div>
              <div class="col-lg-12">
                @error('state')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="city">City</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="city" name="city" value="{{ $data['vendorInfo']->city }}" required>
              </div>
              <div class="col-lg-12">
                @error('city')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="address">Address</label>
              <div class="col-lg-10">
                <textarea class="form-control" rows="5" id="address" name="address" required>{{ $data['vendorInfo']->address }}</textarea>
              </div>
              <div class="col-lg-12">
                @error('address')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="postcode">Postcode</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="postcode" name="postcode" value="{{ $data['vendorInfo']->postcode }}" required>
              </div>
              <div class="col-lg-12">
                @error('postcode')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="form-group mb-0 justify-content-end row">
              <div class="col-10">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>

          </form>

        </div> <!-- end card-box -->
      </div> <!-- end card-->
    </div><!-- end col -->
  </div>
  <!-- end row -->


</div> <!-- container-fluid -->
<script type="text/javascript">
  $("input[name=shop_mobile]").keypress(function(event) {
    return /\d/.test(String.fromCharCode(event.keyCode));
  });


  $('.custom-file-input').on('change', function() {
    console.log("I am inside upload event");
    files = $(this)[0].files;
    name = '';
    for (var i = 0; i < files.length; i++) {
      name += '\"' + files[i].name + '\"' + (i != files.length - 1 ? ", " : "");
    }
    $(this).parent().find(".custom-file-label").html(name);
  })

  $(document).ready(function() {
    $('#country-dd').on('change', function() {
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
        success: function(result) {
          $('#state-dd').html('<option value="">Select State</option>');
          $.each(result.states, function(key, value) {
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