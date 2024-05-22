@extends('layouts.vendor-layout')
@section('pageTitle', 'Category Management')
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
        <h4 class="page-title">{{ __('Category Management') }}</h4>
      </div>
    </div>
  </div>
  <!-- end page title -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
              <a href="{{ url('/'.$shopslug.'/category-management') }}" class="nav-link">
                {{ __('Categories') }}
              </a>
            </li>
            <li class="nav-item">
              <a href="#adminCategories" data-toggle="tab" aria-expanded="true" class="nav-link active">
                {{ __('Admin Categories') }}
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane show active" id="adminCategories">
              <table id="basic-datatable" class="table dt-responsive nowrap">
                <thead>
                  <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Slug') }}</th>
                    <th>{{ __('Has Parent') }}</th>
                    {{--<th>{{ __('Status') }}</th>--}}
                    <th>{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($categories as $category)
                  <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->has_parent == 1 ? 'Yes' : 'No' }}</td>
                    {{--<td>{{ $category->vendor_admin_category_status == 1 ? 'Enable' : 'Disable' }}</td>--}}
                    <td>
                      <div class="col-lg-12">
                        <input type="checkbox" data-plugin="switchery" data-color="#039cfd" data-switchery="true" value="1" data-id="{{ $category->id }}" data-toggle="modal" data-target="#statusmodal" {{ $category->vendor_admin_category_status == 1 ? 'checked' : '' }} /> {{ __('Enable/Disable') }}
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div> <!-- end card body-->
      </div> <!-- end card -->
    </div><!-- end col-->
  </div>
  <!-- end row-->

  <div class="modal fade" id="statusmodal" tabindex="-1" role="dialog" aria-labelledby="myCenterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form method="POST" action="{{ url('/'.$shopslug.'/category-management/store-vendor-admin-category') }}">
          {{ csrf_field() }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Do you want to <span>Enable</span> this category for your store?
          </div>
          <div class="modal-footer">
            <input type="hidden" name="category_id" value="" id="category_id" />
            <input type="hidden" name="vendor_id" value="{{ $user->id }}" id="vendor_id" />
            <input type="hidden" name="status" value="" id="status" />
            <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('Yes') }}</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div> <!-- container-fluid -->

@stop