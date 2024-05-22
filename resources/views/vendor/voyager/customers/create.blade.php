@extends('voyager::master')
@section('page_title', __('Add New Customer'))

@section('page_header')
	@if(session()->has('message'))
	    <div class="alert alert-success">
	        {{ session()->get('message') }}
	    </div>
	@endif
    <div class="container-fluid">
    	<div class="bread-header">
			<h1 class="page-title"><i class="voyager-people"></i>{{ __('Add New Customer') }}</h1>
	    </div>
	</div>
@stop

@section('content')
<div class="page-content edit-add container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-form panel-bordered">
				<form class="form-edit-add" role="form" action="{{url('/admin/customer/save')}}" method="POST" enctype="multipart/form-data">
		            @csrf
		            <div class="panel-body">
		                <div class="form-group  col-md-12 ">                
							<label class="control-label" for="name">{{ __('Name') }}</label>
							<input  type="text" class="form-control" name="name" placeholder="Name" value="@if(count($errors) > 0){{ old('name') }}@endif" required>
							@if($errors->has('name'))
		                        <div class="invalid-feedback full" style="display:block;">{{ __('Please enter your name') }}</div>
		                    @endif
						</div>
						<div class="form-group  col-md-12 ">
							<label class="control-label" for="email">{{ __('Email') }}</label>
							<input type="email" class="form-control" placeholder="Enter email address" name="email" value="@if(count($errors) > 0){{ old('email') }}@endif" required>
							@if($errors->has('email'))
		                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('email') }}</div>
		                    @endif
						</div>
						<div class="form-group col-md-12">
                            <label for="password">{{ __('Password') }}</label><input type="password" class="form-control" required id="password" name="password" value="{{$pass}}"  minlength="8">
                        </div>	
                        <div id="password_err" class="invalid-feedback"></div>
                        @if($errors->has('password'))
		                    <div id="password_error" class="invalid-feedback" style="display:block;">{{ $errors->first('password') }}</div>
		                @endif
						
		              	<div class="form-group col-md-12">
			              	<label for="mobile_number">{{ __('Mobile') }}</label>
			                <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Mobile" value="{{ old('mobile_number') }}">
			                @if($errors->has('mobile_number'))
			                  <span class="invalid-feedback" role="alert">
			                    <strong>{{ $errors->first('mobile_number') }}</strong>
			                  </span>
			                @endif
			            </div>

			            <div class="form-group col-md-12 ">
                            <label class="">{{ __('Assign Vendor') }}</label>
                            <select name="vendor_id" id="vendor_id" class="form-control" required>
                                <option value=''>{{ __('Select') }}</option>
                                @foreach($vendors as $vendor)
                                	@php $shopslug=Helper::getShopslug($vendor->id); @endphp
                                	@if($shopslug != '')
                                    <option value='{{$vendor->id}}'>{{$vendor->name}}</option>
                                	@endif
                                @endforeach
                            </select>
                            <!-- <div class="help-block" id="vendor-err">{{ __('Vendor is required.') }}</div> -->
                        </div>

						<div class="form-group  col-md-12 ">
							<label class="control-label" for="image">{{ __('Upload Image') }}</label>
							<input type="file" name="user_img" accept="image/*">
						</div>

					</div>

					<div class="panel-footer border-top p-30 justify-content-between d-flex  col-md-12">
                        <div class="form-group  col-md-12 ">
                       <!--  <a href="javascript:void(0);" onclick="history.back()" class="btn btn-secondary cancel">{{ __('voyager::generic.cancel') }}</a> -->
						<button type="submit" class="btn btn-primary save">{{ __('Save') }}</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop
@section('css')
<style type="text/css">
    .form-row { display: flex;}
    .form-row .form-control.code { border-top-right-radius: 0; border-bottom-right-radius: 0px; max-width: 70px;}
    .form-row .form-control.code + .form-control { border-top-left-radius: 0px; border-bottom-left-radius: 0px; border-left: 0px; flex-grow: 1;}
    .ccode{ padding-top: 5px;  font-size: 20px;}
    #password_err { margin-left: 15px; margin-bottom: 15px; color: #f1556c; font-size: 12px; }
</style>
@stop
@section('javascript')
<script>
    $("input[name=mobile_number]").keypress(function(event) {
      return /\d/.test(String.fromCharCode(event.keyCode));
    });
    
    $("#password").keyup(function(event) {
       var pswd = $(this).val();
       regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#\$%\^\&*\)\(+=._-])[A-Za-z\d!@#\$%\^\&*\)\(+=._-]{8,}$/;
       if (regex.exec(pswd) == null) {
       	 $("#password_err").html("The password must be at least 8 characters & it should contain at least one capital letter, one digit & one special character.");
			 $("#password").focus();
		    return false;
		}else{
			$("#password_err").html("");
		    return true;
		}
    });
</script>    
@stop