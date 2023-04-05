<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\PermissionService;

/**
*
*/
class AdminPermissions extends BaseComponent {
    protected $css = [
        'AdminPermissions/css/AdminPermissions.css'
    ];

    public function renderHTML() {
         return view('AdminPermissions/templates/AdminPermissions', [
             'permissions' => PermissionService::getPermissions(),
             'permission'  => PermissionService::checkPermission('permission_read'),
         ]);
    }
}
