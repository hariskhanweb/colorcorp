<div class="row">    
<div class="bread-header"> 
    <h1 class="page-title">{{$data['title']}}</h1>
</div>
    @if(Session::has('message'))
        <div class="alert alert-success text-center">
            {{Session::get('message')}}
        </div>
    @endif 
    <form method="POST" action="{{ route('shopSetting.update', ['id' => $data['id']]) }}" enctype="multipart/form-data" class="form-horizontal">
        {{ csrf_field() }}
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="shop_name">Shop Name</label>
            <div class="col-lg-10">
                <input type="text" class="form-control" id="shop_name" name="shop_name" value="{{ $data['vendorInfo']->shop_name }}">
            </div>
            <div class="col-lg-12">
                @error('shop_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-0">
            <label class="col-lg-2 col-form-label">Shop Logo</label>
            <div class="col-lg-10">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="shop_logo" name="shop_logo" accept="image/png, image/jpg, image/jpeg" >
                        <label class="custom-file-label" for="shop_logo">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <img src="{{ asset('uploads/vendors/' . $data['vendorInfo']->shop_logo ) }}" width="50px"> 
            </div>
        </div>

        <div class="form-group row mb-0">
            <label class="col-lg-2 col-form-label">Shop Banner</label>
            <div class="col-lg-10">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="shop_banner" name="shop_banner" accept="image/png, image/jpg, image/jpeg">
                        <label class="custom-file-label" for="shop_banner">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <img src="{{ asset('uploads/vendors/' . $data['vendorInfo']->shop_banner ) }}" width="50px"> 
            </div>
        </div>

        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="shop_email">Shop Email</label>
            <div class="col-lg-10">
                <input type="email" id="shop_email" name="shop_email" class="form-control" placeholder="Email" value="{{ $data['vendorInfo']->shop_email }}">
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
                <input type="text" class="form-control" id="shop_mobile" name="shop_mobile" value="{{ $data['vendorInfo']->shop_mobile }}">
            </div>
            <div class="col-lg-12">
                @error('shop_mobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="shop_url_slug">Shop URL Slug</label>
            <div class="col-lg-10">
                <input type="number" class="form-control" id="shop_url_slug" name="shop_url_slug" value="{{ $data['vendorInfo']->shop_url_slug }}">
            </div>
            <div class="col-lg-12">
                @error('shop_url_slug')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="address">Address</label>
            <div class="col-lg-10">
                <textarea class="form-control" rows="5" id="address" name="address">{{ $data['vendorInfo']->address }}</textarea>
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
            <label class="col-lg-2 col-form-label" for="city">City</label>
            <div class="col-lg-10">
                <input type="text" class="form-control" id="city" name="city" value="{{ $data['vendorInfo']->city }}">
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
            <label class="col-lg-2 col-form-label" for="state">State</label>
            <div class="col-lg-10">
                <input type="text" class="form-control" id="state" name="state" value="{{ $data['vendorInfo']->state }}">
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
            <label class="col-lg-2 col-form-label" for="country">Country</label>
            <div class="col-lg-10">
                <input type="text" class="form-control" id="country" name="country" value="{{ $data['vendorInfo']->country }}">
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
            <label class="col-lg-2 col-form-label" for="postcode">Postcode</label>
            <div class="col-lg-10">
                <input type="text" class="form-control" id="postcode" name="postcode" value="{{ $data['vendorInfo']->postcode }}">
            </div>
            <div class="col-lg-12">
                @error('postcode')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>