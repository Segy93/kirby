<?php

namespace App\Providers;

use App\Providers\SEOService;
use App\Models\Category;

class CategoryService extends BaseService {
    private static $category_tree = [
        [
            'title' => 'Kirby',
            'url'   => 'kirby-sistem',
        ],
        [
            'title' => 'Kese',
            'url'   => 'kese',
        ],
        [
            'title' => 'Hemija',
            'url'   => 'sredstva-za-ciscenje',
        ],
        [
            'title' => 'Delovi',
            'url'   => 'delovi',
        ],
        [
            'title' => 'Oprema',
            'url'   => 'oprema',
        ],
        [
            'title' => 'Rebuild',
            'url'   => 'rebuild',
        ],
    ];

    private static $categories_promoted = [
        [
            'url'  => 'kirby-sistem',
            'img'  => '/default_pictures/a2-ultimate-cleaning.jpg',
            'title' => 'Kirby sistem',
        ],
        [
            'url'  => 'kese',
            'img'  => '/default_pictures/crvene.jpeg',
            'title' => 'Kese',
        ],
        [
            'url'  => 'sredstva-za-ciscenje',
            'img'  => '/default_pictures/shampoo.jpeg',
            'title' => 'Sredstva za čišćenje',
        ],
        [
            'url'  => 'delovi',
            'img'  => '/default_pictures/remen.jpg',
            'title' => 'Rezervni delovi',
        ],
        [
            'url'  => 'oprema',
            'img'  => '/default_pictures/zipp-brush.jpg',
            'title' => 'Dodatna oprema',
        ],
        [
            'url'  => 'rebuild',
            'img'  => '/default_pictures/g6.webp',
            'title' => 'Rebuild',
        ],
    ];










    /**
     *
     * READ
     *
     */










    /**
     * Dohvata sve kategorije
     * @return  array Vraća niz objekata
     */
    public static function getAllCategories() {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('c')
            ->from('App\Models\Category', 'c')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Dohvata kategoriju po id-u
     * @param       int         $category_id    Id kategorije
     * @return      Object      Vraća objekat kategorije
     */
    public static function getCategoryById($category_id) {
        return self::$entity_manager->find('App\Models\Category', $category_id);
    }

    public static function getCategoryByName($category_name) {
        $qb = self::$entity_manager->createQueryBuilder();
        return $qb
            ->select('c')
            ->from('App\Models\Category', 'c')
            ->where('c.name = ?1')
            ->setParameter(1, $category_name)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public static function getCategoryFiltersNames($category_id) {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('a.name_import')
            ->from('App\Models\Attribute', 'a')
            ->where('a.category_id = ?1')
            ->setParameter(1, $category_id)
            ->andWhere('a.order_filter IS NOT NULL')
            ->orderBy('a.order_filter', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Dohvata flitere za kategoriju
     * @param   int      $category_id       Id kategorije
     * @return  array    $filters           Vraća niz sa filterima i vrednostima
     */
    public static function getCategoryFilters($category_id) {
        $qb         = self::$entity_manager->createQueryBuilder();
        $qb_price   = self::$entity_manager->createQueryBuilder();

        $additional_filters = $qb
            ->select('a')
            ->from('App\Models\Attribute', 'a')
            ->where('a.category_id = ?1')
            ->setParameter(1, $category_id)
            ->andWhere('a.order_filter IS NOT NULL')
            ->orderBy('a.order_filter', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        $price = $qb_price
            ->select('min(p.price_retail), max(p.price_retail)')->from('App\Models\Product', 'p')
            ->where('p.category_id = ?1')
            ->setParameter(1, $category_id)
            ->andWhere('p.price_discount > 0 OR p.presales = 1')
            ->andWhere('p.price_retail > 0 OR p.presales = 1')
            ->getQuery()
            ->getResult()
        ;


        $filters = [];
        $price = array_pop($price);

        $filters['cena'] = [
            'label'         =>  'Cena',
            'type'          =>  'slider',
            'name_import'   =>  'price_retail',
            'machine_name'  =>  'price_retail',
            'min'           =>  $price[1],
            'max'           =>  $price[2],
        ];

        $filters['stanje'] = [
            'name'          =>  'Stanje',
            'label'         =>  'stock',
            'type'          =>  'checkbox',
            'name_import'   =>  'stock',
            'machine_name'  =>  'stock',
            'values'        =>  [
                'stock' =>  'Raspoloživo',
            ],
        ];

        $filters['akcija'] = [
            'name'          =>  'Akcija',
            'label'         =>  'on_sale',
            'type'          =>  'checkbox',
            'name_import'   =>  'on_sale',
            'machine_name'  =>  'on_sale',
            'values'        =>  [
                'on_sale' =>  'Na akciji',
            ],
        ];

        $filters['pretprodaja'] = [
            'name'          =>  'Pretprodaja',
            'label'         =>  'presales',
            'type'          =>  'checkbox',
            'name_import'   =>  'presales',
            'machine_name'  =>  'presales',
            'values'        =>  [
                'presales' =>  'Na pretprodaji',
            ],
        ];

        foreach ($additional_filters as $a_f) {
            $filters[$a_f->label] = [
                'label'         =>  $a_f->label,
                'type'          =>  $a_f->type,
                'name_import'   =>  $a_f->name_import,
                'machine_name'  =>  $a_f->machine_name,
            ];

            $values = $a_f->attribute_values;

            $filters[$a_f->label]['values'] = [];

            foreach ($values as $value) {
                $value_name = strtolower($value->value);
                $filters[$a_f->label]['values'][$value->id] = $value->value;
            }
        }



        return $filters;
    }

    /**
     * Mapiranje url-a, koje polje za koji atribut vezano
     * @param   int         $category_id        Id kategorije
     * @return  array       Vraća niz objekata
     */
    public static function getUrlMappings($category_id) {
        $qb = self::$entity_manager->createQueryBuilder();

        $mappings = $qb->select('a.machine_name, a.order_url')->from('App\Models\Attribute', 'a')
        ->where('a.category_id = :category_id')
        ->setParameter('category_id', $category_id)
        ->andWhere('a.order_url IS NOT NULL')
        ->orderBy('a.order_url')
        ->getQuery()->getResult();

        $keys = array_column($mappings, 'order_url');
        $values = array_column($mappings, 'machine_name');

        return array_combine($keys, $values);
    }

    /**
     * Dohvata kategoriju po polju name_import
     * @param   string      $name_import        Naziv kategorije iz međubaze
     * @return  Category    Vraća kategoriju
     */
    public static function getCategoryByNameImport($name_import) {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('c')
            ->from('App\Models\Category', 'c')
            ->where('c.name_import = :name_import')
            ->setParameter('name_import', $name_import)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Get's category attributes, optional can be sorted by sorting columns
     *
     * @param   int[]       $category_ids       ID of categories
     * @param   string      $order_column       Order column(optinal)
     * @return  array       $attributes         Returns array of attributes
     */
    public static function getCategoryAttributes($category_ids, $order_column = null) {
        $qb = self::$entity_manager->createQueryBuilder();

        $query = $qb
            ->select('a.label')
            ->from('App\Models\Attribute', 'a')
            ->groupBy('a.label')
            ->where('a.category_id IN (:category_ids)')
            ->setParameter('category_ids', $category_ids)
            ->orderBy('MIN(a.order_product)')
        ;

        if (!empty($order_column)) {
            $query
                ->andWhere('a.' . $order_column . ' IS NOT NULL')
                ->orderBy('a.' . $order_column, 'ASC')
            ;
        }

        $result = $query
            ->getQuery()
            ->getResult()
        ;

        return array_column($result, 'label');
    }

    public static function getCategoryTree() {
        return self::$category_tree;
    }

    public static function getCategorySubtree(string $parent) {
        foreach (self::$category_tree as $group) {
            if ($group['url'] === $parent) {
                $result = [];
                foreach ($group['sub'] as $section) {
                    $result = array_merge($result, $section);
                }

                return $result;
            }
        }

        return [];
    }

    public static function getCategoriesPromoted() {
        return self::$categories_promoted;
    }
}
