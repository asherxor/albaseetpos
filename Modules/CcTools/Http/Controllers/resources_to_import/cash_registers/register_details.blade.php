<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">

    <div class="modal-header">
      <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h3 class="modal-title">@lang( 'cctools::lang.register_details' ) <br> {{ $register_details->user_name}} </h3>
	  <h5 class="modal-title">@lang( 'cctools::lang.open_date' ) {{ \Carbon::createFromFormat('Y-m-d H:i:s', $register_details->open_time)->format('j M, Y h:i A') }} </h5>
	  <h5 class="modal-title">@lang( 'cctools::lang.close_date' ) {{\Carbon::createFromFormat('Y-m-d H:i:s', $close_time)->format('j M, Y h:i A')}}</h5>
    </div>
	
    <div class="modal-body">
		@if(Auth::user()->can('cctools.view_cash_register_details_open') || $register_details->status == 'close')

			@can('cctools.view_cash_register_details')
	  			@include('cctools::cash_register.patchs')
			@endcan  
			  @php
					$total_in_denominations = 0;
				@endphp
			@if($register_details->declaration == 'declared')
				@if(!empty($register_details->denominations))
					<div class="row">
						<div class="col-md-12">
							<h3>@lang( 'cctools::lang.cash_denominations' )</h3>
							<table class="table table-condensed">
								<thead>
									<tr>
									<th width="45%" class="text-right">@lang('cctools::lang.denomination')</th>
									
									<th width="10%" class="text-center">@lang('cctools::lang.count')</th>
									<th width="45%" class="text-left">@lang('cctools::lang.subtotal')</th>
									</tr>
								</thead>
								<tbody>
									@foreach($register_details->denominations as $key => $value)
									<tr>
										<td class="text-right">@format_currency($key)</td>
										<td class="text-center">{{$value ?? 0}}</td>
										<td class="text-left">
											@format_currency($key * $value)
										</td>
									</tr>
									
									@php
									$total_in_denominations += ($key * $value);
									@endphp
									@endforeach
									
									<tr>
									<td colspan="2"></td>
									<td class="text-left">@format_currency($total_in_denominations)</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				@else
					@php $total_in_denominations = $register_details->closing_amount; @endphp
				@endif

			
				@if($register_details->status == 'close')
				<div class="row">
						<div class="col-md-12">
							<h3 class="text-center"> @lang( 'cctools::lang.bruto_total' ) = @format_currency($register_details->totals_of_all_sells);</h3>
							

							<table class="table table-condensed">
								<!--
								<tr>
									<th colspan="3" class="text-center">@lang('cctools::lang.refunds')</th>
								</tr>
								<tr>
									<td width="45%" class="text-right">@lang('cctools::lang.cash_dues')</td>
									<td></td>
									<td width="45%" class="text-left">@format_currency($register_details->total_cash_refund)</td>
								</tr>
								
								@for($x=1 ; $x<=7 ; $x++)
									@php
										$custom_pay_x = "custom_pay_".$x;
										$total_custom_pay_x_refund = "total_custom_pay_".$x."_refund";
										$register_details_total_x_refund = $register_details->$total_custom_pay_x_refund;
									@endphp
										<tr>
											<td width="45%" class="text-right">{{$payment_types[$custom_pay_x]}}</td>
												<td></td>
											<td width="45%" class="text-left">@format_currency($register_details_total_x_refund)</td>
										</tr>
								@endfor
								-->

								
								<tr>
									<th colspan="3" class="text-center">@lang('cctools::lang.all_others') -> @format_currency($register_details->total_all_other_pay)</th>
								</tr>
								
								<tr>
									<td width="45%" class="text-right">@lang('cctools::lang.advanced')</td>
										<td></td>
									<td width="45%" class="text-left">@format_currency($register_details->total_advance)</td>
								</tr>
								<tr>
									<td width="45%" class="text-right">@lang('cctools::lang.other')</td>
										<td></td>
									<td width="45%" class="text-left">@format_currency($register_details->total_other)</td>
								</tr>
								@for($x=1 ; $x<=7 ; $x++)
									@php
										$custom_pay_x = "custom_pay_".$x;
										$total_custom_x = "total_custom_pay_".$x;
										$register_details_total_x = $register_details->$total_custom_x;
									@endphp
									@if(array_key_exists($custom_pay_x, $payment_types) && $register_details_total_x != 0)
										<tr>
											<td width="45%" class="text-right">{{$payment_types[$custom_pay_x]}}</td>
												<td></td>
											<td width="45%" class="text-left">@format_currency($register_details_total_x)</td>
										</tr>
									@endif
								@endfor
								
								<!--EFECTIVO-->
								@if ($register_details->total_cash > 0)
									<tr>
										<th colspan="3" class="text-center">@lang('cctools::lang.sell_cash') -> @format_currency($register_details->total_cash)</th>
									</tr>
									<tr>
										<td width="45%" class="text-right">@lang('cctools::lang.cash_expenses')</td>
											<td></td>
										<td width="45%" class="text-left">@format_currency($register_details->total_cash_expense)</td>
									</tr>
									<tr>
										<td width="45%" class="text-right">@lang('cctools::lang.cash_in_box_sys')</td>
											<td></td>
										<td width="45%" class="text-left">@format_currency($register_details->cash_by_system)</td>
									</tr>
									<tr>
										<td width="45%" class="text-right">@lang('cctools::lang.cash_in_box_employ')</td>
											<td></td>
										<td width="45%" class="text-left">@format_currency($total_in_denominations)</td>
									</tr>
									@php $diferencia_efectivo =  $total_in_denominations - $register_details->cash_by_system; @endphp
									@if($diferencia_efectivo < 0)
										@php $texto_diferencia = __('cctools::lang.falta'); @endphp
									@elseif($diferencia_efectivo > 0)
										@php $texto_diferencia = __('cctools::lang.sobra'); @endphp
									@elseif($diferencia_efectivo == 0)
										@php $texto_diferencia = __('cctools::lang.correcto'); @endphp
									@endif
									<tr>
										<td width="45%" class="text-right">{{$texto_diferencia}} </td>
											<td></td>
										<td width="45%" class="text-left">@format_currency($diferencia_efectivo)</td>
									</tr>
								@endif
								


								<!--TARJETA-->
								@if ($register_details->total_card > 0)
									<tr>
										<th colspan="3" class="text-center">@lang('cctools::lang.sell_card') -> @format_currency($register_details->total_card)</th>
									</tr>
									<tr>
										<td width="45%" class="text-right">@lang('cctools::lang.card_in_box_sys')</td>
											<td></td>
										<td width="45%" class="text-left">@format_currency($register_details->total_card)</td>
									</tr>
									<tr>
										<td width="45%" class="text-right">@lang('cctools::lang.card_in_box_employ')</td>
											<td></td>
										<td width="45%" class="text-left">@format_currency($register_details->total_card_amount)</td>
									</tr>
									@php $diferencia_tarjeta =  $register_details->total_card_amount - $register_details->total_card; @endphp
									@if($diferencia_tarjeta < 0)
										@php $texto_diferencia = __('cctools::lang.falta'); @endphp
									@elseif($diferencia_tarjeta > 0)
										@php $texto_diferencia = __('cctools::lang.sobra'); @endphp
									@elseif($diferencia_tarjeta == 0)
										@php $texto_diferencia = __('cctools::lang.correcto'); @endphp
									@endif
									<tr>
										<td width="45%" class="text-right">{{$texto_diferencia}} </td><td></td>
										<td width="45%" class="text-left">@format_currency($diferencia_tarjeta)</td>
									</tr>
								@endif



								<!--Transferencias-->
								@if ($register_details->total_bank_transfer > 0)
									<tr>
										<th colspan="3" class="text-center">@lang('cctools::lang.sell_transfer') -> @format_currency($register_details->total_bank_transfer)</th>
									</tr>
									<tr>
										<td width="45%" class="text-right">@lang('cctools::lang.transfer_in_box_sys')</td>
											<td></td>
										<td width="45%" class="text-left">@format_currency($register_details->total_bank_transfer)</td>
									</tr>
									<tr>
										<td width="45%" class="text-right">@lang('cctools::lang.transfer_in_box_employ')</td>
											<td></td>
										<td width="45%" class="text-left">@format_currency($register_details->banks_transfers_amount)</td>
									</tr>
									@php $diferencia_transferencia =  $register_details->banks_transfers_amount - $register_details->total_bank_transfer; @endphp
									@if($diferencia_transferencia < 0)
										@php $texto_diferencia = __('cctools::lang.falta'); @endphp
									@elseif($diferencia_transferencia > 0)
										@php $texto_diferencia = __('cctools::lang.sobra'); @endphp
									@elseif($diferencia_transferencia == 0)
										@php $texto_diferencia = __('cctools::lang.correcto'); @endphp
									@endif
									<tr>
										<td width="45%" class="text-right">{{$texto_diferencia}} </td>
											<td></td>
										<td width="45%" class="text-left">@format_currency($diferencia_transferencia)</td>
									</tr>
								@endif

								<!--Cheques-->
								@if ($register_details->total_cheque > 0)
									<tr>
										<th colspan="3" class="text-center">@lang('cctools::lang.sell_cheque') -> @format_currency($register_details->total_cheque)</th>
									</tr>
									<tr>
										<td width="45%" class="text-right">@lang('cctools::lang.cheque_in_box_sys')</td>
											<td></td>
										<td width="45%" class="text-left">@format_currency($register_details->total_cheque)</td>
									</tr>
									<tr>
										<td width="45%" class="text-right">@lang('cctools::lang.cheque_in_box_employ')</td>
											<td></td>
										<td width="45%" class="text-left">@format_currency($register_details->total_cheque_amount)</td>
									</tr>
									@php $diferencia_cheque =  $register_details->total_cheque_amount - $register_details->total_cheque; @endphp
									@if($diferencia_transferencia < 0)
										@php $diferencia_cheque = __('cctools::lang.falta'); @endphp
									@elseif($diferencia_transferencia > 0)
										@php $diferencia_cheque = __('cctools::lang.sobra'); @endphp
									@elseif($diferencia_transferencia == 0)
										@php $diferencia_cheque = __('cctools::lang.correcto'); @endphp
									@endif
									<tr>
										<td width="45%" class="text-right">{{$diferencia_cheque}} </td>
											<td></td>
										<td width="45%" class="text-left">@format_currency($diferencia_transferencia)</td>
									</tr>
								@endif


								
								<!--FONDO-->
								<tr>
									<th colspan="3" class="text-center">@lang('cctools::lang.flow')</th>
								</tr>
								<tr>
									<td width="45%" class="text-right">@lang('cctools::lang.initial_flow')</td>
										<td></td>
									<td width="45%" class="text-left">@format_currency($register_details->fondo_initial)</td>
								</tr>
								<tr>
									<td width="45%" class="text-right">@lang('cctools::lang.final_flow')</td>
										<td></td>
									<td width="45%" class="text-left">@format_currency($register_details->fondo_final)</td>
								</tr>
								@php $diferencia_fondo =  $register_details->fondo_final - $register_details->fondo_initial; @endphp
								@if($diferencia_fondo < 0)
									@php $texto_diferencia = __('cctools::lang.falta'); @endphp
								@elseif($diferencia_fondo > 0)
									@php $texto_diferencia = __('cctools::lang.sobra'); @endphp
								@elseif($diferencia_fondo == 0)
									@php $texto_diferencia = __('cctools::lang.correcto'); @endphp
								@endif
								<tr>
									<td width="45%" class="text-right">{{$texto_diferencia}} </td>
										<td></td>
									<td width="45%" class="text-left">@format_currency($diferencia_fondo)</td>
								</tr>
								
								<!--DIFERENCIAS-->
								<tr>
									<th colspan="3" class="text-center">@lang('cctools::lang.discrepance')</th>
								</tr>
								@php 
									$diferencia_total = floatval($diferencia_efectivo) 
									+ floatval($diferencia_tarjeta) 
									+ floatval($diferencia_transferencia) 
									+ floatval($diferencia_cheque) 
									+ floatval($diferencia_fondo); 
								@endphp
								@if($diferencia_total < 0)
									@php $texto_diferencia = __('cctools::lang.falta'); @endphp
								@elseif($diferencia_total > 0)
									@php $texto_diferencia = __('cctools::lang.sobra'); @endphp
								@elseif($diferencia_total == 0)
									@php $texto_diferencia = __('cctools::lang.correcto'); @endphp
								@endif
								
								<tr>
									<td width="45%" class="text-right"><h3> {{$texto_diferencia}} </h3></td><td></td>
									<td width="45%" class="text-left"><h3>@format_currency($diferencia_total)</h3></td>
								</tr>
							</table>
						</div>	
				</div>
				@else
					<h3 class=text-center>@lang( 'cctools::lang.open_cash_reg' )</h3>
				@endif
			@else
				<h3 class=text-center>@lang( 'cctools::lang.not_declared_for_user' )</h3>
			@endif
      
			<div class="row">
				<div class="col-xs-4"><b>@lang('report.user'):</b> {{ $register_details->user_name}}<br></div>
				
				<div class="col-xs-4"><b>@lang('business.email'):</b> {{ $register_details->email}}<br></div>

				<div class="col-xs-4"><b>@lang('business.business_location'):</b> {{ $register_details->location_name}}<br></div>
				
			</div>
			<hr>
			<div class="row">
				@if(!empty($register_details->closing_note))
				<div class="col-xs-6">
					<strong>@lang('cash_register.closing_note'):</strong><br>
					{{$register_details->closing_note}}
				</div>
				@endif

				@if(!empty($register_details->personal_check))
				<div class="col-xs-6">
					<strong>@lang('cctools::lang.personal_check'):</strong><br>
					{{$register_details->personal_check}}
				</div>
				@endif
			</div>


		@else
			<h3 class=text-center>@lang( 'cctools::lang.operanding' )</h3>
		@endif
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-primary no-print" 
        aria-label="Print" 
          onclick="$(this).closest('div.modal').printThis();">
        <i class="fa fa-print"></i> @lang( 'messages.print' )
      </button>

      <button type="button" class="btn btn-default no-print" 
        data-dismiss="modal">@lang( 'messages.cancel' )
      </button>
    </div>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<style type="text/css">
  @media print {
    .modal {
        position: absolute;
        left: 0;
        top: 0;
        margin: 0;
        padding: 0;
        overflow: visible!important;
    }
}
</style>