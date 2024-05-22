@extends('voyager::master')

@section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <div class="bread-header">
    <h1 class="page-title">
        <i class="voyager-list-add"></i> {{ __('voyager::generic.viewing') }} {{ ucfirst($dataType->getTranslatedAttribute('display_name_singular')) }} &nbsp;
    </h1>
    <div class="bread-buttons">
        @can('edit', $dataTypeContent)
            <a href="{{ route('voyager.'.$dataType->slug.'.edit', $dataTypeContent->getKey()) }}" class="btn btn-info">
                <i class="glyphicon glyphicon-pencil"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.edit') }}</span>
            </a>
        @endcan
        @can('delete', $dataTypeContent)
            @if($isSoftDeleted)
                <a href="{{ route('voyager.'.$dataType->slug.'.restore', $dataTypeContent->getKey()) }}" title="{{ __('voyager::generic.restore') }}" class="btn btn-default restore" data-id="{{ $dataTypeContent->getKey() }}" id="restore-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.restore') }}</span>
                </a>
            @else
                <a href="javascript:;" title="{{ __('voyager::generic.delete') }}" class="btn btn-danger delete" data-id="{{ $dataTypeContent->getKey() }}" id="delete-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.delete') }}</span>
                </a>
            @endif
        @endcan
        @can('browse', $dataTypeContent)
        <a href="{{ route('voyager.'.$dataType->slug.'.index') }}" class="btn btn-warning">
            <i class="glyphicon glyphicon-list"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.return_to_list') }}</span>
        </a>
        @endcan
    </div>
    @include('voyager::multilingual.language-selector')
    </div>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                   

                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">{{ __('Name') }}</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>{{$prodrecord->name}}</p>
                    </div>
                    <hr style="margin:0;">

                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">{{ __('MSlug') }}</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>{{$prodrecord->slug}}</p>
                    </div>
                    <hr style="margin:0;">

                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">{{ __('SKU') }}</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>{{$prodrecord->sku}}</p>
                    </div>
                    <hr style="margin:0;">

                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">{{ __('Short Description') }}</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>{!! $prodrecord->short_description !!}</p>
                    </div>
                    <hr style="margin:0;">

                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">{{ __('Long Description') }}</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>{!! $prodrecord->long_description !!}</p>
                    </div>
                    <hr style="margin:0;">

                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">{{ __('Price($)') }}</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>{{strip_tags($prodrecord->price)}}</p>
                    </div>
                    <hr style="margin:0;">

                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">{{ __('Status') }}</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>{{ $prodrecord->status== 1 ? 'Active' : 'Inactive' }}</p>
                    </div>
                    <hr style="margin:0;">

                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">{{ __('Vendor') }}</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        @foreach($vendors as $vendor)
                            {{ $vendor->id == $prodrecord->vendor_id ? $vendor->name : '' }}
                        @endforeach
                    </div>
                    <hr style="margin:0;">

                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">{{ __('Category') }}</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
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
                <div class="panel panel-bordered" style="padding-bottom:5px;">            
                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">{{ __('Has Variation') }}</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>{{ $prodrecord->has_variation== 0 ? 'NO' : 'YES' }}</p>
                    </div>
                    <hr style="margin:0;">

                    
                    <div id="prodothrsection" class="form-group read-attr-file" @if($prodrecord->has_variation == '1') style="display:block" @else style="display:none" @endif>
                        
                        <h4 class="">{{ __('Attributes') }}</h4><hr />
                        
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

                        <h5 class="" style="color: #00acc1;"><input type="checkbox" id="prodparentattr{{$pattriblist->id}}" name="prodparentattr[]" value="{{$pattriblist->id}}" onclick="ShowAttributeOpts({{$pattriblist->id}});" @if(isset($chekparentarray) && in_array($pattriblist->id,$chekparentarray)) checked="" @endif> {{$pattriblist->name}}</h5>@endif
                        <div id="showattroption{{$pattriblist->id}}" @if(isset($chekparentarray) && in_array($pattriblist->id,$chekparentarray)) style="display:block" @else style="display:none" @endif class="ml-30">
                          @if(count($pattrbuteoption)>0)
                          @foreach($pattrbuteoption as $pattroptslist)
                          <div class="form-group col-md-12">
                            <div class="custom-control custom-checkbox col-md-2">
                              <input type="checkbox" class="custom-control-input" id="prodattroptid{{$pattroptslist->id}}" name="prodattroptid[]" value="{{$pattroptslist->id}}" @if(isset($chekoptarray) && in_array($pattroptslist->id,$chekoptarray)) checked="" @endif >
                              <label class="custom-control-label" for="prodattroptid{{$pattroptslist->id}}">{{$pattroptslist->options}}</label>
                              <input type="hidden" name="prodattrparentid{{$pattroptslist->id}}" value="{{$pattriblist->id}}" />
                            </div>
                            <div class="col-md-10">
                              @if($pattriblist->type == "select")
                              <div class="">
                                <input type="number" class="form-control" name="prodoptvaryprice{{$pattroptslist->id}}" id="prodoptvaryprice{{$pattroptslist->id}}" placeholder="Enter Price" readonly @if(isset($chekoptarray) && in_array($pattroptslist->id,$chekoptarray)) value="{{$optpriceval[$pattroptslist->id]}}" @else value="0" @endif >
                              </div>
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

            <div class="panel panel-bordered" style="padding-bottom:5px;">    
                <div class="form-group read-attr-file">
                <h4 class="">{{ __('Media') }}</h4>
                <hr />
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label" for="prodprice">{{ __('Featured Image') }}</label>
                        <div class="col-lg-10">
                            <div style="padding:6px">
                              <img id="fimgpreview" src="{{ asset('storage/'.$prodfeatured->url) }}" class="img-responsive" width="100" height="100">
                            </div>
                        </div>
                    </div>
                    <hr style="margin-top:10; ">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label" for="prodprice">{{ __('Gallery Images') }}</label>
                        <div class="col-lg-10">
                           <div style="padding:6px; display:inline-flex">
                              @foreach($prodmedia as $prodimglst)
                              <span id="pgalimg{{$prodimglst->id}}" class="pgalimg">
                                <img src="{{ asset('storage/'.$prodimglst->url) }}" class="img-responsive proimg" width="100" height="100">
                              </span>
                              @endforeach
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 


            <div class="panel panel-bordered" style="padding-bottom:5px;">  
                <div class="form-group read-attr-file" style="margin-top: 15px;">
                    <h4>{{ __('SEO Details') }}</h4>
                </div>
                <hr style="margin:0;">

                <div class="panel-heading" style="border-bottom:0;">
                    <h3 class="panel-title">{{ __('Meta Title') }}</h3>
                </div>
                <div class="panel-body" style="padding-top:0;">
                    <p>{{$prodrecord->meta_title}}</p>
                </div>
                <hr style="margin:0;">

                <div class="panel-heading" style="border-bottom:0;">
                    <h3 class="panel-title">{{ __('Meta Description') }}</h3>
                </div>
                <div class="panel-body" style="padding-top:0;">
                    <p>{{$prodrecord->meta_description}}</p>
                </div>
                <hr style="margin:0;">

                <div class="panel-heading" style="border-bottom:0;">
                    <h3 class="panel-title">{{ __('Meta Keywords') }}</h3>
                </div>
                <div class="panel-body" style="padding-top:0;">
                    <p>{{$prodrecord->meta_keywords}}</p>
                </div>
                <hr style="margin:0;">
            </div>
            



        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('voyager.'.$dataType->slug.'.index') }}" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="{{ __('voyager::generic.delete_confirm') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('javascript')
    @if ($isModelTranslatable)
        <script>
            $(document).ready(function () {
                $('.side-body').multilingual();
            });
        </script>
    @endif
    <script>
        var deleteFormAction;
        $('.delete').on('click', function (e) {
            var form = $('#delete_form')[0];

            if (!deleteFormAction) {
                // Save form action initial value
                deleteFormAction = form.action;
            }

            form.action = deleteFormAction.match(/\/[0-9]+$/)
                ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : deleteFormAction + '/' + $(this).data('id');

            $('#delete_modal').modal('show');
        });

    </script>
@stop