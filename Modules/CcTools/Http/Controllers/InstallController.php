<?php

namespace Modules\CcTools\Http\Controllers;


use App\System; 
use Composer\Semver\Comparator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Faker\Provider\File;

class InstallController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function __construct()
    {
        $this->module_name = 'cctools';
        $this->appVersion =config('cctools.module_version');
    }

    public function index()
    {
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        $this->installSettings();

        //Check if installed or not.
        $is_installed = System::getProperty($this->module_name . '_version');


        if (!empty($is_installed)) {
            abort(404);
        }

        $this->installSettings();
        $output = $this->install();
        return redirect()
            ->action('\App\Http\Controllers\Install\ModulesController@index')
            ->with('status', $output);
    }

    private function installSettings()
    {
        config(['app.debug' => true]);
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
    }

    public function install()
    {
        $is_installed = System::getProperty($this->module_name . '_version');
        if (!empty($is_installed)) {
            abort(404);
        }

        Artisan::call('module:migrate-reset', ['module' => "cctools"]);
        Artisan::call('module:migrate', ['module' => "cctools"]);

        DB::statement('SET default_storage_engine=INNODB;');


        System::addProperty($this->module_name . '_version', $this->appVersion);

        DB::commit();

        $output = ['success' => true,
                'msg' => __("lang_v1.success")
            ];

        $this->import_files();

        return $output;
    }

    public function import_cash_registers_files(){

        

        return $output;
    }




    public function import_files(){
        $title_basic_controller = '[1.0] Instalando controles basicos...'. date('Y-m-d H:i:s');
        Storage::append('cctools.log', $title_basic_controller);  

            //Controlador POS
            $SellPosControllerOriginal = app_path('Http\Controllers\SellPosController.php');
            $SellPosControllerRespaldado = app_path('Http\Controllers\SellPosController_original.php');
            $SellPosControllerCustom = base_path('Modules\CcTools\Http\Controllers\resources_to_import\SellPosController.php');
            if (!file_exists($SellPosControllerRespaldado)) { 
                //Controlador POS
                    if (file_exists($SellPosControllerOriginal)) {
                        \File::move($SellPosControllerOriginal, $SellPosControllerRespaldado);
                        \File::copy($SellPosControllerCustom, $SellPosControllerOriginal);
                        Storage::append('cctools.log', '1.1-Colocando SellPosController_original...');
                    }
            } 
            
            //View pos form
            $PosFormTotalsOriginal = resource_path('views\sale_pos\partials\pos_form_totals.blade.php');
            $PosFormTotalsRespaldado = resource_path('views\sale_pos\partials\pos_form_totals_original.blade.php');
            $PosFormTotalsCustom = base_path('Modules\CcTools\Http\Controllers\resources_to_import\pos_form_totals.blade.php');
            if (!file_exists($PosFormTotalsRespaldado)) {  
                //View pos form
                if (file_exists($PosFormTotalsOriginal)) {
                    \File::move($PosFormTotalsOriginal, $PosFormTotalsRespaldado);
                    \File::copy($PosFormTotalsCustom, $PosFormTotalsOriginal);
                    Storage::append('cctools.log', '1.2-Colocando pos_form_totals_original...');
                }
            }

            //View Create
            $CreatePosOriginal = resource_path('views\sale_pos\create.blade.php');
            $CreatePosRespaldado = resource_path('views\sale_pos\create_original.blade.php');
            $CreatePosCustom = base_path('Modules\CcTools\Http\Controllers\resources_to_import\create.blade.php');
            if (!file_exists($CreatePosRespaldado)) {
                //View Create
                if (file_exists($CreatePosOriginal)) {
                    \File::move($CreatePosOriginal, $CreatePosRespaldado);
                    \File::copy($CreatePosCustom, $CreatePosOriginal);
                    Storage::append('cctools.log', '1.3-Colocando create_original...');
                }
            }







            $title_cashregister_controller = '[2.0] Instalando CashRegister...'. date('Y-m-d H:i:s');
            Storage::append('cctools.log', $title_cashregister_controller);  

            $Original_1 = app_path('Http\Controllers\CashRegisterController.php');
            $Backup_1 = app_path('Http\Controllers\CashRegisterController_original.php');
            $Custom_1 = base_path('Modules\CcTools\Http\Controllers\resources_to_import\cash_registers\CashRegisterController.php');
            if (!file_exists($Backup_1)) { 
                    if (file_exists($Original_1)) {
                        \File::move($Original_1, $Backup_1);
                        \File::copy($Custom_1, $Original_1);
                        Storage::append('cctools.log', '2.1-Colocando CashRegisterController...');
                    }
            } 
            
            //close_register_modal
            $Original_2 = resource_path('views\cash_register\close_register_modal.blade.php');
            $Backup_2 = resource_path('views\cash_register\close_register_modal_original.blade.php');
            $Custom_2 = base_path('Modules\CcTools\Http\Controllers\resources_to_import\cash_registers\close_register_modal.blade.php');
            if (!file_exists($Backup_2)) {  
                if (file_exists($Original_2)) {
                    \File::move($Original_2, $Backup_2);
                    \File::copy($Custom_2, $Original_2);
                    Storage::append('cctools.log', '2.2-Colocando close_register_modal...');
                }
            }

            //register_details
            $Original_3 = resource_path('views\cash_register\register_details.blade.php');
            $Backup_3 = resource_path('views\cash_register\register_details_original.blade.php');
            $Custom_3 = base_path('Modules\CcTools\Http\Controllers\resources_to_import\cash_registers\register_details.blade.php');
            if (!file_exists($Backup_3)) {  
                if (file_exists($Original_3)) {
                    \File::move($Original_3, $Backup_3);
                    \File::copy($Custom_3, $Original_3);
                    Storage::append('cctools.log', '2.3-Colocando register_details...');
                }
            }

            //CashRegisterUtil
            $Original_4 = app_path('Utils\CashRegisterUtil.php');
            $Backup_4 = app_path('Utils\CashRegisterUtil_original.php');
            $Custom_4 = base_path('Modules\CcTools\Http\Controllers\resources_to_import\cash_registers\CashRegisterUtil.php');
            if (!file_exists($Backup_4)) { 
                if (file_exists($Original_4)) {
                    \File::move($Original_4, $Backup_4);
                    \File::copy($Custom_4, $Original_4);
                    Storage::append('cctools.log', '2.4-Colocando CashRegisterUtil...');
                }
            }


    }

    public function restore_files(){
        $title_basic_controller = '[1.0] Desinstalando controles basicos...'. date('Y-m-d H:i:s');
        Storage::append('cctools.log', $title_basic_controller);    
            
        $SellPosControllerOriginal = app_path('Http\Controllers\SellPosController.php');
        $SellPosControllerRespaldado = app_path('Http\Controllers\SellPosController_original.php');
        if (file_exists($SellPosControllerRespaldado)) {
            \File::move($SellPosControllerRespaldado, $SellPosControllerOriginal);
            Storage::append('cctools.log', '1.1-Restaurando archivo original... SellPosController');
        }

        $PosFormTotalsOriginal = resource_path('views\sale_pos\partials\pos_form_totals.blade.php');
        $PosFormTotalsRespaldado = resource_path('views\sale_pos\partials\pos_form_totals_original.blade.php');
        if (file_exists($PosFormTotalsRespaldado)) {
            \File::move($PosFormTotalsRespaldado, $PosFormTotalsOriginal);
            Storage::append('cctools.log', '1.2-Restaurando archivo original pos_form_totals...');
        }

        $CreatePosOriginal = resource_path('views\sale_pos\create.blade.php');
        $CreatePosRespaldado = resource_path('views\sale_pos\create_original.blade.php');
        if (file_exists($CreatePosRespaldado)) {
            \File::move($CreatePosRespaldado, $CreatePosOriginal);
            Storage::append('cctools.log', '1.3-Restaurando archivo original create.blade...');
        }





        $title_cashregister_controller = '[2.0] Desinstalando CashRegister...'. date('Y-m-d H:i:s');
        Storage::append('cctools.log', $title_cashregister_controller);   
        
        $Original_1 = app_path('Http\Controllers\CashRegisterController.php');
        $Backup_1 = app_path('Http\Controllers\CashRegisterController_original.php');
        if (file_exists($Backup_1)) { 
            \File::move($Backup_1, $Original_1);
            Storage::append('cctools.log', '2.1-Restaurando archivo original CashRegisterController...');
        }
        $Original_2 = resource_path('views\cash_register\close_register_modal.blade.php');
        $Backup_2 = resource_path('views\cash_register\close_register_modal_original.blade.php');
        if (file_exists($Backup_2)) { 
            \File::move($Backup_2, $Original_2);
            Storage::append('cctools.log', '2.2-Restaurando archivo original close_register_modal...');
        }
        $Original_3 = resource_path('views\cash_register\register_details.blade.php');
        $Backup_3 = resource_path('views\cash_register\register_details_original.blade.php');
        if (file_exists($Backup_3)) { 
            \File::move($Backup_3, $Original_3);
            Storage::append('cctools.log', '2.3-Restaurando archivo original register_details...');
        }
        $Original_4 = app_path('Utils\CashRegisterUtil.php');
        $Backup_4 = app_path('Utils\CashRegisterUtil_original.php');
        if (file_exists($Backup_4)) { 
            \File::move($Backup_4, $Original_4);
            Storage::append('cctools.log', '2.4-Restaurando archivo original CashRegisterUtil...');
        }
        
    }

    /**
     * Uninstall
     * @return Response
     */
    public function uninstall()
    {
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            System::removeProperty($this->module_name . '_version');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            $output = ['success' => true,
                'msg' => __("lang_v1.success")
            ];
        } catch (\Exception $e) {
            $output = ['success' => false,
                'msg' => $e->getMessage()
            ];
        }

        $this->restore_files();

        return redirect()->back()->with(['status' => $output]);
    }

    /**
     * update module
     * @return Response
     */
    public function update()
    {
        try {
            DB::beginTransaction();
                ini_set('max_execution_time', 0);
                ini_set('memory_limit', '512M');

                $version = System::getProperty($this->module_name . '_version');

                if (Comparator::greaterThan($this->appVersion, $version)) {
                    $this->installSettings();
                    Artisan::call('module:migrate-reset', ['module' => "cctools"]);
                    Artisan::call('module:migrate', ['module' => "cctools"]);
                    System::setProperty($this->module_name . '_version', $this->appVersion);
                } else {
                    abort(404);
                }


            return redirect()->back()->with(['status' => [
                'success' => 1,
                'msg' => 'El módulo cctools se actualizó correctamente a la versión ' . $this->appVersion . ' !!'
            ]]);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }   

}