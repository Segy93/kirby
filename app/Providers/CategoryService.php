<?php

namespace App\Providers;

use App\Providers\SEOService;
use App\Models\Category;

class CategoryService extends BaseService {
    private static $category_tree = [
        [
            'title' => 'Mobilni računari',
            'url'   => 'mobilni-računari',
            'sub'   => [
                [
                    [
                        'title' => 'Laptopovi',
                        'url'   => 'laptopovi',
                        'img'   => '/default_pictures/CategoryPictures/laptopovi.png',
                    ],
                    [
                        'title' => 'Torbe',
                        'url'   => 'torbe',
                        'img'   => '/default_pictures/CategoryPictures/torbe.png',
                    ],
                    [
                        'title' => 'Punjači',
                        'url'   => 'notebook-punjaci',
                        'img'   => '/default_pictures/CategoryPictures/punjaci.png',
                    ],
                    // [
                    //     'title' => 'Netbook',
                    //     'url'   => 'netbook',
                    //     'img'   => '/default_pictures/CategoryPictures/netbook.png',
                    // ],
                ],
                [
                    [
                        'title' => 'TABLET PC',
                        'url'   => 'tableti',
                        'img'   => '/default_pictures/CategoryPictures/tableti.png',
                    ],
                    [
                        'title' => 'Dodatna oprema',
                        'url'   => 'dodatna-oprema-za-laptopove',
                        'img'   => '/default_pictures/CategoryPictures/baterije.png',
                    ],
                ],
            ],
        ],

        [
            'title' => 'Računari',
            'url'   => 'računari',
            'sub'   => [
                [
                    [
                        'title' => 'PC Desktop',
                        'url'   => 'elite-pc',
                        'img'   => '/default_pictures/CategoryPictures/pc-desktop.png',
                    ],
                    [
                        'title' => 'PC Brand',
                        'url'   => 'pc-brand',
                        'img'   => '/default_pictures/CategoryPictures/pc-brand.png',
                    ],
                    [
                        'title' => 'All in One',
                        'url'   => 'all-in-one',
                        'img'   => '/default_pictures/CategoryPictures/all-in-one.png',
                    ],
                    [
                        'title' => 'Serveri',
                        'url'   => 'serveri',
                        'img'   => '/default_pictures/CategoryPictures/serveri.png',
                    ],
                    [
                        'title' => 'Bundle Kits',
                        'url'   => 'bundle-kit',
                        'img'   => '/default_pictures/CategoryPictures/bundle.png',
                    ],
                ],
                [
                    [
                        'title' => 'Procesori',
                        'url'   => 'procesori',
                        'img'   => '/default_pictures/CategoryPictures/procesori.png',
                    ],
                    [
                        'title' => 'Matične ploče',
                        'url'   => 'maticne-ploce',
                        'img'   => '/default_pictures/CategoryPictures/maticne-ploce.png',
                    ],
                    [
                        'title' => 'Grafičke karte',
                        'url'   => 'graficke-karte',
                        'img'   => '/default_pictures/CategoryPictures/graficke-karte.png',
                    ],
                    [
                        'title' => 'Hard diskovi',
                        'url'   => 'hard-diskovi',
                        'img'   => '/default_pictures/CategoryPictures/hard-diskovi.png',
                    ],
                    [
                        'title' => 'SSD',
                        'url'   => 'ssd-diskovi',
                        'img'   => '/default_pictures/CategoryPictures/ssd-diskovi.png',
                    ],
                    [
                        'title' => 'Nas i rack uređaji',
                        'url'   => 'nas-rack-uredjaji',
                        'img'   => '/default_pictures/CategoryPictures/nas-rack.png',
                    ],
                    [
                        'title' => 'Kućišta',
                        'url'   => 'kucista',
                        'img'   => '/default_pictures/CategoryPictures/kucista.png',
                    ],
                ],
                [
                    [
                        'title' => 'Memorije',
                        'url'   => 'ram-memorija',
                        'img'   => '/default_pictures/CategoryPictures/memorije.png',
                    ],
                    [
                        'title' => 'Napajanja',
                        'url'   => 'napajanja',
                        'img'   => '/default_pictures/CategoryPictures/napajanja.png',
                    ],
                    [
                        'title' => 'Kuleri',
                        'url'   => 'kuleri',
                        'img'   => '/default_pictures/CategoryPictures/rashladni-uredjaji.png',
                    ],
                    [
                        'title' => 'Paste za kulere',
                        'url'   => 'termalne-paste',
                        'img'   => '/default_pictures/CategoryPictures/paste.png',
                    ],
                    [
                        'title' => 'Optički uređaji',
                        'url'   => 'opticki-uredjaji',
                        'img'   => '/default_pictures/CategoryPictures/opticki-uredjaji.png',
                    ],
                    [
                        'title' => 'Zvučne kartice',
                        'url'   => 'zvucne-karte',
                        'img'   => '/default_pictures/CategoryPictures/zvucne.jpg',
                    ],
                    [
                        'title' => 'Kontroleri',
                        'url'   => 'kontroleri',
                        'img'   => '/default_pictures/CategoryPictures/kontroleri.png',
                    ],
                ]
            ],
        ],

        [
            'title' => 'Periferije i oprema',
            'url'   => 'periferije-i-oprema',
            'sub'   => [
                [
                    [
                        'title' => 'Skeneri',
                        'url'   => 'skeneri',
                        'img'   => '/default_pictures/CategoryPictures/skeneri.png',
                    ],
                    [
                        'title' => 'Eksterni hdd',
                        'url'   => 'eksterni-hdd',
                        'img'   => '/default_pictures/CategoryPictures/eksterni-hdd.png',
                    ],
                    [
                        'title' => 'Zvučnici',
                        'url'   => 'zvucnici',
                        'img'   => '/default_pictures/CategoryPictures/zvucnici.png',
                    ],
                    [
                        'title' => 'Web kamere',
                        'url'   => 'web-kamere',
                        'img'   => '/default_pictures/CategoryPictures/web-kamere.png',
                    ],
                    [
                        'title' => 'Potrošni za štampače',
                        'url'   => 'potrosni-za-stampace',
                        'img'   => '/default_pictures/CategoryPictures/potrosni-za-stampace.png',
                    ],
                    [
                        'title' => 'Kablovi',
                        'url'   => 'kablovi',
                        'img'   => '/default_pictures/CategoryPictures/kablovi.jpg',
                    ],
                    [
                        'title' => 'Hub uređaji',
                        'url'   => 'hub',
                        'img'   => '/default_pictures/CategoryPictures/hub.png',
                    ],
                    [
                        'title' => 'Čitači kartica',
                        'url'   => 'citaci',
                        'img'   => '/default_pictures/CategoryPictures/citacikartica.jpg',
                    ],
                ],
                [
                    [
                        'title' => 'Tastature',
                        'url'   => 'tastature',
                        'img'   => '/default_pictures/CategoryPictures/tastature.png',
                    ],
                    [
                        'title' => 'Miševi',
                        'url'   => 'misevi',
                        'img'   => '/default_pictures/CategoryPictures/misevi.png',
                    ],
                    [
                        'title' => 'Oprema za igranje',
                        'url'   => 'oprema-za-igranje',
                        'img'   => '/default_pictures/CategoryPictures/oprema-za-igranje.png',
                    ],
                    [
                        'title' => 'Slušalice',
                        'url'   => 'slusalice',
                        'img'   => '/default_pictures/CategoryPictures/slusalice.png',
                    ],
                    [
                        'title' => 'Mikrofoni',
                        'url'   => 'mikrofoni',
                        'img'   => '/default_pictures/CategoryPictures/mikrofoni.png',
                    ],
                    [
                        'title' => 'Mediji',
                        'url'   => 'mediji',
                        'img'   => '/default_pictures/CategoryPictures/mediji.jpg',
                    ],
                    [
                        'title' => 'Fleševi',
                        'url'   => 'flash-memorija',
                        'img'   => '/default_pictures/CategoryPictures/flash.png',
                    ],
                    [
                        'title' => 'Memorijske kartice',
                        'url'   => 'memorijske-kartice',
                        'img'   => '/default_pictures/CategoryPictures/sd.png',
                    ],
                    [
                        'title' => 'Software',
                        'url'   => 'software',
                        'img'   => '/default_pictures/CategoryPictures/software.png',
                    ],
                ],
                [
                    [
                        'title' => 'Antene',
                        'url'   => 'antene',
                        'img'   => '/default_pictures/CategoryPictures/antene.jpg',
                    ],
                    [
                        'title' => 'Wifi kartice i adapteri',
                        'url'   => 'wifi-kartice-i-adapteri',
                        'img'   => '/default_pictures/CategoryPictures/wifi-kartice-i-adapteri.png',
                    ],
                    [
                        'title' => 'Ruteri',
                        'url'   => 'ruteri',
                        'img'   => '/default_pictures/CategoryPictures/ruteri.png',
                    ],
                    [
                        'title' => 'Switch',
                        'url'   => 'switch',
                        'img'   => '/default_pictures/CategoryPictures/switch.png',
                    ],
                    [
                        'title' => 'Produžni kablovi',
                        'url'   => 'prednaponska-zastita',
                        'img'   => '/default_pictures/CategoryPictures/strujna-zastita.png',
                    ],
                    [
                        'title' => 'Ups',
                        'url'   => 'ups',
                        'img'   => '/default_pictures/CategoryPictures/ups.png',
                    ],
                    [
                        'title' => 'Grafičke table',
                        'url'   => 'graficke-table',
                        'img'   => '/default_pictures/CategoryPictures/graficke-table.png',
                    ],
                    [
                        'title' => 'Dronovi',
                        'url'   => 'akcione-kamere-i-dronovi',
                        'img'   => '/default_pictures/CategoryPictures/dronovi.png',
                    ],
                    [
                        'title' => 'Stolovi i stolice',
                        'url'   => 'sto-i-stolice',
                        'img'   => '/default_pictures/CategoryPictures/stolice.png',
                    ],
                    [
                        'title' => 'Alat',
                        'url'   => 'alat',
                        'img'   => '/default_pictures/CategoryPictures/alat.jpg',
                    ],
                ],
            ]
        ],

        [
            'title' => 'Potrošačka elektronika',
            'url'   => 'potrošačka-elektronika',
            'sub'   => [
                [
                    [
                        'title' => 'Televizori',
                        'url'   => 'televizori',
                        'img'   => '/default_pictures/CategoryPictures/televizori.png',
                    ],
                    [
                        'title' => 'Mobilni telefoni',
                        'url'   => 'mobilni-telefoni',
                        'img'   => '/default_pictures/CategoryPictures/mobilni-telefoni.png',
                    ],
                    [
                        'title' => 'Fiksni telefoni',
                        'url'   => 'fiksni-telefoni',
                        'img'   => '/default_pictures/CategoryPictures/fiksni-telefoni.png',
                    ],
                    [
                        'title' => 'Media player',
                        'url'   => 'media-player',
                        'img'   => '/default_pictures/CategoryPictures/mediaplayer.png',
                    ],
                    [
                        'title' => 'Auto player',
                        'url'   => 'auto-player',
                        'img'   => '/default_pictures/CategoryPictures/autoplayer.png',
                    ],
                ],
                [
                    [
                        'title' => 'Projektori',
                        'url'   => 'projektori',
                        'img'   => '/default_pictures/CategoryPictures/projektori.png',
                    ],
                    [
                        'title' => 'Platna za projektore',
                        'url'   => 'platna-za-projektore',
                        'img'   => '/default_pictures/CategoryPictures/platna-za-projektore.png',
                    ],
                    [
                        'title' => 'Nosači za projektore',
                        'url'   => 'nosaci',
                        'img'   => '/default_pictures/CategoryPictures/nosaci-za-projektore.png',
                    ],
                ],
                [
                    [
                        'title' => 'Fotoaparati',
                        'url'   => 'digitalni-fotoaparati',
                        'img'   => '/default_pictures/CategoryPictures/fotoaparati.png',
                    ],
                    [
                        'title' => 'Digitalne kamere',
                        'url'   => 'digitalne-kamere',
                        'img'   => '/default_pictures/CategoryPictures/digitalne-kamere.png',
                    ],
                    [
                        'title' => 'Dvd player',
                        'url'   => 'dvd-blueray-player',
                        'img'   => '/default_pictures/CategoryPictures/dvd-blueray-player.png',
                    ],
                    [
                        'title' => 'Mini linije',
                        'url'   => 'muzicke-linije',
                        'img'   => '/default_pictures/CategoryPictures/mini-linije.png',
                    ],
                    [
                        'title' => 'Navigacije',
                        'url'   => 'navigacije',
                        'img'   => '/default_pictures/CategoryPictures/navigacije.png',
                    ],
                    [
                        'title' => 'Klime',
                        'url'   => 'klima-uredjaji',
                        'img'   => '/default_pictures/CategoryPictures/klime.png',
                    ],
                ],
            ]
        ],

        [
            'title' => 'Monitori',
            'url'   => 'monitori',
            'img'   => '/default_pictures/CategoryPictures/monitori.png',
        ],

        [
            'title' => 'Štampači',
            'url'   => 'stampaci',
            'img'   => '/default_pictures/CategoryPictures/stampaci.png',
        ],
    ];

    private static $categories_promoted = [
        [
            'url'  => 'maticne-ploce',
            'img'  => '/default_pictures/maticne.png',
            'title' => 'Matične ploče',
        ],
        [
            'url'  => 'procesori',
            'img'  => '/default_pictures/procesori.png',
            'title' => 'Procesori',
        ],
        [
            'url'  => 'računari',
            'img'  => '/default_pictures/racunari.png',
            'title' => 'Računari',
        ],
        [
            'url'  => 'laptopovi',
            'img'  => '/default_pictures/laptopovi.png',
            'title' => 'Laptopovi',
        ],
        [
            'url'  => 'tableti',
            'img'  => '/default_pictures/tableti.png',
            'title' => 'Tableti',
        ],
        [
            'url'  => 'graficke-karte',
            'img'  => '/default_pictures/graficke.png',
            'title' => 'Grafičke kartice',
        ],
        [
            'url'  => 'ram-memorija',
            'img'  => '/default_pictures/memorije.png',
            'title' => 'Memorije',
        ],
        [
            'url'  => 'hard-diskovi',
            'img'  => '/default_pictures/hardovi.png',
            'title' => 'Hard diskovi',
        ],
        [
            'url'  => 'ssd-diskovi',
            'img'  => '/default_pictures/ssd.png',
            'title' => 'SSD',
        ],
        [
            'url'  => 'bundle-kit',
            'img'  => '/default_pictures/bundle.png',
            'title' => 'Bundle kit',
        ],
        [
            'url'  => 'televizori',
            'img'  => '/default_pictures/televizori.png',
            'title' => 'Televizori',
        ],
        [
            'url'  => 'monitori',
            'img'  => '/default_pictures/monitori.png',
            'title' => 'Monitori',
        ],
        [
            'url'  => 'kucista',
            'img'  => '/default_pictures/kucista.png',
            'title' => 'Kućišta',
        ],
        [
            'url'  => 'napajanja',
            'img'  => '/default_pictures/napajanja.png',
            'title' => 'Napajanja',
        ],
        [
            'url'  => 'kuleri',
            'img'  => '/default_pictures/rashladnaoprema.png',
            'title' => 'Rashladna oprema',
        ],
        [
            'url'  => 'stampaci',
            'img'  => '/default_pictures/stampaci.png',
            'title' => 'Štampači',
        ],
        [
            'url'  => 'skeneri',
            'img'  => '/default_pictures/skeneri.png',
            'title' => 'Skeneri',
        ],
        [
            'url'  => 'ruteri',
            'img'  => '/default_pictures/ruteri.png',
            'title' => 'Ruteri',
        ],
        [
            'title' => 'Tastature',
            'url'   => 'tastature',
            'img'   => '/default_pictures/CategoryPictures/tastature.png',
        ],
        [
            'title' => 'Miševi',
            'url'   => 'misevi',
            'img'   => '/default_pictures/CategoryPictures/misevi.png',
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
