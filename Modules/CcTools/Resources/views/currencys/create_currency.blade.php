<div class="modal-dialog" role="document">
  <div class="modal-content">



    {!! Form::open(['url' => route('store-currency'), 'method' => 'post', 'id' => 'currency_add_form' ]) !!}
          
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'cctools::lang.add_currency' )</h4>
    </div>

    <div class="modal-body">
      <div class="row">

        <!-- Campos del formulario -->
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('country', __( 'cctools::lang.country' ) . ':*') !!}
            {!! Form::text('country', null, ['class' => 'form-control', 'placeholder' => __( 'cctools::lang.country_placeholder' ) ]); !!}
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('currency', __( 'cctools::lang.currency' ) . ':*') !!}
            {!! Form::text('currency', null, ['class' => 'form-control', 'placeholder' => __( 'cctools::lang.currency_placeholder' ) ]); !!}
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('code', __( 'cctools::lang.code' ) . ':*') !!}
            {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => __( 'cctools::lang.code_placeholder' ) ]); !!}
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('symbol', __( 'cctools::lang.symbol' ) . ':*') !!}
            {!! Form::text('symbol', null, ['class' => 'form-control', 'placeholder' => __( 'cctools::lang.symbol_placeholder' ) ]); !!}
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('thousand_separator', __( 'cctools::lang.thousand_separator' ) . ':*') !!}
            {!! Form::text('thousand_separator', null, ['class' => 'form-control', 'placeholder' => __( 'cctools::lang.thousand_separator_placeholder' ) ]); !!}
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('decimal_separator', __( 'cctools::lang.decimal_separator' ) . ':*') !!}
            {!! Form::text('decimal_separator', null, ['class' => 'form-control', 'placeholder' => __( 'cctools::lang.decimal_separator_placeholder' ) ]); !!}
          </div>
        </div>

      </div>
    </div>

    <div class="modal-footer">
      <button type="submit"  class="btn btn-primary">@lang( 'cctools::lang.save' )</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

@section('scripts')
<script type="text/javascript">
  $(document).ready(function(){
    $('#currency_add_form').submit(function(e) {
      e.preventDefault(); // Evita el envío del formulario por defecto
      
      // Envía el formulario mediante AJAX
      $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: $(this).serialize(),
        success: function(response) {
          if(response.success) {
            // Cierra el modal después de guardar los datos
            $('#currency_modal').modal('hide');
            // Muestra una notificación de éxito
            toastr.success(response.msg);
          } else {
            // Muestra una notificación de error
            toastr.error(response.msg);
          }
        },
        error: function(xhr, status, error) {
          // Maneja los errores si es necesario
          toastr.error('An error occurred while processing the form.');
        }
      });
    });
  });
</script>
@endsection
