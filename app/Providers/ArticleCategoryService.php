<?php

namespace App\Providers;

use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;
use App\Models\Articles\Category;
use App\Providers\ArticleService;
use App\Providers\FolderService;
use App\Providers\PermissionService;
use App\Providers\SEOService;
use App\Providers\ValidationService;

class ArticleCategoryService extends BaseService {
    // Ime foldera
    private static $folder_path = 'uploads_gallery/Clanci';
    private static $folder_path_originals = 'originals';










    /**
     *
     * CREATE
     *
     */










    /**
     * Kreira kategoriju
     * @param   string          $name       Ime kategorije
     * @param   string          $picture    Slika
     * @return  Category/int    $category   Vraća objekat kategorije ako je sve prošlo uredu inače vraća error_code
     */
    public static function create($name, $picture) {
        if (PermissionService::checkPermission('articleCategory_create') === false) {
            throw new PermissionException('Nemate dozvolu za kreiranje kategorija članaka', 3001);
        }

        $name = ValidationService::validateString($name, 63);
        if ($name === false) {
            throw new ValidationException('Ime kategorije nije odgovarajućeg formata', 3002);
        }

        if (self::isCategoryNameTaken($name)) {
            throw new ValidationException('Ime kategorije već postoji', 3007);
        }

        $max = self::$entity_manager->createQueryBuilder()
            ->select('MAX(c.order_category)')
            ->from('App\Models\Articles\Category', 'c')
            ->getQuery()
            ->getSingleScalarResult()
            + 1
        ;

        $category = new Category();

        $category->name             =   $name;
        $category->picture          =   ImageService::uploadImage($picture, self::$static_originals);
        $category->order_category   =   $max;

        self::$entity_manager->persist($category);
        self::$entity_manager->flush();

        $folder_name = self::$folder_path . '/' . $category->name;
        FolderService::createFolder($folder_name);

        return $category->id;
    }










    /**
     *
     * READ
     *
     */










    /**
     * Vraća niz objekata kategorija članaka
     * @return  array       Niz objekata
     */
    public static function getAll() {
        return self::$entity_manager->createQueryBuilder()
            ->select('c')
            ->from('App\Models\Articles\Category', 'c')
            ->orderBy('c.order_category', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Vraća jednu kategoriju
     * @param   int             $category_id    Id kategorije
     * @return  Category        Vraća kategoriju ako je sve prošlu uredu inače vraća error_code
     */
    public static function getByID($category_id) {
        return self::$entity_manager->find('App\Models\Articles\Category', $category_id);
    }

    /**
     * Provera dali postoj članak sa tim imenom
     * @param   string      $name       Ime članka
     * @return  boolean     Vraća true ako postoji ili false ako ne postoji
     */
    public static function isCategoryNameTaken($name) {
        return !empty(self::$entity_manager->createQueryBuilder()
            ->select('c')
            ->from('App\Models\Articles\Category', 'c')
            ->where('c.name = :name')
            ->setParameter('name', $name)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult())
        ;
    }

    /**
     * Vraća kategoriju po redosledu
     * @param   int         $order_category      Redosled
     * @return  Category    Vraća kategoriju
     */
    public static function getCategoryByOrder($order_category) {
        return self::$entity_manager->createQueryBuilder()
            ->select('c')
            ->from('App\Models\Articles\Category', 'c')
            ->where('c.order_category = :order_category')
            ->setParameter('order_category', $order_category)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }










    /**
     *
     * UPDATE
     *
     */










    /**
     * Radi izmenu kategorije
     * @param   int             $category_id    Id kategorije
     * @param   array           $updates        Niz sa izmenama
     * @return  Category/int    $category       Vraća kategoriju ako je sve prošlo uredu inače vraća error_code
     */
    public static function update($category_id, $updates) {
        if (PermissionService::checkPermission('articleCategory_update') === false) {
            throw new PermissionException('Nemate dozvolu za izmenu kategorije članaka', 3003);
        }

        $category = self::getById($category_id);

        if (array_key_exists('name', $updates)) {
            $updates['name'] = ValidationService::validateString($updates['name'], 63);
            if ($updates['name'] === false) {
                throw new ValidationException('Ime kategorije nije odgovarajućeg formata', 3004);
            }

            $category->name = $updates['name'];

            $path_old = self::$folder_path . '/' . $category->name;
            $path_new = self::$folder_path . '/' . $updates['name'];

            FolderService::updateFolder($path_old, $path_new);
            ArticleService::updateImagePath($path_old, $path_new);
        }

        if (array_key_exists('picture', $updates)) {
            if (!empty($category->picture)) {
                ImageService::deletePictures($category->picture, self::$static_folder);
            }

            $image_path = self::$static_folder . '/' . self::$folder_path_originals;
            $picture = ImageService::uploadImage($updates['picture'], $image_path);
            $category->picture = ImageService::getImageName($image_path . '/' . $picture);
        }

        if (array_key_exists('order', $updates)) {
            self::reorder($category_id, $updates['order']);
        }

        if (!empty($updates)) {
            self::$entity_manager->persist($category);
            self::$entity_manager->flush();
        }

        return $category->id;
    }

    /**
     * Menja redosled kategoriji
     * @param   int     $category_id        Id kategorije
     * @param   int     $order_category     Novi redosled
     * @return  void
     */
    public static function reorder($category_id, $order_category) {
        if (PermissionService::checkPermission('articleCategory_update') === false) {
            throw new PermissionException('Nemate dozvolu za izmenu redosleda kategorije članaka', 3005);
        }

        $category = self::getByID($category_id);

        if ($category->order_category !== $order_category) {
            $increment  =   $category->order_category > $order_category ? 1 : -1;
            $direction  =   $category->order_category > $order_category ? 'desc' : 'asc';
            $range      =   $category->order_category > $order_category ?
                [
                    'MIN' => $order_category,
                    'MAX' => $category->order_category
                ]
                :
                [
                    'MIN' => $category->order_category + 1,
                    'MAX' => $order_category
                ];

            $category->order_category = 0;

            self::$entity_manager->persist($category);
            self::$entity_manager->flush();

            $categories = self::$entity_manager->createQueryBuilder()
                ->select('c')
                ->from('App\Models\Articles\Category', 'c')
                ->where('c.order_category BETWEEN :MIN AND :MAX')
                ->setParameter('MIN', $range['MIN'])
                ->setParameter('MAX', $range['MAX'])
                ->orderBy('c.order_category', $direction)
                ->getQuery()
                ->getResult()
            ;

            foreach ($categories as $c) {
                $c->order_category += $increment;

                self::$entity_manager->persist($c);
                self::$entity_manager->flush();
            }

            $category->order_category = $order_category;

            self::$entity_manager->persist($category);
            self::$entity_manager->flush();
        }
    }










    /**
     *
     * DELETE
     *
     */










    /**
     * Briše kategoriju
     * @param   int     $category_id        Id kategorije
     * @return  bool    Vraća true ako je sve prošlo uredu
     */
    public static function delete(int $category_id) {
        if (PermissionService::checkPermission('articleCategory_delete') === false) {
            throw new PermissionException('Nemate dozvolu za brisanje kategorije članaka', 3006);
        }

        $category           = self::getByID($category_id);
        $full_folder_path   = self::$folder_path . '/' . $category->name;
        $picture            = $category->picture;

        $categories = self::$entity_manager->createQueryBuilder()
            ->select('c')
            ->from('App\Models\Articles\Category', 'c')
            ->where('c.order_category > :order_category')
            ->setParameter('order_category', $category->order_category)
            ->getQuery()
            ->getResult()
        ;

        self::$entity_manager->remove($category);
        self::$entity_manager->flush();

        foreach ($categories as $c) {
            $c->order_category -= 1;

            self::$entity_manager->persist($c);
            self::$entity_manager->flush();
        }

        FolderService::deleteFolder($full_folder_path);
        ImageService::deletePictures($picture, self::$static_folder);
        SEOService::deleteByMachineName('articleCategory_' . $category_id);

        return true;
    }
}
