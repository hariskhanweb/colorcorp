@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_header')
<div class="bread-header">
    <h1 class="page-title">
        <i class="voyager-check-circle"></i>
        {{ __('Add Attribute') }}
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
                    <form role="form"
                            class="form-edit-add"
                            action="{{url('/').'/admin/attributes/save'}}"
                            method="POST" enctype="multipart/form-data">
                        
                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            <div class="form-group  col-md-12 " >
                                <label class="control-label" for="name">Code</label>
                                <input  type="text" class="form-control" name="attributecode" id="attributecode" placeholder="Code" required="">
                                @if($errors->has('attributecode'))
                                    <div class="help-block">{{ __('Please enter code') }} </div>
                                @endif
                            </div>
                            <div class="form-group  col-md-12">          
                                <label class="control-label" for="name">Name</label>
                                <input  type="text" class="form-control" name="name" id="name" placeholder="Name" required="">
                                 @if($errors->has('name'))
                                    <div class="help-block">{{ __('Please enter your name') }} </div>
                                @endif
                            </div>
                            <div class="form-group  col-md-12">                        
                                <label class="control-label" for="name">Type</label>
                                <select class="form-control select2" name="type" id="type">
                                    <option value="yesno" selected="selected">Yes/No</option>
                                    <option value="select">Select</option>
                                    <option value="text">Text</option>
                                </select>
                                 @if($errors->has('type'))
                                <div class="help-block">{{ __('Please enter type') }} </div>
                                 @endif
                            </div>
                            <div id="tpval" class="form-group col-md-12 " style="display:none">
                                <div class="row">
                                    <div class="col-md-10">
                                        <label for="">Type Value</label>
                                        <input type="text" name="type_value[]" id="typevalue-1" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="name" class="control-label">&nbsp;</label>
                                        <a href="javascript:;" style="display: inline-block; padding-top: 5px;" onclick="addMoreOtion();"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> more option</a>
                                    </div>
                                </div>
                            </div> 

                            <div class="form-row" id="tpmoreval" style="display: none"></div>

                            <div class="form-group col-md-12" id="price-div">
                                <label class="control-label" for="name">Is Price</label>
                                <select class="form-control select2" name="is_price" id="is_price">
                                    <option value="0"  selected="selected"  >No</option>
                                    <option value="1"  >Yes</option>
                                </select>
                                 @if($errors->has('is_price'))
                                <div class="help-block">{{ __('Please enter is price') }} </div>
                                @endif
                            </div>

                        </div><!-- panel-body -->

                        <div class="panel-footer col-md-12">
                            <div class="form-group  col-md-12 ">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                            </div>
                        </div>
                    </form>

                    <div style="display:none">
                        <input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
                        <input type="hidden" id="upload_type_slug" value="attributes">
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
        $('document').ready(function () {
            let attrcode = attribtoken();
            $('#attributecode').val(attrcode);
        });
        function attribtoken(){
            let attrcode = "ATR-";
            let char = "9876543211234567890";
            for(let i = 0; i < 5; i++){
                let code = Math.floor(Math.random() * char.length);
                attrcode += char.charAt(code);
            }
            return attrcode;
        }

        $('input[id^="typevalue-"]').blur(function(){ var cv = $.trim($(this).val()); $(this).val(cv); });
        $('#type').change(function(){
            let vv = $(this).val();
            if(vv=='select'){ 
                $('#tpval').show(); 
                $('#tpmoreval').show(); 
                $('#typevalue-1').focus(); 
                $('#typevalue-1').attr("required", true); 
            } else {
                $('#tpval').hide(); 
                $('#tpmoreval').hide(); 
                $('#typevalue-1').attr("required", false);
            }

            if(vv=='text'){ 
                $('#price-div').hide();
            } else {
                $('#price-div').show();
            }
        });
        function addMoreOtion(){
            let numItems = tokn();
            let astr = '<div id="cl_'+numItems+'" class="tpmoreval_clone"><div class="form-group col-md-11"><input type="text" name="type_value[]" id="typevalue-'+numItems+'" class="form-control" onblur="trimText(this.id);"></div><div class="form-group col-md-1"><a href="javascript:;" style="color: red;" onclick="remOptValue('+numItems+');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a></div></div>';
            $('#tpmoreval').append(astr);
            $('#typevalue-'+numItems).focus();
        }
        function remOptValue(dval){ $('#cl_'+dval).remove(); }
        function trimText(id){ let cv = $.trim($('#'+id).val()); $('#'+id).val(cv); }
        function tokn(){
            let chars = "9876543211234567890"; let pass = "";
            for (let x = 0; x < 4; x++) {
                let i = Math.floor(Math.random() * chars.length);
                pass += chars.charAt(i);
            }
            return pass;
        }
    </script>
@stop
