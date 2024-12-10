<?php

namespace App\Http\Middleware;

use App\Utils\ModuleUtil;
use Closure;
use Menu;
use Modules\CustomDashboard\Entities\CustomDashboard;

class AdminSidebarMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->ajax()) {
            return $next($request);
        }

        Menu::create('admin-sidebar-menu', function ($menu) {
            $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

            $common_settings = !empty(session('business.common_settings')) ? session('business.common_settings') : [];
            $pos_settings = !empty(session('business.pos_settings')) ? json_decode(session('business.pos_settings'), true) : [];

            $is_admin = auth()->user()->hasRole('Admin#' . session('business.id')) ? true : false;
            //Home
            //     $menu->url(action([\App\Http\Controllers\HomeController::class, 'index']), __('home.home'), ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            //     <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            //     <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            //     <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
            //     <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
            //     <path d="M10 12h4v4h-4z"></path>
            //   </svg>', 'active' => request()->segment(1) == 'home'])->order(5);

            
                  
            $menu->url(action([\App\Http\Controllers\HomeController::class, 'index']), __('home.home'), ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 640 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path fill="#1b0e63" d="M218.3 8.5c12.3-11.3 31.2-11.3 43.4 0l208 192c6.7 6.2 10.3 14.8 10.3 23.5l-144 0c-19.1 0-36.3 8.4-48 21.7l0-37.7c0-8.8-7.2-16-16-16l-64 0c-8.8 0-16 7.2-16 16l0 64c0 8.8 7.2 16 16 16l64 0 0 128-160 0c-26.5 0-48-21.5-48-48l0-112-32 0c-13.2 0-25-8.1-29.8-20.3s-1.6-26.2 8.1-35.2l208-192zM352 304l0 144 192 0 0-144-192 0zm-48-16c0-17.7 14.3-32 32-32l224 0c17.7 0 32 14.3 32 32l0 160 32 0c8.8 0 16 7.2 16 16c0 26.5-21.5 48-48 48l-48 0-192 0-48 0c-26.5 0-48-21.5-48-48c0-8.8 7.2-16 16-16l32 0 0-160z"/>
            </svg>
          ', 'active' => request()->segment(1) == 'home'])->order(0);

            //User management dropdown
            if (auth()->user()->can('user.view') || auth()->user()->can('user.create') || auth()->user()->can('roles.view')) {
                $menu->dropdown(
                    __('user.user_management'),
                    function ($sub) {
                        if (auth()->user()->can('user.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ManageUserController::class, 'index']),
                                __('user.users'),
                                ['icon' => '', 'active' => request()->segment(1) == 'users']
                            );
                        }
                        if (auth()->user()->can('roles.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\RoleController::class, 'index']),
                                __('user.roles'),
                                ['icon' => '', 'active' => request()->segment(1) == 'roles']
                            );
                        }
                        if (auth()->user()->can('user.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\SalesCommissionAgentController::class, 'index']),
                                __('lang_v1.sales_commission_agents'),
                                ['icon' => '', 'active' => request()->segment(1) == 'sales-commission-agents']
                            );
                        }
                    },
                    ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 640 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ca2240" d="M144 160A80 80 0 1 0 144 0a80 80 0 1 0 0 160zm368 0A80 80 0 1 0 512 0a80 80 0 1 0 0 160zM0 298.7C0 310.4 9.6 320 21.3 320l213.3 0c.2 0 .4 0 .7 0c-26.6-23.5-43.3-57.8-43.3-96c0-7.6 .7-15 1.9-22.3c-13.6-6.3-28.7-9.7-44.6-9.7l-42.7 0C47.8 192 0 239.8 0 298.7zM320 320c24 0 45.9-8.8 62.7-23.3c2.5-3.7 5.2-7.3 8-10.7c2.7-3.3 5.7-6.1 9-8.3C410 262.3 416 243.9 416 224c0-53-43-96-96-96s-96 43-96 96s43 96 96 96zm65.4 60.2c-10.3-5.9-18.1-16.2-20.8-28.2l-103.2 0C187.7 352 128 411.7 128 485.3c0 14.7 11.9 26.7 26.7 26.7l300.6 0c-2.1-5.2-3.2-10.9-3.2-16.4l0-3c-1.3-.7-2.7-1.5-4-2.3l-2.6 1.5c-16.8 9.7-40.5 8-54.7-9.7c-4.5-5.6-8.6-11.5-12.4-17.6l-.1-.2-.1-.2-2.4-4.1-.1-.2-.1-.2c-3.4-6.2-6.4-12.6-9-19.3c-8.2-21.2 2.2-42.6 19-52.3l2.7-1.5c0-.8 0-1.5 0-2.3s0-1.5 0-2.3l-2.7-1.5zM533.3 192l-42.7 0c-15.9 0-31 3.5-44.6 9.7c1.3 7.2 1.9 14.7 1.9 22.3c0 17.4-3.5 33.9-9.7 49c2.5 .9 4.9 2 7.1 3.3l2.6 1.5c1.3-.8 2.6-1.6 4-2.3l0-3c0-19.4 13.3-39.1 35.8-42.6c7.9-1.2 16-1.9 24.2-1.9s16.3 .6 24.2 1.9c22.5 3.5 35.8 23.2 35.8 42.6l0 3c1.3 .7 2.7 1.5 4 2.3l2.6-1.5c16.8-9.7 40.5-8 54.7 9.7c2.3 2.8 4.5 5.8 6.6 8.7c-2.1-57.1-49-102.7-106.6-102.7zm91.3 163.9c6.3-3.6 9.5-11.1 6.8-18c-2.1-5.5-4.6-10.8-7.4-15.9l-2.3-4c-3.1-5.1-6.5-9.9-10.2-14.5c-4.6-5.7-12.7-6.7-19-3l-2.9 1.7c-9.2 5.3-20.4 4-29.6-1.3s-16.1-14.5-16.1-25.1l0-3.4c0-7.3-4.9-13.8-12.1-14.9c-6.5-1-13.1-1.5-19.9-1.5s-13.4 .5-19.9 1.5c-7.2 1.1-12.1 7.6-12.1 14.9l0 3.4c0 10.6-6.9 19.8-16.1 25.1s-20.4 6.6-29.6 1.3l-2.9-1.7c-6.3-3.6-14.4-2.6-19 3c-3.7 4.6-7.1 9.5-10.2 14.6l-2.3 3.9c-2.8 5.1-5.3 10.4-7.4 15.9c-2.6 6.8 .5 14.3 6.8 17.9l2.9 1.7c9.2 5.3 13.7 15.8 13.7 26.4s-4.5 21.1-13.7 26.4l-3 1.7c-6.3 3.6-9.5 11.1-6.8 17.9c2.1 5.5 4.6 10.7 7.4 15.8l2.4 4.1c3 5.1 6.4 9.9 10.1 14.5c4.6 5.7 12.7 6.7 19 3l2.9-1.7c9.2-5.3 20.4-4 29.6 1.3s16.1 14.5 16.1 25.1l0 3.4c0 7.3 4.9 13.8 12.1 14.9c6.5 1 13.1 1.5 19.9 1.5s13.4-.5 19.9-1.5c7.2-1.1 12.1-7.6 12.1-14.9l0-3.4c0-10.6 6.9-19.8 16.1-25.1s20.4-6.6 29.6-1.3l2.9 1.7c6.3 3.6 14.4 2.6 19-3c3.7-4.6 7.1-9.4 10.1-14.5l2.4-4.2c2.8-5.1 5.3-10.3 7.4-15.8c2.6-6.8-.5-14.3-6.8-17.9l-3-1.7c-9.2-5.3-13.7-15.8-13.7-26.4s4.5-21.1 13.7-26.4l3-1.7zM472 384a40 40 0 1 1 80 0 40 40 0 1 1 -80 0z"/></svg>', ]
                )->order(0.01);
            }

            //Contacts dropdown
            if (auth()->user()->can('supplier.view') || auth()->user()->can('customer.view') || auth()->user()->can('supplier.view_own') || auth()->user()->can('customer.view_own')) {
                $menu->dropdown(
                    __('contact.contacts'),
                    function ($sub) {
                        if (auth()->user()->can('supplier.view') || auth()->user()->can('supplier.view_own')) {
                            $sub->url(
                                action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => 'supplier']),
                                __('report.supplier'),
                                ['icon' => '', 'active' => request()->input('type') == 'supplier']
                            );
                        }
                        if (auth()->user()->can('customer.view') || auth()->user()->can('customer.view_own')) {
                            $sub->url(
                                action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => 'customer']),
                                __('report.customer'),
                                ['icon' => '', 'active' => request()->input('type') == 'customer']
                            );
                            $sub->url(
                                action([\App\Http\Controllers\CustomerGroupController::class, 'index']),
                                __('lang_v1.customer_groups'),
                                ['icon' => '', 'active' => request()->segment(1) == 'customer-group']
                            );
                        }
                        if (auth()->user()->can('supplier.create') || auth()->user()->can('customer.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\ContactController::class, 'getImportContacts']),
                                __('lang_v1.import_contacts'),
                                ['icon' => '', 'active' => request()->segment(1) == 'contacts' && request()->segment(2) == 'import']
                            );
                        }

                        if (!empty(env('GOOGLE_MAP_API_KEY'))) {
                            $sub->url(
                                action([\App\Http\Controllers\ContactController::class, 'contactMap']),
                                __('lang_v1.map'),
                                ['icon' => 'fa fas fa-map-marker-alt', 'active' => request()->segment(1) == 'contacts' && request()->segment(2) == 'map']
                            );
                        }
                    },
                    ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#27b013" d="M96 0C60.7 0 32 28.7 32 64l0 384c0 35.3 28.7 64 64 64l288 0c35.3 0 64-28.7 64-64l0-384c0-35.3-28.7-64-64-64L96 0zM208 288l64 0c44.2 0 80 35.8 80 80c0 8.8-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16c0-44.2 35.8-80 80-80zm-32-96a64 64 0 1 1 128 0 64 64 0 1 1 -128 0zM512 80c0-8.8-7.2-16-16-16s-16 7.2-16 16l0 64c0 8.8 7.2 16 16 16s16-7.2 16-16l0-64zM496 192c-8.8 0-16 7.2-16 16l0 64c0 8.8 7.2 16 16 16s16-7.2 16-16l0-64c0-8.8-7.2-16-16-16zm16 144c0-8.8-7.2-16-16-16s-16 7.2-16 16l0 64c0 8.8 7.2 16 16 16s16-7.2 16-16l0-64z"/></svg>', 'id' => 'tour_step4']
                )->order(0.02);
            }

            //Products dropdown
            if (auth()->user()->can('product.view') || auth()->user()->can('product.create') ||
                auth()->user()->can('brand.view') || auth()->user()->can('unit.view') ||
                auth()->user()->can('category.view') || auth()->user()->can('brand.create') ||
                auth()->user()->can('unit.create') || auth()->user()->can('category.create')) {
                $menu->dropdown(
                    __('sale.products'),
                    function ($sub) {
                        if (auth()->user()->can('product.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ProductController::class, 'index']),
                                __('lang_v1.list_products'),
                                ['icon' => '', 'active' => request()->segment(1) == 'products' && request()->segment(2) == '']
                            );
                        }

                        if (auth()->user()->can('product.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\ProductController::class, 'create']),
                                __('product.add_product'),
                                ['icon' => '', 'active' => request()->segment(1) == 'products' && request()->segment(2) == 'create']
                            );
                        }
                        if (auth()->user()->can('product.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\SellingPriceGroupController::class, 'updateProductPrice']),
                                __('lang_v1.update_product_price'),
                                ['icon' => '', 'active' => request()->segment(1) == 'update-product-price']
                            )->order(1);
                        }
                        if (auth()->user()->can('product.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\LabelsController::class, 'show']),
                                __('barcode.print_labels'),
                                ['icon' => '', 'active' => request()->segment(1) == 'labels' && request()->segment(2) == 'show']
                            );
                        }
                        if (auth()->user()->can('product.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\VariationTemplateController::class, 'index']),
                                __('product.variations'),
                                ['icon' => '', 'active' => request()->segment(1) == 'variation-templates']
                            );
                            $sub->url(
                                action([\App\Http\Controllers\ImportProductsController::class, 'index']),
                                __('product.import_products'),
                                ['icon' => '', 'active' => request()->segment(1) == 'import-products']
                            );
                        }
                        if (auth()->user()->can('product.opening_stock')) {
                            $sub->url(
                                action([\App\Http\Controllers\ImportOpeningStockController::class, 'index']),
                                __('lang_v1.import_opening_stock'),
                                ['icon' => '', 'active' => request()->segment(1) == 'import-opening-stock']
                            );
                        }
                        if (auth()->user()->can('product.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\SellingPriceGroupController::class, 'index']),
                                __('lang_v1.selling_price_group'),
                                ['icon' => '', 'active' => request()->segment(1) == 'selling-price-group']
                            );
                        }
                        if (auth()->user()->can('unit.view') || auth()->user()->can('unit.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\UnitController::class, 'index']),
                                __('unit.units'),
                                ['icon' => '', 'active' => request()->segment(1) == 'units']
                            );
                        }
                        if (auth()->user()->can('category.view') || auth()->user()->can('category.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\TaxonomyController::class, 'index']) . '?type=product',
                                __('category.categories'),
                                ['icon' => '', 'active' => request()->segment(1) == 'taxonomies' && request()->get('type') == 'product']
                            );
                        }
                        if (auth()->user()->can('brand.view') || auth()->user()->can('brand.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\BrandController::class, 'index']),
                                __('brand.brands'),
                                ['icon' => '', 'active' => request()->segment(1) == 'brands']
                            );
                        }

                        $sub->url(
                            action([\App\Http\Controllers\WarrantyController::class, 'index']),
                            __('lang_v1.warranties'),
                            ['icon' => '', 'active' => request()->segment(1) == 'warranties']
                        );
                    },
                    ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 640 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#804000" d="M0 488L0 171.3c0-26.2 15.9-49.7 40.2-59.4L308.1 4.8c7.6-3.1 16.1-3.1 23.8 0L599.8 111.9c24.3 9.7 40.2 33.3 40.2 59.4L640 488c0 13.3-10.7 24-24 24l-48 0c-13.3 0-24-10.7-24-24l0-264c0-17.7-14.3-32-32-32l-384 0c-17.7 0-32 14.3-32 32l0 264c0 13.3-10.7 24-24 24l-48 0c-13.3 0-24-10.7-24-24zm488 24l-336 0c-13.3 0-24-10.7-24-24l0-56 384 0 0 56c0 13.3-10.7 24-24 24zM128 400l0-64 384 0 0 64-384 0zm0-96l0-80 384 0 0 80-384 0z"/></svg>', 'id' => 'tour_step5']
                )->order(0.03);
            }

            //Purchase dropdown
            if (in_array('purchases', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create') || auth()->user()->can('purchase.update'))) {
                $menu->dropdown(
                    __('purchase.purchases'),
                    function ($sub) use ($common_settings) {
                        if (!empty($common_settings['enable_purchase_requisition']) && (auth()->user()->can('purchase_requisition.view_all') || auth()->user()->can('purchase_requisition.view_own'))) {
                            $sub->url(
                                action([\App\Http\Controllers\PurchaseRequisitionController::class, 'index']),
                                __('lang_v1.purchase_requisition'),
                                ['icon' => '', 'active' => request()->segment(1) == 'purchase-requisition']
                            );
                        }

                        if (!empty($common_settings['enable_purchase_order']) && (auth()->user()->can('purchase_order.view_all') || auth()->user()->can('purchase_order.view_own'))) {
                            $sub->url(
                                action([\App\Http\Controllers\PurchaseOrderController::class, 'index']),
                                __('lang_v1.purchase_order'),
                                ['icon' => '', 'active' => request()->segment(1) == 'purchase-order']
                            );
                        }
                        if (auth()->user()->can('purchase.view') || auth()->user()->can('view_own_purchase')) {
                            $sub->url(
                                action([\App\Http\Controllers\PurchaseController::class, 'index']),
                                __('purchase.list_purchase'),
                                ['icon' => '', 'active' => request()->segment(1) == 'purchases' && request()->segment(2) == null]
                            );
                        }
                        if (auth()->user()->can('purchase.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\PurchaseController::class, 'create']),
                                __('purchase.add_purchase'),
                                ['icon' => '', 'active' => request()->segment(1) == 'purchases' && request()->segment(2) == 'create']
                            );
                        }
                        if (auth()->user()->can('purchase.update')) {
                            $sub->url(
                                action([\App\Http\Controllers\PurchaseReturnController::class, 'index']),
                                __('lang_v1.list_purchase_return'),
                                ['icon' => '', 'active' => request()->segment(1) == 'purchase-return']
                            );
                        }
                    },
                    ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 576 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#B197FC" d="M64 64C28.7 64 0 92.7 0 128L0 384c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64L64 64zM272 192l224 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-224 0c-8.8 0-16-7.2-16-16s7.2-16 16-16zM256 304c0-8.8 7.2-16 16-16l224 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-224 0c-8.8 0-16-7.2-16-16zM164 152l0 13.9c7.5 1.2 14.6 2.9 21.1 4.7c10.7 2.8 17 13.8 14.2 24.5s-13.8 17-24.5 14.2c-11-2.9-21.6-5-31.2-5.2c-7.9-.1-16 1.8-21.5 5c-4.8 2.8-6.2 5.6-6.2 9.3c0 1.8 .1 3.5 5.3 6.7c6.3 3.8 15.5 6.7 28.3 10.5l.7 .2c11.2 3.4 25.6 7.7 37.1 15c12.9 8.1 24.3 21.3 24.6 41.6c.3 20.9-10.5 36.1-24.8 45c-7.2 4.5-15.2 7.3-23.2 9l0 13.8c0 11-9 20-20 20s-20-9-20-20l0-14.6c-10.3-2.2-20-5.5-28.2-8.4c0 0 0 0 0 0s0 0 0 0c-2.1-.7-4.1-1.4-6.1-2.1c-10.5-3.5-16.1-14.8-12.6-25.3s14.8-16.1 25.3-12.6c2.5 .8 4.9 1.7 7.2 2.4c13.6 4.6 24 8.1 35.1 8.5c8.6 .3 16.5-1.6 21.4-4.7c4.1-2.5 6-5.5 5.9-10.5c0-2.9-.8-5-5.9-8.2c-6.3-4-15.4-6.9-28-10.7l-1.7-.5c-10.9-3.3-24.6-7.4-35.6-14c-12.7-7.7-24.6-20.5-24.7-40.7c-.1-21.1 11.8-35.7 25.8-43.9c6.9-4.1 14.5-6.8 22.2-8.5l0-14c0-11 9-20 20-20s20 9 20 20z"/></svg>', 'id' => 'tour_step6']
                )->order(0.06);
            }
            //Sell dropdown
            if ($is_admin || auth()->user()->hasAnyPermission(['sell.view', 'sell.create', 'direct_sell.access', 'view_own_sell_only', 'view_commission_agent_sell', 'access_shipping', 'access_own_shipping', 'access_commission_agent_shipping', 'access_sell_return', 'direct_sell.view', 'direct_sell.update', 'access_own_sell_return'])) {
                $menu->dropdown(
                    __('sale.sale'),
                    function ($sub) use ($enabled_modules, $is_admin, $pos_settings) {
                        if (!empty($pos_settings['enable_sales_order']) && ($is_admin || auth()->user()->hasAnyPermission(['so.view_own', 'so.view_all', 'so.create']))) {
                            $sub->url(
                                action([\App\Http\Controllers\SalesOrderController::class, 'index']),
                                __('lang_v1.sales_order'),
                                ['icon' => '', 'active' => request()->segment(1) == 'sales-order']
                            );
                        }

                        if ($is_admin || auth()->user()->hasAnyPermission(['sell.view', 'sell.create', 'direct_sell.access', 'direct_sell.view', 'view_own_sell_only', 'view_commission_agent_sell', 'access_shipping', 'access_own_shipping', 'access_commission_agent_shipping'])) {
                            $sub->url(
                                action([\App\Http\Controllers\SellController::class, 'index']),
                                __('lang_v1.all_sales'),
                                ['icon' => '', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == null]
                            );
                        }
                        if (in_array('add_sale', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
                            $sub->url(
                                action([\App\Http\Controllers\SellController::class, 'create']),
                                __('sale.add_sale'),
                                ['icon' => '', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == 'create' && empty(request()->get('status'))]
                            );
                        }
                        if (auth()->user()->can('sell.create')) {
                            if (in_array('pos_sale', $enabled_modules)) {
                                if (auth()->user()->can('sell.view')) {
                                    $sub->url(
                                        action([\App\Http\Controllers\SellPosController::class, 'index']),
                                        __('sale.list_pos'),
                                        ['icon' => '', 'active' => request()->segment(1) == 'pos' && request()->segment(2) == null]
                                    );
                                }

                                $sub->url(
                                    action([\App\Http\Controllers\SellPosController::class, 'create']),
                                    __('sale.pos_sale'),
                                    ['icon' => '', 'active' => request()->segment(1) == 'pos' && request()->segment(2) == 'create']
                                );
                            }
                        }

                        if (in_array('add_sale', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
                            $sub->url(
                                action([\App\Http\Controllers\SellController::class, 'create'], ['status' => 'draft']),
                                __('lang_v1.add_draft'),
                                ['icon' => '', 'active' => request()->get('status') == 'draft']
                            );
                        }
                        if (in_array('add_sale', $enabled_modules) && ($is_admin || auth()->user()->hasAnyPermission(['draft.view_all', 'draft.view_own']))) {
                            $sub->url(
                                action([\App\Http\Controllers\SellController::class, 'getDrafts']),
                                __('lang_v1.list_drafts'),
                                ['icon' => '', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == 'drafts']
                            );
                        }
                        if (in_array('add_sale', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
                            $sub->url(
                                action([\App\Http\Controllers\SellController::class, 'create'], ['status' => 'quotation']),
                                __('lang_v1.add_quotation'),
                                ['icon' => '', 'active' => request()->get('status') == 'quotation']
                            );
                        }
                        if (in_array('add_sale', $enabled_modules) && ($is_admin || auth()->user()->hasAnyPermission(['quotation.view_all', 'quotation.view_own']))) {
                            $sub->url(
                                action([\App\Http\Controllers\SellController::class, 'getQuotations']),
                                __('lang_v1.list_quotations'),
                                ['icon' => '', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == 'quotations']
                            );
                        }

                        if (auth()->user()->can('access_sell_return') || auth()->user()->can('access_own_sell_return')) {
                            $sub->url(
                                action([\App\Http\Controllers\SellReturnController::class, 'index']),
                                __('lang_v1.list_sell_return'),
                                ['icon' => '', 'active' => request()->segment(1) == 'sell-return' && request()->segment(2) == null]
                            );
                        }

                        if ($is_admin || auth()->user()->hasAnyPermission(['access_shipping', 'access_own_shipping', 'access_commission_agent_shipping'])) {
                            $sub->url(
                                action([\App\Http\Controllers\SellController::class, 'shipments']),
                                __('lang_v1.shipments'),
                                ['icon' => '', 'active' => request()->segment(1) == 'shipments']
                            );
                        }

                        if (auth()->user()->can('discount.access')) {
                            $sub->url(
                                action([\App\Http\Controllers\DiscountController::class, 'index']),
                                __('lang_v1.discounts'),
                                ['icon' => '', 'active' => request()->segment(1) == 'discount']
                            );
                        }
                        if (in_array('subscription', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
                            $sub->url(
                                action([\App\Http\Controllers\SellPosController::class, 'listSubscriptions']),
                                __('lang_v1.subscriptions'),
                                ['icon' => '', 'active' => request()->segment(1) == 'subscriptions']
                            );
                        }

                        if (auth()->user()->can('sell.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\ImportSalesController::class, 'index']),
                                __('lang_v1.import_sales'),
                                ['icon' => '', 'active' => request()->segment(1) == 'import-sales']
                            );
                        }
                    },
                    ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#6cff6c" d="M256 448c141.4 0 256-93.1 256-208S397.4 32 256 32S0 125.1 0 240c0 45.1 17.7 86.8 47.7 120.9c-1.9 24.5-11.4 46.3-21.4 62.9c-5.5 9.2-11.1 16.6-15.2 21.6c-2.1 2.5-3.7 4.4-4.9 5.7c-.6 .6-1 1.1-1.3 1.4l-.3 .3c0 0 0 0 0 0c0 0 0 0 0 0s0 0 0 0s0 0 0 0c-4.6 4.6-5.9 11.4-3.4 17.4c2.5 6 8.3 9.9 14.8 9.9c28.7 0 57.6-8.9 81.6-19.3c22.9-10 42.4-21.9 54.3-30.6c31.8 11.5 67 17.9 104.1 17.9zm20-312l0 13.9c7.5 1.2 14.6 2.9 21.1 4.7c10.7 2.8 17 13.8 14.2 24.5s-13.8 17-24.5 14.2c-11-2.9-21.6-5-31.2-5.2c-7.9-.1-16 1.8-21.5 5c-4.8 2.8-6.2 5.6-6.2 9.3c0 1.8 .1 3.5 5.3 6.7c6.3 3.8 15.5 6.7 28.3 10.5l.7 .2c11.2 3.4 25.6 7.7 37.1 15c12.9 8.1 24.3 21.3 24.6 41.6c.3 20.9-10.5 36.1-24.8 45c-7.2 4.5-15.2 7.3-23.2 9l0 13.8c0 11-9 20-20 20s-20-9-20-20l0-14.6c-10.3-2.2-20-5.5-28.2-8.4c0 0 0 0 0 0s0 0 0 0c-2.1-.7-4.1-1.4-6.1-2.1c-10.5-3.5-16.1-14.8-12.6-25.3s14.8-16.1 25.3-12.6c2.5 .8 4.9 1.7 7.2 2.4c0 0 0 0 0 0c13.6 4.6 24 8.1 35.1 8.5c8.6 .3 16.5-1.6 21.4-4.7c4.1-2.5 6-5.5 5.9-10.5c0-2.9-.8-5-5.9-8.2c-6.3-4-15.4-6.9-28-10.7l-1.7-.5c-10.9-3.3-24.6-7.4-35.6-14c-12.7-7.7-24.6-20.5-24.7-40.7c-.1-21.1 11.8-35.7 25.8-43.9c6.9-4.1 14.5-6.8 22.2-8.5l0-14c0-11 9-20 20-20s20 9 20 20z"/></svg>', 'id' => 'tour_step7']
                )->order(0.07);
            }

            //Stock transfer dropdown
            if (in_array('stock_transfers', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create') || auth()->user()->can('view_own_purchase'))) {
                $menu->dropdown(
                    __('lang_v1.stock_transfers'),
                    function ($sub) {
                        if (auth()->user()->can('purchase.view') || auth()->user()->can('view_own_purchase')) {
                            $sub->url(
                                action([\App\Http\Controllers\StockTransferController::class, 'index']),
                                __('lang_v1.list_stock_transfers'),
                                ['icon' => '', 'active' => request()->segment(1) == 'stock-transfers' && request()->segment(2) == null]
                            );
                        }
                        if (auth()->user()->can('purchase.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\StockTransferController::class, 'create']),
                                __('lang_v1.add_stock_transfer'),
                                ['icon' => '', 'active' => request()->segment(1) == 'stock-transfers' && request()->segment(2) == 'create']
                            );
                        }
                    },
                    ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 640 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#FFD43B" d="M0 48C0 21.5 21.5 0 48 0L368 0c26.5 0 48 21.5 48 48l0 48 50.7 0c17 0 33.3 6.7 45.3 18.7L589.3 192c12 12 18.7 28.3 18.7 45.3l0 18.7 0 32 0 64c17.7 0 32 14.3 32 32s-14.3 32-32 32l-32 0c0 53-43 96-96 96s-96-43-96-96l-128 0c0 53-43 96-96 96s-96-43-96-96l-16 0c-26.5 0-48-21.5-48-48L0 48zM416 256l128 0 0-18.7L466.7 160 416 160l0 96zM160 464a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm368-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM257 95c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l39 39L96 168c-13.3 0-24 10.7-24 24s10.7 24 24 24l166.1 0-39 39c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l80-80c9.4-9.4 9.4-24.6 0-33.9L257 95z"/></svg>']
                )->order(0.04);
            }

            //stock adjustment dropdown
            if (in_array('stock_adjustment', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create') || auth()->user()->can('view_own_purchase'))) {
                $menu->dropdown(
                    __('stock_adjustment.stock_adjustment'),
                    function ($sub) {
                        if (auth()->user()->can('purchase.view')  || auth()->user()->can('view_own_purchase')) {
                            $sub->url(
                                action([\App\Http\Controllers\StockAdjustmentController::class, 'index']),
                                __('stock_adjustment.list'),
                                ['icon' => '', 'active' => request()->segment(1) == 'stock-adjustments' && request()->segment(2) == null]
                            );
                        }
                        if (auth()->user()->can('purchase.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\StockAdjustmentController::class, 'create']),
                                __('stock_adjustment.add'),
                                ['icon' => '', 'active' => request()->segment(1) == 'stock-adjustments' && request()->segment(2) == 'create']
                            );
                        }
                    },
                    ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 576 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ff0000" d="M49.7 32c-10.5 0-19.8 6.9-22.9 16.9L.9 133c-.6 2-.9 4.1-.9 6.1C0 150.7 9.3 160 20.9 160l94 0L140.5 32 49.7 32zM272 160l0-128-98.9 0L147.5 160 272 160zm32 0l124.5 0L402.9 32 304 32l0 128zm157.1 0l94 0c11.5 0 20.9-9.3 20.9-20.9c0-2.1-.3-4.1-.9-6.1L549.2 48.9C546.1 38.9 536.8 32 526.3 32l-90.8 0 25.6 128zM32 192l4 32-4 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l12 0L64 448c0 17.7 14.3 32 32 32s32-14.3 32-32l320 0c0 17.7 14.3 32 32 32s32-14.3 32-32l20-160 12 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-4 0 4-32L32 192z"/></svg>']
                )->order(0.05);
            }

            //Expense dropdown
            if (in_array('expenses', $enabled_modules) && (auth()->user()->can('all_expense.access') || auth()->user()->can('view_own_expense'))) {
                $menu->dropdown(
                    __('expense.expenses'),
                    function ($sub) {
                        $sub->url(
                            action([\App\Http\Controllers\ExpenseController::class, 'index']),
                            __('lang_v1.list_expenses'),
                            ['icon' => '', 'active' => request()->segment(1) == 'expenses' && request()->segment(2) == null]
                        );

                        if (auth()->user()->can('expense.add')) {
                            $sub->url(
                                action([\App\Http\Controllers\ExpenseController::class, 'create']),
                                __('expense.add_expense'),
                                ['icon' => '', 'active' => request()->segment(1) == 'expenses' && request()->segment(2) == 'create']
                            );
                        }

                        if (auth()->user()->can('expense.add') || auth()->user()->can('expense.edit')) {
                            $sub->url(
                                action([\App\Http\Controllers\ExpenseCategoryController::class, 'index']),
                                __('expense.expense_categories'),
                                ['icon' => '', 'active' => request()->segment(1) == 'expense-categories']
                            );
                        }
                    },
                    ['icon' => ' <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 384 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#3e402f" d="M14 2.2C22.5-1.7 32.5-.3 39.6 5.8L80 40.4 120.4 5.8c9-7.7 22.3-7.7 31.2 0L192 40.4 232.4 5.8c9-7.7 22.3-7.7 31.2 0L304 40.4 344.4 5.8c7.1-6.1 17.1-7.5 25.6-3.6s14 12.4 14 21.8l0 464c0 9.4-5.5 17.9-14 21.8s-18.5 2.5-25.6-3.6L304 471.6l-40.4 34.6c-9 7.7-22.3 7.7-31.2 0L192 471.6l-40.4 34.6c-9 7.7-22.3 7.7-31.2 0L80 471.6 39.6 506.2c-7.1 6.1-17.1 7.5-25.6 3.6S0 497.4 0 488L0 24C0 14.6 5.5 6.1 14 2.2zM96 144c-8.8 0-16 7.2-16 16s7.2 16 16 16l192 0c8.8 0 16-7.2 16-16s-7.2-16-16-16L96 144zM80 352c0 8.8 7.2 16 16 16l192 0c8.8 0 16-7.2 16-16s-7.2-16-16-16L96 336c-8.8 0-16 7.2-16 16zM96 240c-8.8 0-16 7.2-16 16s7.2 16 16 16l192 0c8.8 0 16-7.2 16-16s-7.2-16-16-16L96 240z"/></svg>']
                )->order(0.08);
            }
            //Accounts dropdown
            if (auth()->user()->can('account.access') && in_array('account', $enabled_modules)) {
                $menu->dropdown(
                    __('lang_v1.payment_accounts'),
                    function ($sub) {
                        $sub->url(
                            action([\App\Http\Controllers\AccountController::class, 'index']),
                            __('account.list_accounts'),
                            ['icon' => '', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'account']
                        );
                        $sub->url(
                            action([\App\Http\Controllers\AccountReportsController::class, 'balanceSheet']),
                            __('account.balance_sheet'),
                            ['icon' => '', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'balance-sheet']
                        );
                        $sub->url(
                            action([\App\Http\Controllers\AccountReportsController::class, 'trialBalance']),
                            __('account.trial_balance'),
                            ['icon' => '', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'trial-balance']
                        );
                        $sub->url(
                            action([\App\Http\Controllers\AccountController::class, 'cashFlow']),
                            __('lang_v1.cash_flow'),
                            ['icon' => '', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'cash-flow']
                        );
                        $sub->url(
                            action([\App\Http\Controllers\AccountReportsController::class, 'paymentAccountReport']),
                            __('account.payment_account_report'),
                            ['icon' => '', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'payment-account-report']
                        );
                    },
                    ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 384 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#332ef5" d="M64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-384c0-35.3-28.7-64-64-64L64 0zM96 64l192 0c17.7 0 32 14.3 32 32l0 32c0 17.7-14.3 32-32 32L96 160c-17.7 0-32-14.3-32-32l0-32c0-17.7 14.3-32 32-32zm32 160a32 32 0 1 1 -64 0 32 32 0 1 1 64 0zM96 352a32 32 0 1 1 0-64 32 32 0 1 1 0 64zM64 416c0-17.7 14.3-32 32-32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-96 0c-17.7 0-32-14.3-32-32zM192 256a32 32 0 1 1 0-64 32 32 0 1 1 0 64zm32 64a32 32 0 1 1 -64 0 32 32 0 1 1 64 0zm64-64a32 32 0 1 1 0-64 32 32 0 1 1 0 64zm32 64a32 32 0 1 1 -64 0 32 32 0 1 1 64 0zM288 448a32 32 0 1 1 0-64 32 32 0 1 1 0 64z"/></svg>']
                )->order(0.09);
            }

            //Reports dropdown
            if (auth()->user()->can('purchase_n_sell_report.view') || auth()->user()->can('contacts_report.view')
                || auth()->user()->can('stock_report.view') || auth()->user()->can('tax_report.view')
                || auth()->user()->can('trending_product_report.view') || auth()->user()->can('sales_representative.view') || auth()->user()->can('register_report.view')
                || auth()->user()->can('expense_report.view')) {
                $menu->dropdown(
                    __('report.reports'),
                    function ($sub) use ($enabled_modules, $is_admin) {
                        if (auth()->user()->can('profit_loss_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getProfitLoss']),
                                __('report.profit_loss'),
                                ['icon' => '', 'active' => request()->segment(2) == 'profit-loss']
                            );
                        }
                        if (config('constants.show_report_606') == true) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'purchaseReport']),
                                'Report 606 (' . __('lang_v1.purchase') . ')',
                                ['icon' => '', 'active' => request()->segment(2) == 'purchase-report']
                            );
                        }
                        if (config('constants.show_report_607') == true) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'saleReport']),
                                'Report 607 (' . __('business.sale') . ')',
                                ['icon' => '', 'active' => request()->segment(2) == 'sale-report']
                            );
                        }
                        if ((in_array('purchases', $enabled_modules) || in_array('add_sale', $enabled_modules) || in_array('pos_sale', $enabled_modules)) && auth()->user()->can('purchase_n_sell_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getPurchaseSell']),
                                __('report.purchase_sell_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'purchase-sell']
                            );
                        }

                        if (auth()->user()->can('tax_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getTaxReport']),
                                __('report.tax_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'tax-report']
                            );
                        }
                        if (auth()->user()->can('contacts_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getCustomerSuppliers']),
                                __('report.contacts'),
                                ['icon' => '', 'active' => request()->segment(2) == 'customer-supplier']
                            );
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getCustomerGroup']),
                                __('lang_v1.customer_groups_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'customer-group']
                            );
                        }
                        if (auth()->user()->can('stock_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getStockReport']),
                                __('report.stock_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'stock-report']
                            );
                            if (session('business.enable_product_expiry') == 1) {
                                $sub->url(
                                    action([\App\Http\Controllers\ReportController::class, 'getStockExpiryReport']),
                                    __('report.stock_expiry_report'),
                                    ['icon' => '', 'active' => request()->segment(2) == 'stock-expiry']
                                );
                            }
                            if (session('business.enable_lot_number') == 1) {
                                $sub->url(
                                    action([\App\Http\Controllers\ReportController::class, 'getLotReport']),
                                    __('lang_v1.lot_report'),
                                    ['icon' => '', 'active' => request()->segment(2) == 'lot-report']
                                );
                            }

                            if (in_array('stock_adjustment', $enabled_modules)) {
                                $sub->url(
                                    action([\App\Http\Controllers\ReportController::class, 'getStockAdjustmentReport']),
                                    __('report.stock_adjustment_report'),
                                    ['icon' => '', 'active' => request()->segment(2) == 'stock-adjustment-report']
                                );
                            }
                        }

                        if (auth()->user()->can('trending_product_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getTrendingProducts']),
                                __('report.trending_products'),
                                ['icon' => '', 'active' => request()->segment(2) == 'trending-products']
                            );
                        }

                        if (auth()->user()->can('purchase_n_sell_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'itemsReport']),
                                __('lang_v1.items_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'items-report']
                            );

                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getproductPurchaseReport']),
                                __('lang_v1.product_purchase_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'product-purchase-report']
                            );

                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getproductSellReport']),
                                __('lang_v1.product_sell_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'product-sell-report']
                            );

                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'purchasePaymentReport']),
                                __('lang_v1.purchase_payment_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'purchase-payment-report']
                            );

                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'sellPaymentReport']),
                                __('lang_v1.sell_payment_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'sell-payment-report']
                            );
                        }
                        if (in_array('expenses', $enabled_modules) && auth()->user()->can('expense_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getExpenseReport']),
                                __('report.expense_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'expense-report']
                            );
                        }
                        if (auth()->user()->can('register_report.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getRegisterReport']),
                                __('report.register_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'register-report']
                            );
                        }
                        if (auth()->user()->can('sales_representative.view')) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getSalesRepresentativeReport']),
                                __('report.sales_representative'),
                                ['icon' => '', 'active' => request()->segment(2) == 'sales-representative-report']
                            );
                        }
                        if (auth()->user()->can('purchase_n_sell_report.view') && in_array('tables', $enabled_modules)) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getTableReport']),
                                __('restaurant.table_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'table-report']
                            );
                        }

                        if (auth()->user()->can('tax_report.view') && !empty(config('constants.enable_gst_report_india'))) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'gstSalesReport']),
                                __('lang_v1.gst_sales_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'gst-sales-report']
                            );

                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'gstPurchaseReport']),
                                __('lang_v1.gst_purchase_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'gst-purchase-report']
                            );
                        }

                        if (auth()->user()->can('sales_representative.view') && in_array('service_staff', $enabled_modules)) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'getServiceStaffReport']),
                                __('restaurant.service_staff_report'),
                                ['icon' => '', 'active' => request()->segment(2) == 'service-staff-report']
                            );
                        }

                        if ($is_admin) {
                            $sub->url(
                                action([\App\Http\Controllers\ReportController::class, 'activityLog']),
                                __('lang_v1.activity_log'),
                                ['icon' => '', 'active' => request()->segment(2) == 'activity-log']
                            );
                        }
                    },
                    ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#63E6BE" d="M128 0C92.7 0 64 28.7 64 64l0 96 64 0 0-96 226.7 0L384 93.3l0 66.7 64 0 0-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0L128 0zM384 352l0 32 0 64-256 0 0-64 0-16 0-16 256 0zm64 32l32 0c17.7 0 32-14.3 32-32l0-96c0-35.3-28.7-64-64-64L64 192c-35.3 0-64 28.7-64 64l0 96c0 17.7 14.3 32 32 32l32 0 0 64c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/></svg>', 'id' => 'tour_step8']
                )->order(10);
            }

            //Backup menu
            if (auth()->user()->can('backup')) {
                $menu->url(action([\App\Http\Controllers\BackUpController::class, 'index']), __('lang_v1.backup'), ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#a970af" d="M0 96C0 60.7 28.7 32 64 32l384 0c35.3 0 64 28.7 64 64l0 184.4c-17-15.2-39.4-24.4-64-24.4L64 256c-24.6 0-47 9.2-64 24.4L0 96zM64 288l384 0c35.3 0 64 28.7 64 64l0 64c0 35.3-28.7 64-64 64L64 480c-35.3 0-64-28.7-64-64l0-64c0-35.3 28.7-64 64-64zM320 416a32 32 0 1 0 0-64 32 32 0 1 0 0 64zm128-32a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>', 'active' => request()->segment(1) == 'backup'])->order(60);
            }

            //Modules menu
            if (auth()->user()->can('manage_modules')) {
                $menu->url(action([\App\Http\Controllers\Install\ModulesController::class, 'index']), __('lang_v1.modules'), ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="10" width="11.25" viewBox="0 0 576 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#10674d" d="M264.5 5.2c14.9-6.9 32.1-6.9 47 0l218.6 101c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 149.8C37.4 145.8 32 137.3 32 128s5.4-17.9 13.9-21.8L264.5 5.2zM476.9 209.6l53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 277.8C37.4 273.8 32 265.3 32 256s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0l152-70.2zm-152 198.2l152-70.2 53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 405.8C37.4 401.8 32 393.3 32 384s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0z"/></svg>', 'active' => request()->segment(1) == 'manage-modules'])->order(60);
            }

            //Booking menu
            if (in_array('booking', $enabled_modules) && (auth()->user()->can('crud_all_bookings') || auth()->user()->can('crud_own_bookings'))) {
                $menu->url(action([\App\Http\Controllers\Restaurant\BookingController::class, 'index']), __('restaurant.bookings'), ['icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v6" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M15 19l2 2l4 -4" /></svg>', 'active' => request()->segment(1) == 'bookings'])->order(65);
            }

            //Kitchen menu
            if (in_array('kitchen', $enabled_modules)) {
                $menu->url(action([\App\Http\Controllers\Restaurant\KitchenController::class, 'index']), __('restaurant.kitchen'), ['icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-flame"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12c2 -2.96 0 -7 -1 -8c0 3.038 -1.773 4.741 -3 6c-1.226 1.26 -2 3.24 -2 5a6 6 0 1 0 12 0c0 -1.532 -1.056 -3.94 -2 -5c-1.786 3 -2.791 3 -4 2z" /></svg>', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'kitchen'])->order(70);
            }

            //Service Staff menu
            if (in_array('service_staff', $enabled_modules)) {
                $menu->url(action([\App\Http\Controllers\Restaurant\OrderController::class, 'index']), __('restaurant.orders'), ['icon' => 'fa fas fa-list-alt', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'orders'])->order(75);
            }

            //Notification template menu
            if (auth()->user()->can('send_notifications')) {
                $menu->url(action([\App\Http\Controllers\NotificationTemplateController::class, 'index']), __('lang_v1.notification_templates'), ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path>
                    <path d="M3 7l9 6l9 -6"></path>
                  </svg>', 'active' => request()->segment(1) == 'notification-templates'])->order(80);
            }

            //Settings Dropdown
            if (auth()->user()->can('business_settings.access') ||
                auth()->user()->can('barcode_settings.access') ||
                auth()->user()->can('invoice_settings.access') ||
                auth()->user()->can('tax_rate.view') ||
                auth()->user()->can('tax_rate.create') ||
                auth()->user()->can('access_package_subscriptions')) {
                $menu->dropdown(
                    __('business.settings'),
                    function ($sub) use ($enabled_modules) {
                        if (auth()->user()->can('business_settings.access')) {
                            $sub->url(
                                action([\App\Http\Controllers\BusinessController::class, 'getBusinessSettings']),
                                __('business.business_settings'),
                                ['icon' => '', 'active' => request()->segment(1) == 'business', 'id' => 'tour_step2']
                            );
                            $sub->url(
                                action([\App\Http\Controllers\BusinessLocationController::class, 'index']),
                                __('business.business_locations'),
                                ['icon' => '', 'active' => request()->segment(1) == 'business-location']
                            );
                        }
                        if (auth()->user()->can('invoice_settings.access')) {
                            $sub->url(
                                action([\App\Http\Controllers\InvoiceSchemeController::class, 'index']),
                                __('invoice.invoice_settings'),
                                ['icon' => '', 'active' => in_array(request()->segment(1), ['invoice-schemes', 'invoice-layouts'])]
                            );
                        }
                        if (auth()->user()->can('barcode_settings.access')) {
                            $sub->url(
                                action([\App\Http\Controllers\BarcodeController::class, 'index']),
                                __('barcode.barcode_settings'),
                                ['icon' => '', 'active' => request()->segment(1) == 'barcodes']
                            );
                        }
                        if (auth()->user()->can('access_printers')) {
                            $sub->url(
                                action([\App\Http\Controllers\PrinterController::class, 'index']),
                                __('printer.receipt_printers'),
                                ['icon' => '', 'active' => request()->segment(1) == 'printers']
                            );
                        }

                        if (auth()->user()->can('tax_rate.view') || auth()->user()->can('tax_rate.create')) {
                            $sub->url(
                                action([\App\Http\Controllers\TaxRateController::class, 'index']),
                                __('tax_rate.tax_rates'),
                                ['icon' => '', 'active' => request()->segment(1) == 'tax-rates']
                            );
                        }

                        if (in_array('tables', $enabled_modules) && auth()->user()->can('access_tables')) {
                            $sub->url(
                                action([\App\Http\Controllers\Restaurant\TableController::class, 'index']),
                                __('restaurant.tables'),
                                ['icon' => '', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'tables']
                            );
                        }

                        if (in_array('modifiers', $enabled_modules) && (auth()->user()->can('product.view') || auth()->user()->can('product.create'))) {
                            $sub->url(
                                action([\App\Http\Controllers\Restaurant\ModifierSetsController::class, 'index']),
                                __('restaurant.modifiers'),
                                ['icon' => '', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'modifiers']
                            );
                        }

                        if (in_array('types_of_service', $enabled_modules) && auth()->user()->can('access_types_of_service')) {
                            $sub->url(
                                action([\App\Http\Controllers\TypesOfServiceController::class, 'index']),
                                __('lang_v1.types_of_service'),
                                ['icon' => '', 'active' => request()->segment(1) == 'types-of-service']
                            );
                        }
                    },
                    ['icon' => '<svg aria-hidden="true" class="tw-size-5 tw-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path>
                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                  </svg>', 'id' => 'tour_step3']
                )->order(11);
            }
        });

        //Add menus from modules
        $moduleUtil = new ModuleUtil;
        $moduleUtil->getModuleData('modifyAdminMenu');

        return $next($request);
    }
}
