<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    {!! Form::open(['url' => action([\App\Http\Controllers\CashRegisterController::class, 'postCloseRegister']), 'method' => 'post' ]) !!}

    {!! Form::hidden('user_id', $register_details->user_id) !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <h3 class="modal-title">
        @lang('cash_register.current_register') ( {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $register_details->open_time)->format('jS M, Y h:i A') }} - {{ \Carbon\Carbon::now()->format('jS M, Y h:i A') }})
      </h3>
    </div>
    <div class="modal-body">
    @if(Auth::user()->can('cctools.view_cash_register_details'))
      @include('cctools::cash_register.patchs')
      <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
          {!! Form::label('declaration', __( 'cctools::lang.declaration_permitions' )) !!}
          {!! Form::select('declaration', [
            'declared' => __('cctools::lang.declared'), 
            'not_declared' => __('cctools::lang.not_declared')
          ], 'not_declared', ['id' => 'declaration', 'class' => 'form-control']) !!}
          <small>@lang('cctools::lang.declaration_instructions')</small>
        </div>
      </div>
    @else 
      {!! Form::hidden('declaration', 'declared', ['id' => 'declaration']) !!}
    @endif 
    
      <div class="row">
        <div class="col-sm-1"></div>
        <!-- Columna para el monto de cierre en efectivo -->
        <div class="col-sm-3">
          @if(empty($pos_settings['cash_denominations']))
              <div class="form-group">
                {!! Form::label('closing_amount', __( 'cash_register.total_cash' ) . ':*') !!}
                {!! Form::text('closing_amount', @num_format(0), ['class' => 'form-control input_number', 'required', 'placeholder' => __( 'cash_register.total_cash' ) ]) !!}
              </div>
          @endif
        </div>
        <!-- Columna para el fondo final -->
        <div class="col-sm-7">
          <div class="form-group">
            {!! Form::label('fondo_final', __( 'cctools::lang.total_fondo' ) . ':*') !!}
            @show_tooltip(__('cctools::lang.total_fondo_tooltip'))
            {!! Form::number('fondo_final', @num_format(0), ['class' => 'form-control', 'required', 'placeholder' => __( 'cctools::lang.total_fondo' ), 'min' => 0 ]) !!}
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-1"></div>
        <!-- Columna para las transferencias bancarias -->
        <div class="col-sm-3">
          <div class="form-group">
            {!! Form::label('banks_transfers', __( 'cctools::lang.banks_transfers' ) . ':*') !!}
            @show_tooltip(__('cctools::lang.banks_transfers_tooltip'))
            {!! Form::number('banks_transfers', '0', ['class' => 'form-control', 'required', 'placeholder' => __( 'cctools::lang.banks_transfers' ), 'min' => 0 ]) !!}
          </div>
        </div>
        <!-- Columna para las transferencias bancarias -->
        <div class="col-sm-7">
          <div class="form-group">
            {!! Form::label('banks_transfers_amount', __( 'cctools::lang.banks_transfers_amount' ) . ':*') !!}
            @show_tooltip(__('cctools::lang.banks_transfers_amount_tooltip'))
            {!! Form::number('banks_transfers_amount', '0', ['class' => 'form-control', 'required', 'placeholder' => __( 'cctools::lang.banks_transfers_amount' ), 'min' => 0 ]) !!}
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-1"></div>
        <!-- Columna para los slips de tarjeta -->
        <div class="col-sm-3">
          <div class="form-group">
            {!! Form::label('total_card_slips', __( 'cctools::lang.total_card_slips' ) . ':*') !!}
            @show_tooltip(__('cctools::lang.total_card_slips_tooltip'))
            {!! Form::number('total_card_slips', '0', ['class' => 'form-control', 'required', 'placeholder' => __( 'cctools::lang.total_card_slips' ), 'min' => 0 ]) !!}
          </div>
        </div>

        <!-- Columna para el monto total de tarjeta -->
        <div class="col-sm-7">
          <div class="form-group">
            {!! Form::label('total_card_amount', __( 'cctools::lang.total_card' ) . ':*') !!}
            @show_tooltip(__('cctools::lang.total_card_tooltip'))
            {!! Form::text('total_card_amount', @num_format(0), ['class' => 'form-control input_number', 'required', 'placeholder' => __( 'cctools::lang.total_card' )]) !!}
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-1"></div>
        <!-- Columna para los cheques -->
        <div class="col-sm-3">
          <div class="form-group">
            {!! Form::label('total_cheques', __( 'cctools::lang.total_cheques' ) . ':*') !!}
            @show_tooltip(__('cctools::lang.total_cheques_tooltip'))
            {!! Form::number('total_cheques', '0', ['class' => 'form-control', 'required', 'placeholder' => __( 'cctools::lang.total_cheques' ), 'min' => 0 ]) !!}
          </div>
        </div>
        <!-- Columna para el monto total de cheques -->
        <div class="col-sm-7">
          <div class="form-group">
            {!! Form::label('total_cheque_amount', __( 'cctools::lang.total_cheques_amount' ) . ':*') !!}
            @show_tooltip(__('cctools::lang.total_cheques_amount_tooltip'))
            {!! Form::number('total_cheque_amount', '0', ['class' => 'form-control', 'required', 'placeholder' => __( 'cctools::lang.total_cheques_amount' ), 'min' => 0 ]) !!}
          </div>
        </div>
      </div>

      <div class="row">
      <div class="col-sm-1"></div>
        <!-- Columna para el pago de otros métodos -->
        <div class="col-sm-3">
          <div class="form-group">
            {!! Form::label('total_other_payment_mod', __( 'cctools::lang.total_other_payment' ) . ':*') !!}
            @show_tooltip(__('cctools::lang.total_other_payment_tooltip'))
            {!! Form::number('total_other_payment_mod', '0', ['class' => 'form-control', 'required', 'placeholder' => __( 'cctools::lang.total_other_payment' ), 'min' => 0 ]) !!}
          </div>
        </div>

        <!-- Columna para el monto total de otros pagos -->
        <div class="col-sm-7">
          <div class="form-group">
            {!! Form::label('total_other_payment_amount', __( 'cctools::lang.total_other_payment_amount' ) . ':*') !!}
            @show_tooltip(__('cctools::lang.total_other_payment_amount_tooltip'))
            {!! Form::number('total_other_payment_amount', '0', ['class' => 'form-control', 'required', 'placeholder' => __( 'cctools::lang.total_other_payment_amount' ), 'min' => 0 ]) !!}
          </div>
        </div>
      </div>

      <!-- Sección para las denominaciones de efectivo -->
      <div class="row">
        <div class="col-md-12">
          <h3>@lang('cctools::lang.cash_denominations')</h3>
          @if(!empty($pos_settings['cash_denominations']))
            <table class="table table-slim">
              <thead>
                <tr>
                  <th width="20%" class="text-right">@lang('lang_v1.denomination')</th>
                  <th width="20%">&nbsp;</th>
                  <th width="20%" class="text-center">@lang('lang_v1.count')</th>
                  <th width="20%">&nbsp;</th>
                  <th width="20%" class="text-left">@lang('sale.subtotal')</th>
                </tr>
              </thead>
              <tbody>
                @foreach(explode(',', $pos_settings['cash_denominations']) as $dnm)
                <tr>
                  <td class="text-right">{{ $dnm }}</td>
                  <td class="text-center">X</td>
                  <td>{!! Form::number("denominations[$dnm]", null, ['class' => 'form-control cash_denomination input-sm', 'min' => 0, 'data-denomination' => $dnm, 'style' => 'width: 100px; margin:auto;' ]) !!}</td>
                  <td class="text-center">=</td>
                  <td class="text-left">
                    <span class="denomination_subtotal">0</span>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="4" class="text-center">@lang('sale.total')</th>
                  <td><span class="denomination_total">0</span></td>
                </tr>
              </tfoot>
            </table>
          @else
            <p class="help-block">@lang('lang_v1.denomination_add_help_text')</p>
          @endif
        </div>
      </div>

      <div class="row">
        <!-- Nota de cierre -->
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('closing_note', __( 'cash_register.closing_note' ) . ':') !!}
            {!! Form::textarea('closing_note', null, ['class' => 'form-control', 'placeholder' => __( 'cash_register.closing_note' ), 'rows' => 3 ]) !!}
          </div>
        </div>

        <!-- Cheque personal -->
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('personal_check', __( 'cctools::lang.personal_check' ) . ':') !!}
            {!! Form::textarea('personal_check', null, ['class' => 'form-control', 'placeholder' => __( 'cctools::lang.personal_check' ), 'rows' => 3 ]) !!}
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Información del usuario y notas -->
        <div class="col-md-6">
          <b>@lang('report.user'):</b> {{ $register_details->user_name }}<br>
          <b>@lang('business.email'):</b> {{ $register_details->email }}<br>
          <b>@lang('business.business_location'):</b> {{ $register_details->location_name }}<br>
        </div>

        @if(!empty($register_details->closing_note))
          <div class="col-md-6">
            <strong>@lang('cash_register.closing_note'):</strong><br>
            {{ $register_details->closing_note }}
          </div>
        @endif

        @if(!empty($register_details->personal_check))
          <div class="col-md-6">
            <strong>@lang('cctools::lang.personal_check'):</strong><br>
            {{ $register_details->personal_check }}
          </div>
        @endif
      </div>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.cancel')</button>
      <button type="submit" class="btn btn-primary">@lang('cash_register.close_register')</button>
    </div>

    {!! Form::close() !!}
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
