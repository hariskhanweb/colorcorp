@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('Add Category'))

@section('page_header')
<div class="bread-header">
    <h1 class="page-title">
        <i class="voyager-categories"></i>
        {{ __('Add Category') }}
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
                    <form role="form" id="add-category"
                            class="form-edit-add"
                            action="{{ url('/admin/categories/store') }}"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        
                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">
                            <!-- Adding / Editing -->
                            <div class="form-group  col-md-12 ">
                                <label class="control-label" for="name">{{ __('Name:') }}</label>
                                <input required type="text" class="form-control category-name" name="name" placeholder="Name" value="{{ old('name') }}">
                                @if($errors->has('name'))
                                <div class="help-block">{{ $errors->first('name') }} </div>
                                @endif
                            </div>
                            <div class="form-group  col-md-12 ">
                                <label class="control-label" for="name">{{ __('Slug:') }}</label>
                                <input required type="text" class="form-control category-slug" name="slug" placeholder="Slug" value="@if(count($errors) > 0){{ old('slug') }}@endif">
                                @if($errors->has('slug'))
                                <div class="help-block">{{ $errors->first('slug')  }} </div>
                                @endif
                            </div>
                            <div class="form-group  col-md-12 ">
                                <label class="control-label" for="name">{{ __('Image:') }}</label>
                                <input type="file" class="form-control" id="image" placeholder="Image" name="image" required />
                                @if($errors->has('image'))
                                <div class="help-block">{{ $errors->first('image') }} </div>
                                @endif
                            </div>

                            <div class="form-group col-md-12 ">
                                <label class="">{{ __('Assign Vendor:') }}</label>
                                <select name="vendor_id" id="vendor_id" class="form-control" required>
                                    <option value=''>Select</option>
                                    @foreach($vendors as $vendor)
                                        @php $shopslug=Helper::getShopslug($vendor->id); @endphp
                                        @if($shopslug != '')
                                        <option value='{{$vendor->id}}'>{{$vendor->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="help-block" id="vendor-err">{{ __('Vendor is required.') }}</div>
                            </div>

                            <div class="form-group col-md-12 ">
                                <label class="">{{ __('Has parent:') }}</label>
                                <select name="has_parent" class="form-control select_parent">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>

                            <div class="col-md-12 show-parent-categories" style="display: none;">
                                <div class="form-group">
                                    <label class="">{{ __('Parent Category:') }}</label>
                                    <select name="parent_id[]" id="parent_id" class="form-control parent_id select2" multiple >
                                        <option value="0">{{ __('--Select--') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Status:') }}</label>
                                <select required name="status" class="form-control shorting-table">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                                @if($errors->has('status'))
                                <div class="invalid-feedback" style="display:block;">{{$errors->first('status') }}</div>
                                @endif
                            </div>

                            

                            <div class="form-group  col-md-12" style="margin-top: 15px;">
                                <h4>{{ __('SEO Details') }}</h4>
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Title:') }}</label>
                                <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}" placeholder="Enter Meta Title" maxlength="255" />
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Description:') }}</label>
                                <textarea class="form-control" rows="5" name="meta_description" placeholder="Enter Meta Description">{{ old('meta_description') }}</textarea>
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Keywords:') }}</label>
                                <input type="text" class="form-control" name="meta_keywords" placeholder="Enter Meta Keywords" maxlength="255" value="{{ old('meta_keywords') }}"/>
                            </div>

                        </div><!-- panel-body -->

                        <div class="panel-footer col-md-12">
                            <div class="form-group  col-md-12 ">
                            @section('submit-buttons')
                                <button type="submit" id="category-submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                            </div>
                        </div>
                    </form>

                    <div style="display:none">
                        <input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
                        <input type="hidden" id="upload_type_slug" value="categories">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script>
    $(document).ready(function() {
        $('#vendor-err').hide();
        $('#category-submit').click(function(event) {
            var selectedValue = $('#vendor_id').val();
            if (selectedValue === '') {
                $('#vendor-err').show();
                $('#vendor_id').focus();
                $('#category-submit').prop('type', 'button');
            } else {
                $('#vendor-err').hide();
                $('#category-submit').prop('type', 'submit');
            }
        });

});


        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   'categories',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.categories.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('.select_parent').on('change', function() {
            var selVal = this.value;
            if(selVal == 1){
                $(".show-parent-categories").show();
                $('#parent_id').attr('required', true);
            } else{
                $('#parent_id').attr('required', false);
                $(".show-parent-categories").hide();
            }
        });
        $('.category-name').keyup(function(){
            let str = this.value;
            var newstr = str.replace('&', 'and');
            newstr = newstr.replace(/[&@\/\\#, +()$~%.'":*?<>{}]/g, '-');
            $('.category-slug').val(newstr.toLowerCase());
        });
    </script>
@stop
