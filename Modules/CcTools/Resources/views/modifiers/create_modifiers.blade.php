<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => route('store_modifier'), 'method' => 'post', 'id' => 'table_add_form' ]) !!}
          
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'cctools::lang.add_modifier' )</h4>
    </div>

    <div class="modal-body">
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <h4>@lang( 'cctools::lang.modifiers_group_name' )</h4>
            <input type='text' name='grupo' class='form-control' placeholder='Nombre del grupo' required>
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
            {!! Form::label('locations', __( 'cctools::lang.select_locations' ) . ':') !!}
            <i class="fa fa-info-circle" data-toggle="tooltip" title="@lang('cctools::lang.tooltip_select_locations')" style="margin-left: 5px;"></i>
            <select name="locations[]" id="locations" class="form-control" multiple>
              @foreach($locationsEnabled as $location)
                <option value="{{ $location->id }}">{{ $location->name }}</option>
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
      appendTo: "#table_add_form",
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
