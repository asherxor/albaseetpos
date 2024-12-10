<div class="row">
  <div class="col-md-12">
   
    <h3>@lang( 'cctools::lang.custom_payments' )</h3>
    <table class="table table-condensed">
      <tr>
        <th>#</th>
		<th>@lang('cctools::lang.customers')</th>
        <th>@lang('cctools::lang.folio')</th>
		<th>@lang('cctools::lang.coments')</th>
		<th>@lang('cctools::lang.payment_type')</th>
		<th>@lang('cctools::lang.payment')</th>
      </tr>
      @foreach($details_cpay['details_custom_payment'] as $detail_cp)
        <tr>
          <td>
            {{$loop->iteration}}.
          </td>
		  <td>
		  {{$detail_cp->name_cnt}}
          </td>
		  <td>
		  {{$detail_cp->invoice}}
          </td>
		  <td>
		  {{$detail_cp->note}}
          </td>
			@php 
				$type_payment = $detail_cp->payment_m;
				$amount_pay = $detail_cp->amount_pay;			
			@endphp
		  <td>{{$payment_types[$type_payment]}}</td>
		  <td>@format_currency($amount_pay)</td>
        </tr>
      @endforeach


    </table>
  </div>
</div>
<!--MODIFICACIONES-->