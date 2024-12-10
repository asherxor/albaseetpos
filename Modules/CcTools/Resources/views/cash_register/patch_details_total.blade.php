<div class="row">
  <div class="col-sm-12">

<table class="table table-condensed">
      <tr class="success">
        <th>
          @lang('cash_register.total_sales'):
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $details['transaction_details']->total_sales }}</span></b>
        </td>
      </tr>
	  
	  <tr>
        <td>
          @lang('cash_register.total_card'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_card}}</span>
        </td>
      </tr>
	  <tr>
        <td>
          @lang('cash_register.total_cash'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_sale - $register_details->total_card}}</span>
        </td>
      </tr>
      <tr class="danger">
        <th>
          @lang('cash_register.total_refund')
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_refund }}</span></b><br>
          <small>
          @if($register_details->total_cash_refund != 0)
            Cash: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cash_refund }}</span><br>
          @endif
          @if($register_details->total_cheque_refund != 0) 
            Cheque: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cheque_refund }}</span><br>
          @endif
          @if($register_details->total_card_refund != 0) 
            Card: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_card_refund }}</span><br> 
          @endif
          @if($register_details->total_bank_transfer_refund != 0)
            Bank Transfer: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_bank_transfer_refund }}</span><br>
          @endif
		  
          @if(array_key_exists('custom_pay_1', $payment_types) && $register_details->total_custom_pay_1_refund != 0)
              {{$payment_types['custom_pay_1']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_1_refund }}</span>
          @endif
          @if(array_key_exists('custom_pay_2', $payment_types) && $register_details->total_custom_pay_2_refund != 0)
              {{$payment_types['custom_pay_2']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_2_refund }}</span>
          @endif
          @if(array_key_exists('custom_pay_3', $payment_types) && $register_details->total_custom_pay_3_refund != 0)
              {{$payment_types['custom_pay_3']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_3_refund }}</span>
          @endif
          @if($register_details->total_other_refund != 0)
            Other: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_other_refund }}</span>
          @endif
          </small>
        </td>
      </tr>
	  <tr class="danger">
        <th>
          @lang('cash_register.total_expense'):
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_expense }}</span></b>
        </td>
      </tr>
      <tr class="danger">
        <th>
          @lang('cash_register.cash_phantom'):
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $details['transaction_details']->total_sales - $register_details->total_sale }}</span></b>
        </td>
      </tr>
      <tr class="success">
        <th>
          @lang('cash_register.cash_with')
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $register_details->cash_in_hand + $register_details->total_cash - $register_details->total_cash_refund }}</span></b>
        </td>
      </tr>
	  <tr>
        <td>
          @lang('cash_register.cash_in_hand'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->cash_in_hand }}</span>
        </td>
      </tr>
	  
      <tr>
        <td>
          @lang('cash_register.total_cash_disc_expense'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_sale - $register_details->total_card  -$register_details->total_expense}}</span>
        </td>
      </tr>
      
    </table>
	
	</div>
</div>
