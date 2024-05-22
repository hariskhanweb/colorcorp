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
  <div class="row align-items-center">
    <div class="col-6">
      <div class="page-title-box">
        <h4 class="page-title">{{ __('Category Management') }}</h4>
      </div>
    </div>
    <div class="col-6">
      <div class="pull-right mt-2 mb-2">
        <a href="{{ url('/'.$shopslug.'/category-management/add') }}" class="btn btn-primary waves-effect waves-light">
          <i class="fe-plus"></i> {{ __('Add Category') }}
        </a>
      </div>
    </div>
  </div>

  <!-- end page title -->
  <div class="row">
    <div class="col-12">
      <div class="card card-box">
        <div class="card-body">
          <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
              <a href="#vendorCategories" data-toggle="tab" aria-expanded="false" class="nav-link active">
                {{ __('Categories') }}
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/'.$shopslug.'/category-management/admin-categories') }}" class="nav-link">
                {{ __('Admin Categories') }}
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane show active" id="vendorCategories">
              <table id="basic-datatable" class="table dt-responsive nowrap">
                <thead>
                  <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Slug') }}</th>
                    <th>{{ __('Image') }}</th>
                    <th>{{ __('Has Parent') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Created At') }}</th>
                    <th>{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($categories as $category)
                  <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>
                      @if($category->image)
                      <img src="{{ asset('storage/'.$category->image) }}" width="50" height="50" />
                      @endif
                    </td>
                    <td>{{ $category->has_parent == 1 ? 'Yes' : 'No' }}</td>
                    <td>{{ $category->status == 1 ? 'Enable' : 'Disable'  }}</td>
                    <td>{{ App\Helpers\Helpers::getDateFormatted($category->created_at) }}</td>
                    <td>
                      <a href="{{ url('/'.$shopslug.'/category-management/edit/'.$category->id) }}" class="btn btn-primary waves-effect waves-light">
                        <i class="fe-edit"></i>
                      </a>
                      <a href="{{ url('/'.$shopslug.'/category-management/delete/'.$category->id) }}" class="btn btn-danger waves-effect waves-light">
                        <i class="fe-trash-2"></i>
                      </a>
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
</div> <!-- container-fluid -->

<!-- Alert Success Modal -->
<div class="modal fade" id="sucessModal" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label=""><span>×</span></button>
      </div>

      <div class="modal-body">

        <div class="sucess-pop">
          <img src="{{ asset('img/Green-Round-Tick.png') }}" alt="">
          <h1>{{ __('Great!') }}</h1>
          <p>{{Session::get('message')}}</p>
        </div>

      </div>
    </div>
  </div>
</div>

@if (session()->has('message'))
<script type="text/javascript">
  $(document).ready(function() {
    $('#sucessModal').modal('show');
  });
</script>
@endif

@endsection