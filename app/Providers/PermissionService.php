<?php

namespace App\Providers;

use App\Providers\AdminService;
use App\Models\RolePermission;
use App\Models\Permission;
use App\Exceptions\PermissionException;

class PermissionService extends BaseService {

    /**
     *
     * CREATE
     *
     */

    /**
     * Dodaje dozvolu ulozi
     * @param   int         $role_id            Id uloge
     * @param   int         $permission_id      Id dozvole
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprotnom vraća neki error_code
     */
    public static function assignPermission($role_id, $permission_id) {
        try {
            if (self::checkPermission('permission_assign') === false) {
                throw new PermissionException('Nemate dozvolu za dodeljivanje dozvole', 13001);
            }

            if (self::checkPermission('permission_assign') === false) {
                throw new PermissionException('Nemate dozvolu za dodavanje dozvola drugim ulogama', 13002);
            }

            $role_permission = new RolePermission();
            $role_permission->role_id       = $role_id;
            $role_permission->permission_id = $permission_id;

            //Upisuje u bazu
            self::$entity_manager->persist($role_permission);
            self::$entity_manager->flush();

            return true;
        } catch (\Doctrine\DBAL\DBALException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     *
     * READ
     *
     */

    /**
     * Proverava dali ima dozvolu
     * @param   string      $permission_name        Ime dozvole
     * @param   int         $admin_id               Id administratora
     * @return  bool        Vraća true ako ime u suprotnom vraća false
     */
    public static function checkPermission($permission_name, $admin_id = null) {
        try {
            if ($admin_id === null) {
                $admin_id = AdminService::getCurrentAdminId();
            }

            if ($admin_id === 1) {
                return true;
            }

            if ($admin_id === false) {
                return false;
            }

            $admin = self::$entity_manager->find('App\Models\Admin', $admin_id);

            $permissions = $admin->role->permissions;

            $qb = self::$entity_manager->createQueryBuilder();

            $permission = $qb
                ->select('p')
                ->from('App\Models\Permission', 'p')
                ->join('p.roles', 'r')
                ->where('r.id = ?1')
                ->setParameter(1, $admin->role_id)
                ->andWhere('p.machine_name = ?2')
                ->setParameter(2, $permission_name)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;

            return !empty($permission) ? true : false;
        } catch (\Doctrine\DBAL\DBALException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Dohvata dozvole
     * @return array Niz sa dozvolama
     */
    public static function getPermissions() {
        try {
            if (PermissionService::checkPermission('permission_read') === false) {
                throw new PermissionException('Nemate dozvolu za pretragu dozvola', 13003);
            }

            return self::$entity_manager->createQueryBuilder()
                ->select('p')
                ->from('App\Models\Permission', 'p')
                ->getQuery()
                ->getResult()
            ;
        } catch (\Doctrine\DBAL\DBALException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Dohvata dozvolu po id-u
     * @param   int             $permission_id      Id dozvole
     * @return  Permission      Dozvola
     */
    private static function getPermissionById($permission_id) {
        try {
            if (PermissionService::checkPermission('permission_read') === false) {
                throw new PermissionException('Nemate dozvolu za dohvatanje dozvole po id-u', 13004);
            }

            return self::$entity_manager->find('App\Models\Permission', $permission_id);
        } catch (\Doctrine\DBAL\DBALException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
