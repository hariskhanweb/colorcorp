@extends('layouts.vendor-layout')
@section('pageTitle', 'View Product')
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
            <li class="breadcrumb-item active">{{ __('View Product') }}</li>
          </ol>
        </div>
        <h4 class="page-title">{{ __('View Product') }}</h4>
      </div>
    </div>
  </div>
  <!-- end page title -->
  <div class="row">
    <div class="col-12">
      <form id="editproductfrm" class="form-horizontal" method="POST" enctype="multipart/form-data" action="">
        {{ csrf_field() }}
        <div class="card">
          <div class="card-body">
            <h4 class="page-title">{{ __('General') }}</h4>
            <hr />
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="simpleinput">{{ __('Name') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" readonly name="prodname" id="prodname" placeholder="Enter Name" value="{{$prodrecord->name}}"  maxlength="255">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="simpleinput">{{ __('SKU') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" name="prodsku" id="prodsku" placeholder="Enter SKU" value="{{$prodrecord->sku}}"  maxlength="255" readonly="readonly">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="example-textarea">{{ __('Short Description') }}</label>
              <div class="col-lg-10">
                <!-- <textarea class="form-control" rows="5" id="prodmetadescript" name="prodmetadescript" readonly placeholder="Enter Meta Description">{!! $prodrecord->short_description !!}</textarea> -->
                {!! $prodrecord->short_description !!}
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="example-textarea">{{ __('Long Description') }}</label>
              <div class="col-lg-10">
                <!-- <textarea class="form-control" rows="5" id="prodmetadescript" name="prodmetadescript" readonly placeholder="Enter Meta Description">{!! $prodrecord->long_description !!}</textarea> -->
                 {!! $prodrecord->long_description !!}
              </div>
            </div>
            <!-- <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="prodprice">{{ __('Price') }}</label>
              <div class="col-lg-10">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">{{ __('$') }}</span>
                  </div>
                  <input type="number" class="form-control" name="prodprice" id="prodprice" placeholder="Enter Price" readonly value="{{$prodrecord->price}}" >
                </div>
              </div>
            </div> -->
           
            <div class="form-group row">
              <label class="col-lg-2 col-form-label">{{ __('Category') }}</label>
              <div class="col-lg-10">
                
                 @php 
                    $pcategory = App\Helpers\Helpers::getCategoriesForProduct($prodrecord->vendor_id);
                    @endphp
                    @if(count($pcategory)>0)
                    @foreach($pcategory as $pcateglist)
                        @if($pcateglist->has_parent == 0)
                            @php $subCat = App\Helpers\Helpers::getSubCategories($pcateglist->id); @endphp
                            @if(count($subCat)>0)
                              @foreach($subCat as $subcatlist)
                                @php 
                                    $checkCategorySelected = App\Helpers\Helpers::checkCategorySelected($prodrecord->id, $pcateglist->id, $subcatlist->id); 
                                @endphp
                                 @if($checkCategorySelected == 1){{$subcatlist->name}}@endif
                             </br>
                              @endforeach
                            @endif
                        @endif
                      @endforeach
                      @endif
                
              </div>
            </div>

            <div class="form-group row">
              <label class="col-lg-2 col-form-label">{{ __('Status') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="prodmetatitle" name="prodmetatitle" placeholder="Enter Meta Titles" readonly value="{{ $prodrecord->status== 1 ? 'Active' : 'Inactive' }}" maxlength="255">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-lg-2 col-form-label">{{ __('Apply Attributes') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="prodmetatitle" name="prodmetatitle" placeholder="Enter Meta Titles" readonly value="{{ $prodrecord->has_variation== 0 ? 'NO' : 'YES' }}" maxlength="255">
              </div>
            </div>
            
          </div> <!-- end card-box -->
        </div> <!-- end card -->

        <div id="prodothrsection" class="card" @if($prodrecord->has_variation == '1') style="display:block" @else style="display:none" @endif>


          
          <div class="card-body">
            <h4 class="page-title">{{ __('Attributes') }}</h4>
            <hr />

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
            @if(isset($chekparentarray) && in_array($pattriblist->id,$chekparentarray))
            <h5 class="page-title" style="color: #00acc1;"><input type="checkbox" id="prodparentattr{{$pattriblist->id}}" name="prodparentattr[]" value="{{$pattriblist->id}}" onclick="ShowAttributeOpts({{$pattriblist->id}});" @if(isset($chekparentarray) && in_array($pattriblist->id,$chekparentarray)) checked="" @endif> {{$pattriblist->name}}</h5>@endif
            <div id="showattroption{{$pattriblist->id}}" @if(isset($chekparentarray) && in_array($pattriblist->id,$chekparentarray)) style="display:block" @else style="display:none" @endif class="ml-30">
              @if(count($pattrbuteoption)>0)
              @foreach($pattrbuteoption as $pattroptslist)
              <div class="form-group row">
                <div class="custom-control custom-checkbox col-lg-2">
                  <input type="checkbox" class="custom-control-input" id="prodattroptid{{$pattroptslist->id}}" name="prodattroptid[]" value="{{$pattroptslist->id}}" @if(isset($chekoptarray) && in_array($pattroptslist->id,$chekoptarray)) checked="" @endif >
                  <label class="custom-control-label" for="prodattroptid{{$pattroptslist->id}}">{{$pattroptslist->options}}</label>
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
              <label class="col-lg-2 col-form-label" for="prodprice">{{ __('Featured Image') }}</small></label>
              <div class="col-lg-10">
                <div style="padding:6px">
                  @foreach($prodfeatured as $prodfeature)
                  <img id="fimgpreview" src="{{ asset('storage/'.$prodfeature->url) }}" class="img-responsive" width="100" height="100">
                  @endforeach
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="prodprice">{{ __('Gallery Images') }}</small></label>
              <div class="col-lg-10">
                <div id="gallery" style="padding:6px"></div>
                <div style="padding:6px; display:inline-flex">
                  @foreach($prodmedia as $prodimglst)
                  <span id="pgalimg{{$prodimglst->id}}" style="padding:2px;">
                    <img src="{{ asset('storage/'.$prodimglst->url) }}" class="img-responsive" width="100" height="100">
                  </span>
                  @endforeach
                </div>
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
                <input type="text" class="form-control" id="prodmetatitle" name="prodmetatitle" placeholder="Enter Meta Titles" readonly value="{{$prodrecord->meta_title}}" maxlength="255">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="">{{ __('Meta Description') }}</label>
              <div class="col-lg-10">
                <textarea class="form-control" rows="5" id="prodmetadescript" name="prodmetadescript" readonly placeholder="Enter Meta Description">{{$prodrecord->meta_description}}</textarea>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="">{{ __('Meta Keywords') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="prodmetakeyword" name="prodmetakeyword" placeholder="Enter Meta Keywords" value="{{$prodrecord->meta_keywords}}" readonly maxlength="255">
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-lg-12">
            <div id="prodedtfrmalert"></div>
            <input type="hidden" name="redpath" value="{{ route('vendor.product', ['vendor_name' => $vendor]) }}">
            <a class="btn btn-secondary" href="{{ route('vendor.product', ['vendor_name' => $vendor]) }}">{{ __('Cancel') }}</a>
          </div>
        </div>
      </form>
    </div><!-- end col-->
  </div>
  <!-- end row-->
</div> <!-- container-fluid -->
@endsection