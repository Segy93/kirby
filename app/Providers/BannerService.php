<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Position;
use App\Models\PageType;
use App\Providers\CategoryService;
use App\Providers\PermissionService;
use App\Providers\ValidationService;
use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;

class BannerService extends BaseService {

    private static $fetch_limit = 10;

    /**
     *
     * CREATE
     *
     */

    /**
     * Kreiranje banera
     * @param   int         $position_id    Id pozicije
     * @param   string      $title          Naslov
     * @param   string      $image          Ime slike(prethodno mora da se sačuva i samo ime slike da se prosledi)
     * @param   string      $link           Link ka kome vodi baner
     * @param   string      $urls           Link-ovi na kojim treba baner da se pojavljuje
     * @return  Banner      $banner         Vraća objekat banner ako je sve prošlo uredu
     *                                      u suprotnom vraća neki error_code
     */
    public static function createBanner($position_id, $title, $image, $link, $urls) {
        if (PermissionService::checkPermission('banner_create') === false) {
            throw new PermissionException('Nemate dozvolu za kreiranje banera', 5001);
        }

        if ($urls === '') {
            $urls = '/';
        }

        $position_id = ValidationService::validateInteger(
            $position_id,
            ValidationService::$RANGE_INTEGER_UNSIGNED['min'],
            ValidationService::$RANGE_INTEGER_UNSIGNED['max']
        );

        if ($position_id === false) {
            throw new ValidationException('Id pozicije nije odgovarajućeg formata', 5002);
        }

        $title = ValidationService::validateString($title, 127);
        if ($title === false) {
            throw new ValidationException('Naslov nije odgovarajućeg formata', 5003);
        }

        $link = ValidationService::validateString($link, 255);
        if ($link === false) {
            throw new ValidationException('Link nije odgovarajućeg formata', 5005);
        }

        $urls = ValidationService::validateString($urls, 255);
        if ($urls === false) {
            throw new ValidationException('Linkovi gde te baner da se pojavljuje nisu odgovarajućeg formata', 5006);
        }

        $banner = new Banner();

        $position = self::getPositionById($position_id);

        $banner->position   =   $position;
        $banner->title      =   $title;
        $banner->image      =   ImageService::uploadImage($image, self::$static_originals);
        $banner->link       =   $link;

        $type               = $position->page_type->machine_name;
        $urls_formatted     = self::getFormattedUrls($urls, $type);
        $banner->urls       = $urls_formatted;

        self::$entity_manager->persist($banner);
        self::$entity_manager->flush();

        return $banner;
    }

    /**
     * Kreiranje pozivije
     * @param   int         $page_type_id   Id tipa stranice
     * @param   string      $position       Pozicija banera
     * @param   int         $image_width    Širina stranice
     * @param   int         $image_height   Visina stranice
     * @return  Position    $position       Vraća objekat pozicije ako je sve prošlo uredu
     *                                      u suprotnom vraća neki error_code
     */
    public static function createPosition($page_type_id, $position, $image_width, $image_height) {
        if (PermissionService::checkPermission('banner_create') === false) {
            throw new PermissionException('Nemate dozvolu za kreiranje pozicija banera', 5007);
        }

        $page_type_id = ValidationService::validateInteger(
            $page_type_id,
            ValidationService::$RANGE_SMALLINTEGER_UNSIGNED['min'],
            ValidationService::$RANGE_INTEGER_UNSIGNED['max']
        );

        if ($page_type_id === false) {
            throw new ValidationException('Id tipa stranice nije odgovarajućeg formata', 5008);
        }

        $position = ValidationService::validateString($position, 63);
        if ($position === false) {
            throw new ValidationException('Naziv pozicije nije odgovarajućeg formata', 5009);
        }

        $image_width = ValidationService::validateInteger(
            $image_width,
            ValidationService::$RANGE_SMALLINTEGER_UNSIGNED['min'],
            ValidationService::$RANGE_SMALLINTEGER_UNSIGNED['max']
        );
        if ($image_width === false) {
            throw new ValidationException('Širina slike nije odgovarajućeg formata', 5010);
        }

        $image_height = ValidationService::validateInteger(
            $image_height,
            ValidationService::$RANGE_SMALLINTEGER_UNSIGNED['min'],
            ValidationService::$RANGE_SMALLINTEGER_UNSIGNED['max']
        );
        if ($image_height === false) {
            throw new ValidationException('Visina slike nije odgovarajućeg formata', 5011);
        }

        $page_type = self::getPageTypeById($page_type_id);

        $positionObj = new Position();

        $positionObj->page_type = $page_type;
        $positionObj->position = $position;
        $positionObj->image_width = $image_width;
        $positionObj->image_height = $image_height;

        self::$entity_manager->persist($positionObj);
        self::$entity_manager->flush();

        return $positionObj;
    }

    /**
     * Kreira tip stranice
     * @param   string      $type           Tip stranice
     * @return  PageType    $pagetype       Vraća objekat tip stranice ako se je sve prošlo uredu
     *                                      u suprotnom vraća error_code
     */
    public static function createPageType($type) {
        if (PermissionService::checkPermission('banner_create') === false) {
            throw new PermissionException('Nemate dozvolu za kreiranje tipova strana', 5012);
        }

        $type = ValidationService::validateString($type, 127);
        if ($type === false) {
            throw new ValidationException('Tip strane nije odgovarajućeg formata', 5013);
        }

        $pagetype = new PageType();

        $pagetype->type = $type;

        self::$entity_manager->persist($pagetype);
        self::$entity_manager->flush();

        return $pagetype;
    }

    /**
     *
     * READ
     *
     */

    /**
      * Pretraga banera
      * @param  int         $banner_id      Id banera
      * @param  mixed       $search         Pretražuje po naslovu banera, poziciji ili id-u
      * @param  boolean     $direction      Smer u kojem dohvata banere (manje ili više od prosleđenog id-a)
      * @param  int         $limit          Limit koliko banera dohvata
      * @return array       $banners        Vraća niz objekat banera
      */
    public static function getAll($banner_id = null, $search = null, $direction = true, $limit = null) {
        $permission = PermissionService::checkPermission('banner_read');

        $qb = self::$entity_manager->createQueryBuilder();

        $banners = $qb
            ->select('b')
            ->from('App\Models\Banner', 'b')
            ->orderBy('b.id', 'DESC')
            ->setMaxResults($limit === null ? self::$fetch_limit : $limit)
        ;

        if ($permission === false) {
            $banners->where('b.status = true');
        }

        if (!empty($banner_id)) {
            $direction = $direction ? '<' : '>';

            $query = 'b.id ' . $direction . ' :banner_id';

            $banners
                ->andWhere($query)
                ->setParameter('banner_id', $banner_id)
            ;
        }

        if (!empty($search)) {
            $type = gettype($search);

            if ($type === 'integer') {
                $banners
                    ->andWhere('b.id = :banner_search_id')
                    ->setParameter('banner_search_id', $search)
                ;
            } else {
                $banners
                    ->andWhere('b.title LIKE :search')
                    ->join('b.position', 'p')
                    ->orWhere('p.position LIKE :search')
                    ->setParameter('search', '%' . $search . '%')
                ;
            }
        }

        return $banners
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Dohvata sve tipova strana
     * @return  \App\Models\PageType[]   Niz objekata tipova strana
     */
    public static function getPageTypes(): array {
        return self::$entity_manager
            ->createQueryBuilder()
            ->select('pt')
            ->from('App\Models\PageType', 'pt')
            ->orderBy('pt.type', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public static function getBannerPageFiltersPageID($page_type_id) {
        $page_type = self::getPageTypeById($page_type_id);
        $machine_name = $page_type->machine_name;
        if ($machine_name === 'product_category') {
            return CategoryService::getAllCategories();
        } elseif ($machine_name === 'product') {
            return null;
        } elseif ($machine_name === 'article') {
            return null;
        } elseif ($machine_name === 'article_list') {
            return null;
        } else {
            return null;
        }
    }

    /**
     * Dohvatanje banera koji ide na stranicu pretrage,
     * iznad rezultata
     *
     * @param   string|null      $term      Pretraga koju je korisnik ukucao
     * @return  Banner|null                 Baner koji treba prikazati (ili null ako ne treba ništa)
     */
    public static function getBannerSearchPage(?string $term = null): ?Banner {
        $position = self::getPositionByName(Banner::$POSITION_SEARCH_ABOVE);

        if ($position === null) {
            return null;
        }

        $query = self::$entity_manager
            ->createQueryBuilder()
            ->select('b')
            ->from('App\Models\Banner', 'b')
            ->where('b.position = :position')
            ->setParameter(':position', $position->id)
        ;

        if ($term !== null && $term !== '') {
            $query
                ->andWhere('b.urls = :term')
                ->setParameter(':term', $term)
            ;
        }

        return $query
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // poslat standardni url dohvatam sa tipom strane i pravim url
    // koji kasnije unosim u bazu u mom formatu
    private static function getFormattedUrls($urls, $type) {
        try {
            $decoded_url    = urldecode($urls);
            $matches = [];

            if ($type === 'search_page') {
                return $urls;
            }

            if ($urls !== '/') {
                $decoded_url            = preg_replace('/^http(s?)\:\/\/[A-z0-9\.]+\//', '', $decoded_url);
                preg_match('/([A-z\-]+)/', $decoded_url, $matches);
                $category           = $matches[1];
                // $banner_filters     = $type !== '' ? self::getBannersFiltersByPageType($type, $category) : [];
                $formatted_urls      = '';
                $formatted_urls .= $type;
                $formatted_urls .= "=";
                $formatted_urls .= $category;

                $query_exists   = false;
                if (count($matches) >= 3) {
                    $query_exists = $matches[2] === "?";
                }

                $decoded_url            = preg_replace('/([A-z]+)(\?)/', '', $decoded_url);
                if ($type === 'product_category') {
                    if ($query_exists) {
                        $filter_params  = explode('&', $decoded_url);
                        $formatted       = [];
                        foreach ($filter_params as $filter) {
                            if ($filter !== "") {
                                $pair        = explode('=', $filter);
                                $formatted[$pair[0]] = $pair[1];
                            }
                        }
                    }
                }
            } else {
                $formatted_urls = '/';
            }

            return $formatted_urls;
        } catch (\Exception $e) {
            $formatted_urls = '/';
            return $formatted_urls;
        }
    }

    // Dohvatam poziciju prema njenom imenu
    public static function getPositionByName(string $name): ?Position {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('p')
            ->from('App\Models\Position', 'p')
            ->where('p.position = :name')
            ->setParameter('name', $name)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Dohvatanje pozicija po tipu strane
     * @param   int         $page_type_id       Id tipa strane
     * @return  array       Vraća pozicije sa taj tip strane
     */
    public static function getPostionsByPageTypeId($page_type_id) {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('p')
            ->from('App\Models\Position', 'p')
            ->where('p.page_type_id = :page_type_id')
            ->setParameter('page_type_id', $page_type_id)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Dohvata banner po id-u
     * @param   int         $banner_id      Id banera
     * @return  Banner                      Banner objekat
     */
    public static function getBannerById($banner_id) {
        return self::$entity_manager->find('App\Models\Banner', $banner_id);
    }


    public static function getBannersByPositionId($position_id) {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('b')
            ->from('App\Models\Banner', 'b')
            ->where('b.position_id = :position_id')
            ->setParameter('position_id', $position_id)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Dohvata poziciju po id-u
     * @param   int         $position_id    Id pozicije
     * @return  Position                    Position objekat
     */
    public static function getPositionById($position_id) {
        return self::$entity_manager->find('App\Models\Position', $position_id);
    }

    /**
     * Dohvata tip strane po id-u
     * @param   int         $page_type_id   Id strane
     * @return  PageType                    Pagetype objekat
     */
    public static function getPageTypeById($page_type_id) {
        return self::$entity_manager->find('App\Models\PageType', $page_type_id);
    }

    public static function getBanners($position_id = null, $category = '', $not_in = []) {
        $background_position = self::getPositionByName('Pozadina');
        $qb = self::$entity_manager->createQueryBuilder();
        $banners = $qb
            ->select('b')
            ->from('App\Models\Banner', 'b')
        ;

        if ($position_id !== null) {
            $banners
                ->where('b.position_id = :position_id')
                ->setParameter('position_id', $position_id)
            ;
        }

        if ($category !== '') {
            $category = str_replace('product_category=', '', $category);
            $banners
                ->andWhere('b.urls LIKE :category')
                ->setParameter('category', '%' . $category . '%')
            ;
        }
        $admin = AdminService::isAdminLoggedIn();

        if (!$admin) {
                $banners
                    ->andWhere('b.status = 1')
                ;
        }

        if (!empty($not_in) && $position_id !== $background_position->id) {
            $banners
                ->andWhere($qb->expr()->notIn('b.id', $not_in))
            ;
        }

        $banners = $banners
            ->getQuery()
            ->getResult()
        ;
        return $banners;
    }

    public static function getBannersFiltersByPageType($type, $category_name) {
        $category = CategoryService::getCategoryByName($category_name);
        $category_name = strtoupper($category_name);
        $banner_filters = [
            'product_category' => [
                'LAPTOPOVI' => [
                    'Proizvođač',
                    'search',
                    'Proizvođač procesora',
                ],
            ],
        ];
        if (array_key_exists($type, $banner_filters)) {
            if (array_key_exists($category_name, $banner_filters[$type])) {
                $filters = $banner_filters[$type][$category_name];
            } else {
                $filters = CategoryService::getCategoryFiltersNames($category->id);
            }
        } else {
            $filters = [];
        }
        return $filters;
    }

    public static function getBannersByUrl($position, $url = null, $type = '', $nr_banners = 1, $random = false) {
        $banners_shown = SessionService::getSessionValueForService('banners_shown', 'banner_service');
        $banners_shown = $banners_shown !== null ? $banners_shown : [];
        // $banner_filters = $type !== '' ? self::getBannersFiltersByPageType($type, 'laptopovi') : [];
        if ($url !== null) {
            // sklanjam protokol i domen sajta
            $url            = preg_replace('/^http(s?)\:\/\/[A-z0-9\.]+\//', '', $url);
            $matches        = [];
            // proverava da l je strana akcije i sklanja iz url-a
            $on_sale      = preg_match('/akcija/', $url);
            if ($on_sale) {
                $url = preg_replace('/\/akcija/', '', $url);
            }
            // hvatam sve do querija
            preg_match('/([A-z]+)(\?)?/', $url, $matches);
            // sklanja sve do querija
            $url            = preg_replace('/([A-z]+\?)/', '', $url);
            // Uzimam kategoriju iz preg_match-a
            $category       = $matches[1];
            $query_url      = $type . '=' . $category;
            $query_exists   = false;
            if (count($matches) >= 3) {
                $query_exists = $matches[2] === "?";
            }


            if ($type === 'product_category') {
                // dohvatam sve banere za zadatu kategoriju, treba dodati i za poziciju
                // i objavljene
                $banners        = BannerService::getBanners($position, $query_url, $banners_shown);
                // pravim niz od url-a sa vrenodstima $kljuc=$vrednost sacuvanim kao string
                if ($query_exists) {
                    $filter_params  = explode('&', $url);
                    $formatted       = [];
                    $formatted_used  = [];
                    // svaku vrednost niza url-a razbijam i vrednostu upisujem u niz sa kljucem $kljuc
                    // i vrednosti $vrednost
                    foreach ($filter_params as $filter) {
                        $pair        = explode('=', $filter);
                        $formatted[$pair[0]] = $pair[1];
                    }
                    // Iz niza u url-u proveravam sta me zanima za trenutnu kategoriju
                    // tj uporedjujem sa podacima iz servisa
                    // foreach ($banner_filters as $banner_filter) {
                    //     if(array_key_exists($banner_filter, $formatted)) {
                    //         $formatted_used[$banner_filter] = $formatted[$banner_filter];
                    //     }
                    // }
                }
                $score          = [];

                // za svaki baner proveravam poklapanje sa url-om i kreiram skor
                if (!empty($banners)) {
                    foreach ($banners as $banner) {
                        $single_score = 0;
                        $banner_url = explode('/', $banner->urls);
                        $formatted_banner_url       = [];

                        foreach ($banner_url as $group) {
                            $paira        = explode('=', $group);
                            $formatted_banner_url[$paira[0]] = $paira[1];
                        }
                        $nr_params = count($formatted_banner_url);
                        if ($query_exists) {
                            foreach ($formatted_used as $key => $value) {
                                if (array_key_exists($key, $formatted_banner_url)) {
                                    if ($formatted_banner_url[$key] === $value) {
                                        $single_score += 1;
                                    }
                                }
                            }
                        }

                        // ukoliko se svi parametri poklapaju savrsen je i dobija
                        // ekstremnu vrednost
                        if ($single_score === $nr_params) {
                            $single_score += 10;
                        }
                        // ukolio nemam filtere ili filteri ne odgovaraju
                        // nagradjuje se onaj koji je identican
                        if ($banner->urls === $query_url) {
                            $single_score ++;
                        }

                        array_push($score, $single_score);
                    }
                }
                // uzimam index banera sa najboljim skorom i vracam ga u komponentu
                arsort($score);
                $keys       = array_keys($score);

                if ($random) {
                    $best_indexes = [];
                    $score_sum = 0;
                    foreach ($score as $single_score) {
                        $score_sum += $single_score;
                    }

                    $rand = rand(0, $score_sum);
                    foreach ($score as $key => $value) {
                        if ($rand < $value) {
                            $best_indexes [] = $key;
                            break;
                        }
                    }
                } else {
                    $best_indexes = array_splice($keys, 0, $nr_banners);
                }

                $selected_banners = [];
                foreach ($best_indexes as $index) {
                    $selected_banners [] = $banners[$index];
                }

                if (count($selected_banners) === 0) {
                    $selected_banners = BannerService::getBanners($position, '/', $banners_shown);
                    if (!empty($selected_banners)) {
                        if ($random) {
                            $count = count($selected_banners);
                            $rand  = rand(0, $count - 1);
                            $selected_tmp = [];

                            $selected_tmp [] = $selected_banners[$rand];
                            $selected_banners = $selected_tmp;
                        } else {
                            $selected_banners = array_splice($selected_banners, 0, $nr_banners);
                        }
                    } else {
                        $selected_banners = [];
                    }
                }

                foreach ($selected_banners as $banner) {
                    SessionService::setSessionForService('banners_shown', $banner->id, true, 'banner_service');
                }
                return $selected_banners;
            }
        } else {
            $banners = self::getBanners($position, '', $banners_shown);
            $banners = array_splice($banners, 0, $nr_banners);
            foreach ($banners as $banner) {
                SessionService::setSessionForService('banners_shown', $banner->id, true, 'banner_service');
            }
            return $banners;
        }
    }

    /**
     * Proverava dali postoji banner sa tim imenom
     * @param   string      $title      Naslov banera
     * @return  boolean     Vraća true ako je ime zauzeto ili false ako nije
     */
    public static function isNameTaken($title) {
        return !empty(self::$entity_manager->createQueryBuilder()
            ->select('b')
            ->from('App\Models\Banner', 'b')
            ->where('b.title = :title')
            ->setParameter('title', $title)
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
     * Izmena banera
     * @param   int         $banner_id      Id banera
     * @param   array       $updates        Niz sa izmena
     * @return  bool|int                    true ako je sve prošlo uredu u suprotnom vraća neki error_code
     */
    public static function updateBanner($banner_id, $updates) {
        if (PermissionService::checkPermission('banner_update') === false) {
            throw new PermissionException('Nemate dozvolu za izmenu banera', 5014);
        }

        $banner = self::getBannerById($banner_id);
        if (empty($banner)) {
            throw new ValidationException('Baner sa tim id-om nije pronađen', 5015);
        }

        if (array_key_exists('position_id', $updates)) {
            $updates['position_id'] = ValidationService::validateInteger(
                $updates['position_id'],
                ValidationService::$RANGE_INTEGER_UNSIGNED['min'],
                ValidationService::$RANGE_INTEGER_UNSIGNED['max']
            );

            if ($updates['position_id'] === false) {
                throw new ValidationException('Id pozicije nije odgovarajućeg formata', 5016);
            }

            $position = self::getPositionById($updates['position_id']);
            if (empty($position)) {
                throw new ValidationException('Pozicija sa tim id-om ne postoji', 5017);
            }

            $banner->position = $position;
        }

        if (array_key_exists('title', $updates)) {
            $updates['title'] = ValidationService::validateString($updates['title'], 127);

            if ($updates['title'] === false) {
                throw new ValidationException('Naslov nije odgovarajućeg formata', 5018);
            }

            $banner->title = $updates['title'];
        }

        if (array_key_exists('image', $updates)) {
            $banner->image = ImageService::uploadImage($updates['image'], self::$static_originals);
        }

        if (array_key_exists('link', $updates)) {
            $updates['link'] = ValidationService::validateString($updates['link'], 255);

            if ($updates['link'] === false) {
                throw new ValidationException('Link nije odgovarajućeg formata', 5020);
            }

            $banner->link = $updates['link'];
        }

        if (array_key_exists('urls', $updates)) {
            $updates['urls'] = ValidationService::validateString($updates['urls'], 255);

            if ($updates['urls'] === false) {
                throw new ValidationException(
                    'Linkovi na kojima treba da se pojavljuje baner nije odgovarajućeg formata',
                    5021
                );
            }

            $type = $banner->position->page_type->machine_name;
            if ($banner->urls !== $updates['urls']) {
                $urls         = self::getFormattedUrls($updates['urls'], $type);
                $banner->urls = $urls;
            }
        }

        if (array_key_exists('status', $updates)) {
            $banner->status = ValidationService::validateBoolean($updates['status']);
        }

        if (!empty($updates)) {
            self::$entity_manager->persist($banner);
            self::$entity_manager->flush();
        }

        return true;
    }

    /**
     * Dodaje za jedan na broj klikova baneru
     * @param   int         $banner_id      Id banera
     * @return  bool|int                    true ako je sve prošlo uredu u suprotnom vraća neki error_code
     */
    public static function updateBannerNrClicks($banner_id) {
        $banner = self::getBannerById($banner_id);
        if (empty($banner)) {
            throw new ValidationException('Baner sa tim id-om nije pronađen', 5022);
        }

        $banner->nr_clicks++;

        self::$entity_manager->persist($banner);
        self::$entity_manager->flush();

        return true;
    }

    /**
     * Izmena pozicije banera
     * @param   int         $position_id    Id pozicije
     * @param   array       $updates        Niz sa izmenama
     * @return  bool|int                    true ako je sve prošlo uredu u suprotnom vraća neki error_code
     */
    public static function updatePosition($position_id, $updates) {
        if (PermissionService::checkPermission('banner_update') === false) {
            throw new PermissionException('Nemate dozvolu za izmenu pozicije za banere', 5023);
        }

        $position = self::getPositionById($position_id);
        if (empty($position)) {
            throw new ValidationException('Pozicija sa tim id-om nije pronađena', 5024);
        }

        if (array_key_exists('page_type_id', $updates)) {
            $updates['page_type_id'] = ValidationService::validateInteger(
                $updates['page_type_id'],
                ValidationService::$RANGE_INTEGER_UNSIGNED['min'],
                ValidationService::$RANGE_INTEGER_UNSIGNED['max']
            );

            if ($updates['page_type_id'] === false) {
                throw new ValidationException('Id tipa stane nije odgovarajućeg formata', 5025);
            }

            $page_type = self::getPageTypeById($updates['page_type_id']);
            if (empty($page_type)) {
                throw new ValidationException('Tip strane sa tim id-om nije pronađen', 5026);
            }

            $position->page_type = $page_type;
        }

        if (array_key_exists('position', $updates)) {
            $updates['position'] = ValidationService::validateString($updates['position'], 63);

            if ($updates['position'] === false) {
                throw new ValidationException('Naziv pozicije nije odgovarajućeg formata', 5027);
            }

            $position->position = $updates['position'];
        }

        if (array_key_exists('image_width', $updates)) {
            $updates['image_width'] = ValidationService::validateInteger(
                $updates['image_width'],
                ValidationService::$RANGE_SMALLINTEGER_UNSIGNED['min'],
                ValidationService::$RANGE_SMALLINTEGER_UNSIGNED['max']
            );

            if ($updates['image_width'] === false) {
                throw new ValidationException('Širina slike nije odgovarajućeg formata', 5028);
            }

            $position->image_width = $updates['image_width'];
        }

        if (array_key_exists('image_height', $updates)) {
            $updates['image_height'] = ValidationService::validateInteger(
                $updates['image_height'],
                ValidationService::$RANGE_SMALLINTEGER_UNSIGNED['min'],
                ValidationService::$RANGE_SMALLINTEGER_UNSIGNED['max']
            );

            if ($updates['image_height'] === false) {
                throw new ValidationException('Visina slike nije odgovarajućeg formata', 5029);
            }

            $position->image_height = $updates['image_height'];
        }

        if (!empty($updates)) {
            self::$entity_manager->persist($position);
            self::$entity_manager->flush();
        }

        return true;
    }

    /**
     * Izmena tipa stranice
     * @param   int         $page_type_id       Id tipa stranice
     * @param   array       $updates            Niz sa izmenama
     * @return  bool|int                        true ako je sve prošlo uredu u suprotnom vraća neki error_code
     */
    public static function updatePageType($page_type_id, $updates) {
        if (PermissionService::checkPermission('banner_update') === false) {
            throw new PermissionException('Nemate dozvolu za izmenu tipe strane', 5030);
        }

        $page_type = self::getPageTypeById($page_type_id);
        if (empty($page_type)) {
            throw new ValidationException('Tip stranice sa tim id-om nije pronađen', 5031);
        }

        if (array_key_exists('type', $updates)) {
            $updates['type'] = ValidationService::validateString($updates['type'], 127);

            if ($updates['type'] === false) {
                throw new ValidationException('Tip stranice nije odgovarajućeg formata', 5032);
            }

            $page_type->type = $updates['type'];
        }

        if (!empty($updates)) {
            self::$entity_manager->persist($page_type);
            self::$entity_manager->flush();
        }

        return true;
    }

    /**
     *
     * DELETE
     *
     */

    /**
     * Briše baner
     * @param   int         $banner_id      Id banera
     * @return  bool|int                    true ako je sve prošlo uredu u suprotnom vraća neki error_code
     */
    public static function deleteBanner($banner_id) {
        if (PermissionService::checkPermission('banner_delete') === false) {
            throw new PermissionException('Nemate dozvolu za brisanje banera', 5033);
        }

        $banner = self::getBannerById($banner_id);
        if (empty($banner)) {
            throw new ValidationException('Banner sa tim id-om nije pronađen', 5034);
        }

        self::$entity_manager->remove($banner);
        self::$entity_manager->flush();

        return true;
    }

    /**
     * Briše poziciju
     * @param   int         $position_id        Id pozicije
     * @return  bool|int                        true ako je sve prošlo uredu u suprotnom vraća neki error_code
     */
    public static function deletePosition($position_id) {
        if (PermissionService::checkPermission('banner_delete') === false) {
            throw new PermissionException('Nemate dozvolu za brisanje pozicija', 5035);
        }

        $position = self::getPositionById($position_id);
        if (empty($position)) {
            throw new ValidationException('Pozicija sa tim id-om nije pronađen', 5036);
        }

        self::$entity_manager->remove($position);
        self::$entity_manager->flush();

        return true;
    }

    /**
     * Briše tip stranice
     * @param   int         $page_type_id       Id tipa stranice
     * @return  bool|int                        true ako je sve prošlo uredu u suprotnom vraća neki error_code
     */
    public static function deletePageType($page_type_id) {
        if (PermissionService::checkPermission('banner_delete') === false) {
            throw new PermissionException('Nemate dozvolu za brisanje tipova strana', 5037);
        }

        $page_type = self::getPageTypeById($page_type_id);
        if (empty($page_type)) {
            throw new ValidationException('PageType sa tim id-om nije pronađen', 5038);
        }

        self::$entity_manager->remove($page_type);
        self::$entity_manager->flush();

        return true;
    }

    public static function removeShownBanners() {
        SessionService::deleteSession('banners_shown', 'banner_service');
    }
}
