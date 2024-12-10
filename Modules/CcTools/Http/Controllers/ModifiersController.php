<?php

namespace Modules\CcTools\Http\Controllers;

use Modules\CcTools\Entities\ProductLocation;
use App\Product;
use App\Variation;
use App\Utils\ProductUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\VariationValueTemplate;
use App\VariationTemplate;
use App\ProductVariation;
use App\Media;
use App\Unit;
use Illuminate\Support\Facades\Gate;
use App\BusinessLocation;
use App\TransactionSellLine;

class ModifiersController extends Controller
{

    protected $productUtil;

    
    public function __construct(ProductUtil $productUtil)
    {
        $this->productUtil = $productUtil;
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('cctools::modifiers.index');
    }

    public function index_modifiers()
    {

        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id);

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $location_id = request()->get('location_id', null);

            // Obtiene el conjunto de modificadores con sus relaciones cargadas
            $modifier_set = Product::where('business_id', $business_id)
                            ->where('type', 'modifier')
                            ->with(['variations', 'modifier_products', 'product_locations'])
                            ->get();

            return \Datatables::of($modifier_set)
                ->addColumn('locations', function ($row) {
                    $locations = [];
                    foreach ($row->product_locations as $productLocation) {
                        if ($productLocation->location) {
                            $locations[] = $productLocation->location->name;
                        }
                    }
                    return implode(', ', $locations);
                })
                ->addColumn('action', function ($row) {
                    $actionButtons = '';
                    
                    // Verifica si el usuario tiene permiso para actualizar productos
                    if (Gate::allows('cctools.modifiers_edit')) {
                        $actionButtons .= '<button type="button" data-href="' . action('App\Http\Controllers\Restaurant\ProductModifierSetController@edit', [$row->id]) . '" class="btn btn-xs btn-info edit_modifier_button" data-container=".modifier_modal"><i class="fa fa-cubes"></i> ' . __('restaurant.manage_products') . '</button> ';
                    }
                    
                    // Verifica si el usuario tiene permiso para actualizar productos
                    if (Gate::allows('cctools.modifiers_create')) {
                        $actionButtons .= '<button data-href="' . action('Modules\CcTools\Http\Controllers\ModifiersController@clone', [$row->id]) . '" class="btn btn-xs btn-info clone_modifier_button"><i class="glyphicon glyphicon-duplicate"></i> ' . __('cctools::lang.clone') . '</button> ';
                    }

                    if (Gate::allows('cctools.modifiers_edit')) {
                        $actionButtons .= '<button data-href="' . action('Modules\CcTools\Http\Controllers\ModifiersController@edit', [$row->id]) . '" class="btn btn-xs btn-info update_modifier_button"><i class="glyphicon glyphicon-cog"></i> ' . __('cctools::lang.edit') . '</button> ';
                    }
                    
                    // Verifica si el usuario tiene permiso para eliminar productos
                    if (Gate::allows('cctools.modifiers_delete')) {
                        $actionButtons .= '<button data-href="' . action('Modules\CcTools\Http\Controllers\ModifiersController@destroy_modifier', [$row->id]) . '" class="btn btn-xs btn-danger delete_modifier_button"><i class="glyphicon glyphicon-trash"></i> ' . __('messages.delete') . '</button> ';
                    }

                    // Accede al 'status_modifier' de la primera variación
                    $statusModifier = $row->status_modifier ?? 'disabled';
                    // Añade un botón si 'status_modifier' está habilitado
                    if ($statusModifier == "enabled"){
                        $actionButtons .= '<button data-href="' . action('Modules\CcTools\Http\Controllers\ModifiersController@update_status', [$row->id]) . '" class="btn btn-xs btn-success status_modifier_button"><i class="fa fa-cubes"></i> ' . __('cctools::lang.enabled') . '</button> ';
                    }elseif ($statusModifier == "disabled"){
                        $actionButtons .= '<button data-href="' . action('Modules\CcTools\Http\Controllers\ModifiersController@update_status', [$row->id]) . '" class="btn btn-xs btn-secondary status_modifier_button"><i class="fa fa-cubes"></i> ' . __('cctools::lang.disabled') . '</button> '; 
                    }
                    
                    return $actionButtons;
                })
                
                ->editColumn('modifier_products', function ($row) {
                    $products = [];
                    foreach ($row->modifier_products as $product) {
                        $products[] = $product->name;
                    }
                    return implode(', ', $products);
                })
                ->editColumn('variations', function ($row) {
                    // Arreglo de colores considerados como claros
                    $lightColors = ['default', 'white', 'yellow'];
                    
                    // Definir el estilo de texto basado en si el color de fondo es claro u oscuro
                    $formattedModifiers = [];
                    foreach ($row->variations as $vars) {
                        $color = $vars->color_button_modifier; // Obtener el color del modificador
                        $buttonStyle = 'background-color:' . $color . ';'; // Estilo de fondo del botón
                        // Establecer el color del texto según el tipo de color de fondo
                        $textColor = in_array($color, $lightColors) ? 'color:black;' : 'color:white;';
                        $buttonStyle .= $textColor; // Agregar estilo de texto al botón
                        $formattedModifier = '<button type="button" class="btn btn-xs" style="' . $buttonStyle . '">' . $vars->name . '</button>';
                        $formattedModifiers[] = $formattedModifier;
                    }
                    return implode(' ', $formattedModifiers);
                })
                
                ->removeColumn('id')
                ->escapeColumns(['action'])
                ->make(true);
        }

        return view('cctools::modifiers.index')
        ->with(compact('business_locations'));
    }

   
    public function update_status($id, Request $request){
        if (!auth()->user()->can('cctools.modifiers_create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $business_id = $request->session()->get('user.business_id');
    
            $product = Product::find($id);
                // Actualiza el status_modifier
                if ($product->status_modifier == 'enabled') {
                    $product->status_modifier = 'disabled';
                } elseif ($product->status_modifier == 'disabled') {
                    $product->status_modifier = 'enabled';
                }
            $product->save();
    
            DB::commit();
            $output = ['success' => 1, 'msg' => __('lang_v1.updated_success')];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());
    
            $output = ['success' => 0, 'msg' => __('messages.something_went_wrong')];
        }
    
        return $output;
    }
    



    public function destroy_modifier($id, Request $request)
    {
        if (!auth()->user()->can('cctools.modifiers_delete')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();
            $business_id = $request->session()->get('user.business_id');

            // Verificar si hay ventas relacionadas con el producto
            $hasSales = TransactionSellLine::where('product_id', $id)
                                            ->exists();

            if ($hasSales) {
                // Si hay ventas relacionadas, devuelve un mensaje indicando que el producto tiene ventas
                $output = ['success' => 0, 'msg' => __('cctools::lang.product_has_sales')];
            } else {
                // Si no hay ventas relacionadas, procede con la eliminación del producto y sus relaciones
                Product::where('business_id', $business_id)
                    ->where('type', 'modifier')
                    ->where('id', $id)
                    ->delete();
                Variation::where('product_id', $id)->delete();
                ProductVariation::where('product_id', $id)->delete();
                ProductLocation::where('product_id', $id)->delete();

                DB::commit();

                $output = ['success' => 1, 'msg' => __('lang_v1.deleted_success')];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

            $output = ['success' => 0, 'msg' => __('messages.something_went_wrong')];
        }

        return $output;
    }


    public function create_modifier()
    {
        if (!auth()->user()->can('cctools.modifiers_create')) {
            abort(403, 'Unauthorized action.');
        }
    
        $business_id = request()->session()->get('user.business_id');
    
        $locationsEnabled = BusinessLocation::where('business_id', $business_id)->get();
    
        return view('cctools::modifiers.create_modifiers')
            ->with(compact('locationsEnabled'));
    }

    public function modifier_row($product_id)
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $variation = Variation::find($product_id);
            $variation_id = $variation->product_id;
            $product = Product::find($variation_id);
            $unity = Unit::find($product->unit_id);
            
            return view('cctools::modifiers.product_row_modifiers')
                ->with(compact('product','variation','unity'));
        }
    }

    public function createVariableProductVariationsModifier($product, $input_variations, $business_id = null)
    {
        if (! is_object($product)) {
            $product = Product::find($product);
        }

        //create product variations
        foreach ($input_variations as $key => $value) {
            $images = [];
            $variation_template_name = ! empty($value['name']) ? $value['name'] : null;
            $variation_template_id = ! empty($value['variation_template_id']) ? $value['variation_template_id'] : null;

            if (empty($variation_template_id)) {
                if ($variation_template_name != 'DUMMY') {
                    $variation_template = VariationTemplate::where('business_id', $business_id)
                                                        ->whereRaw('LOWER(name)="'.strtolower($variation_template_name).'"')
                                                        ->with(['values'])
                                                        ->first();
                    if (empty($variation_template)) {
                        $variation_template = VariationTemplate::create([
                            'name' => $variation_template_name,
                            'business_id' => $business_id,
                        ]);
                    }
                    $variation_template_id = $variation_template->id;
                }
            } else {
                $variation_template = VariationTemplate::with(['values'])->find($value['variation_template_id']);
                $variation_template_id = $variation_template->id;
                $variation_template_name = $variation_template->name;
            }

            $product_variation_data = [
                'name' => $variation_template_name,
                'product_id' => $product->id,
                'is_dummy' => 0,
                'variation_template_id' => $variation_template_id,
            ];
            $product_variation = ProductVariation::create($product_variation_data);

            //create variations
            if (! empty($value['variations'])) {
                $variation_data = [];
                $color_data = [];

                $c = Variation::withTrashed()
                        ->where('product_id', $product->id)
                        ->count() + 1;

                foreach ($value['variations'] as $k => $v) {
                    //skip hidden variations
                    if (isset($v['is_hidden']) && $v['is_hidden'] == 1) {
                        continue;
                    }
                    $sku_type='C128';
                    $sub_sku = empty($v['sub_sku']) ? $this->productUtil->generateSubSku($product->sku, $c, $product->barcode_type, $v['value'], $sku_type) : $v['sub_sku'];
                    $variation_value_id = ! empty($v['variation_value_id']) ? $v['variation_value_id'] : null;
                    $variation_value_name = ! empty($v['value']) ? $v['value'] : null;

                    if (! empty($variation_value_id)) {
                        $variation_value = $variation_template->values->filter(function ($item) use ($variation_value_id) {
                            return $item->id == $variation_value_id;
                        })->first();
                        $variation_value_name = $variation_value->name;
                    } else {
                        if (! empty($variation_template)) {
                            $variation_value = VariationValueTemplate::where('variation_template_id', $variation_template->id)
                                ->whereRaw('LOWER(name)="'.$variation_value_name.'"')
                                ->first();
                            if (empty($variation_value)) {
                                $variation_value = VariationValueTemplate::create([
                                    'name' => $variation_value_name,
                                    'variation_template_id' => $variation_template->id,
                                ]);
                            }
                            $variation_value_id = $variation_value->id;
                            $variation_value_name = $variation_value->name;
                        } else {
                            $variation_value_id = null;
                            $variation_value_name = $variation_value_name;
                        }
                    }

                    $variation_data[] = [
                        'name' => $variation_value_name,
                        'variation_value_id' => $variation_value_id,
                        'product_id' => $product->id,
                        'sub_sku' => $sub_sku,
                        'default_purchase_price' => $this->productUtil->num_uf($v['default_purchase_price']),
                        'dpp_inc_tax' => $this->productUtil->num_uf($v['dpp_inc_tax']),
                        'profit_percent' => $this->productUtil->num_uf($v['profit_percent']),
                        'default_sell_price' => $this->productUtil->num_uf($v['default_sell_price']),
                        'sell_price_inc_tax' => $this->productUtil->num_uf($v['sell_price_inc_tax']),
                        'modifier_product' => $this->productUtil->num_uf($v['modifier_product']),
                        'modifier_qty' => $this->productUtil->num_uf($v['modifier_qty']),
                        
                    ];

                    


                    $c++;
                    $images[] = 'variation_images_'.$key.'_'.$k;
                }
                $variations = $product_variation->variations()->createMany($variation_data);

                

                $i = 0;
                foreach ($variations as $variation) {
                    Media::uploadMedia($product->business_id, $variation, request(), $images[$i]);
                    $i++;
                }
            }
        }
    }




    public function getProductsWithoutVariationsModifier()
    {
        if (request()->ajax()) {
            $term = request()->input('term', '');

            $business_id = request()->session()->get('user.business_id');

            $products = Variation::join('products', 'products.id', '=', 'variations.product_id')
                ->where('products.business_id', $business_id)
                ->where('products.type', '!=', 'modifier');

            //Include search
            if (! empty($term)) {
                $products->where(function ($query) use ($term) {
                    $query->where('products.name', 'like', '%'.$term.'%');
                    $query->orWhere('sku', 'like', '%'.$term.'%');
                    $query->orWhere('sub_sku', 'like', '%'.$term.'%');
                });
            }
			

            $products = $products->groupBy('variations.id')
            ->select(
                'variations.id as variation_id',
                'variations.name as variation_name',
                'products.name',
                'products.type',
                'products.enable_stock',
                'products.sku',
                'variations.id as id',
                DB::raw('CONCAT(products.name, "(", variations.name, ")") as text')
            )
                ->get();
			
			
            return json_encode($products);
        }
    }

    public function store_modifier(Request $request)
    {
        try {
            if (! auth()->user()->can('cctools.modifiers_create')) {
                abort(403, 'Unauthorized action.');
            }

            $input = $request->all();
            $business_id = $request->session()->get('user.business_id');
            $user_id = $request->session()->get('user.id');

            $modifer_set_data = [
                'name' => $input['grupo'],
                'type' => 'modifier',
                'sku' => ' ',
                'tax_type' => 'inclusive',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ];
            

            DB::beginTransaction();
            $modifer_set = Product::create($modifer_set_data);
            $newProductId = $modifer_set->id;
            


            $sku = $this->productUtil->generateProductSku($modifer_set->id);
            $modifer_set->sku = $sku;
            $modifer_set->save();
            $modifers = [];
            foreach ($input['modifier_name'] as $key => $value) {
                
                $modifers[] = [
                    'value' => $value,
                    'default_purchase_price' => $input['modifier_price'][$key],
                    'dpp_inc_tax' => $input['modifier_price'][$key],
                    'profit_percent' => 0,
                    'default_sell_price' => $input['modifier_price'][$key],
                    'sell_price_inc_tax' => $input['modifier_price'][$key],
                    'modifier_product' => $input['modifier_product'][$key],
                    'modifier_qty' => $input['modifier_pieces'][$key],
                ];
                
            }
            $modifiers_data = [];
            $modifiers_data[] = [
                'name' => 'DUMMY',
                'variations' => $modifers,
            ];
            $this->createVariableProductVariationsModifier($modifer_set->id, $modifiers_data);
            
            foreach ($input['locations'] as $location) {
                    $locations_data = [
                        'product_id'=> $newProductId,
                        'location_id'=> $location
                    ];
                    ProductLocation::create($locations_data);
            }     


            // Obtener todas las variaciones asociadas al nuevo producto
            $variations = Variation::where('product_id', $newProductId)->get();

            // Crear un arreglo para almacenar los colores
            $colors = $input['colorSelect'];

            // Verificar si el número de variaciones coincide con el número de colores
            if (count($variations) === count($colors)) {
                // Combinar las variaciones y los colores en un solo arreglo
                $dataToUpdate = array_combine($variations->pluck('id')->toArray(), $colors);

                // Iterar sobre el arreglo combinado y actualizar las variaciones
                foreach ($dataToUpdate as $variationId => $color) {
                    $variation = Variation::find($variationId);
                    if ($variation) {
                        $variation->color_button_modifier = $color;
                        $variation->save();
                    }
                }
            } else {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
                $output = ['success' => 0, 'msg' => __('cctools::lang.disalign_array')];
            }
            
            

            DB::commit();

            $output = ['success' => 1, 'msg' => __('lang_v1.added_success')];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => 0, 'msg' => __('messages.something_went_wrong')];
        }

        return $output;
    }

    public function edit_modifier(Request $request, $id)
    {
        try {
            if (! auth()->user()->can('cctools.modifiers_edit')) {
                abort(403, 'Unauthorized action.');
            }

            $input = $request->all();
            $business_id = $request->session()->get('user.business_id');
            $user_id = $request->session()->get('user.id');

            $UPDATE_PRODUCT = Product::find($id);
            $UPDATE_PRODUCT->name = $input['grupo'];
            $UPDATE_PRODUCT->save();

            DB::beginTransaction();
            Variation::where('product_id', $id)->delete();
            ProductLocation::where('product_id', $id)->delete();

            $modifer_set = $id;
            $newProductId = $modifer_set;
            

            $modifers = [];
            foreach ($input['modifier_name'] as $key => $value) {
                
                $modifers[] = [
                    'value' => $value,
                    'default_purchase_price' => $input['modifier_price'][$key],
                    'dpp_inc_tax' => $input['modifier_price'][$key],
                    'profit_percent' => 0,
                    'default_sell_price' => $input['modifier_price'][$key],
                    'sell_price_inc_tax' => $input['modifier_price'][$key],
                    'modifier_product' => $input['modifier_product'][$key],
                    'modifier_qty' => $input['modifier_pieces'][$key],
                ];
                
            }
            $modifiers_data = [];
            $modifiers_data[] = [
                'name' => 'DUMMY',
                'variations' => $modifers,
            ];
            $this->createVariableProductVariationsModifier($modifer_set, $modifiers_data);
            
            foreach ($input['locations'] as $location) {
                    $locations_data = [
                        'product_id'=> $newProductId,
                        'location_id'=> $location
                    ];
                    ProductLocation::create($locations_data);
            }     


            // Obtener todas las variaciones asociadas al nuevo producto
            $variations = Variation::where('product_id', $newProductId)->get();

            // Crear un arreglo para almacenar los colores
            $colors = $input['colorSelect'];

            // Verificar si el número de variaciones coincide con el número de colores
            if (count($variations) === count($colors)) {
                // Combinar las variaciones y los colores en un solo arreglo
                $dataToUpdate = array_combine($variations->pluck('id')->toArray(), $colors);

                // Iterar sobre el arreglo combinado y actualizar las variaciones
                foreach ($dataToUpdate as $variationId => $color) {
                    $variation = Variation::find($variationId);
                    if ($variation) {
                        $variation->color_button_modifier = $color;
                        $variation->save();
                    }
                }
            } else {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
                $output = ['success' => 0, 'msg' => __('cctools::lang.disalign_array')];
            }
            
            

            DB::commit();

            $output = ['success' => 1, 'msg' => __('lang_v1.added_success')];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => 0, 'msg' => __('messages.something_went_wrong')];
        }

        return $output;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('cctools::modifiers.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('cctools::modifiers.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id){
            $business_id = request()->session()->get('user.business_id');
        
            if (!auth()->user()->can('cctools.modifiers_edit')) {
                abort(403, 'Unauthorized action.');
            }
        
            $product = Product::find($id);
            $locationsEnabled = BusinessLocation::where('business_id', $business_id)->get();
            $productLocations = $product->productLocations ? $product->productLocations->pluck('location_id')->toArray() : [];

            $array_vars=[];
            $base=Variation::where('product_id',$product->id)->get();
            $note="";
           
            foreach ($base as $b) {
                $variations = Variation::find($b->modifier_product);
                $product_invent = Product::find($variations->product_id);
                $unity= Unit::find($product_invent->unit_id);
                $note= $note.$product_invent->id.'|';
                    $array_vars[] = [
                        'id_var' => $variations->id, //Id de la variacion con inventario
                        'alias'=>$b->name, //Variasion Base
                        'nombre_pv'=> $product_invent->name . '('.$product_invent->sku .')'.
                                        '<br>'. $variations->name. '('.$variations->sub_sku .')',
                        'pieces'=> $b->modifier_qty,
                        'unity'=> $unity->short_name,
                        'price'=> $b->sell_price_inc_tax,
                        'color'=> $b->color_button_modifier,
                    ];
                    
            }

            $unusedLocations = $locationsEnabled->reject(function ($location) use ($productLocations) {
                return in_array($location->id, $productLocations);
            });
        
            return view('cctools::modifiers.edit_modifiers', [
                'locationsUsed' => $productLocations,
                'locationsUnused' => $unusedLocations,
                'locationsEnabled' => $locationsEnabled,
                'product' => $product,
                'arrayVars' => $array_vars,
                'v' => $note,
            ]);
    }
    

    public function clone($id){
        $business_id = request()->session()->get('user.business_id');
    
        if (!auth()->user()->can('cctools.modifiers_create')) {
            abort(403, 'Unauthorized action.');
        }
    
        $product = Product::find($id);
        $locationsEnabled = BusinessLocation::where('business_id', $business_id)->get();
        $productLocations = $product->productLocations ? $product->productLocations->pluck('location_id')->toArray() : [];

        $array_vars=[];
        $base=Variation::where('product_id',$product->id)->get();
        $note="";
       
        foreach ($base as $b) {
            $variations = Variation::find($b->modifier_product);
            $product_invent = Product::find($variations->product_id);
            $unity= Unit::find($product_invent->unit_id);
            $note= $note.$product_invent->id.'|';
                $array_vars[] = [
                    'id_var' => $variations->id, //Id de la variacion con inventario
                    'alias'=>$b->name, //Variasion Base
                    'nombre_pv'=> $product_invent->name . '('.$product_invent->sku .')'.
                                    '<br>'. $variations->name. '('.$variations->sub_sku .')',
                    'pieces'=> $b->modifier_qty,
                    'unity'=> $unity->short_name,
                    'price'=> $b->sell_price_inc_tax,
                    'color'=> $b->color_button_modifier,
                ];
                
        }

        $unusedLocations = $locationsEnabled->reject(function ($location) use ($productLocations) {
            return in_array($location->id, $productLocations);
        });
    
        return view('cctools::modifiers.clone_modifiers', [
            'locationsUsed' => $productLocations,
            'locationsUnused' => $unusedLocations,
            'locationsEnabled' => $locationsEnabled,
            'product' => $product,
            'arrayVars' => $array_vars,
            'v' => $note,
        ]);
}


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}

