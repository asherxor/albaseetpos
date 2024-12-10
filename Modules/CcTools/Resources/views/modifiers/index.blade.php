@extends('layouts.app')
@section('title', __('cctools::lang.modifiers'))

@section('content')
@include('cctools::layouts.partials.nav')
@if(auth()->user()->can('cctools.modifiers_view'))
    <section class="content-header">
        <h1>@lang( 'cctools::lang.modifier_sets' )
            <small>@lang( 'cctools::lang.manage_your_modifiers' )</small>
        </h1>
    </section>
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang( 'cctools::lang.all_your_modifiers' )</h3>
                @can('cctools.modifiers_create')
                    <div class="box-tools">
                        <button type="button" class="btn btn-block btn-primary btn-modal" 
                            data-href="{{route('create_modifier')}}" 
                            data-container=".modifier_modal">
                            <i class="fa fa-plus"></i> @lang( 'cctools::lang.add' )</button>
                    </div>
                @endcan
                
            </div>
            
            <div class="box-body">
                    <table class="table table-bordered table-striped" id="modifier_table">
                        <thead>
                            <tr>
                                <th>@lang( 'cctools::lang.modifier_sets' )</th>
                                <th>@lang( 'cctools::lang.modifiers' )</th>
                                <th>@lang( 'cctools::lang.locations' )</th>
                                <th>@lang( 'cctools::lang.products' )</th>
                                <th>@lang( 'cctools::lang.action' )</th>
                            </tr>
                        </thead>
                    </table>
                </div>
        </div>
        <div class="modal fade modifier_modal" tabindex="-1" role="dialog" 
    	    aria-labelledby="gridSystemModalLabel">
        </div>
        <div class="modal fade update_modal" tabindex="-1" role="dialog" 
    	    aria-labelledby="gridSystemModalLabel">
        </div>
        <div class="modal fade clone_modal" tabindex="-1" role="dialog" 
    	    aria-labelledby="gridSystemModalLabel">
        </div>

        @include('product.partials.edit_product_location_modal')
    </section>
    @else
    <div class="alert alert-danger">
        <h4><i class="icon fa fa-ban"></i> @lang('cctools::lang.unauthorized')</h4>
        @lang('cctools::lang.you_are_not_authorized')
    </div>
    @endif

@endsection
@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click', 'button.remove-modifier-row', function(e){
            $(this).closest('tr').remove();
        });

        $(document).on('submit', 'form#table_add_form', function(e){
            e.preventDefault();
            var data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: $(this).attr("action"),
                dataType: "json",
                data: data,
                success: function(result){
                    if(result.success == true){
                        $('div.modifier_modal').modal('hide');
                        toastr.success(result.msg);
                        modifier_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                }
            });
        });

        // Inicialización de la tabla DataTable
        var modifier_table = $('#modifier_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/cctools/index-modifier',
            columnDefs: [{
                "targets": [1, 2, 3],
                "orderable": false,
                "searchable": false,
            }],
            columns: [
                { data: 'name', name: 'name' },
                { data: 'variations', name: 'variations' },
                {
                    data: 'product_locations',
                    name: 'product_locations',
                    render: function(data, type, full, meta) {
                        var locations = full.product_locations.map(function(location) {
                            return location.name;
                        }).join(', ');
                        return locations;
                    }
                },
                { data: 'modifier_products', name: 'modifier_products' },
                { data: 'action', name: 'action' }
            ],
        });

        $(document).on('click', 'button.edit_modifier_button', function(){
            $("div.modifier_modal").load($(this).data('href'), function(){
                $(this).modal('show');

                $('form#edit_form').submit(function(e){
                    e.preventDefault();
                    var data = $(this).serialize();

                    $.ajax({
                        method: "POST",
                        url: $(this).attr("action"),
                        dataType: "json",
                        data: data,
                        success: function(result){
                            if(result.success == true){
                                $('div.update_modal').modal('hide');
                                toastr.success(result.msg);
                                modifier_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                });
            });
        });

        $(document).on('click', 'button.clone_modifier_button', function(){
            $("div.clone_modal").load($(this).data('href'), function(){
                $(this).modal('show');

                $('form#clone_add_form').submit(function(e){
                    e.preventDefault();
                    var data = $(this).serialize();

                    $.ajax({
                        method: "POST",
                        url: $(this).attr("action"),
                        dataType: "json",
                        data: data,
                        success: function(result){
                            if(result.success == true){
                                $('div.clone_modal').modal('hide');
                                toastr.success(result.msg);
                                modifier_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                });
            });
        });

        $(document).on('click', 'button.update_modifier_button', function(){
            $("div.update_modal").load($(this).data('href'), function(){
                $(this).modal('show');

                $('form#update_add_form').submit(function(e){
                    e.preventDefault();
                    var data = $(this).serialize();

                    $.ajax({
                        method: "POST",
                        url: $(this).attr("action"),
                        dataType: "json",
                        data: data,
                        success: function(result){
                            if(result.success == true){
                                $('div.update_modal').modal('hide');
                                toastr.success(result.msg);
                                modifier_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                });
            });
        });

        $(document).on('click', 'button.delete_modifier_button', function(){
            swal({
                title: LANG.sure,
                text: LANG.confirm_delete_table,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).data('href');
                    var data = $(this).serialize();

                    $.ajax({
                        method: "DELETE",
                        url: href,
                        dataType: "json",
                        data: data,
                        success: function(result){
                            if(result.success == true){
                                toastr.success(result.msg);
                                modifier_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', 'button.add-modifier-row', function(){
            $('table#add-modifier-table').append($(this).data('html'));
        });

        $(document).on('click', 'button.remove_modifier_product', function(){
            swal({
                title: LANG.sure,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $(this).closest('tr').remove();
                }
            });
        });

        // Maneja el clic en el botón de estado del modificador
        $(document).on('click', '.status_modifier_button', function() {
            var button = $(this);
            var href = button.data('href');

            // Realiza una solicitud AJAX para actualizar el estado del modificador
            $.ajax({
                url: href,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.msg); // Muestra un mensaje de éxito
                        modifier_table.ajax.reload(); // Recarga la tabla de datos
                    } else {
                        toastr.error(response.msg); // Muestra un mensaje de error
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Error al procesar la solicitud'); // Muestra un mensaje de error genérico
                }
            });
        });
    });
</script>

@endsection