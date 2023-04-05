<?php

namespace App\Providers;

use App\Models\StaticPages\Page;
use App\Models\StaticPages\Category;
use App\Providers\ValidationService;
use App\Providers\PermissionService;
use App\Providers\SEOService;
use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;

class StaticPageService extends BaseService {

    /**
     *
     * CREATE
     *
     */

    /**
     * Kreira kategoriju za statičke strane
     * @param   string      $name       Naziv kategorije
     * @return  bool        Vraća true ako je sve prošlo uredu
     */
    public static function createCategory($name) {
        if (PermissionService::checkPermission('category_static_delete') === false) {
            throw new PermissionException('Nemate dozvolu za kreiranje kategorija statičkih strana', 20001);
        }

        if (ValidationService::validateString($name, 32) === false) {
            throw new ValidationException('Naziv kategorije nije odgovarajućeg formata', 20002);
        }

        $category = new Category();

        $category->name = $name;

        self::$entity_manager->persist($category);
        self::$entity_manager->flush();

        return $category->id;
    }

    /**
     * Kreira statičku stranu
     * @param   int         $category_id        Id kategorije
     * @param   string      $title              Naslov strane
     * @param   string      $text               Sadržaj strane
     * @return  bool        Vraća true ako je sve prošlo uredu
     */
    public static function createPage($title, $category_id, $text) {
        if (PermissionService::checkPermission('staticPage_create') === false) {
            throw new PermissionException('Nemate dozvolu za kreiranje statičke strane', 20003);
        }

        if (ValidationService::validateString($title, 127) === false) {
            throw new ValidationException('Naslov strane nije odgovarajućeg formata', 20004);
        }

        if (ValidationService::validateHTML($text) === false) {
            throw new ValidationException('Sadržaj nije odgovarajućeg fomrata', 20005);
        }

        $order = self::$entity_manager->createQueryBuilder()
            ->select('MAX(p.order_page)')
            ->from('App\Models\StaticPages\Page', 'p')
            ->getQuery()
            ->getSingleScalarResult()
            + 1
        ;

        $page = new Page();

        $category = self::getCategoryById($category_id);
        $page->category = $category;
        $page->title = $title;
        $page->text = $text;
        $page->order_page = $order;

        self::$entity_manager->persist($page);
        self::$entity_manager->flush();

        //SEOService::createSEO('page_'.$page->id);

        return $page->id;
    }

    /**
     *
     * READ
     *
     */

    /**
     * Dohvata kategoirju statičkih strana po id-u
     * @param   int         $category_id        Id kategorije
     * @return  Category    Vraća objekat kategorije
     */
    public static function getCategoryById($category_id) {
        return self::$entity_manager->find('App\Models\StaticPages\Category', $category_id);
    }

    /**
     * Dohvata stranicu po id-u
     * @param   int         $page_id        Id stranice
     * @return  Page        Vraća objekat stranice
     */
    public static function getPageById($page_id) {
        return self::$entity_manager->find('App\Models\StaticPages\Page', $page_id);
    }



    /**
     * Dohvata sve kategorije statičkih strana
     * @return  array       Vraća niz objekata
     */
    public static function getAllPages() {
        return self::$entity_manager->getRepository('App\Models\StaticPages\Page')->findAll();
    }

    /**
     * Dohvata sve kategorije statičkih strana
     * @return  array       Vraća niz objekata
     */
    public static function getAllCategories() {
        return self::$entity_manager->getRepository('App\Models\StaticPages\Category')->findAll();
    }

    /**
     * Dohvata strane po id-u kategorije
     * @param   int         $category_id        Id kategorije
     * @return  array       Vraća niz objekata
     */
    public static function getAllPagesByCategoryId($category_id) {
        return self::$entity_manager->createQueryBuilder()
            ->select('p')
            ->from('App\Models\StaticPages\Page', 'p')
            ->where('p.category_id = :category_id')
            ->setParameter('category_id', $category_id)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     *
     * READ
     *
     */

    /**
     * Izmena kategorije
     * @param   int             $category_id        Id kategorije
     * @param   array           $updates            Niz sa izmenama
     * @return  bool            Vraća true ako je sve prošlo uredu
     */
    public static function updateCategory($category_id, $updates) {
        if (PermissionService::checkPermission('category_static_update') === false) {
            throw new PermissionException('Nemate dozvolu za izmene kategorije statičkih strana', 20006);
        }

        $category = self::getCategoryById($category_id);

        if (array_key_exists('name', $updates)) {
            if (ValidationService::validateString($updates['name'], 63) === false) {
                throw new ValidationException('Ime kategorije nije odgovarajućeg formata', 20007);
            }

            $category->name = $updates['name'];
        }

        if (!empty($updates)) {
            self::$entity_manager->persist($category);
            self::$entity_manager->flush();
        }

        return true;
    }

    /**
     * Izmena stranice
     * @param   int         $page_id        Id stranice
     * @param   array       $updates        Niz sa izmena
     * @return  bool        Vraća true ako je sve prošlo uredu
     */
    public static function updatePage($page_id, $updates) {
        if (PermissionService::checkPermission('staticPage_update') === false) {
            throw new PermissionException('Nemate dozvolu za izmenu statičkih strana', 20008);
        }

        $page = self::getPageById($page_id);

        if (array_key_exists('category_id', $updates)) {
            $category = self::getCategoryById($updates['category_id']);
            if (empty($category)) {
                throw new ValidationException('Kategorija sa tim id-om nije pronađena', 20009);
            }

            $page->category = $category;
        }

        if (array_key_exists('title', $updates)) {
            if (ValidationService::validateString($updates['title'], 127) === false) {
                throw new ValidationException('Naslov stranice nije odgovarajućeg formata', 20010);
            }

            $page->title = $updates['title'];
        }

        if (array_key_exists('text', $updates)) {
            if (ValidationService::validateString($updates['text']) === false) {
                throw new ValidationException('Tekst nije odgovarajućeg formata', 20011);
            }

            $page->text = $updates['text'];
        }

        if (array_key_exists('order_page', $updates)) {
            self::reorder($page_id, $updates['order_page']);
        }

        if (!empty($updates)) {
            self::$entity_manager->persist($page);
            self::$entity_manager->flush();
        }

        return true;
    }

    /**
     * Provera dali postoj kategorija sa tim imenom
     * @param   string      $name       Ime kategorije
     * @return  boolean     Vraća true ako postoji ili false ako ne postoji
     */
    public static function isCategoryNameTaken($name) {
        return !empty(self::$entity_manager->createQueryBuilder()
            ->select('c')
            ->from('App\Models\StaticPages\Category', 'c')
            ->where('c.name = :name')
            ->setParameter('name', $name)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult())
        ;
    }


    public static function isPageNameTaken($name) {
        return !empty(self::$entity_manager->createQueryBuilder()
            ->select('p')
            ->from('App\Models\StaticPages\Page', 'p')
            ->where('p.title = :name')
            ->setParameter('name', $name)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult())
        ;
    }

    /**
     * Menja redosle stranici
     * @param   int     $page_id            Id stranice
     * @param   int     $order_page         Novi redosled
     * @return  void
     */
    public static function reorder($page_id, $order_page) {
        if (PermissionService::checkPermission('staticPage_update') === false) {
            throw new PermissionException('Nemate dozvolu za izmenu redosleda statičkih stranica', 20012);
        }

        $page = self::getPageById($page_id);

        if ($page->order_page !== $order_page) {
            $increment  =   $page->order_page > $order_page ? 1 : -1;
            $direction  =   $page->order_page > $order_page ? 'desc' : 'asc';
            $range      =   $page->order_page > $order_page ?
                [
                    'MIN' => $order_page,
                    'MAX' => $page->order_page
                ]
                :
                [
                    'MIN' => $page->order_page + 1,
                    'MAX' => $order_page
                ];

            $page->order_page = 0;

            self::$entity_manager->persist($page);
            self::$entity_manager->flush();

            $pages = self::$entity_manager->createQueryBuilder()
                ->select('p')
                ->from('App\Models\StaticPages\Page', 'p')
                ->where('p.order_page BETWEEN :MIN AND :MAX')
                ->setParameter('MIN', $range['MIN'])
                ->setParameter('MAX', $range['MAX'])
                ->orderBy('p.order_page', $direction)
                ->getQuery()
                ->getResult()
            ;

            foreach ($pages as $p) {
                $p->order_page += $increment;

                self::$entity_manager->persist($p);
                self::$entity_manager->flush();
            }

            $page->order_page = $order_page;

            self::$entity_manager->persist($page);
            self::$entity_manager->flush();
        }
    }

    /**
     *
     * DELETE
     *
     */

    /**
     * Brisanje kategorije statičkih strana
     * @param   int         $category_id        Id kategorije
     * @return  bool        Vraća true
     */
    public static function deleteCategory($category_id) {
        if (PermissionService::checkPermission('category_static_delete') === false) {
            throw new PermissionException('Nemate dozvolu za brisanje kategorija statičkih stranica', 20013);
        }

        $category = self::getCategoryById($category_id);

        self::$entity_manager->remove($category);
        self::$entity_manager->flush();

        return true;
    }

    public static function deletePage($page_id) {
        if (PermissionService::checkPermission('staticPage_delete') === false) {
            throw new PermissionException('Nemate dozvolu za brisanje statičkih stranica', 20014);
        }

        $page = self::getPageById($page_id);

        $pages = self::$entity_manager->createQueryBuilder()
            ->select('p')
            ->from('App\Models\StaticPages\Page', 'p')
            ->where('p.order_page > :order_page')
            ->setParameter('order_page', $page->order_page)
            ->getQuery()
            ->getResult()
        ;

        self::$entity_manager->remove($page);
        self::$entity_manager->flush();

        SEOService::deleteByMachineName('static_' . $page_id);

        foreach ($pages as $p) {
            $p->order_page -= 1;

            self::$entity_manager->persist($p);
            self::$entity_manager->flush();
        }

        return true;
    }
}
