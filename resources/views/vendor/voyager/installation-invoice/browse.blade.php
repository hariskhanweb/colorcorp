@extends('voyager::master')

@section('page_title', __('Installation Invoice'))

@section('page_header')
<div class="container-fluid">
    <div class="bread-header">
        <h1 class="page-title">
            <i class="voyager-check"></i> {{ __('Invoices') }}
        </h1>
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
                                    <th class="dt-not-orderable sorting_disabled">
                                        <input type="checkbox" class="select_all">
                                    </th>
                                    <th class="space">Order Number</th>
                                    <th class="space">Full Name</th>
                                    <th class="space">Total Amount</th>
                                    <th class="space">Invoice Number</th>
                                    <th class="space">Status</th>
                                    <th class="space">Created At</th>
                                    <th class="space actions text-right dt-not-orderable sorting_disabled">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                @php 
                                    $status="";
                                    if($order->ic_status == 0){
                                            $status = '-';
                                    }else if($order->ic_status == 2){ 
                                        $status = 'Completed';                
                                    }else{
                                        $status = 'Pending';
                                    }  
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" name="row_id" id="checkbox_{{ $order->getKey() }}" value="{{ $order->getKey() }}">
                                    </td>
                                    <td class="space">{{ $order->order_number }}</td>
                                    <td class="space">{{ $order->userOrder->name }}</td>
                                    <td class="space">{{ $order->total_charges?setting('payment-setting.currency')." ".number_format($order->total_charges,2):'-' }}</td>
                                    <td class="space">{{ $order->inv_number??"-" }}</td>
                                    <td class="space">{{ $status }}</td>
                                    <td class="space">
                                        @php
                                        if($order->ic_created_at){
                                            echo date_format(date_create($order->ic_created_at),"d/m/Y");
                                        }else{
                                            echo '-';
                                        }
                                        @endphp
                                    </td>
                                    <td class="no-sort no-click bread-actions">
                                        @php
                                        if(empty($order->ic_status)){                                            
                                        @endphp
                                            <a href="/admin/installation-invoice/create/{{ $order->id }}" title="Prepare Invoice" class="btn btn-sm btn-primary pull-right">
                                                <i class="voyager-file-text"></i> <span class="hidden-xs hidden-sm">Prepare Invoice</span>
                                            </a>
                                        @php
                                        }else{
                                        @endphp                                            
                                            <a href="/admin/orders/{{ $order->id }}" title="View Invoice" class="btn btn-sm btn-warning pull-right view">
                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">View</span>
                                            </a>
                                        @php
                                        }
                                        @endphp
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