@extends('layouts.app')
@section('title', __('cctools::lang.tazas'))

@section('content')
@include('cctools::layouts.partials.nav')
@if(auth()->user()->can('cctools.rated_view'))
    <section class="content-header">
        <h1>@lang( 'cctools::lang.tazas_sets' )
            <small>@lang( 'cctools::lang.manage_your_tazas' )</small>
        </h1>
    </section>
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang( 'cctools::lang.all_your_tazas' )</h3>
                @can('cctools.create_rated')
                    <div class="box-tools">
                        <button type="button" class="btn btn-block btn-primary btn-modal" 
                            data-href="{{route('create-tazas')}}" 
                            data-container=".currency_modal">
                            <i class="fa fa-plus"></i> @lang( 'cctools::lang.add' )</button>
                    </div>
                @endcan
                
            </div>
            
            <div class="box-body">
                    <table class="table table-bordered table-striped" id="taza_table">
                        <thead>
                            <tr>
                                <th>@lang( 'cctools::lang.alias' )</th>
                                <th>@lang( 'cctools::lang.country_currency' )</th>
                                <th>@lang( 'cctools::lang.value_tax' )</th>
                                <th>@lang( 'cctools::lang.action' )</th>
                                
                            </tr>
                        </thead>
                    </table>
                </div>
        </div>
        <div class="modal fade taza_modal" tabindex="-1" role="dialog" 
    	    aria-labelledby="gridSystemModalLabel" id="taza_modal">
        </div>
    </section>
    @else
    <div class="alert alert-danger">
        <h4><i class="icon fa fa-ban"></i> @lang('cctools::lang.unauthorized')</h4>
        @lang('cctools::lang.you_are_not_authorized')
    </div>
    @endif
@endsection
@section('javascript')
<script>
$(document).ready(function () {
    var taza_table = $('#taza_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/cctools/taza_s',
        columnDefs: [{
            "targets": [1,2, 3],
            "orderable": false,
            "searchable": false
        }],
        columns: [
            { data: 'alias', name: 'alias' },
            { data: 'country_currency', name: 'country_currency' },
            { data: 'value_tax', name: 'value_tax' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', 'button.delete_taza_button', function(){
        var button = $(this);
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_table,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                var href = button.data('href');
                var data = button.serialize();

                $.ajax({
                    method: "DELETE",
                    url: href,
                    dataType: "json",
                    data: data,
                    success: function(result){
                        if(result.success == true){
                            toastr.success(result.msg);
                            taza_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        });
    });

    // Abre el modal cuando se hace clic en el botón
    $(document).on('click', 'button.btn-modal', function(){
        var href = $(this).data('href');
        $.get(href, function(data) {
            $('#taza_modal').html(data);
            $('#taza_modal').modal('show');
        });
    });
});


    </script>
@endsection