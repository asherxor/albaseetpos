<?php

use Modules\CcTools\Http\Controllers\AdvanceShipmentController;
use Modules\CcTools\Http\Controllers\CcToolsController;
use Modules\CcTools\Http\Controllers\ModifiersController;
use Modules\CcTools\Http\Controllers\CurrencysController;

Route::middleware(['web', 'SetSessionData', 'auth', 'language', 'timezone', 'AdminSidebarMenu'])
    ->prefix('cctools')
    ->group(function() {
        Route::get('/', [CcToolsController::class, 'index']);
        Route::get('/install', 'InstallController@index');
        Route::post('/install', 'InstallController@install');
        Route::get('/install/uninstall', 'InstallController@uninstall');
        Route::get('/install/update', 'InstallController@update');
        Route::get('/modifiers-sets', 'CcTools@index');

        Route::get('/index-modifier', [ModifiersController::class, 'index_modifiers'])->name('index_modifiers');
        Route::get('/create-modifier', [ModifiersController::class, 'create_modifier'])->name('create_modifier');
        Route::post('/store-modifier', [ModifiersController::class, 'store_modifier'])->name('store_modifier');
        Route::get('/product-to-modifier', [ModifiersController::class, 'getProductsWithoutVariationsModifier'])->name('getProductsWithoutVariationsModifier');
        Route::get('/modifier-row/{product_id}', [ModifiersController::class, 'modifier_row'])->name('modifier_row');
        Route::delete('/destroy-modifier/{id}', [ModifiersController::class, 'destroy_modifier'])->name('destroy_modifier');
        Route::post('/update-modifier/{id}', [ModifiersController::class, 'update_status'])->name('update_status');
        Route::get('/edit/{id}', [ModifiersController::class, 'edit'])->name('edit');
        Route::post('/edit-modifier/{id}', [ModifiersController::class, 'edit_modifier'])->name('edit_modifier');
        Route::get('/clone/{id}', [ModifiersController::class, 'clone'])->name('clone');

        Route::get('/index-currencys', [CurrencysController::class, 'index_currencys'])->name('index_currencys');
        Route::get('/product-to-currency', [CurrencysController::class, 'getCurrency'])->name('getCurrency');
        Route::get('/currency-row/{currency_id}', [CurrencysController::class, 'currency_row'])->name('currency_row');
        Route::get('/create-currency', [CurrencysController::class, 'create_currency'])->name('create-currency');
        Route::post('/store-currency', [CurrencysController::class, 'store_currency'])->name('store-currency');
        Route::get('/taza_s', [CurrencysController::class, 'taza_s'])->name('taza_s');
        Route::get('/tazas', [CurrencysController::class, 'tazas'])->name('tazas');
        Route::get('/create-tazas', [CurrencysController::class, 'create_tazas'])->name('create-tazas');
        Route::post('/store-taza', [CurrencysController::class, 'store_tz'])->name('store-taza');
        Route::delete('/destroy/{id}', [CurrencysController::class, 'destroy'])->name('destroy');
        Route::delete('/destroy_taza/{id}', [CurrencysController::class, 'destroy_taza'])->name('destroy_taza');

       

});