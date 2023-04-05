<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\PermissionService;
use App\Providers\RoleService;
use App\Providers\SessionService;
/**
*
*/
class AdminRoles extends BaseComponent {
    protected $css = [
        'AdminRoles/css/AdminRoles.css',
    ];

    protected $js = [
        'AdminRoles/js/AdminRolesCreate.js',
        'AdminRoles/js/AdminRolesList.js',
    ];

    public function renderHTML() {
        return view('AdminRoles/templates/AdminRoles', [
            'permissions' => [
                'role_create'       => PermissionService::checkPermission('role_create'),
                'role_read'         => PermissionService::checkPermission('role_read'),
                'role_update'       => PermissionService::checkPermission('role_update'),
                'role_delete'       => PermissionService::checkPermission('role_delete'),
                'permission_assign' => PermissionService::checkPermission('permission_assign'),
                'permission_read'   => PermissionService::checkPermission('permission_read'),
            ],
            'csrf_field'    => SessionService::getCsrfField(),
        ]);
    }










    /**
     * Pravi novi predmet
     * @param   string  $params['name']     Ime predmeta
     * @return  int                         error_code prilikom izvrsavanja funkcije
     */
    public function createRole($params) {
        $name = $params['name'];

        return RoleService::createRole($name);
    }






    /**
     * Dohvata podatke neophodne za funkcionisanje komponenti
     * @return array   Vraca niz koji ima kljuceve roles i permissions, koji je onda niz uloga i dozvola
     */
    public function fetchData($params) {
        return [
            'roles' => RoleService::getRoles(),
            'permissions' => PermissionService::getPermissions(),
        ];
    }










    /**
     * Promena da li uloga ima dozvolu
     * @param  int      $params['role_id']          ID predmeta koji menjamo
     * @param  int      $params['permission_id']    ID razreda kojem dodeljujemo predmet
     * @param  string   $params['state']            Sluzi da odredi da li povezujemo ova dva
     *                                              ili prekidamo vezu izmedju njih
     * @return int                                  0 ako je sve proslo kako treba ili kod za gresku ukoliko nije
     */
    public function toggleRolePermission($params) {
        $state = boolval($params['state']);
        $role_id = intval($params['role_id']);
        $permission_id = intval($params['permission_id']);

        return RoleService::toggleRolePermission($role_id, $permission_id, $state);
    }

    /**
     * Promena imena uloge
     * @param   int     $params['role_id']              ID uloge kojoj zelimo da menjamo ime
     * @param   string  $params['description']          Opis uloge koje menjamo
     * @return  boolean                                 Vraca false u slucaju da predmet nije pronadjen
     *                                                  i nema dozvolu inace vraca true
     */
    public function changeText($params) {
        $role_id = intval($params['role_id']);
        $description = $params['description'];

        return RoleService::updateRole($role_id, ['description' => $description]);
    }










    /**
     * Brisanje uloge
     * @param   int     $params['role_id']      Uloga koju zelimo da obrisemo
     * @return  boolean                         Da li je sve OK proslo
     */
    public function deleteRole($params) {
        return RoleService::deleteRole($params['role_id']);
    }
}
