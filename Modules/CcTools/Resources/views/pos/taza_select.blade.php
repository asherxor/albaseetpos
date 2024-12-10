				<td>
						<b>@lang('CcTools::lang.rate')(+): @show_tooltip(__('CcTools::lang.rate_tool'))</b> 
								
						<select class="select2" name="taza_id" id="taza_id" class="form-control">
							<option value="1.00">{{ __('CcTools::lang.select_rate') }}</option>
							@foreach($tazas as $taza)
								<option value="{{ $taza->value }}">{{ $taza->alias }} - {{ $taza->value }}</option>
							@endforeach
						</select>
						<input type="hidden" name="taza_value" id="taza_value" value="1.00">
			
				</td>