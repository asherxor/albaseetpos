<div class="row">
  <div class="col-md-12">
    <h3>@lang( 'cctools::lang.list_expenses' )</h3>
    <table class="table table-condensed">
      <tr>
        <th>#</th>
        <th>@lang('cctools::lang.categories')</th>
        <th>@lang('cctools::lang.description')</th>
        <th>@lang('cctools::lang.total_amount')</th>
      </tr>
      @foreach($details_expense['expense_details_by_category'] as $detail_exp)
        <tr>
          <td>
            {{$loop->iteration}}.
          </td>
          <td>
            {{$detail_exp->expense_name}}
          </td>
		  
		  <td>
			{{$detail_exp->expenses_note}}
          </td>
		  
		  <td>
			@format_currency($detail_exp->total_expenses)
          </td>
        </tr>
      @endforeach


    </table>
  </div>
</div>
<!--MODIFICACIONES-->
