<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => route('store_modifier'), 'method' => 'post', 'id' => 'clone_add_form' ]) !!}
          
    <div class="modal-header">
      <button type="button"  class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'cctools::lang.clone_modifier' )</h4>
    </div>

    <div class="modal-body">
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <h4>@lang( 'cctools::lang.modifiers_group_name' )</h4>
            <input value='{{ $product->name}}',type='text' name='grupo' class='form-control' placeholder='Nombre del grupo' required>
          </div>
        </div>
        
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('name', __( 'cctools::lang.modifier_set_items' ) . ':*') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __( 'cctools::lang.search_product_placeholder' ), 'id' => 'search_product' ]) !!}
          </div>
        </div>
        
        <div class="col-sm-12">
            <div class="form-group">
                {!! Form::label('locations', __('cctools::lang.select_locations') . ':') !!}
                <i class="fa fa-info-circle" data-toggle="tooltip" title="@lang('cctools::lang.tooltip_select_locations')" style="margin-left: 5px;"></i>
                <select name="locations[]" id="locations" class="form-control" multiple>
                    {{-- Iterar sobre todas las ubicaciones y marcar las utilizadas como seleccionadas --}}
                    @foreach($locationsEnabled as $location)
                        <option value="{{ $location->id }}" {{ in_array($location->id, $locationsUsed) ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>


        
        <div class="col-sm-12">
          <h4>@lang( 'cctools::lang.modifiers' )</h4>
        </div>
               
        <div class="col-sm-12">
          <table class="table table-condensed" id="add-modifier-table">
            <thead>
              <tr>
                <th>@lang( 'cctools::lang.modifier')</th>
                <th>@lang( 'cctools::lang.product')</th>
                <th>@lang( 'cctools::lang.pieces')</th>
                <th>@lang( 'cctools::lang.price')</th>
                 </tr>
            </thead>
            <tbody>
            @foreach($arrayVars as $var)
            <tr>  
              <td>
                  <div class='form-group'>
                      <input type='text' name='modifier_name[]' value="{{ $var['alias'] }}"
                      class='form-control' 
                      placeholder= {{ __('cctools::lang.product_name') }} required>
                  </div>
              </td>

            <td> 
            {!! $var['nombre_pv'] !!}
            
              <input type="hidden" name="modifier_product[]" value="{{ $var['id_var'] }}">
            </td>

              
            <td>
              <div class='form-group'>
                      <input type='text' name='modifier_pieces[]' 
                      class='form-control input_number' value="{{ $var['pieces'] }}"
                      placeholder= {{ __('cctools::lang.pieces') }} required>
                  </div>
              </td>
              <td> 
                {{ $var['unity'] }}
              </td>
            <td>
                  <div class='form-group'>
                      <input type='text' name='modifier_price[]' value="{{ $var['price'] }}" class='form-control input_number' 
                      placeholder= {{ __('cctools::lang.price') }} required>
                </div>
              </td>	
              <td>
                  <label for="colorSelect">{{ __('cctools::lang.color_select') }}</label>
                  <select id="colorSelect" name="colorSelect[]">
                      <option value="{{ $var['color'] }}" style="background-color: {{ $var['color'] }}; color: white;">
                          {{ __('cctools::lang.select_color') }}:{{ __('cctools::lang.' . $var['color']) }}
                      </option>
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
            @endforeach
            
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'cctools::lang.save' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'cctools::lang.close' )</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script type="text/javascript">
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();

    $( "#search_product" ).autocomplete({
      source: function(request, response) {
        $.getJSON("/cctools/product-to-modifier", { term: request.term }, response);
      },
      minLength: 2,
      appendTo: "#clone_add_form",
      response: function(event,ui) {
        if (ui.content.length == 1)
        {
          ui.item = ui.content[0];
        } else if (ui.content.length == 0) {
          swal(LANG.no_products_found)
              .then((value) => {
            $('input#search_product').select();
          });
        }
      },
      select: function( event, ui ) {
        add_product_row(ui.item.id);
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      var string =  "<div>" + item.name + "-" + item.variation_name;
      string += ' -[' + item.id + ']' + "</div>";
      return $( "<li>" ).append(string).appendTo( ul );
    };
  });

  function add_product_row(product_id){
    $.ajax({
      method: "GET",
      url: '/cctools/modifier-row/' + product_id,
      dataType: "html",
      success: function(result){
        $('table#add-modifier-table').append(result);
      }
    });
  }
</script>
