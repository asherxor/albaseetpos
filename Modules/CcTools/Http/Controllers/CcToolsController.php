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

class CcToolsController extends Controller
{

    protected $productUtil;

    
    public function __construct(ProductUtil $productUtil)
    {
        $this->productUtil = $productUtil;
    }

    public function index()
    {
        return view('cctools::index');
    }

    
    public function create()
    {
        return view('cctools::modifiers.create');
    }

    public function store(Request $request)
    {
        //
    }
    public function show($id)
    {
        return view('cctools::modifiers.show');
    }

    public function edit(){
    }
    
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
