@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_header')
<div class="bread-header">
    <h1 class="page-title">
        <i class="voyager-check-circle"></i>
        Edit Attribute
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
                            action="{{url('/').'/admin/attributes/update'}}"
                            method="POST" enctype="multipart/form-data">
                        
                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                        <div class="form-group  col-md-12 " >
                                <label class="control-label" for="name">Code</label>
                                <input  type="text" class="form-control" name="attributecode" id="attributecode" placeholder="Code" required="" value="{{$recordattrb->attributecode}}" readonly>
                                @if($errors->has('attributecode'))
                                    <div class="help-block">{{ __('Please enter code') }} </div>
                                @endif
                            </div>
                            <div class="form-group  col-md-12 " >                                    
                                <label class="control-label" for="name">Name</label>
                                <input  type="text" class="form-control" name="name" id="name" placeholder="Name" required="" value="{{$recordattrb->name}}" >
                                @if($errors->has('name'))
                                    <div class="help-block">{{ __('Please enter your name') }} </div>
                                @endif
                            </div>
                            <p style="display: none;" id="db_type_val" data="{{$recordattrb->type}}"></p>
                            <div class="form-group  col-md-12">     
                                <label class="control-label" for="name">Type</label>
                                <select class="form-control select2" name="type" id="type">
                                    <option value="yesno" @if($recordattrb->type=='yesno') selected="selected" @endif >Yes/No</option>
                                    <option value="select" @if($recordattrb->type=='select') selected="selected" @endif >Select</option>
                                    <option value="text" @if($recordattrb->type=='text') selected="selected" @endif >Text</option>
                                </select>
                                @if($errors->has('type'))
                                <div class="help-block">{{ __('Please enter type') }} </div>
                                 @endif
                            </div>                            
                            @php $op = 1;
                            $codn = ($recordattrb->type=='select')?'display:block':'display:none';
                            @endphp
                            <div id="tpval" class="form-group col-md-12" style="{{$codn}}">
                                <div class="row">
                                    <div class="col-md-10">
                                        <label for="name">Type Value</label>
                                        @if(count($recordattrbopts)>0)
                                            @foreach($recordattrbopts as $recoptlst)
                                            @if($op == 1 )
                                            <input type="text" name="type_value[]" id="typevalue-1" class="form-control" value="{{$recoptlst->options}}">
                                            <input type="hidden" name="type_valueid[]" value="{{$recoptlst->id}}">
                                            @php $op++; @endphp
                                            @endif
                                            @endforeach
                                        @else
                                        <input type="text" name="type_value[]" id="typevalue-1" class="form-control" >
                                        <input type="hidden" name="type_valueid[]">
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        <style type="text/css">label.control-label {display: block;}</style>
                                        <label for="name" class="control-label">&nbsp;</label>
                                        <a href="javascript:;" style="display: inline-block; padding-top: 5px;" onclick="addMoreOtion();"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> more option</a>
                                    </div>
                                </div>  
                            </div>
                            <div class="form-row" id="tpmoreval" style="{{$codn}}">
                                @if(count($recordattrbopts)>0)
                                    @php $op1 = 1; $c = 2; @endphp
                                    @foreach($recordattrbopts as $recoptlst2)
                                    @if($op1 > 1 )
                                    <div id="cl_{{$c}}" class="tpmoreval_clone"><div class="form-group col-md-11"><input type="text" name="type_value[]" id="typevalue-{{$c}}" class="form-control" onblur="trimText(this.id);" value="{{$recoptlst2->options}}"></div><div class="form-group col-md-1"><a href="javascript:;" style="color: red;" onclick="remOptValue({{$c}});"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a><input type="hidden" name="type_valueid[]" value="{{$recoptlst2->id}}"></div></div>
                                                                        
                                    @endif
                                    @php $op1++; $c++; @endphp
                                    @endforeach
                                @endif
                            </div>                                                           
                            <div class="form-group  col-md-12" id="price-div" @if($recordattrb->type=='text') style="display:none;" @endif>    
                                <label class="control-label" for="name">Is Price</label>
                                <select class="form-control select2" name="is_price" id="is_price">
                                    <option value="0" @if($recordattrb->is_price=='0') selected="selected" @endif >No</option>
                                    <option value="1" @if($recordattrb->is_price=='1') selected="selected" @endif >Yes</option>
                                </select>
                                 @if($errors->has('is_price'))
                                <div class="help-block">{{ __('Please enter is price') }} </div>
                                @endif
                            </div>

                        </div><!-- panel-body -->

                        <div class="panel-footer col-md-12">
                            <div class="form-group  col-md-12 ">
                            <input type="hidden" name="attributeid" id="attributeid" value="{{$dataid}}">
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
            var type=jQuery("#type").val();
            if(type=='select') { 
                $('#typevalue-1').attr("required", true); 
            } else {
                $('#typevalue-1').attr("required", false); 
            }
        });

        $('input[id^="typevalue-"]').blur(function(){ var cv = $.trim($(this).val()); $(this).val(cv); });
        $('#type').change(function(){
            let vv = $(this).val();
            if(vv=='select'){ 
                if($('#db_type_val').attr('data') == 'text') {
                    $('#typevalue-1').val(''); 
                }
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
            let astr = '<div id="cl_'+numItems+'" class="tpmoreval_clone"><div class="form-group col-md-11"><input type="text" name="type_value[]" id="typevalue-'+numItems+'" class="form-control" onblur="trimText(this.id);"></div><div class="form-group col-md-1"><a href="javascript:;" style="color: red;" onclick="remOptValue('+numItems+');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a><input type="hidden" name="type_valueid[]" value="0"></div></div>';
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
