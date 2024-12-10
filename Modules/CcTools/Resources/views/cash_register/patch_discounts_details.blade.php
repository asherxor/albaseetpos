<div class="row">
  <div class="col-md-12">
   
    <h3>@lang( 'cctools::lang.total_discount' )</h3>
    <table class="table table-condensed">
      <tr>
        <th>#</th>
		<th>@lang('cctools::lang.customers')</th>
        <th>@lang('cctools::lang.folio')</th>
        <th>@lang('cctools::lang.total_amount')</th>
		<th>@lang('cctools::lang.coments')</th>
      </tr>
      @foreach($details_discounts['discount_details_by_category'] as $detail_disc)
        <tr>
          <td>
            {{$loop->iteration}}.
          </td>
		  <td>
		  {{$detail_disc->name_cnt}}
          </td>
		  <td>
		  {{$detail_disc->invoice}}
          </td>
          <td>
            @format_currency($detail_disc->total_discounts)
          </td>
		  <td>
		  {{$detail_disc->note}}
          </td>
        </tr>
      @endforeach


    </table>
  </div>
</div>
<!--MODIFICACIONES-->