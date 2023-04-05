<?php

namespace App\Providers;

use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;
use App\Providers\SEOService;
use App\Models\Articles\Tag;

class TagService extends BaseService {

    /**
     *
     * CREATE
     *
     */

    /**
     * Kreira tag za članke
     * @param   string      $name       Ime taga
     * @return  Tag         $tag        Objekat
     */
    public static function create($name) {
        if (PermissionService::checkPermission('tag_create') === false) {
            throw new PermissionException('Nemate dozvolu za kreiranje taga', 21001);
        }

        $name = ValidationService::validateString($name, 63);
        if ($name === false) {
            throw new ValidationException('Ime taga nije odgovarajućeg formata', 21002);
        }

        $tag = new Tag();

        $tag->name = $name;

        self::$entity_manager->persist($tag);
        self::$entity_manager->flush();

        return $tag->id;
    }

    /**
     *
     * READ
     *
     */

    /**
     * Dohvata sve tagove
     * @return array    Vraća niz objekata
     */
    public static function getAll() {
        return self::$entity_manager->getRepository('App\Models\Articles\Tag')->findAll();
    }

    /**
     * Dohvata tag po id-u
     * @param   int         $tag_id     Id taga
     * @return  Tag         Objekat
     */
    public static function getByID($tag_id) {
        return self::$entity_manager->createQueryBuilder()
            ->select('t')
            ->from('App\Models\Articles\Tag', 't')
            ->where('t.id = :tag_id')
            ->setParameter('tag_id', $tag_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
        //return self::$entity_manager->find('App\Models\Articles\Tag', $tag_id);
    }

    /**
     * Proverava dali je ime zauzeto
     * @param   string      $name   Ime taga
     * @return  boolean     vraća true ako jeste ili false ako nije
     */
    public static function isNameTaken($name) {
        return !empty(self::$entity_manager->createQueryBuilder()
            ->select('t')
            ->from('App\Models\Articles\Tag', 't')
            ->where('t.name = :name')
            ->setParameter('name', $name)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult())
        ;
    }

    /**
     *
     * UPDATE
     *
     */

    /**
     * Izmena taga
     * @param   int         $tag_id     Id taga
     * @param   array       $updates    Niz sa izmenama
     * @return  void/int    Ne vraća ništa ako je sve prošlo uredu inače vraća error_code
     */
    public static function update($tag_id, $updates) {
        if (PermissionService::checkPermission('tag_update') === false) {
            throw new PermissionException('Nemate dozvolu za izmenu taga', 21003);
        }

        $tag = self::getById($tag_id);

        if (array_key_exists('name', $updates)) {
            $updates['name'] = ValidationService::validateString($updates['name'], 63);
            if ($updates['name'] === false) {
                throw new ValidationException('Ime taga nije odgovarajućeg formata', 21004);
            }

            $tag->name = $updates['name'];
        }

        if (!empty($updates)) {
            self::$entity_manager->persist($tag);
            self::$entity_manager->flush();
        }

        return $tag->id;
    }

    /**
     *
     * DELETE
     *
     */

    /**
     * Briše tag
     * @param   int         $tag_id     Id taga
     * @return  Void/int    Ne vraća ništa ako je sve prošlo uredu inače vraća error_code
     */
    public static function delete($tag_id) {
        if (PermissionService::checkPermission('tag_delete') === false) {
            throw new PermissionException('Nemate dozvolu za brisanje taga', 21005);
        }

        $tag = self::getById($tag_id);

        self::$entity_manager->remove($tag);
        self::$entity_manager->flush();

        SEOService::deleteByMachineName('tag_' . $tag_id);
    }
}
