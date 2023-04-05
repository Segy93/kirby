<?php

namespace App\Providers;

use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;
use App\Models\Category;
use App\Models\Product;
use App\Providers\PermissionService;
use App\Providers\ProductService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportService extends BaseService {

    /**
     *
     * CREATE
     *
     */










    /**
     * Prebacuje sve proizvode iz među baze u bazu aplikacije po kategorija
     * @param   string          $category_name  Nazive kategorije ako dohvata proizvode po samo jednoj kategoriji
     * @return  bool/string                 Vraća true ako je sve prošlo uredu inače vraća error message
     */
    public static function importProducts($category_name = null) {
        if (config(php_uname('n') . '.PROGRESS_SHOW_HTML')) {
            header("Content-Type: text/event-stream\n\n");
        }

        if (PermissionService::checkPermission('product_import') === false
            && self::checkPermission() === false
        ) {
            throw new PermissionException('Nemate dozvolu za unos proizvoda', 12001);
        }

        // zakomentarisano je da ne bi bacao bazu na masteru prilikom bilo kog uvoza
        if (config(php_uname('n') . '.DATABASE_TRUNCATE')) {
            self::truncate();
        }

        ini_set('max_execution_time', 30000);
        $em = self::$entity_manager;

        // $sqlLogger = $em->getConnection()->getConfiguration()->getSQLLogger();
        // $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $batchSize = 20;
        $num_all_categories = 0;
        $current_category_num = 0;
        $num_all_categories = self::getNumOfImportCategories($category_name);

        $num_all_products_category = 0;
        $duplicates = [];
        $categories = self::getImportCategoriesOrCategory($category_name);
        $categories_of_import = [];
        foreach ($categories as $category) {
            $current_category_num++;
            $current_product_num            = 0;
            $num_all_products_category      = self::getNumOfImportProductsByCategory($category->tipid);
            $category_attributes            = self::getCategoryAttributes($category->tipid);
            $products                       = self::getImportProductsIdNameByCategory($category->tipid);
            $products_ids                   = [];
            $category_local                 = self::getLocalCategoryByName($category->tip, true);

            if (!empty($category_local)) {
                $product_names = [];
                $prev_name      = "";
                $prev_artid     = 0;
                $product_ids    = [];

                foreach ($products as $product) {
                    $product_id = $product['artid'];
                    $mod_artikal_name = preg_replace('/(\()+|(\))+|(\/)+|( )+/', '-', strtolower($product['artikal']));
                    $mod_prev_name    = preg_replace('/(\()+|(\))+|(\/)+|( )+/', '-', strtolower($prev_name));
                    if ($mod_artikal_name !== $mod_prev_name) {
                        $current_product_num++;
                        $product_ids [] = [$product_id];
                        $start = microtime(true);
                        $start_product = microtime(true);
                        $response = self::importProduct($product_id, $category_attributes, $product_names);
                        $categories_of_import [] = $category_local->id;
                        if (!array_key_exists($response['current_product_name'], $product_names)) {
                            $product_names[$response['current_product_name']] = 1;
                        } else {
                            $product_names[$response['current_product_name']] = $product_names[$response['current_product_name']] + 1;
                        }
                        $import_product_time = microtime(true) - $start;

                        if (($current_product_num % $batchSize) === 0
                            || $current_product_num === $num_all_products_category
                        ) {
                            self::$queued_entities = [
                                'products'              => [],
                                'attribute_values'      => [],
                                'product_attributes'    => [],
                            ];
                            $em->flush();
                            $em->clear();
                            unset($product_names);
                            $product_names = [];
                        }

                        $return = [
                            'type'                      => 'product',
                            'num_all_categories'        => $num_all_categories,
                            'current_category_num'      => $current_category_num,
                            'current_category_name'     => $category_local->name,
                            'num_all_products_category' => $num_all_products_category,
                            'current_product_num'       => $current_product_num,
                            'times'                     => [
                                'import_product_time'   => $import_product_time,
                                'product_time'          => microtime(true) - $start_product,
                            ],
                        ];

                        $return = array_merge($return, $response);

                        if (config(php_uname('n') . '.PROGRESS_SHOW_HTML')) {
                            ob_start();
                            $json = json_encode($return);
                            echo "data: {$json}\r\n\n";
                            while (ob_get_level() > 0) {
                                ob_end_flush();
                            }
                            flush();
                        } else {
                            yield $return;
                        }

                        if (gc_enabled()) {
                            gc_collect_cycles();
                        }

                        if (config(php_uname('n') . '.PROGRESS_SLOW_DOWN')) {
                            sleep(1);
                        }
                    } else {
                        if (config(php_uname('n') . '.PROGRESS_SHOW_HTML')) {
                            $error_message = "DUPLIKAT: ProizvodI artid = "
                                . $product['artid']
                                . " I artid = "
                                . $prev_artid
                                .  "su duplikati"
                            ;

                            $error_message = [
                                'type' => 'error',
                                'message' => $error_message,
                            ];
                            ob_start();
                            $json = json_encode($error_message);
                            echo "data: {$json}\r\n\n";
                            while (ob_get_level() > 0) {
                                ob_end_flush();
                            }
                            flush();
                            Log::info($error_message);
                        } else {
                            yield $product['artid'];
                        }
                        $duplicates [] = $product['artid'];
                    }

                    $prev_name = $product["artikal"];
                    $prev_artid   = $product["artid"];
                }
                $em->flush();
                $em->clear();
                echo "UVOZ SLIKA";
                $pic_import_count   = 0;
                $pic_imported_count = 0;
                $category_products  = ProductService::getProductsByArtids(array_map('current', $product_ids));
                $cat_pro_num        = count($category_products);
                foreach ($category_products as $product) {
                    if ($pic_import_count === 20  || $pic_import_count === $num_all_products_category) {
                        self::$entity_manager->flush();
                        $pic_import_count = 0;
                    }
                    $pic_imported_count++;
                    $data_pictures = 'Unesene slike za proizvod ' . $pic_imported_count . " od " . $cat_pro_num;
                    $product_images = self::getImportImages($product->artid);
                    if (!empty($product_images)) {
                        $names = ProductService::importImages($product_images, $product);
                    }

                    $pic_import_count++;

                    if (config(php_uname('n') . '.PROGRESS_SHOW_HTML')) {
                        $data_pictures = [
                            'type'    => 'pictures',
                            'message' => $data_pictures,
                        ];
                        ob_start();
                        $json = json_encode($data_pictures);
                        echo "data: {$json}\r\n\n";
                        while (ob_get_level() > 0) {
                            ob_end_flush();
                        }
                        flush();
                    } else {
                        yield $data_pictures;
                    }


                    if (gc_enabled()) {
                        gc_collect_cycles();
                    }
                }
            } else {
                echo 'Nema kategorije' . $category->tip . '</br>';
            }
        }

        self::$queued_entities = [
            'products'              => [],
            'attribute_values'      => [],
            'product_attributes'    => [],
        ];

        $em->flush();
        $em->clear();
        $categories_of_import = array_unique($categories_of_import);

        self::$entity_manager->createQuery(
            'UPDATE App\Models\Category c
            SET c.updated_at = NULL
            WHERE c.id IN (' . implode(',', $categories_of_import) . ')'
        )->execute();
        // $em->getConnection()->getConfiguration()->setSQLLogger($sqlLogger);

        SeoService::createSitemap();
        if (config(php_uname('n') . '.PROGRESS_SHOW_HTML')) {
            $finish = json_encode(['finish' => 'Gotovo!Počinje kreiranje sitemap-a']);

            echo "data: $finish" . "\n\n";
        } else {
            yield ['finish' => 'Gotovo!Počinje kreiranje sitemap-a'];
        }

        if (config(php_uname('n') . '.PROGRESS_SHOW_HTML')) {
            $finish_sitemap = json_encode(['finish' => 'Uvežen sitemap']);

            echo "data: $finish_sitemap" . "\n\n";
        } else {
            yield ['finish' => 'Uvežen sitemap'];
        }
    }



    /**
     * Prebacuje pojedinčan proizvod iz među baze u bazu aplikacije po id proivzoda
     * @param   int             $artid      ID proizvoda
     * @return  array|string    True ako je sve prošlo uredu inače error message
     */
    public static function importProduct($artid, $category_attributes = [], $product_names = [], $console = false) {
        if (PermissionService::checkPermission('product_import') === false
        && self::checkPermission() === false
        ) {
            throw new PermissionException('Nemate dozvolu za unos proizvoda', 12002);
        }

        $product = self::getImportProductById($artid);
        if (empty($category_attributes)) {
            $category_attributes            = self::getCategoryAttributes($product['tipid']);
        }
        if (empty($product)) {
            throw new ValidationException('Proizvod nije pronađen', 12003);
        }

        $return['current_product_name'] = $product['artikal'];

        $start_import_single = microtime(true);
        $attribute_values = self::getImportAttributeValuesByProduct($artid);
        $end_import_single = microtime(true) - $start_import_single;
        $attributes = [];
        $i = 0;
        //Log::info("Additional attributes count:".count($attribute_values));
        foreach ($category_attributes as $category_attribute) {
            if (!empty($attribute_values)) {
                if (array_key_exists($i, $attribute_values)) {
                    if ($category_attribute['id_opis_kljuc'] === $attribute_values[$i]['id_opis_kljuc']) {
                        array_push(
                            $attributes,
                            [
                                "kljuc_naziv"   => $category_attribute["kljuc_naziv"],
                                "vrednost"      => $attribute_values[$i]['vrednost']
                            ]
                        );
                        $i++;
                    }
                }
            }
        }
        //return $attributes;
        $product_data = self::parseData($product, $attributes);

        //yield 'mappings' => $product_data;

        try {
            $ps = ProductService::createProduct($product_data[0], $product_data[1], $product_names);
            if ($console) {
                $em = self::$entity_manager;
                $em->flush();
                $em->clear();
                self::importImageForArtid($artid);
            }
            $return['success'] = 'Uspešno unešen proizvod';
        } catch (\Exception $e) {
            $return['ps'] = $e->getMessage();
        }
        return $return;
    }

    public static function importImageForArtid($artid) {
        echo "UVOZ SLIKA";
        $product_images = self::getImportImages($artid);
        $local_product = ProductService::getProductByArtid($artid);
        if (!empty($product_images)) {
            ProductService::importImages($product_images, $local_product);
            $em = self::$entity_manager;
            $em->flush();
            $em->clear();
        }
    }

    public static function syncImages($artid) {
        ProductService::deletePictures($artid, true);
        self::importImageForArtid($artid);
    }










    /**
     *
     * READ
     *
     */










    /**
     * Dohvata sve header-e
     * @return  array                       Vraća niz sa ključevima i vrednostima
     */
    private static function getallheaders() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    /**
     * Proverava dozvolu ako je zahtev poslat preko cURL
     * @return  bool                        Vraća true ako ima dozvolu u suprotnom vraća false
     */
    private static function checkPermission() {
        $headers = self::getallheaders();

        if (array_key_exists('Token', $headers)) {
            if ($headers['Token'] !== config(php_uname('n') . '.IMPORT_TOKEN')) {
                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * Dohvata sve kategorije ili samo jednu ako je prosleđeno ime kategorije
     * @param   string          $category_name Naziv kategorija(Opcionalno)
     * @return  array                       Vraća niz sa kategorijama
     */
    private static function getImportCategoriesOrCategory($category_name = null) {
        $qb = self::$entity_manager->createQueryBuilder();

        $qb
            ->select('mk')
            ->from('App\Models\MonitorMiddleDB\MonitorKategorija', 'mk')
        ;

        if (!empty($category_name)) {
            $qb
                ->where('mk.tip = ?1')
                ->setParameter(1, $category_name)
            ;
        }

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Prebrojava kategorije
     * @param   string          $category_name Naziv kategorije (u ovom slučaju vraća 1 pošto je jedna kategorija)
     * @return  int                         Vraća broj kategorija
     */
    private static function getNumOfImportCategories($category_name = null) {
        $qb = self::$entity_manager->createQueryBuilder();
        $qb
            ->select('COUNT(mk.tip)')
            ->from('App\Models\MonitorMiddleDB\MonitorKategorija', 'mk')
        ;

        if (!empty($category_name)) {
            $qb
                ->where('mk.tip = ?1')
                ->setParameter(1, $category_name)
            ;
        }

        return $qb
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }



    /**
     * Dohvata id-jeve proizvoda po kategoriji
     * @param   int             $category_id ID kategorije
     * @return  array                       Vraća niz id-jeva proizvoda
     */
    private static function getImportProductsIdNameByCategory($category_id) {
        $impoted_product_ids = self::getProductIdsNotToImportQuery();

        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('ma.artid', 'ma.artikal')
            ->from('App\Models\MonitorMiddleDB\MonitorArtikal', 'ma')
            ->where('ma.tipid = ?1')
            ->setParameter(1, $category_id)
            ->andWhere($qb->expr()->notIn('ma.artid', $impoted_product_ids->getDQL()))
            ->orderBy('ma.artikal', 'ASC')
            ->getQuery()
            ->getScalarResult()
        ;
    }

    /**
     * Dohvata broj proizvoda za import u bazu
     * @param   int             $category_id description]
     * @return  int                         Broj proizvoda
     */
    private static function getNumOfImportProductsByCategory($category_id) {
        $impoted_product_ids = self::getProductIdsNotToImportQuery();

        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('COUNT(ma.artid)')
            ->from('App\Models\MonitorMiddleDB\MonitorArtikal', 'ma')
            ->where('ma.tipid = ?1')
            ->andWhere($qb->expr()->notIn('ma.artid', $impoted_product_ids->getDQL()))
            ->setParameter(1, $category_id)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Dohvata broj proizvoda koji treba da se update
     * @param   int             $category_id Id kategorije
     * @return  int                         broj proizvoda
     */
    private static function getNumOfUpdateProductsByCategory($category_id) {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb->select('COUNT(DISTINCT mus.artid)')
            ->from('App\Models\MonitorMiddleDB\MonitorUpdateSajt', 'mus')
            ->join('mus.artikal', 'ma')
            ->where('ma.tipid = ?1')
            ->setParameter(1, $category_id)
            ->distinct()
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Vraća podupit za proizvode koje netreba da unese
     * @return  query                   Vraća pod upit
     */
    private static function getProductIdsNotToImportQuery() {
        return self::$entity_manager
            ->createQueryBuilder()
            ->select('p.artid')
            ->from('App\Models\Product', 'p')
        ;
    }

    /**
     * Dovhata id-jeve proizvoda koji imaju izmene
     * @param   int             $category_id ID kategorije
     * @return  array                       Vraća niz sa id-jevima
     */
    private static function getImportProductUpdatesIdByCategory($category_id) {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb->select('mus.artid')
        ->from('App\Models\MonitorMiddleDB\MonitorUpdateSajt', 'mus')
        ->join('mus.artikal', 'ma')
        ->where('ma.tipid = ?1')
        ->setParameter(1, $category_id)
        ->distinct()
        ->orderBy('mus.artid', 'ASC')
        ->getQuery()->getResult();
    }

    /**
     * Dohvata proizvod po id-u
     * @param   int             $product_id ID proizvoda
     * @return  monitor_artikal             Vraća monitor artikal ako je našao proizvod ako ne vraća grešku
     */
    private static function getImportProductById($artid) {
        $qb = self::$entity_manager->createQueryBuilder();
        return $query =  $qb
            ->select(
                'ma.artid',
                'ma.artikal',
                'ma.tipid',
                'ma.enduserdin',
                'ma.dealerdin',
                'ma.vaucer',
                'ma.f_magacin_stanje',
                'ma.f_radnja_stanje',
                'ma.f_akcija',
                'ma.f_istaknut',
                'ma.f_presales',
                'ma.f_published',
                'mok.tip'
            )
            ->from('App\Models\MonitorMiddleDB\MonitorArtikal', 'ma')
            ->join('ma.kategorija', 'mok')
            ->where('ma.artid = ?1')
            ->setParameter(1, $artid)
            ->setMaxResults(1)
            ->getQuery()

            ->getOneOrNullResult();
        ;

        //var_dump($query->getParameters());die;
    }

    private static function getImportImages($artid) {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select(
                'os.artid',
                'os.rb',
                'os.fajl'
            )
            ->from('App\Models\MonitorMiddleDB\OpisSlika', 'os')
            ->where('os.artid = :artid')
            ->setParameter('artid', $artid)
            ->getQuery()
            ->getResult()
        ;
    }

    private static function getCategoryAttributes($category_id) {
        $qb = self::$entity_manager->createQueryBuilder();
        return $qb->select('mok.id_opis_kljuc', 'mok.kljuc_naziv')
                ->from('App\Models\MonitorMiddleDB\MonitorOpisKategorija', 'mok')
                ->where('mok.tipid = :category_id')
                ->setParameter('category_id', $category_id)
                ->orderBy('mok.id_opis_kljuc')
                ->getQuery()->getResult()
            ;
    }

    /**
     * Dohvata polja proizvoda po id proizvoda
     * @param   int             $product_id ID proizvoda
     * @return  array                       Vraća niz sa poljima i vrednostima polja proizvoa
     */
    private static function getImportAttributeValuesByProduct($product_id) {
        $qb = self::$entity_manager->createQueryBuilder();
        return $qb->select('DISTINCT mov.id_opis_kljuc', 'mov.vrednost')
        ->from('App\Models\MonitorMiddleDB\MonitorOpisVrednost', 'mov')
        ->where('mov.artid = ?1')
        ->setParameter(1, $product_id)
        ->orderBy('mov.id_opis_kljuc')
        ->getQuery()->getResult();
    }

    /**
     * Dohvata izmene iz monitor_update_sajt tabele
     * @param   int             $artid      ID proizvoda
     * @return  array                       Vraća niz sa izmenama
     */
    private static function getImportUpdateProductById($artid) {
        $qb = self::$entity_manager->createQueryBuilder();
        return $qb->select(
            'mus.kljuc',
            'mus.vrednost',
            'mus.f_obrisano'
        )
        ->from('App\Models\MonitorMiddleDB\MonitorUpdateSajt', 'mus')
        ->where('mus.artid = ?1')
        ->setParameter(1, $artid)
        ->getQuery()->getResult();
    }

    /**
     * Dohvata kategoriju po imenu
     * @param   string      $name           Naziva kategorije importovano
     * @param   bool        $local          Ako je true dohvata po importovanom imenu ako nije dohvata po lokalnom imenu
     * @return  Category    $category       Vraća objekat kategorije
     */
    public static function getLocalCategoryByName($name, $local = false) {
        if (PermissionService::checkPermission('product_import') === false
            && self::checkPermission() === false
        ) {
            throw new PermissionException('Nemate dozvolu za dohvatanje kategorije', 12006);
        }

        $qb = self::$entity_manager->createQueryBuilder();

        $field = $local === true ? 'name_import' : 'name';

        return $qb->select('c')->from('App\Models\Category', 'c')
        ->where("c.$field = ?1")
        ->setParameter(1, $name)
        ->setMaxResults(1)
        ->getQuery()->getOneOrNullResult();
    }

    /**
     * Dohvata lokalnu kategoriju po id-u
     * @param   int             $category_id ID kategorije
     * @return  Category                    Vraća objekat kategorije
     */
    public static function getLocalCategoryById($category_id) {
        if (PermissionService::checkPermission('product_import') === false
            && self::checkPermission() === false
        ) {
            throw new PermissionException('Nemate dozvolu za dohvatanje kategorije', 12007);
        }

        return self::$entity_manager->find('App\Models\Category', $category_id);
    }

    /**
     * Priprema(konvertuje) podatke u format za productService
     * @param   array           $product    Niz sa podacima proizvoda
     * @param   array           $attributes Niz sa podacima dodatnih polja
     * @param   bool            $update     Parametar koji označava dali je update
     *                                      ili import jer nije mapiranje isto u oba slučaja
     * @return  array                       Vraća niz sa sve pripremljenim podacima
     */
    private static function parseData($product, $attributes, $update = false) {
        //Dohvata osnovna polja koja su vezana za proizvod
        $product_fields = $update === true ? Product::getMappingUpdate() : Product::getMappingImport();

        //Dohvata kategoriju
        $category_name = $product['tip'];
        $category = self::getLocalCategoryByName($category_name, true);
        if (empty($category)) {
            throw new ValidationException("Kategorija" . ' "' . $category_name . '" ' . "nije pronađena", 12008);
        }

        $product_attributes['category_id'] = $category->id;
        $additional_attributes = [];

        //Mapira polja proizvoda
        foreach ($product_fields as $field) {
            if (array_key_exists($field['name_import'], $product)) {
                $product_attributes[$field['name_local']] = $product[$field['name_import']];
            }

            foreach ($attributes as $attribute) {
                $attribute_key = $update !== true ? $attribute['kljuc_naziv'] : $attribute['kljuc'];

                if ($attribute_key === $field['name_import']) {
                    $product_attributes[$field['name_local']] = $attribute['vrednost'];
                }
            }
        }

        //Mapira dodatna polja vezana za kategoriju
        foreach ($category->attributes as $attribute_object) {
            foreach ($attributes as $attribute) {
                $attribute_key = $update !== true ? $attribute['kljuc_naziv'] : $attribute['kljuc'];

                if ($attribute_object->name_import === $attribute_key) {
                    $additional_attributes[$attribute_object->machine_name] = $attribute['vrednost'];
                }

                $dimension = self::parseDimensions($attribute_key, $attribute['vrednost']);
                if (!empty($dimension)) {
                    $additional_attributes['dimensions'] = $dimension;
                }
            }
        }

        return [$product_attributes, $additional_attributes];
    }

    /**
     * Priprema(konvertuje veličine)
     * @param   string          $dimension  Dimenzija
     * @return  string                      Vrača ispravnu dimenziju
     */
    private static function parseDimensions($dimension, $value) {
        if (!empty($dimension) && !empty($value)) {
            $dimension = mb_strtoupper($dimension);
            $dimensions = [];
            if ($dimension === 'TEŽINA U KG') {
                $dimensions['mass_unit'] = 'g';
                $dimensions['mass_weight'] = $value * 1000;
            } elseif ($dimension === 'VELIČINA (Š X V X D) U MM') {
                $dimensions['size_unit'] = 'mm';
                $size_array = explode('x', $value);
                $dimensions['size_width'] = $size_array[0];
                $dimensions['size_height'] = $size_array[1];
                $dimensions['size_length'] = $size_array[2];
            } elseif ($dimension === 'VELIČINA ( Š X V X D )') {
                $size_array = explode('x', $value);
                $dimensions['size_width'] = $size_array[0];
                $dimensions['size_height'] = $size_array[1];
                $dimensions['size_length'] = $size_array[2];
            } elseif ($dimension === 'DIMENZIJE') {
                $value = str_replace(' ', '', $value);
                $value = str_replace('mm', '', $value);
                $value = str_replace('×', 'x', $value);
                $size_array = explode('x', $value);
                $dimensions['size_width'] = $size_array[0];
                $dimensions['size_height'] = $size_array[1];
                if (array_key_exists(2, $size_array)) {
                    $dimensions['size_length'] = $size_array[2];
                }
            } elseif ($dimension === 'VELIČINA' && strpos($value, 'GB') === false) {
                $size_array = explode('x', $value);
                $dimensions['size_width'] = $size_array[0];
                $dimensions['size_height'] = $size_array[1];
                if (array_key_exists(2, $size_array)) {
                    $dimensions['size_length'] = $size_array[2];
                }
            }

            return $dimensions;
        }
    }










    /**
     *
     * UPDATE
     *
     */










    /**
     * Radi izmene na svim proizvodima
     * @param   string          $category_name Ime kategorije
     * @return  bool|string                 Vraća true ako je sve uredu inače vraća error message
     */
    public static function updateProducts($param = null) {
        if (PermissionService::checkPermission('product_import') === false && self::checkPermission() === false) {
            throw new PermissionException('Nemate dozvolu za izmenu proizvoda', 12009);
        }
        ini_set('max_execution_time', 30000);
        $em = self::$entity_manager;
        $batchSize              = 20;
        $num_all_categories     = 0;
        $current_category_num   = 0;
        $num_all_categories     = self::getNumOfImportCategories($param);
        $categories             = self::getImportCategoriesOrCategory($param);
        $categories_of_import   = [];
        foreach ($categories as $category) {
            $current_category_num++;
            $current_product_num        = 0;
            $product_ids                = self::getImportProductUpdatesIdByCategory($category->tipid);
            $num_all_products_category  = self::getNumOfUpdateProductsByCategory($category->tipid);
            $category_local             = self::getLocalCategoryByName($category->tip, true);
            foreach ($product_ids as $product_id) {
                $current_product_num++;
                $response = self::updateProduct($product_id["artid"]);
                $categories_of_import [] = $category_local->id;
                $return = [
                    'num_all_categories'        => $num_all_categories,
                    'current_category_num'      => $current_category_num,
                    'current_category_name'     => $category->tip,
                    'num_all_products_category' => $num_all_products_category,
                    'current_product_num'       => $current_product_num,
                ];
                $return = array_merge($return, $response);

                if (config(php_uname('n') . '.PROGRESS_SHOW_HTML')) {
                    $json = json_encode($return);

                    echo "data: $json" . "\n\n";
                } else {
                    yield $return;
                }

                if (ob_get_contents()) {
                    ob_end_clean();
                }

                flush();

                if (gc_enabled()) {
                    gc_collect_cycles();
                }

                if (config(php_uname('n') . '.PROGRESS_SLOW_DOWN')) {
                    sleep(1);
                }
            }
        }
        $categories_of_import = array_unique($categories_of_import);
        self::$entity_manager->createQuery('
            UPDATE App\Models\Category c
            SET c.updated_at = NULL
            WHERE c.id IN (' . implode(',', $categories_of_import) . ')
        ')->execute();

        $em->flush();
        $em->clear();
        if (config(php_uname('n') . '.PROGRESS_SHOW_HTML')) {
            $finish = json_encode(['finish' => 'Gotovo!']);

            echo "data: $finish" . "\n\n";
        } else {
            yield ['finish' => 'Gotovo!'];
        }
    }

    /**
     * Radi izmene na jednom proizvodu
     * @param   int             $artid      Id proizvoda
     * @return  array|string                 Vraća true ako je sve prošlo uredu inače vraća error message
     */
    public static function updateProduct($artid) {
        if (PermissionService::checkPermission('product_import') === false && self::checkPermission() === false) {
            throw new PermissionException('Nemate dozvolu za izmenu proizvoda', 12010);
        }

        //Dohvata proizvod po artid
        $product = self::getImportProductById($artid);

        if (empty($product)) {
            throw new ValidationException('Proizvod nije pronađen', 12011);
        }

        //$product = array_pop($product);
        //var_dump($product);
        $return['current_product_name'] = $product['artikal'];

        //Izmene
        $updates = self::getImportUpdateProductById($artid);

        //Provera dali postoje izmene
        if (empty($updates)) {
            throw new ValidationException("Izmene za id proizvoda: (" . $artid . ") nisu pronađene", 12012);
        }

        foreach ($updates as $update) {
            if ($update['f_obrisano']) {
                ProductService::deleteProduct($artid);
                throw new ValidationException("Proizvod pod id: " . $artid . " je obrisan", 12013);
            } elseif ($update['kljuc'] === 'UCITAJSLIKU') {
                ProductService::deletePictures($artid, true);
                $import_images = self::getImportImages($artid);
                $product_local = ProductService::getProductByArtid($artid);
                ProductService::importImages($import_images, $product_local);
            }
        }

        $product_data = self::parseData($product, $updates, true);

        //Ako su podaci dobro spakovani šalju se dalje ako ne baca grešku
        if (is_array($product_data)) {
            //Prosleđuje podatke servisu podatke u zavisnosti od odgovora vraća true ili baca grešku
            $response = ProductService::updateProduct($artid, $product_data[0], $product_data[1], true);
            if ($response !== true) {
                throw new ValidationException($response, 12014);
            }
        } else {
            throw new ValidationException($product_data, 12015);
        }

        $return['success'] = 'Uspešno izmenjen proizvod';

        return $return;
    }










    /**
     *
     * DELETE
     *
     */










    /**
     * Briše proizvode
     * @return void
     */
    public static function truncate() {
        try {
            self::$entity_manager->createQuery('DELETE App\Models\WishList')->execute();
            self::$entity_manager->createQuery('DELETE App\Models\Cart')->execute();
            self::$entity_manager->createQuery('DELETE App\Models\StockShop')->execute();
            self::$entity_manager->createQuery('DELETE App\Models\Order')->execute();
            self::$entity_manager->createQuery('DELETE App\Models\ProductAttribute')->execute();
            self::$entity_manager->createQuery('DELETE App\Models\AttributeValue')->execute();
            DB::statement("DELETE s FROM SEO s
                WHERE SUBSTRING_INDEX(machine_name, '_', 1) = 'product'
                AND SUBSTRING_INDEX(machine_name, '_', -1) IN (SELECT p.artid FROM Products p)")
            ;
            self::$entity_manager->createQuery('DELETE App\Models\ProductPictureImageType')->execute();
            self::$entity_manager->createQuery('DELETE App\Models\ProductPicture')->execute();
            self::$entity_manager->createQuery('DELETE App\Models\UserProductVote')->execute();
            self::$entity_manager->createQuery('DELETE App\Models\Product')->execute();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die;
        }
    }
}
