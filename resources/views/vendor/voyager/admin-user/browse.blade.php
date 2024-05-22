@extends('voyager::master')

@section('page_title', __('Admin Users'))

@section('page_header')
<div class="container-fluid">
    <div class="bread-header">
        <h1 class="page-title">
            <i class="voyager-person"></i> {{ __('Users') }}
        </h1>
        <div class="bread-buttons">
            <a href="/admin/admin-user/create" class="btn btn-success btn-add-new">
                <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
            </a>
        </div>
    </div>
</div>
<style type="text/css">
    .space {
        border-left-width: 0px !important;
        border-right-width: 0px !important;
    }

    table.dataTable thead .sorting_desc {
        background-image: none !important;
    }

    table.dataTable thead .sorting_asc {
        background-image: none !important;
    }

    table.dataTable.no-footer {
        border-bottom: none !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0px !important;
    }

    .btn.btn-danger {
        background-color: #fa2a00;
        border-color: #fa2a00;
    }

    table.dataTable thead th,
    table.dataTable tbody td {
        font-weight: normal;
        font-size: 14px;
    }
</style>
@stop
<div id="voyager-notifications"></div>
@section('content')
<div class="page-content browse container-fluid">
    @include('voyager::alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table admin-table table-hover">
                            <thead>
                                <tr>

                                    <th class="dt-not-orderable">
                                        <input type="checkbox" class="select_all">
                                    </th>

                                    <th class="space">Id</th>
                                    <th class="space">User Name</th>
                                    <th class="space">Avatar </th>
                                    <th class="space">User Role </th>
                                    <th class="space">Category</th>
                                    <th class="space">Created Date</th>
                                    <th class="space actions text-right sorting_disabled">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($adminuser as $user_list)
                                <?php
                                $dateFormate = date('m/d/Y', strtotime($user_list->created_at));

                                if (isset($user_list->bussinessCategories)) {
                                    $catname = $user_list->bussinessCategories->name;
                                } else {
                                    $catname = '';
                                }
                                ?>
                                <tr>

                                    <td>
                                        <input type="checkbox" name="row_id" id="checkbox_{{ $user_list->getKey() }}" value="{{ $user_list->getKey() }}">
                                    </td>

                                    <td class="space">{{ $user_list->id }}</td>
                                    <td class="space">{{ $user_list->name }}</td>
                                    <td class="space"><img src="{{ asset('/storage/'.$user_list->avatar) }}" width="100px"></td>
                                    <td class="space">{{$user_list->getuserRoll->display_name}}</td>
                                    <td class="space">{{$catname}}</td>
                                    <td class="space">{{ $dateFormate }}</td>
                                    <td class="no-sort no-click bread-actions">
                                        @if($user_list->role->id!=3)
                                        <a href="javascript:;" title="Delete" class="btn btn-sm btn-danger pull-right delete" data-id="{{ $user_list->id }}" id="delete-{{ $user_list->id }}">
                                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Delete</span>
                                        </a>
                                        @endif
                                        <a href="/admin/admin-user/edit/{{ $user_list->id }}" title="Edit" class="btn btn-sm btn-primary pull-right edit">
                                            <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Edit</span>
                                        </a>
                                        <!--  <a href="/admin/admin-user/edit/{{ $user_list->id }}" title="Edit" class="btn btn-sm btn-primary pull-right edit" style="background-color: #454545;border-color: #454545;">
                                    <i class="voyager-edit"></i></a> -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('Are you sure you want to delete this') }} {{ __('user') }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal" style="margin-top: -15px!important;">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.1/css/dataTables.dateTime.min.css">
@stop

@section('javascript')
<script>
    $(document).ready(function() {
        $('#dataTable').dataTable({
            paging: true,
            searching: true,
            "info": true,
            "bLengthChange": true, //thought this line could hide the LengthMenu
            "bInfo": true,
            aoColumnDefs: [{
                bSortable: true,
                aTargets: [4]
            }],

        });
        $('#dataTable_filter input[type="search"]').attr('placeholder', 'Search...');
        $('#dataTable_filter input[type="search"]').css('background-color', '#ffffff');
        $('#dataTable_filter input[type="search"]').css('border', '1px solid #e0e0e0');
    });
    var deleteFormAction;
    $('td').on('click', '.delete', function(e) {
        $('#delete_form')[0].action = '{{ url("/admin/admin-user/delete") }}';
        $('#user_id').val($(this).data('id'));
        $('#delete_modal').modal('show');
    });
</script>
@stop
<!--     <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script> -->