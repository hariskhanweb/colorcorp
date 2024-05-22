@extends('voyager::master')

@section('page_title', __('Edit Customer'))

@section('page_header')
	@if(session()->has('message'))
	    <div class="alert alert-success">
	        {{ session()->get('message') }}
	    </div>
	@endif
	<div class="container-fluid">
    	<div class="bread-header">
			<h1 class="page-title"><i class="voyager-people"></i>{{ __('Edit Customer') }}</h1>
	    </div>
	</div>
@stop

@section('content')
<div class="page-content edit-add container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-form panel-bordered">
				<form class="form-edit-add" role="form" action="{{url('/admin/customer/update/')}}" method="POST" enctype="multipart/form-data">
		            @csrf
		            <div class="panel-body p-30">
		                <div class="form-group  col-md-12 ">                
							<label class="control-label" for="first_name">{{ __('Name:') }}</label>
							<input  type="hidden" name="user_id" value="{{$data['id']}}"  />
							<input  type="text" class="form-control" name="name" placeholder="Name" value="{{$data['name']}}" required>
							@if($errors->has('name'))
		                        <div class="invalid-feedback full" style="display:block;">{{ __('Please enter your name') }}</div>
		                    @endif
						</div>
						<div class="form-group  col-md-12 ">
							<label class="control-label" for="email">{{ __('Email:') }}</label>
							<input type="email" class="form-control" placeholder="Enter email address" name="email" value="{{$data['email']}}" required>
							@if($errors->has('email'))
		                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('email') }}</div>
		                    @endif
						</div>

						<div class="form-group  col-md-12 ">
							<label class="control-label" for="password">{{ __('New Password (if want to change then enter new password else keep it blank.) :') }}</label> 
							<input type="password" class="form-control" placeholder="Enter Password" name="password" id="password">
							<div id="password_err" class="invalid-feedback"></div>
							@if($errors->has('password'))
		                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('password') }}</div>
		                    @endif
						</div>	

						<div class="form-group col-md-12">
			              	<label for="mobile_number">{{ __('Mobile') }}</label>
			                <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Mobile" value="{{$data['mobile_number']}}">
			                @if($errors->has('mobile_number'))
			                  <span class="invalid-feedback" role="alert">
			                    <strong>{{ $errors->first('mobile_number') }}</strong>
			                  </span>
			                @endif
			            </div>				
		                
						<div class="form-group col-md-12 ">
                            <label class="">{{ __('Assign Vendor:') }}</label>
                            <select name="vendor_id" id="vendor_id" class="form-control" required>
                                <option value=''>{{ __('Select') }}</option>
                                @foreach($vendors as $vendor)
                                    @php $shopslug=Helper::getShopslug($vendor->id); @endphp
                                        @if($shopslug != '')
                                        <option value='{{$vendor->id}}' {{ $vendor->id == $data->vendor_id ? 'selected' : '' }}>{{$vendor->name}}</option>
                                        @endif
                                @endforeach
                            </select>
                            <!-- <div class="help-block" id="vendor-err">{{ __('Vendor is required.') }}</div> -->
                        </div>

						

						<!-- <div class="form-group  col-md-12 ">
							<label class="control-label" for="Status" >{{ __('Active Status:') }}</label>
							<select name="status" class="form-control" required>
								<option value="">Select Status</option>							  
							  	<option value="1" <?php if($data['status']==1){ echo 'selected';}?>>Yes</option>
							  	<option value="0" <?php if($data['status']==0){ echo 'selected';}?>>No</option>
							</select>
							@if($errors->has('status'))
		                        <div class="invalid-feedback" style="display:block;">{{ __('Please Select User Status') }}</div>
		                    @endif
						</div> -->

						<div class="form-group col-md-12">
							<label class="control-label" for="name">{{ __('Upload Image') }}</label>
							@if($data['avatar'])
							<img src="{{ asset('/storage/'.$data['avatar']) }}" alt="Image" width="100px"> <br>
							@endif
							<input type="file" name="user_img" accept="image/*">
						</div>
					</div>


					<div class="panel-footer border-top p-30 justify-content-between d-flex  col-md-12">
                        <div class="form-group  col-md-12 ">
							<button type="submit" class="btn btn-primary save">{{ __('Update Customer') }}</button>
						</div>
					</div>
				</form>
			</div>	
		</div>	
	</div>	
</div>	
@stop
<style type="text/css">
    .form-row { display: flex;}
    .form-row .form-control.code { border-top-right-radius: 0; border-bottom-right-radius: 0px; max-width: 70px;}
    .form-row .form-control.code + .form-control { border-top-left-radius: 0px; border-bottom-left-radius: 0px; border-left: 0px; flex-grow: 1;}
    .ccode{ padding-top: 5px;  font-size: 20px;}
    #password_err { color: #f1556c; font-size: 13px; }
</style>
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
