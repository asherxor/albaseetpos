<?php

namespace Modules\CcTools\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Menu;
use App\Utils\ModuleUtil;
class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */



    public function superadmin_package()
    {
        return [
            [
                'name' => 'cctools_module',
                'label' => __('cctools::lang.tools_kit'),
                'default' => false
            ],
        ];
    }

    /* Module menu*/
    public function modifyAdminMenu()
    {


        $business_id = session()->get('user.business_id');
        $module_util = new ModuleUtil();
        //REVISAR MODULOS ACTIVADOS
        $is_cctools_enabled = (boolean)$module_util->hasThePermissionInSubscription($business_id, 'cctools_module', 'superadmin_package');
        $is_cctools_shipment_enabled = (boolean)$module_util->hasThePermissionInSubscription($business_id, 'cctools_shipment', 'superadmin_package');

        $menu= Menu::instance('admin-sidebar-menu');
        if($is_cctools_enabled){
            $menu_tools = $menu;
            if (auth()->user()->can('cctools.view_cctools')) {
                $menu_tools->url(
                    action('\Modules\CcTools\Http\Controllers\CcToolsController@index'),
                    __('cctools::lang.tools_kit'),
					['icon' => 'fa fas fa-shield-alt', 'style' => 'background-color: cyan !important;']
    
                )->order(2);
    
            }

        }

        
    }

    public function user_permissions()
    {
            return [             
                [
                    'value' => 'cctools.view_cctools',
                    'label' =>  __('cctools::lang.view_cctools'),
                    'default' => false,
                    'end_group' => true
                ],




                [
                    'value' => 'cctools.rated_view',
                    'label' =>  __('cctools::lang.rated_view'),
                    'default' => false,
                ],
                [
                    'value' => 'cctools.create_rated',
                    'label' =>  __('cctools::lang.create_rated'),
                    'default' => false
                ],
                [
                    'value' => 'cctools.delete_rated',
                    'label' =>  __('cctools::lang.delete_rated'),
                    'default' => false
                ],
                [
                    'value' => 'cctools.rated_pos',
                    'label' =>  __('cctools::lang.rated_permisions_pos'),
                    'default' => false,
                    'end_group' => true
                ],




                [
                    'value' => 'cctools.modifiers_view',
                    'label' =>  __('cctools::lang.modifiers_view'),
                    'default' => false,
                ],
                [
                    'value' => 'cctools.modifiers_create',
                    'label' =>  __('cctools::lang.modifiers_create'),
                    'default' => false
                ],
                [
                    'value' => 'cctools.modifiers_edit',
                    'label' =>  __('cctools::lang.modifiers_edit'),
                    'default' => false
                ],                
                [
                    'value' => 'cctools.modifiers_delete',
                    'label' =>  __('cctools::lang.modifiers_delete'),
                    'default' => false,
                    'end_group' => true
                ],

                [
                    'value' => 'cctools.view_cash_register_details_open',
                    'label' =>  __('cctools::lang.view_cash_register_details_open'),
                    'default' => false,
                ],
                [
                    'value' => 'cctools.view_cash_register_details',
                    'label' =>  __('cctools::lang.view_cash_register_details'),
                    'default' => false,
                    'end_group' => true
                ],


            ];
        
    }
    public function index()
    {
        return view('CcTools::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('cctools::create');
    }



}
