@extends('layouts.vendor-layout')
@section('pageTitle', 'Edit Category')
@section('content')

@php
$user = auth()->user();
$vendata = Session::get('vendordata');
$shopslug = $vendata['shop_url_slug'];
@endphp
<!-- Start Content-->
<div class="container-fluid">

  <!-- start page title -->
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <h4 class="page-title">{{ __('Edit Category') }}</h4>
      </div>
    </div>
  </div>
  <!-- end page title -->
  <div class="row">
    <div class="col-12">
      <form class="form-horizontal" method="POST" action="{{ url('/'.$shopslug.'/category-management/update') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="card">
          <div class="card-body">
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="simpleinput">{{ __('Name') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control category-name" name="name" id="simpleinput" placeholder="Name" value="{{ $category->name }}" required="required" />
                @if($errors->has('name'))
                <div class="help-block">{{ $errors->first('name') }} </div>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="simpleinput">{{ __('Slug') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control category-slug" name="slug" id="simpleinput" placeholder="Slug" value="{{ $category->slug }}" required="required" />
                @if($errors->has('slug'))
                <div class="help-block">{{ $errors->first('slug') }} </div>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="image">{{ __('Image') }}</label>
              <div class="col-lg-10">
                <input type="file" class="form-control" id="image" placeholder="Image" name="image" />
                @if($errors->has('image'))
                <div class="help-block">{{ $errors->first('image') }} </div>
                @endif
                @if($category->image)
                <div class="image-preview">
                  <img src="{{ asset('storage/'.$category->image) }}" class="img-responsive" width="80" height="80">
                </div>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="simpleinput">{{ __('Has parent') }}</label>
              <div class="col-lg-10">
                <select class="form-control" name="has_parent" id="has_parent">
                  <option value="0" {{ $category->has_parent == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                  <option value="1" {{ $category->has_parent == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                </select>
              </div>
            </div>
            <div class="form-group row parent-category-wrap" style="{{ $category->has_parent == 0 ? 'display:none' : '' }}">
              <label class="col-lg-2 col-form-label" for="simpleinput">{{ __('Parent Category') }}</label>
              <div class="col-lg-10">
                <select class="form-control" name="parent_id">
                  <option value="">{{ __('--Select--') }}</option>
                  @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ $cat->id == $category->parent_id ? 'selected' : '' }}>{{ $cat->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label">{{ __('Status') }}</label>
              <div class="col-lg-10">
                <select class="form-control" name="status">
                  <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Enable</option>
                  <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Disable</option>
                </select>
              </div>
            </div>
          </div> <!-- end card body-->
        </div> <!-- end card -->
        <div class="card">
          <div class="card-body">
            <h4 class="page-title">{{ __('SEO Details') }}</h4>
            <hr />
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="">{{ __('Meta Titles') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" name="meta_title" placeholder="Enter Meta Titles" maxlength="255" value="{{ $category->meta_title }}" />
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="">{{ __('Meta Description') }}</label>
              <div class="col-lg-10">
                <textarea class="form-control" rows="5" name="meta_description" placeholder="Enter Meta Description">{{ $category->meta_description }}</textarea>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="">{{ __('Meta Keywords') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" name="meta_keywords" placeholder="Enter Meta Keywords" maxlength="255" value="{{ $category->meta_keywords }}" />
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-lg-12">
            <input type="hidden" name="category_id" value="{{ $category->id }}" />
            <input type="hidden" name="vendor_id" value="{{ $user->id }}" required="required"  />
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
          </div>
        </div>
      </form>  
    </div><!-- end col-->
  </div>
  <!-- end row-->
</div> <!-- container-fluid -->

@endsection