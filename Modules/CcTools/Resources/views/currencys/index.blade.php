@extends('layouts.app')
@section('title', __('cctools::lang.currencys'))

@section('content')
@include('cctools::layouts.partials.nav')
    <section class="content-header">
        <h1>@lang( 'cctools::lang.currency_sets' )
            <small>@lang( 'cctools::lang.manage_your_currencys' )</small>
        </h1>
    </section>
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang( 'cctools::lang.all_your_currency' )</h3>
                @can('cctools.create_rated')
                    <div class="box-tools">
                        <button type="button" class="btn btn-block btn-primary btn-modal" 
                            data-href="{{route('create-currency')}}" 
                            data-container=".currency_modal">
                            <i class="fa fa-plus"></i> @lang( 'cctools::lang.add' )</button>
                    </div>
                @endcan
                
            </div>
            
            <div class="box-body">
                    <table class="table table-bordered table-striped" id="currency_table">
                        <thead>
                            <tr>
                                <th>@lang( 'cctools::lang.country' )</th>
                                <th>@lang( 'cctools::lang.currency' )</th>
                                <th>@lang( 'cctools::lang.code' )</th>
                                <th>@lang( 'cctools::lang.symbol' )</th>
                                <th>@lang( 'cctools::lang.thousand_separator' )</th>
                                <th>@lang( 'cctools::lang.decimal_separator' )</th>
                                <th>@lang( 'cctools::lang.action' )</th>
                            </tr>
                        </thead>
                    </table>
                </div>
        </div>
        <div class="modal fade currency_modal" tabindex="-1" role="dialog" 
    	    aria-labelledby="gridSystemModalLabel" id="currency_modal">
        </div>
    </section>

@endsection
@section('javascript')
    <script>

$(document).ready(function () {
    var currency_table = $('#currency_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/cctools/index-currencys',
        columns: [
            { data: 'country', name: 'country' },
            { data: 'currency', name: 'currency' },
            { data: 'code', name: 'code' },
            { data: 'symbol', name: 'symbol' },
            { data: 'thousand_separator', name: 'thousand_separator' },
            { data: 'decimal_separator', name: 'decimal_separator' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', 'button.delete_currency_button', function(){
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
                            currency_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        });
    });
});

    </script>
@endsection