<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => route('store-taza'), 'method' => 'post', 'id' => 'table_add_form' ]) !!}
          
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'cctools::lang.add_tazas' )</h4>
    </div>

    <div class="modal-body">
      <div class="row">

        

        <!--//formulario de busqueda avanzada-->
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('name', __( 'cctools::lang.taza_set_items' ) . ':*') !!}
            {!! Form::text('name', null, ['class' => 'form-control',
            'placeholder' => __( 'cctools::lang.search_currency' ), 
            'id' => 'search_product' ]) !!}
          </div>
        </div>
        <div class="col-sm-12">
          <h4>@lang( 'cctools::lang.tazas' )</h4>
        </div>


        <div class="col-sm-12">
          <table class="table table-condensed" id="add-modifier-table">
            <thead>
              <tr>
              <th>@lang( 'cctools::lang.alias')</th>
				        <th>@lang( 'cctools::lang.currency_data')</th>
                <th>@lang( 'cctools::lang.qty_taza')</th>
				
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
    $( "#search_product" ).autocomplete({
      source: function(request, response) {
        $.getJSON("/cctools/product-to-currency", { term: request.term }, response);
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
    var string =  "<div>" + item.country + "-" + item.currency;
    string += ' -[' + item.id + ']' + "</div>";
    return $( "<li>" ).append(string).appendTo( ul );
  };
});

function add_product_row(product_id){
  $.ajax({
    method: "GET",
    url: '/cctools/currency-row/' + product_id,
    dataType: "html",
    success: function(result){
      $('table#add-modifier-table').append(result);
    }
  });
}
</script>