@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('Edit Product'))

@section('page_header')
<div class="bread-header">
    <h1 class="page-title">
        <i class="voyager-list-add"></i>
        {{ __('Edit Product') }}
    </h1>
    @include('voyager::multilingual.language-selector')
</div>
@stop

@section('content')


@section('content')

    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form" id="add-products"
                            class="form-edit-add"
                            action="{{ url('/admin/products/update') }}"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        
                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">
                            <!-- Adding / Editing -->
                            <div class="form-group  col-md-12 ">
                                <label class="control-label" for="name">{{ __('Name') }}</label>
                                <input required type="text" class="form-control product-name" name="prodname" placeholder="Name" value="{{$prodrecord->name}}">
                                @if($errors->has('name'))
                                <div class="help-block">{{ $errors->first('prodname') }} </div>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label" for="name">{{ __('Slug') }}</label>
                                <input required type="text" class="form-control product-slug" name="prodslug" placeholder="Slug" value="{{$prodrecord->slug}}">
                                @if($errors->has('prodslug'))
                                <div class="help-block">{{ $errors->first('prodslug')  }} </div>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="sku">{{ __('SKU') }}</label>
                                <input type="text" class="form-control" name="prodsku" id="sku" placeholder="SKU" maxlength="255" value="{{$prodrecord->sku}}" required readonly="readonly" />
                                @if($errors->has('prodsku'))
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('prodsku') }}</strong>
                                  </span>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="sort_description">{{ __('Short Description') }}</label>
                                <textarea class="form-control ckeditor" id="sort_description" name="prodshortdesc" placeholder="Enter Short Description" rows="3">{{$prodrecord->short_description}}</textarea>
                                @if($errors->has('prodshortdesc'))
                                <div class="help-block">{{ $errors->first('prodshortdesc') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="long_description">{{ __('Long Description') }}</label>
                                <textarea class="form-control ckeditor" rows="5" id="long_description" name="prodlongdesc" placeholder="Enter Brief Description" >{{$prodrecord->long_description}}</textarea>
                                @if($errors->has('prodrecord'))
                                <div class="help-block">{{ $errors->first('prodrecord') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="price">{{ __('Price($)') }}</label>
                                <input type="number" class="form-control" name="prodprice" id="price" placeholder="Enter Price" value="{{$prodrecord->price}}" required />
                                
                                @if($errors->has('price'))
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('price') }}</strong>
                                  </span>
                                @endif
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="control-label">{{ __('Status') }}</label>
                                <select required name="prodstatus" class="form-control shorting-table">
                                    <option value="1" {{ $prodrecord->status== 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $prodrecord->status== 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @if($errors->has('status'))
                                <div class="invalid-feedback" style="display:block;">{{$errors->first('status') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-12 ">
                                <label class="control-label">{{ __('Assign Vendor') }}</label>
                                <select name="vendor_id" id="vendor_id" class="form-control" required>
                                    <option value=''>Select</option>
                                    @foreach($vendors as $vendor)
                                        @php $shopslug=Helper::getShopslug($vendor->id); @endphp
                                        @if($shopslug != '')
                                        <option value='{{$vendor->id}}' {{ $vendor->id == $prodrecord->vendor_id ? 'selected' : '' }}>{{$vendor->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="help-block" id="vendor-err" style="display: none;">{{ __('Vendor is required.') }}</div>
                            </div>


                            <div class="form-group col-md-12 ">
                                <label class="control-label">{{ __('Assign Category') }}</label>
                                <select class="form-control select2 select2-hidden-accessible product-cat-select" name="prodcategory[]" id="prodcategory" multiple data-placeholder="Select a Category" style="width: 100%;" tabindex="-1" aria-hidden="true" required="required" >
                                @php 
                                    $pcategory = App\Helpers\Helpers::getCategoriesForProduct($prodrecord->vendor_id);

                                @endphp
                                @if(count($pcategory)>0)
                                @foreach($pcategory as $pcateglist)
                                    @if($pcateglist->has_parent == 0)
                                      <optgroup label="{{$pcateglist->name}}">
                                        @php $subCat = App\Helpers\Helpers::getSubCategories($pcateglist->id); @endphp
                                        @if(count($subCat)>0)
                                          @foreach($subCat as $subcatlist)
                                            @php $checkCategorySelected = App\Helpers\Helpers::checkCategorySelected($prodrecord->id, $pcateglist->id, $subcatlist->id); @endphp
                                            <option value="{{ $pcateglist->id."-".$subcatlist->id }}" @if($checkCategorySelected == 1) selected="selected" @endif>{{$subcatlist->name}}</option>
                                          @endforeach
                                        @endif
                                      </optgroup>
                                    @endif
                                  @endforeach
                                  @endif

                                </select>
                                <div class="help-block" id="category-err" style="display: none;">{{ __('Category is required.') }}</div>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label">{{ __('Apply Attributes') }}</label>
                                <select class="form-control" name="prodhasvariate" id="prodhasvariate">
                                  <option value="0" {{ $prodrecord->has_variation== 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                                  <option value="1" {{ $prodrecord->has_variation== 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                </select>
                            </div>

                            <div id="prodothrsection" class="form-group col-md-12" @if($prodrecord->has_variation == '1') style="display:block" @else style="display:none" @endif>
                              <div class="">
                                <h4 class="">{{ __('Attributes') }}</h4>
                                <hr />
                                <p>{{ __('Please "Check / Uncheck" checkbox for the attribute options you required in product. Put price in the field if your price vary with options that price will added with base price OR otherwise primary price is applicable.') }}</p>

                                @php
                                if(count($prodattributes)>0){
                                foreach($prodattributes as $attributevalue){
                                $chekoptarray[] = $attributevalue->option_id;
                                $chekparentarray[] = $attributevalue->attribute_id;
                                $optpriceval[$attributevalue->option_id] = $attributevalue->variable_price;
                                }
                                }

                                $pattrbute = App\Helpers\Helpers::getAttributes(); @endphp
                                @if(count($pattrbute)>0)
                                @foreach($pattrbute as $pattriblist)
                                @php $pattrbuteoption = App\Helpers\Helpers::getAttributeOptions($pattriblist->id); @endphp

                                <h5 class="" style="color: #00acc1;"><input type="checkbox" id="prodparentattr{{$pattriblist->id}}" name="prodparentattr[]" value="{{$pattriblist->id}}" onclick="ShowAttributeOpts({{$pattriblist->id}});" @if(isset($chekparentarray) && in_array($pattriblist->id,$chekparentarray)) checked="" @endif> {{$pattriblist->name}}</h5>
                                <div id="showattroption{{$pattriblist->id}}" @if(isset($chekparentarray) && in_array($pattriblist->id,$chekparentarray)) style="display:block" @else style="display:none" @endif class="ml-30">
                                  @if(count($pattrbuteoption)>0)
                                  @foreach($pattrbuteoption as $pattroptslist)
                                  <div class="form-group col-md-12">
                                    <div class="custom-control custom-checkbox col-md-2">
                                      <input type="checkbox" class="custom-control-input" id="prodattroptid{{$pattroptslist->id}}" name="prodattroptid[]" value="{{$pattroptslist->id}}" @if(isset($chekoptarray) && in_array($pattroptslist->id,$chekoptarray)) checked="" @endif >
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
                                        <input type="number" class="form-control" name="prodoptvaryprice{{$pattroptslist->id}}" id="prodoptvaryprice{{$pattroptslist->id}}" placeholder="Enter Price" @if(isset($chekoptarray) && in_array($pattroptslist->id,$chekoptarray)) value="{{$optpriceval[$pattroptslist->id]}}" @else value="0" @endif >
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



                            <div class="form-group col-md-12">
                                <h4 class="">{{ __('Media') }}</h4>
                                <hr />
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label" for="prodprice">{{ __('Featured Image') }}<br /><small>( Only .jpg, .jpeg, .png format allowed. )</small></label>
                                        <div class="col-lg-10">
                                        <input type="file" class="form-control" name="prodfeatureimg" id="prodfeatureimg" accept="image/png, image/jpg, image/jpeg" value="{{$prodfeatured->url}}">
                                        <div style="padding:6px">
                                          <img id="fimgpreview" src="{{ asset('storage/'.$prodfeatured->url) }}" class="img-responsive" width="100" height="100">
                                        </div>
                                        @if($errors->has('prodfeatureimg'))
                                          <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('prodfeatureimg') }}</strong>
                                          </span>
                                        @endif
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
                                           <div style="padding:6px; display:inline-flex">
                                              @foreach($prodmedia as $prodimglst)
                                              <span id="pgalimg{{$prodimglst->id}}" class="pgalimg">
                                                <img src="{{ asset('storage/'.$prodimglst->url) }}" class="img-responsive proimg" width="100" height="100">
                                                <a href="javascript:void(0);" class="prodimgatag" onclick="RemoveGalleryImg({{$prodimglst->id}});">Remove</a>
                                              </span>
                                              @endforeach
                                            </div>
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
                                <input type="text" class="form-control" name="prodmetatitle" value="{{$prodrecord->meta_titles}}" placeholder="Enter Meta Title" maxlength="255" />
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Description') }}</label>
                                <textarea class="form-control" rows="5" name="prodmetadescript" placeholder="Enter Meta Description">{{$prodrecord->meta_description}}</textarea>
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Keywords') }}</label>
                                <input type="text" class="form-control" name="prodmetakeyword" value="{{$prodrecord->meta_keywords}}" placeholder="Enter Meta Keywords" maxlength="255" />
                            </div>


                        </div><!-- panel-body -->

                        <div class="panel-footer col-md-12">
                            <div class="form-group  col-md-12 ">
                            @section('submit-buttons')
                            <input type="hidden" name="prodid" value="{{$prodrecord->id}}">
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

<!-- Confirm Modal -->
<div class="modal fade" id="cofirmgallModal" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <div class="sucess-pop" style="text-align: center;">
          <img src="{{ asset('img/confirmask.png') }}" alt="">
          <h3>{{ __('Are you sure to remove the Image?') }}</h3>
          <p>{{ __('You won\'t be able to revert this!') }}</p>
          <div class="model-btn-div">
            <input type="hidden" id="gallryimgid" value="">
            <button type="button" class="btn btn-danger ajaxButton">{{ __('Yes, delete it !') }}</button>
            <button type="button" data-dismiss="modal" class="btn btn-secondary">{{ __('Cancel') }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Confirm Modal -->


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