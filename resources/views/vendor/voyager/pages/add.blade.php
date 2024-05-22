@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('Add Page'))

@section('page_header')
<div class="bread-header">
    <h1 class="page-title">
        <i class="voyager-file-text"></i>
        {{ __('Add Page') }}
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
                    <form role="form" id="add-pages"
                            class="form-edit-add"
                            action="{{ url('/admin/pages/store') }}"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        
                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">
                            <!-- Adding / Editing -->
                            <div class="form-group  col-md-12 ">
                                <label class="control-label" for="name">{{ __('Name') }}</label>
                                <input required type="text" class="form-control page-name" name="name" placeholder="Name" value="{{ old('name') }}">
                                @if($errors->has('name'))
                                <div class="help-block">{{ $errors->first('name') }} </div>
                                @endif
                            </div>
                            <div class="form-group  col-md-12 ">
                                <label class="control-label" for="name">{{ __('Slug') }}</label>
                                <input required type="text" class="form-control page-slug" name="slug" placeholder="Slug" value="@if(count($errors) > 0){{ old('slug') }}@endif">
                                @if($errors->has('slug'))
                                <div class="help-block">{{ $errors->first('slug')  }} </div>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="excerpt">{{ __('Short Description') }}</label>
                                <textarea class="form-control ckeditor" id="excerpt" name="excerpt" placeholder="Enter Short Description" rows="3">{{ old('excerpt') }}</textarea>
                                @if($errors->has('excerpt'))
                                {{ $errors->first('excerpt') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="body">{{ __('Long Description') }}</label>
                                <textarea class="form-control ckeditor" rows="5" id="body" name="body" placeholder="Enter Brief Description" >{{ old('body') }}</textarea>
                                @if($errors->has('body'))
                                <div class="help-block">{{ $errors->first('body') }}</div>
                                @endif
                            </div>

                            <!-- <div class="form-group  col-md-12 ">
                                <label class="control-label" for="name">{{ __('Image') }}</label>
                                <input type="file" class="form-control" id="image" placeholder="Image" name="image" required value="" />
                                @if($errors->has('image'))
                                <div class="help-block">{{ $errors->first('image') }}</div>
                                @endif
                            </div> -->
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

                            <div class="form-group col-md-12">
                                <label class="control-label" for="is_home">
                                  <input type="checkbox" name="is_home" class="custom-control-input" id="is_home" value="1">
                                  <span>{{ __('Is Home!') }}</span>
                                </label>
                                @if($errors->has('is_home'))
                                <br>
                                  <span class="invalid-feedback help-block" role="alert">
                                    <strong>{{ $errors->first('is_home') }}</strong>
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


                            <div class="form-group col-md-12 ">
                                <label class="">{{ __('Searchable Option') }}</label>
                                <div>
                                    <input type="checkbox" name="searchable_option[]" class="custom-control-input" value="search" checked>
                                    <span>{{ __(' Search Box') }}</span> &nbsp;&nbsp;
                                    <input type="checkbox" name="searchable_option[]" class="custom-control-input" value="division">
                                    <span>{{ __(' Search by Division') }}</span> &nbsp;&nbsp;
                                    <input type="checkbox" name="searchable_option[]" class="custom-control-input" value="vehicle">
                                    <span>{{ __(' Search by Vehicle') }}</span> &nbsp;&nbsp;
                                    <input type="checkbox" name="searchable_option[]" class="custom-control-input" value="accessories">
                                    <span>{{ __(' Accessories') }}</span>
                                    <p id="errorMessage" style="color: red; display: none;">{{ __('Please select at least one Searchable Option.') }}</p>
                                </div>
                            </div>


                            <div class="form-group col-md-12 ">
                                <label class="">{{ __('Top Menu Option') }}</label>
                                <div>
                                    <input type="checkbox" name="top_menu_option[]" class="custom-control-input" value="home" checked>
                                    <span>{{ __('Home') }}</span> &nbsp;&nbsp;
                                    <input type="checkbox" name="top_menu_option[]" class="custom-control-input" value="division">
                                    <span>{{ __('Choose Division') }}</span> &nbsp;&nbsp;
                                    <input type="checkbox" name="top_menu_option[]" class="custom-control-input" value="vehicle">
                                    <span>{{ __('Choose Vehicle') }}</span> &nbsp;&nbsp;
                                    <input type="checkbox" name="top_menu_option[]" class="custom-control-input" value="accessories">
                                    <span>{{ __(' Accessories') }}</span>
                                    <p id="menuErrorMessage" style="color: red; display: none;">{{ __('Please select at least one Top Menu Option.') }}</p>
                                </div>
                            </div>


                            <div class="form-group  col-md-12" style="margin-top: 15px;">
                                <h4>{{ __('SEO Details') }}</h4>
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Title') }}</label>
                                <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}" placeholder="Enter Meta Title" maxlength="255" />
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Description') }}</label>
                                <textarea class="form-control" rows="5" name="meta_description" placeholder="Enter Meta Description">{{ old('meta_description') }}</textarea>
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Keywords') }}</label>
                                <input type="text" class="form-control" name="meta_keywords" placeholder="Enter Meta Keywords" maxlength="255" value="{{ old('meta_keywords') }}"/>
                            </div>


                        </div><!-- panel-body -->

                        <div class="panel-footer col-md-12">
                            <div class="form-group  col-md-12 ">
                            @section('submit-buttons')
                                <button type="submit" id="pages-submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                            </div>
                        </div>
                    </form>

                    <div style="display:none">
                        <input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
                        <input type="hidden" id="upload_type_slug" value="pages">
                    </div>
                </div>
            </div>
        </div>
    </div> 

<script type="text/javascript">
const form = document.getElementById('add-pages');
form.addEventListener('submit', function(event) {
  const checkboxes = document.querySelectorAll('input[name="searchable_option[]"]');
  console.log('checkboxes = '+checkboxes);
  let isAnyChecked = false;
  for (const checkbox of checkboxes) {
    if (checkbox.checked) {
      isAnyChecked = true;
      break;
    }
  }
  if (!isAnyChecked) {
    const errorMessage = document.getElementById('errorMessage');
    errorMessage.style.display = 'block';
    event.preventDefault();
  } else {
    const errorMessage = document.getElementById('errorMessage');
    errorMessage.style.display = 'none';
  }
});

form.addEventListener('submit', function(event) {
  const checkboxes = document.querySelectorAll('input[name="top_menu_option[]"]');
  console.log('checkboxes = '+checkboxes);
  let isAnyChecked = false;
  for (const checkbox of checkboxes) {
    if (checkbox.checked) {
      isAnyChecked = true;
      break;
    }
  }
  if (!isAnyChecked) {
    const errorMessage = document.getElementById('menuErrorMessage');
    errorMessage.style.display = 'block';
    event.preventDefault();
  } else {
    const errorMessage = document.getElementById('menuErrorMessage');
    errorMessage.style.display = 'none';
  }
});
</script>

@stop