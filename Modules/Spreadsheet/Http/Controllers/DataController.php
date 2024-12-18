<?php

namespace Modules\Spreadsheet\Http\Controllers;

use App\Utils\ModuleUtil;
use Illuminate\Routing\Controller;
use Menu;
use Modules\Spreadsheet\Entities\Spreadsheet;
use Modules\Spreadsheet\Entities\SpreadsheetShare;

class DataController extends Controller
{
    public function superadmin_package()
    {
        return [
            [
                'name' => 'spreadsheet_module',
                'label' => __('spreadsheet::lang.spreadsheet_module'),
                'default' => false,
            ],
        ];
    }

    /**
     * Defines user permissions for the module.
     *
     * @return array
     */
    public function user_permissions()
    {
        $permissions = [
            [
                'value' => 'access.spreadsheet',
                'label' => __('spreadsheet::lang.access_spreadsheet'),
                'default' => false,
            ],
            [
                'value' => 'create.spreadsheet',
                'label' => __('spreadsheet::lang.create_spreadsheet'),
                'default' => false,
            ],
        ];

        return $permissions;
    }

    /**
     * Adds Spreadsheet menus
     *
     * @return null
     */
    public function modifyAdminMenu()
    {
        $business_id = session()->get('user.business_id');
        $module_util = new ModuleUtil();
        $is_spreadsheet_enabled = (bool) $module_util->hasThePermissionInSubscription($business_id, 'spreadsheet_module');

        $background_color = '';
        if (config('app.env') == 'demo') {
            $background_color = '#0086f9 !important;color:white';
        }

        if ($is_spreadsheet_enabled && auth()->user()->can('access.spreadsheet')) {
            Menu::modify('admin-sidebar-menu', function ($menu) use ($background_color) {
                $menu->url(
                    action([\Modules\Spreadsheet\Http\Controllers\SpreadsheetController::class, 'index']),
                    __('spreadsheet::lang.spreadsheet'),
                    ['icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-percent"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 17l4 -4" /><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M10 13h.01" /><path d="M14 17h.01" /></svg>', 'active' => request()->segment(1) == 'spreadsheet', 'style' => 'background-color:' . $background_color]
                )
                    ->order(90);
            });
        }
    }

    /**
     * Parses notification message from database.
     *
     * @return array
     */
    public function parse_notification($notification)
    {
        $notification_datas = [];
        if ($notification->type == 'Modules\Spreadsheet\Notifications\SpreadsheetShared') {
            $data = $notification->data;
            $spreadsheet = Spreadsheet::with('createdBy')->find($data['sheet_id']);
            if (!empty($spreadsheet)) {
                $msg = __(
                    'spreadsheet::lang.spreadsheet_shared_notif_text',
                    [
                        'shared_by' => $spreadsheet->createdBy->user_full_name,
                        'name' => $spreadsheet->name,
                    ]
                );
                $notification_datas = [
                    'msg' => $msg,
                    'icon_class' => 'fas fa fa-file-excel bg-green',
                    'read_at' => $notification->read_at,
                    'link' => action([\Modules\Spreadsheet\Http\Controllers\SpreadsheetController::class, 'show'], [$spreadsheet->id]),
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            }
        }

        return $notification_datas;
    }

    public function getSharedSpreadsheetForGivenData($params)
    {
        $business_id = $params['business_id'];
        $shared_with = $params['shared_with'];
        $shared_id = $params['shared_id'];

        $sheets = SpreadsheetShare::where('shared_with', $shared_with)
            ->where('shared_id', $shared_id)
            ->join('sheet_spreadsheets as ss', 'sheet_spreadsheet_shares.sheet_spreadsheet_id', '=', 'ss.id')
            ->where('ss.business_id', $business_id)
            ->select('name as sheet_name', 'sheet_spreadsheet_id as sheet_id')
            ->get();

        return $sheets;
    }
}
