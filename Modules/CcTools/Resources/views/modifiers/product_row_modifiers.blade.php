<tr>

	<td>
        <div class='form-group'>
            <input type='text' name='modifier_name[]' value="{{$product->name}}-{{$variation->name}} "
            class='form-control' 
            placeholder= {{ __('cctools::lang.product_name') }} required>
        </div>
    </td>

	<td> 
    {{$product->name}} ({{$product->sku}})<br>
    {{$variation->name}} ({{$variation->sub_sku}})<br>
   
		<input type="hidden" name="modifier_product[]" value="{{$variation->id}}">
	</td>

		
	<td>
		<div class='form-group'>
            <input type='text' name='modifier_pieces[]' 
            class='form-control input_number'
            placeholder= {{ __('cctools::lang.pieces') }} required>
        </div>
    </td>
    <td> 
    {{$unity->short_name}} <br>
    </td>
	<td>
        <div class='form-group'>
            <input type='text' name='modifier_price[]' class='form-control input_number' 
            placeholder= {{ __('cctools::lang.price') }} required>
  		</div>
    </td>	
    <td>
        <label for="colorSelect">{{ __('cctools::lang.color_select') }}</label>
        <select id="colorSelect" name="colorSelect[]">
            <option value="default">{{ __('cctools::lang.default_color') }}</option>
            <option value="black" style="background-color: black; color: white;">{{ __('cctools::lang.black') }}</option>
            <option value="white" style="background-color: white; color: black;">{{ __('cctools::lang.white') }}</option>
            <option value="red" style="background-color: red; color: white;">{{ __('cctools::lang.red') }}</option>
            <option value="green" style="background-color: green; color: white;">{{ __('cctools::lang.green') }}</option>
            <option value="blue" style="background-color: blue; color: white;">{{ __('cctools::lang.blue') }}</option>
            <option value="yellow" style="background-color: yellow; color: black;">{{ __('cctools::lang.yellow') }}</option>
            <option value="orange" style="background-color: orange; color: white;">{{ __('cctools::lang.orange') }}</option>
            <option value="purple" style="background-color: purple; color: white;">{{ __('cctools::lang.purple') }}</option>
            <option value="brown" style="background-color: brown; color: white;">{{ __('cctools::lang.brown') }}</option>
            <option value="gray" style="background-color: gray; color: white;">{{ __('cctools::lang.gray') }}</option>

        </select>
    </td>
	<td>
		<button type="button" class="btn btn-danger btn-xs remove_modifier_product"><i class="fa fa-times"></i></button>
	</td>
    
</tr>