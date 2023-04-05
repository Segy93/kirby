<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\AdminService;
use App\Providers\PermissionService;
use App\Providers\RoleService;
use App\Providers\SessionService;

/**
*
*/
class Administrators extends BaseComponent {
    protected $css = [
        'Administrators/css/Administrators.css',
    ];

    protected $js = [
        'Administrators/js/AdministratorsCreate.js',
        'Administrators/js/AdministratorsList.js'
    ];

    public function renderHTML() {
        return view('Administrators/templates/Administrators', [
            'roles'         =>  RoleService::getRoles(),
            'permissions'   =>  [
                'admin_create'      =>  PermissionService::checkPermission('admin_create'),
                'admin_read'        =>  PermissionService::checkPermission('admin_read'),
                'admin_update'      =>  PermissionService::checkPermission('admin_update'),
                'admin_delete'      =>  PermissionService::checkPermission('admin_delete'),
                'admin_role_set'    =>  PermissionService::checkPermission('admin_role_set'),
                'role_read'         =>  PermissionService::checkPermission('role_read'),
            ],
            'csrf_field'    => SessionService::getCsrfField(),
        ]);
    }










    /**
     * Pravi novog admina
     * @param   string  $params['name']     Ime admina
     * @param   string  $params['email']    Email admina
     * @param   number  $params['role']     Uloga admina
     * @return  int                         error_code prilikom izvrsavanja funkcije
     */
    public function createAdmin($params) {
        $name = $params['name'];
        $role_id = intval($params['role']);
        $email = $params['email'];

        return AdminService::signUp($role_id, $name, $email);
    }










    /**
     * Dohvata podatke neophodne za funkcionisanje komponenti
     * @return array   Vraca niz koji ima kljuceve roles i administrators, koji je onda niz uloga i admina
     */
    public function fetchData($params) {
        return [
            'roles'             => RoleService::getRoles(),
            'administrators'    => AdminService::getAdmins(),
        ];
    }










    public function updateAdmin($params) {
        $admin_id   = intval($params['admin_id']);
        $name       = $params['admin_name'];
        $email      = $params['admin_email'];

        return AdminService::updateAdmin($admin_id, [
            'username' => $name,
            'email' => $email,
        ]);
    }

    public function resetPassword($params) {
        $admin_id = intval($params['admin_id']);

        return AdminService::resetPassword($admin_id);
    }

    /**
     * Dodeljuje novu ulogu datom adminu
     * @param   int     $params['role_id']      ID uloge
     * @param   int     $params['admin_id']     ID admina
     * @return  int                             error_code prilikom izvrsavanja funkcije
     */
    public function setRole($params) {
        $role_id  = intval($params['role_id']);
        $admin_id = intval($params['admin_id']);
        $updates = [
            'role_id'   => $role_id,
        ];
        return AdminService::updateAdmin($admin_id, $updates);
    }










    /**
     * Brisanje admina
     * @param   int     $params['admin_id']     Admin kojeg zelimo da obrisemo
     * @return  boolean                         Da li je sve OK proslo
     */

    public function deleteAdmin($params) {
        $admin_id = intval($params['admin_id']);

        return AdminService::deleteAdmin($admin_id);
    }
}
