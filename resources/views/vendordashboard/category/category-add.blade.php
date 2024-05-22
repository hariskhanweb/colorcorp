@extends('layouts.vendor-layout')
@section('pageTitle', 'Add Category')
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
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('/'.$shopslug.'/category-management') }}">{{ __('Category Management') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Add Category') }}</li>
          </ol>
        </div>
        <h4 class="page-title">{{ __('Add Category') }}</h4>
      </div>
    </div>
  </div>
  <!-- end page title -->
  <div class="row">
    <div class="col-12">
      <form class="form-horizontal" method="POST" action="{{ url('/'.$shopslug.'/category-management/store') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="card card-box">
          <div class="card-body">
              <div class="form-group row">
                <label class="col-lg-2 col-form-label" for="simpleinput">{{ __('Name') }}</label>
                <div class="col-lg-10">
                  <input type="text" class="form-control category-name" name="name" id="simpleinput" placeholder="Name" value="" required />
                  @if($errors->has('name'))
                  <div class="help-block">{{ $errors->first('name') }} </div>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-2 col-form-label" for="simpleinput">{{ __('Slug') }}</label>
                <div class="col-lg-10">
                  <input type="text" class="form-control category-slug" name="slug" id="simpleinput" placeholder="Slug" value="" required />
                  @if($errors->has('slug'))
                  <div class="help-block">{{ $errors->first('slug') }} </div>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-2 col-form-label" for="image">{{ __('Image') }}</label>
                <div class="col-lg-10">
                  <input type="file" class="form-control" id="image" placeholder="Image" name="image" required />
                  @if($errors->has('image'))
                  <div class="help-block">{{ $errors->first('image') }} </div>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-2 col-form-label" for="simpleinput">{{ __('Has parent') }}</label>
                <div class="col-lg-10">
                  <select class="form-control" name="has_parent" id="has_parent">
                    <option value="0">{{ __('No') }}</option>
                    <option value="1">{{ __('Yes') }}</option>
                  </select>
                </div>
              </div>
              <div class="form-group row parent-category-wrap" style="display:none;">
                <label class="col-lg-2 col-form-label" for="simpleinput">{{ __('Parent Category') }}</label>
                <div class="col-lg-10">
                  <select class="form-control" name="parent_id">
                    <option value="">{{ __('--Select--') }}</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-2 col-form-label">{{ __('Status') }}</label>
                <div class="col-lg-10">
                  <select class="form-control" name="status">
                    <option value="1">{{ __('Enable') }}</option>
                    <option value="0">{{ __('Disable') }}</option>
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
                <input type="text" class="form-control" name="meta_title" placeholder="Enter Meta Titles" maxlength="255">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="">{{ __('Meta Description') }}</label>
              <div class="col-lg-10">
                <textarea class="form-control" rows="5" name="meta_description" placeholder="Enter Meta Description"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label" for="">{{ __('Meta Keywords') }}</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" name="meta_keywords" placeholder="Enter Meta Keywords" maxlength="255">
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-lg-12">
            <input type="hidden" name="vendor_id" value="{{ $user->id }}" required />
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
          </div>
        </div>
      </form>
    </div><!-- end col-->
  </div>
  <!-- end row-->
</div> <!-- container-fluid -->

@endsection