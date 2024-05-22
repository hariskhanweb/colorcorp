@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('Add Product'))

@section('page_header')
<div class="bread-header">
    <h1 class="page-title">
        <i class="voyager-list-add"></i>
        {{ __('Add Product') }}
    </h1>
</div>
    @include('voyager::multilingual.language-selector')
@stop



@section('content')

    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form" id="add-products"
                            class="form-edit-add"
                            action="{{ url('/admin/products/store') }}"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        
                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">
                            <!-- Adding / Editing -->
                            <div class="form-group  col-md-12 ">
                                <label class="control-label" for="name">{{ __('Name') }}</label>
                                <input required type="text" class="form-control product-name" name="prodname" placeholder="Name" value="{{ old('prodname') }}">
                                @if($errors->has('name'))
                                <div class="help-block">{{ $errors->first('prodname') }} </div>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label" for="name">{{ __('Slug') }}</label>
                                <input required type="text" class="form-control product-slug" name="prodslug" placeholder="Slug" value="@if(count($errors) > 0){{ old('prodslug') }}@endif">
                                @if($errors->has('prodslug'))
                                <div class="help-block">{{ $errors->first('prodslug')  }} </div>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="sku">{{ __('SKU') }}</label>
                                <input type="text" class="form-control" name="prodsku" id="sku" placeholder="SKU" maxlength="255" value="@if(count($errors) > 0){{ old('prodsku') }}@endif" required />
                                @if($errors->has('prodsku'))
                                  <span class="invalid-feedback help-block" role="alert">
                                    <strong>{{ $errors->first('prodsku') }}</strong>
                                  </span>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="sort_description">{{ __('Short Description') }}</label>
                                <textarea class="form-control ckeditor" id="sort_description" name="prodshortdesc" placeholder="Enter Short Description" rows="3">{{ old('prodshortdesc') }}</textarea>
                                @if($errors->has('prodshortdesc'))
                                <div class="help-block">{{ $errors->first('prodshortdesc') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="long_description">{{ __('Long Description') }}</label>
                                <textarea class="form-control ckeditor" rows="5" id="long_description" name="prodlongdesc" placeholder="Enter Brief Description" >{{ old('prodlongdesc') }}</textarea>
                                @if($errors->has('prodlongdesc'))
                                <div class="help-block">{{ $errors->first('prodlongdesc') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="price">{{ __('Price($)') }}</label>
                                <input type="number" class="form-control" name="prodprice" id="price" placeholder="Enter Price" value="{{ old('prodprice') }}" required />
                                @if($errors->has('price'))
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('price') }}</strong>
                                  </span>
                                @endif
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="control-label">{{ __('Status') }}</label>
                                <select required name="prodstatus" class="form-control shorting-table">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                                @if($errors->has('status'))
                                <div class="invalid-feedback" style="display:block;">{{$errors->first('status') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-12 ">
                                <label class="control-label">{{ __('Assign Vendor') }}</label>
                                <select name="vendor_id" id="vendor_id" class="form-control" required>
                                    <option value=''>{{ __('Select') }}</option>
                                    @foreach($vendors as $vendor)
                                        @php $shopslug=Helper::getShopslug($vendor->id); @endphp
                                        @if($shopslug != '')
                                        <option value='{{$vendor->id}}'>{{$vendor->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="help-block" id="vendor-err" style="display: none;">{{ __('Vendor is required.') }}</div>
                            </div>

                            <div class="form-group col-md-12 ">
                                <label class="control-label">{{ __('Assign Category') }}</label>
                                <select class="form-control select2 select2-hidden-accessible product-cat-select" name="prodcategory[]" id="prodcategory" multiple data-placeholder="Select a Category" style="width: 100%;" tabindex="-1" aria-hidden="true" required="required" >
                                </select>
                                <div class="help-block" id="category-err" style="display: none;">{{ __('Category is required.') }}</div>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label">{{ __('Apply Attributes') }}</label>
                                <select class="form-control" name="prodhasvariate" id="prodhasvariate">
                                  <option value="0" selected="selected">{{__('No')}}</option>
                                  <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>

                            <div id="prodothrsection" class="form-group col-md-12" style="display:none">
                                <h4 class="">{{ __('Attributes') }}</h4>
                                <hr />
                                <p>{{ __('Please "Check / Uncheck" checkbox for the attribute options you required in product. Put price in the field if your price vary with options that price will added with base price OR otherwise primary price is applicable.') }}</p>

                                @php $pattrbute = App\Helpers\Helpers::getAttributes(); @endphp
                                @if(count($pattrbute)>0)
                                @foreach($pattrbute as $pattriblist)
                                <h5 class="" style="color: #00acc1;"><input type="checkbox" id="prodparentattr{{$pattriblist->id}}" name="prodparentattr[]" value="{{$pattriblist->id}}" onclick="ShowAttributeOpts({{$pattriblist->id}});"> {{$pattriblist->name}}</h5>
                                <div id="showattroption{{$pattriblist->id}}" style="display:none" class="ml-30">
                                  @php $pattrbuteoption = App\Helpers\Helpers::getAttributeOptions($pattriblist->id); @endphp
                                  @if(count($pattrbuteoption)>0)
                                  @foreach($pattrbuteoption as $pattroptslist)

                                  <div class="form-group col-md-12">
                                    <div class="custom-control custom-checkbox col-md-2">
                                      <input type="checkbox" class="custom-control-input" id="prodattroptid{{$pattroptslist->id}}" name="prodattroptid[]" value="{{$pattroptslist->id}}">
                                      <!-- <label class="custom-control-label" for="prodattroptid{{$pattroptslist->id}}">{{$pattroptslist->options}}</label> -->
                                      @if($pattriblist->type == "text")
                                      <label class="custom-control-label" for="prodattroptid{{$pattroptslist->id}}"> {{ __('Text') }} </label>
                                      @else
                                      <label class="custom-control-label" for="prodattroptid{{$pattroptslist->id}}">{{$pattroptslist->options}}</label>
                                      @endif
                                      <input type="hidden" name="prodattrparentid{{$pattroptslist->id}}" value="{{$pattriblist->id}}" />
                                    </div>
                                    <div class="col-md-10">
                                      @if($pattriblist->type == "select")
                                      <div class="">
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



                            <div class="form-group col-md-12">
                                <h4 class="">{{ __('Media') }}</h4>
                                <hr />
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label" for="prodprice">{{ __('Featured Image') }}<br /><small>{{ __('( Only .jpg, .jpeg, .png format allowed. )') }}</small></label>
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
                                        <label class="col-lg-2 col-form-label" for="prodprice">{{ __('Gallery Images') }}<br /><small>{{ __('( Only .jpg, .jpeg, .png format allowed. )') }}</small></label>
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

                              



                            <div class="form-group  col-md-12" style="margin-top: 15px;">
                                <h4>{{ __('SEO Details') }}</h4>
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Title') }}</label>
                                <input type="text" class="form-control" name="prodmetatitle" value="{{ old('prodmetatitle') }}" placeholder="Enter Meta Title" maxlength="255" />
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Description') }}</label>
                                <textarea class="form-control" rows="5" name="prodmetadescript" placeholder="Enter Meta Description">{{ old('prodmetadescript') }}</textarea>
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Keywords') }}</label>
                                <input type="text" class="form-control" name="prodmetakeyword" placeholder="Enter Meta Keywords" maxlength="255" value="{{ old('prodmetakeyword') }}"/>
                            </div>


                        </div><!-- panel-body -->

                        <div class="panel-footer col-md-12">
                            <div class="form-group  col-md-12 ">
                            @section('submit-buttons')
                                <button type="submit" id="products-submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                            </div>
                        </div>
                    </form>

                    <div style="display:none">
                        <input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
                        <input type="hidden" id="upload_type_slug" value="products">
                    </div>
                </div>
            </div>
        </div>
    </div> 


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

@stop