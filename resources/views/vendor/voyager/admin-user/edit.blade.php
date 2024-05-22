@extends('voyager::master')

@section('page_title', __('Edit User'))

@section('page_header')
	@if(session()->has('message'))
	    <div class="alert alert-success">
	        {{ session()->get('message') }}
	    </div>
	@endif
	<div class="container-fluid">
    	<div class="bread-header">
			<h1 class="page-title"><i class="voyager-person"></i>{{ __('Edit User') }}</h1>
	    </div>
	</div>
@stop

@section('content')
<div class="page-content edit-add container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-form panel-bordered">
				<form class="form-edit-add" role="form" action="{{url('/admin/admin-user/update/')}}" method="POST" enctype="multipart/form-data">
		            @csrf
		            <div class="panel-body p-30">
		                <div class="form-group  col-md-12 ">                
							<label class="control-label" for="first_name">{{ __('Name:') }}</label>
							<input  type="hidden" name="user_id" value="{{$userInfo['id']}}"  />
							<input  type="text" class="form-control" name="name" placeholder="Name" value="{{$userInfo['name']}}" required>
							@if($errors->has('name'))
		                        <div class="invalid-feedback full" style="display:block;">{{ __('Please enter your name') }}</div>
		                    @endif
						</div>
						<div class="form-group  col-md-12 ">
							<label class="control-label" for="email">{{ __('Email:') }}</label>
							<input type="email" class="form-control" placeholder="Enter email address" name="email" value="{{$userInfo['email']}}" required>
							@if($errors->has('email'))
		                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('email') }}</div>
		                    @endif
						</div>

						<div class="form-group  col-md-12 ">
							<label class="control-label" for="password">{{ __('New Password (if want to change then enter new password else keep it blank.) :') }}</label>
							<input type="password" class="form-control" placeholder="Enter Password" name="password" >
							<div id="password_err" class="invalid-feedback"></div>
							@if($errors->has('password'))
		                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('password') }}</div>
		                    @endif
						</div>					
		                
						<div class="form-group  col-md-12 ">
							<label class="control-label" for="role">{{ __('Select Role:') }}</label>
							<select id="user_role"  name="user_role" class="form-control" disabled>
								<option value="">Select Role</option>
							  @foreach ($userrole as $role_list)
							  	@if($role_list['id'] == $userInfo['role_id'])
							  		<option value="{{ $role_list['id'] }}" data-role="{{ $role_list['display_name'] }}"
							  		selected>{{ $role_list['display_name'] }}</option>
							  	@else
							  		<option value="{{ $role_list['id'] }}" data-role="{{ $role_list['display_name'] }}">{{ $role_list['display_name'] }}</option>
							  	@endif
							  @endforeach		
							  
							</select>
							@if($errors->has('user_role'))
		                        <div class="invalid-feedback" style="display:block;">{{ __('Please Select User Role') }}</div>
		                    @endif
						</div>

						<div id="category_block" class="form-group  col-md-12 @if($userInfo['role_id'] !=2)  hide @endif">
							<label class="control-label" for="role">{{ __('Category:') }} </label>
							<select id="bus_cat" name="bus_cat" class="form-control">
								<option value="">Select Category</option>
							  @foreach ($buscat as $buscat_list)
							  	@if($buscat_list['id'] == $userInfo['business_category'])
							  		<option value="{{ $buscat_list['id'] }}" selected>{{ $buscat_list['name'] }}</option>
							  	@else
							  		<option value="{{ $buscat_list['id'] }}">{{ $buscat_list['name'] }}</option>
							  	@endif
							  @endforeach		
							  
							</select>
							@if($errors->has('bus_cat'))
		                        <div class="invalid-feedback" style="display:block;">{{ __('Please Select User Category') }}</div>
		                    @endif
						</div>

						<!-- <div class="form-group  col-md-12 ">
							<label class="control-label" for="Status" >{{ __('Active Status:') }}</label>
							<select name="status" class="form-control" required>
								<option value="">Select Status</option>							  
							  	<option value="1" <?php if($userInfo['status']==1){ echo 'selected';}?>>Yes</option>
							  	<option value="0" <?php if($userInfo['status']==0){ echo 'selected';}?>>No</option>
							</select>
							@if($errors->has('status'))
		                        <div class="invalid-feedback" style="display:block;">{{ __('Please Select User Status') }}</div>
		                    @endif
						</div> -->

						<div class="form-group col-md-12">
							<label class="control-label" for="name">Upload Image</label>
							@if($userInfo['avatar'])
							<img src="{{ asset('/storage/'.$userInfo['avatar']) }}" alt="Image" width="100px"> <br>
							@endif
							<input type="file" name="user_img" accept="image/*">
						</div>

						<div class="form-group col-md-12">
							<label class="control-label" for="name">Upload Logo</label>
							@if($userInfo['user_logo'])
							<img src="{{ asset('/storage/'.$userInfo['user_logo']) }}" alt="Image" width="100px"> <br>
							@endif
							<input type="file" name="user_logo" accept="image/*">
						</div>
					</div>



					<div class="panel-footer border-top p-30 justify-content-between d-flex  col-md-12">
                            <div class="form-group  col-md-12 ">
                 <!--    <a href="javascript:void(0);" onclick="history.back()" class="btn btn-secondary cancel">{{ __('voyager::generic.cancel') }}</a> -->
						<button type="submit" class="btn btn-primary save">Update User</button>
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
</style>
@section('javascript')
<script>

	$("input[name=contact_number]").keypress(function(event) {
		return /\d/.test(String.fromCharCode(event.keyCode));
	});
	$("input[name=zip_code]").keypress(function(event) {
		return /\d/.test(String.fromCharCode(event.keyCode));
	});



    $('#user_role').on('change', function() {
		var user_role=$(this).find(':selected').data("role");

		if(user_role=='Vendor'){
         	$("#category_block").removeClass("hide");
         	$("#bus_cat").attr('required', 'required');
		}else{
         	$("#category_block").addClass("hide");
         	$("#bus_cat").removeAttr('required');

		}
	});
</script>    
@stop
