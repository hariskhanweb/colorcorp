@extends('layouts.vendor-layout')
@section('pageTitle', 'Add New Product')
@section('content')
@php $vendor= App\Helpers\Helpers::getShopslug(Auth::user()->id); @endphp
<!-- Start Content-->
<div class="container-fluid">

  <!-- start page title -->
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('vendor.product', ['vendor_name' => $vendor]) }}">{{ __('Product Management') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Add Product') }}</li>
          </ol>
        </div>
        <h4 class="page-title">{{ __('Add Product') }}</h4>
      </div>
    </div>
  </div>
  <!-- end page title -->
  <div class="row">
    <div class="col-12">
      <form id="addproductfrm" class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('vendor.product.save', ['vendor_name' => $vendor]) }}">
        {{ csrf_field() }}
        <div class="card">
          <div class="card-body">
            <h4 class="page-title">{{ __('General') }}</h4>
            <hr />
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="simpleinput">{{ __('Name') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" name="prodname" id="prodname" placeholder="Enter Name" maxlength="255" value="{{ old('prodname') }}" required/>
                @if($errors->has('prodname'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('prodname') }}</strong>
                  </span>
                @endif 
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="simpleinput">{{ __('SKU') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" name="prodsku" id="prodsku" placeholder="Enter SKU" maxlength="255" value="{{ old('prodsku') }}" required />
                @if($errors->has('prodsku'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('prodsku') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="example-textarea">{{ __('Short Description') }}</label>
              <div class="col-lg-10">
                <textarea class="form-control editor" id="prodshortdesc" name="prodshortdesc" placeholder="Enter Short Description">{{ old('prodshortdesc') }}</textarea>
                @if($errors->has('prodshortdesc'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('prodshortdesc') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="example-textarea">{{ __('Long Description') }}</label>
              <div class="col-lg-10">
                <textarea class="form-control editor" id="prodlongdesc" name="prodlongdesc" placeholder="Enter Brief Description" >{{ old('prodlongdesc') }}</textarea>
                @if($errors->has('prodlongdesc'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('prodlongdesc') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="prodprice">{{ __('Price') }}</label>
              <div class="col-lg-10">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">{{ __('$') }}</span>
                  </div>
                  <input type="number" class="form-control" name="prodprice" id="prodprice" placeholder="Enter Price" value="{{ old('prodprice') }}" required />
                </div>
                @if($errors->has('prodprice'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('prodprice') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label">{{ __('Category') }}</label>
              <div class="col-lg-10">
                @php 
                  $pcategory = App\Helpers\Helpers::getCategories(Auth::user()->id);
                  $accessoriesId = App\Helpers\Helpers::getAccessoriesCatId();
                @endphp
                <select class="form-control select2 select2-hidden-accessible product-cat-select" name="prodcategory[]" id="prodcategory" multiple data-placeholder="Select a Category" style="width: 100%;" tabindex="-1" aria-hidden="true" required="required" >
                  @if(count($pcategory)>0)
                  @foreach($pcategory as $pcateglist)
                    @if($pcateglist->id != $accessoriesId)
                      <optgroup label="{{$pcateglist->name}}">
                        @php $subCat = App\Helpers\Helpers::getSubCategories($pcateglist->id); @endphp
                        @if(count($subCat)>0)
                          @foreach($subCat as $subcatlist)
                            <option value="{{ $pcateglist->id."-".$subcatlist->id}}">{{$subcatlist->name}}</option>
                          @endforeach
                        @endif
                      </optgroup>
                    @else
                      <option value="{{$pcateglist->id}}">{{$pcateglist->name}}</option>
                    @endif
                  @endforeach
                  @endif
                </select>
                @if($errors->has('prodcategory'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('prodcategory') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label">{{ __('Apply Attributes') }}</label>
              <div class="col-lg-10">
                <select class="form-control" name="prodhasvariate" id="prodhasvariate">
                  <option value="0" selected="selected">No</option>
                  <option value="1">Yes</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label">{{ __('Status') }}</label>
              <div class="col-lg-10">
                <select class="form-control" name="prodstatus" id="prodstatus">
                  <option value="1" selected="selected">Enable</option>
                  <option value="0">Disable</option>
                </select>
              </div>
            </div>
          </div> <!-- end card-box -->
        </div> <!-- end card -->

        <div id="prodothrsection" class="card" style="display:none">
          <div class="card-body">
            <h4 class="page-title">{{ __('Attributes') }}</h4>
            <hr />
            <p>{{ __('Please "Check / Uncheck" checkbox for the attribute options you required in product. Put price in the field if your price vary with options that price will added with base price OR otherwise primary price is applicable.') }}</p>

            @php $pattrbute = App\Helpers\Helpers::getAttributes(); @endphp
            @if(count($pattrbute)>0)
            @foreach($pattrbute as $pattriblist)
            <h5 class="page-title" style="color: #00acc1;"><input type="checkbox" id="prodparentattr{{$pattriblist->id}}" name="prodparentattr[]" value="{{$pattriblist->id}}" onclick="ShowAttributeOpts({{$pattriblist->id}});"> {{$pattriblist->name}}</h5>
            <div id="showattroption{{$pattriblist->id}}" style="display:none" class="ml-30">
              @php $pattrbuteoption = App\Helpers\Helpers::getAttributeOptions($pattriblist->id); @endphp
              @if(count($pattrbuteoption)>0)
              @foreach($pattrbuteoption as $pattroptslist)
              <div class="form-group row">
                <div class="custom-control custom-checkbox col-lg-2">
                  <input type="checkbox" class="custom-control-input" id="prodattroptid{{$pattroptslist->id}}" name="prodattroptid[]" value="{{$pattroptslist->id}}">
                  <label class="custom-control-label" for="prodattroptid{{$pattroptslist->id}}">{{$pattroptslist->options}}</label>
                  <input type="hidden" name="prodattrparentid{{$pattroptslist->id}}" value="{{$pattriblist->id}}" />
                </div>
                <div class="col-lg-10">
                  @if($pattriblist->type == "select")
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">{{ __('$') }}</span>
                    </div>
                    <input type="number" class="form-control" name="prodoptvaryprice{{$pattroptslist->id}}" id="prodoptvaryprice{{$pattroptslist->id}}" placeholder="Enter Price" value="0.00">
                  </div>
                  @else
                  <input type="hidden" class="form-control" name="prodoptvaryprice{{$pattroptslist->id}}" id="prodoptvaryprice{{$pattroptslist->id}}" value="0">
                  @endif
                </div>
              </div>
              @endforeach
              @endif
            </div>
            @endforeach
            @endif
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <h4 class="page-title">{{ __('Media') }}</h4>
            <hr />
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="prodprice">{{ __('Featured Image') }}<br /><small>( Only .jpg, .jpeg, .png format allowed. )</small></label>
              <div class="col-lg-10">
                <input type="file" class="form-control" name="prodfeatureimg" id="prodfeatureimg" accept="image/png, image/jpg, image/jpeg" required >
                <div style="padding:6px">
                  <img id="fimgpreview" src="{{ asset('img/preview.jpg') }}" alt="preview" class="img-responsive" width="100" height="100">
                </div>
                @if($errors->has('prodfeatureimg'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('prodfeatureimg') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="prodprice">{{ __('Gallery Images') }}<br /><small>( Only .jpg, .jpeg, .png format allowed. )</small></label>
              <div class="col-lg-10">
                <input type="file" class="form-control" name="prodgalleryimg[]" id="prodgalleryimg" accept="image/png, image/jpg, image/jpeg" multiple>
                <div id="gallery" style="padding:6px"></div>
                @if($errors->has('prodgalleryimg'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('prodgalleryimg') }}</strong>
                  </span>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <h4 class="page-title">{{ __('SEO Details') }}</h4>
            <hr />
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="">{{ __('Meta Titles') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="prodmetatitle" name="prodmetatitle" placeholder="Enter Meta Titles" maxlength="255" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="">{{ __('Meta Description') }}</label>
              <div class="col-lg-10">
                <textarea class="form-control" rows="5" id="prodmetadescript" name="prodmetadescript" placeholder="Enter Meta Description"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="">{{ __('Meta Keywords') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="prodmetakeyword" name="prodmetakeyword" placeholder="Enter Meta Keywords" maxlength="255" />
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-lg-12">
            <div id="prodfrmalert"></div>
            <input type="hidden" name="redpath" value="{{ route('vendor.product', ['vendor_name' => $vendor]) }}">
            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
            <a class="btn btn-secondary" href="{{ route('vendor.product', ['vendor_name' => $vendor]) }}">{{ __('Cancel') }}</a>
          </div>
        </div>
      </form>
    </div><!-- end col-->
  </div>
  <!-- end row-->
</div> <!-- container-fluid -->
<script>
  // Check Upload File Size
  function checkSizeFileUpload(fldid) {
    var fp2 = $("#" + fldid);
    var lg2 = fp2[0].files.length;
    var items = fp2[0].files;
    var fileSize = 0;
    if (lg2 > 0) {
      for (var i = 0; i < lg2; i++) {
        fileSize = fileSize + items[i].size;
      }
      if (fileSize > 1048576) {
        $("#" + fldid).val('');
        $("#" + fldid).after('<span id="alt' + fldid + '" style="color:red; size:11px;">Attached file size more than 1 MB.</span>');
        setTimeout(function() {
          $('#alt' + fldid).fadeOut('slow');
        }, 2000);
      }
    }
  }
  // Featured Image
  prodfeatureimg.onchange = evt => {
    checkSizeFileUpload('prodfeatureimg');
    const [file] = prodfeatureimg.files
    if (file) {
      fimgpreview.src = URL.createObjectURL(file)
    }
  }
  // gallery Images
  const preview = (file) => {
    const fr = new FileReader();
    fr.onload = () => {
      const img = document.createElement("img");
      img.src = fr.result; // String Base64 
      img.alt = file.name;
      img.class = "img-responsive";
      img.width = "100";
      img.height = "100";
      img.style = "padding:5px;";
      document.querySelector('#gallery').append(img);
    };
    fr.readAsDataURL(file);
  };

  document.querySelector("#prodgalleryimg").addEventListener("change", (ev) => {
    $('#gallery').html('');
    checkSizeFileUpload('prodgalleryimg');
    if (!ev.target.files) return; // Do nothing.
    [...ev.target.files].forEach(preview);
  });
</script>

@endsection