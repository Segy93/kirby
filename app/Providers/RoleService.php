<?php

namespace App\Providers;

use App\Providers\PermissionService;
use App\Models\Role;
use App\Models\RolePermission;
use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;

class RoleService extends BaseService {

    /**
     *
     * CRAETE
     *
     */

    /**
     * Kreira ulugu administratora
     * @param   strine      $description    Opis uloge
     * @return  Role        $role           Vraća objekat uloge
     */
    public static function createRole($description) {
        if (PermissionService::checkPermission('role_create') === false) {
            throw new PermissionException('Nemate dozvolu za kreiranje uloga', 15001);
        }
        //Instanciranje objekta uloge
        $role = new Role();

        //Setovanje propertija
        $role->description = $description;

        //Čuvanje u bazi
        self::$entity_manager->persist($role);
        self::$entity_manager->flush();

        return $role;
    }

    /**
     *
     * READ
     *
     */

    /**
     * Dohvata sve uloge
     * @return  Collection  Kolekcija modela uloga
     */
    public static function getRoles() {
        if (PermissionService::checkPermission('role_read') === false) {
            throw new PermissionException('Nemate dozvolu za čitanje uloga', 15002);
        }

        return self::$entity_manager->getRepository('App\Models\Role')->findAll();
    }

    /**
     * Dohvata jednu ulugu po id-u
     * @param   int    $role_id     Id uloge
     * @return  Role   $role        Objekat uloge
     */
    public static function getRoleById($role_id) {
        if (PermissionService::checkPermission('role_read') === false) {
            throw new PermissionException('Nemate dozvolu za dohvatanje uloge po id-u', 15003);
        }

        return self::$entity_manager->find('App\Models\Role', $role_id);
    }

    /**
     *
     * UPDATE
     *
     */

    /**
     * Izmena uloge
     * @param   int         $role_id        Id uloge
     * @param   array       $updates        Niz sa izmenama
     * @return  bool/int    Vraća true ako je sve prošlo uredi, u slučaju da nije vraća neki error code
     */
    public static function updateRole($role_id, $updates) {
        if (PermissionService::checkPermission('role_update') === false) {
            throw new PermissionException('Nemate dozvolu za izmenu uloge', 15004);
        }

        //Dohvatam ulugu po id-u
        $role = self::getRoleById($role_id);
        if (empty($role)) {
            throw new ValidationException("Uloga sa tim $role_id id-om nije pronađena", 15005);
        }

        //Proverava dali postoji ključ opis
        if (array_key_exists('description', $updates)) {
            $updates['description'] = ValidationService::validateString($updates['description'], 255);
            if ($updates['description'] === false) {
                throw new ValidationException('Opis kategorije nije odgovarajućeg formata', 15006);
            }

            $role->description = $updates['description'];
        }

        //Proverava dali su izmene prazne
        if (!empty($updates)) {
            //Čuvanje izmenjene uloge u bazi
            self::$entity_manager->persist($role);
            self::$entity_manager->flush();
        }

        return true;
    }



    public static function toggleRolePermission($role_id, $permission_id, $state) {
        $qb = self::$entity_manager->createQueryBuilder();
        $error_code = 0;
        if (PermissionService::checkPermission('role_update') !== true) {
            $error_code = 1;
        }

        if ($state === true) {
            $rp = new RolePermission();
            $rp->role_id = $role_id;
            $rp->permission_id = $permission_id;

            self::$entity_manager->persist($rp);
            self::$entity_manager->flush();
        } else {
            $rp = $qb
                ->select('rp')
                ->from('App\Models\RolePermission', 'rp')
                ->where('rp.role_id = :role_id')
                ->setParameter('role_id', $role_id)
                ->andWhere('rp.permission_id = :permission_id')
                ->setParameter('permission_id', $permission_id)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;

            if (empty($rp)) {
                    $error_code = 2;
            } else {
                self::$entity_manager->remove($rp);
                self::$entity_manager->flush();
            }
        }
        return $error_code;
    }

    /**
     *
     * DELETE
     *
     */

    /**
     * Briše ulogu
     * @param   int         $role_id    Id uloge
     * @return  true/int    Vraća true ako je sve prošlo uredu u suprotnom vraća neki error code
     */
    public static function deleteRole($role_id) {
        if (PermissionService::checkPermission('role_delete') === false) {
            throw new PermissionException('Nemate dozvolu za brisanje uloge', 15007);
        }

        $role = self::getRoleById($role_id);
        if (empty($role)) {
            throw new ValidationException("Uloga sa tim $role_id id-om nije pronađena", 15005);
        }

        //Briše ulogu
        self::$entity_manager->remove($role);
        self::$entity_manager->flush();

        return true;
    }
}
