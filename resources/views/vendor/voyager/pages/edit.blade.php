@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('Edit Page'))

@section('page_header')
<div class="bread-header">
    <h1 class="page-title">
        <i class="voyager-file-text"></i>
        {{ __('Edit page') }}
    </h1>
    @include('voyager::multilingual.language-selector')
</div>
@stop

@section('content')

    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form" id="form-edit-add" 
                            class="form-edit-add"
                            action="{{ url('/admin/pages/update') }}"
                            method="POST" enctype="multipart/form-data">

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}
                        <input  type="hidden" name="page_id" value="{{ $data->id }}"  />
                        <div class="panel-body">
                           

                            <div class="form-group  col-md-12 ">
                                <label class="control-label" for="name">{{ __('Name:') }}</label>
                                <input required="" type="text" class="form-control page-name" name="name" placeholder="Name" value="{{ $data->title }}">
                                @if($errors->has('name'))
                                <div class="help-block">{{ $errors->first('name') }} </div>
                                @endif
                            </div>
                            <div class="form-group col-md-12 ">
                                <label class="control-label" for="name">{{ __('Slug:') }}</label>
                                <input required="" type="text" class="form-control page-slug" name="slug" placeholder="Slug" value="{{ $data->slug }}">
                                @if($errors->has('slug'))
                                <div class="help-block">{{ $errors->first('slug')  }} </div>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="excerpt">{{ __('Short Description') }}</label>
                                <textarea class="form-control ckeditor" id="excerpt" name="excerpt" placeholder="Enter Short Description" rows="3">{{ $data->excerpt }}</textarea>
                                @if($errors->has('excerpt'))
                                {{ $errors->first('excerpt') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="body">{{ __('Long Description') }}</label>
                                <textarea class="form-control ckeditor" rows="5" id="body" name="body" placeholder="Enter Brief Description" >{{ $data->body }}</textarea>
                                @if($errors->has('body'))
                                <div class="help-block">{{ $errors->first('body') }}</div>
                                @endif
                            </div>

                            <!-- <div class="form-group  col-md-12 ">
                                <label class="control-label" for="name">{{ __('Image:') }}</label>
                                <input type="file" id="image" placeholder="Image" name="image" />
                                @if($errors->has('image'))
                                <div class="help-block">{{ $errors->first('image') }} </div>
                                @endif
                                @if($data->image)
                                <div class="image-preview">
                                    <img src="{{ asset('storage/'.$data->image) }}" class="img-responsive" width="80" height="80">
                                </div>
                                @endif
                            </div> -->
                            

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Status:') }}</label>
                                <select name="status" class="form-control shorting-table">
                                    <option value="1" {{ $data->status== 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                </select>
                                @if($errors->has('status'))
                                <div class="invalid-feedback" style="display:block;">{{$errors->first('status') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label" for="is_home">
                                  <input type="checkbox" name="is_home" class="custom-control-input" id="is_home" value="1" {{ $data->is_home == 1 ? 'checked' : '' }}>
                                  <span>{{ __('Is Home!') }}</span>
                                </label>
                                @if($errors->has('is_home'))
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('is_home') }}</strong>
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

                            @php 
                            $searchableOption = [];
                            if(isset($data->searchable_option)) {
                              $searchableOption = json_decode($data->searchable_option);
                            }
                            if(empty($searchableOption)) {
                              $searchableOption = array('0'=>'search');
                            }
                            @endphp

                            <div class="form-group col-md-12 ">
                                <label class="">{{ __('Searchable Option') }}</label>
                                <div>
                                    <input type="checkbox" name="searchable_option[]" class="custom-control-input" value="search" @if(!empty($searchableOption)) {{ in_array('search', $searchableOption) ? 'checked' : '' }} @endif>
                                    <span>{{ __(' Search Box') }}</span> &nbsp;&nbsp;
                                    <input type="checkbox" name="searchable_option[]" class="custom-control-input" value="division" @if(!empty($searchableOption)) {{ in_array('division', $searchableOption) ? 'checked' : '' }} @endif>
                                    <span>{{ __(' Search by Division') }}</span> &nbsp;&nbsp;
                                    <input type="checkbox" name="searchable_option[]" class="custom-control-input" value="vehicle" @if(!empty($searchableOption)) {{ in_array('vehicle', $searchableOption) ? 'checked' : '' }} @endif>
                                    <span>{{ __(' Search by Vehicle') }}</span> &nbsp;&nbsp;
                                    <input type="checkbox" name="searchable_option[]" class="custom-control-input" value="accessories" @if(!empty($searchableOption)) {{ in_array('accessories', $searchableOption) ? 'checked' : '' }} @endif>
                                    <span>{{ __(' Accessories') }}</span>
                                    <p id="errorMessage" style="color: red; display: none;">{{ __('Please select at least one Searchable Option.') }}</p>
                                </div>
                            </div>

                            @php 
                            $topMenuOption = [];
                            if(isset($data->top_menu_option)) {
                              $topMenuOption = json_decode($data->top_menu_option);
                            }
                            if(empty($topMenuOption)) {
                              $topMenuOption = array('0'=>'home');
                            }
                            @endphp
                            <div class="form-group col-md-12 ">
                                <label class="">{{ __('Top Menu Option') }}</label>
                                <div>
                                    <input type="checkbox" name="top_menu_option[]" class="custom-control-input" value="home" @if(!empty($topMenuOption)) {{ in_array('home', $topMenuOption) ? 'checked' : '' }} @endif>
                                    <span>{{ __('Home') }}</span> &nbsp;&nbsp;
                                    <input type="checkbox" name="top_menu_option[]" class="custom-control-input" value="division" @if(!empty($topMenuOption)) {{ in_array('division', $topMenuOption) ? 'checked' : '' }} @endif>
                                    <span>{{ __('Choose Division') }}</span> &nbsp;&nbsp;
                                    <input type="checkbox" name="top_menu_option[]" class="custom-control-input" value="vehicle" @if(!empty($topMenuOption)) {{ in_array('vehicle', $topMenuOption) ? 'checked' : '' }} @endif>
                                    <span>{{ __('Choose Vehicle') }}</span> &nbsp;&nbsp;
                                    <input type="checkbox" name="top_menu_option[]" class="custom-control-input" value="accessories" @if(!empty($topMenuOption)) {{ in_array('accessories', $topMenuOption) ? 'checked' : '' }} @endif>
                                    <span>{{ __(' Accessories') }}</span>
                                    <p id="menuErrorMessage" style="color: red; display: none;">{{ __('Please select at least one Top Menu Option.') }}</p>
                                </div>
                            </div>


                            <div class="form-group  col-md-12" style="margin-top: 15px;">
                                <h4>{{ __('SEO Details') }}</h4>
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Title') }}</label>
                                <input type="text" class="form-control" name="meta_title" value="{{ $data->meta_title }}" placeholder="Enter Meta Title" maxlength="255" />
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Description:') }}</label>
                                <textarea class="form-control" rows="5" name="meta_description" placeholder="Enter Meta Description">{{ $data->meta_description }}</textarea>
                            </div>

                            <div class="form-group  col-md-12 ">
                                <label class="">{{ __('Meta Keywords:') }}</label>
                                <input type="text" class="form-control" name="meta_keywords" placeholder="Enter Meta Keywords" maxlength="255" value="{{ $data->meta_keywords }}" />
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
                        <input type="hidden" id="upload_type_slug" value="pages">
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

<script type="text/javascript">
const form = document.getElementById('form-edit-add');
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