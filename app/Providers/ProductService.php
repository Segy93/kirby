<?php

namespace App\Providers;

use App\Exceptions\ValidationException;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductPicture;
use App\Models\ProductPictureImageType;
use App\Models\StockShop;
use App\Models\UserProductVote;
use App\Providers\CategoryService;
use App\Providers\ImageService;
use App\Providers\SEOService;
use App\Providers\ShopService;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Illuminate\Support\Facades\Log;

class ProductService extends BaseService {

    //Limit koliko proizvoda dohvata
    private static $limit = 10;

    protected static $service = 'ProductService';

    /**
     *
     * CREATE
     *
     */

    /**
     * Kreira proizvod
     * @param   array       $product_attributes         Niz sa vrednostima proizvoda
     * @param   array       $additional_attributes      Niz sa vrednostima dodatnih atributa
     * @return  bool/int    Vraća true ako je sve prošlo urednu inače vraća neki eror kod
     */
    public static function createProduct($product_attributes, $additional_attributes, $product_names = []) {
        try {
            $category = CategoryService::getCategoryById($product_attributes['category_id']);
            $category_name = $category->name;

            $product = new Product();

            $product->category = $category;
            foreach ($product_attributes as $key => $value) {
                if ($value === null || $key === 'stock_shop') {
                    continue;
                }

                if ($value === null || $key === 'voucher') {
                    continue;
                }

                $product->$key = $value;
            }

            if ($product_attributes['stock_shop']) {
                self::createStockShop($product);
            }

            $voucher = $product_attributes['voucher'] < 0 ? 0 : $product_attributes['voucher'] ;
            $voucher = $voucher === null ? 0 : $voucher;
            $product->voucher = $voucher;
            if (empty(array_filter($additional_attributes))) {
                self::$entity_manager->persist($product);
            } else {
                if (array_key_exists('dimensions', $additional_attributes)) {
                    $value = $additional_attributes['dimensions'];
                    if (array_key_exists('mass_weight', $value)) {
                        $weight = $value['mass_weight'];
                        $product->weight = $weight;
                    }

                    if (array_key_exists('size_width', $value)) {
                        $width  = $value['size_width'];
                        $product->width  = $width;
                    }

                    if (array_key_exists('size_height', $value)) {
                        $height = $value['size_height'];
                        $product->height = $height;
                    }

                    if (array_key_exists('size_length', $value)) {
                        $length = $value['size_length'];
                        $product->length = $length;
                    }
                }

                foreach ($additional_attributes as $key => $value) {
                    $attribute = self::getAttributeByName($category->id, $key);
                    if (!empty($value) && $key !== 'dimensions') {
                        $value = substr($value, 0, 255);
                        $attribute_value = self::getAttributeValueByAttributeIdAndValue($attribute->id, $value);

                        if (empty($attribute_value)) {
                            $attribute_value = self::createAttributeValue($attribute, $value);
                        }
                        self::createProductValue($product, $attribute_value);
                    }
                }
            }
            if (array_key_exists($product->name, $product_names)) {
                $encoded_name = preg_replace('/\(+|\)+|\/+|\s+|_+/', '-', $product->name);
                $encoded_name = urlencode($encoded_name);
                Log::info('unos u seo');
                Log::info($product->name);
                SEOService::createSEO(
                    'product_' . $product->artid,
                    $category->url . $encoded_name . '_' . $product_names[$product->name],
                    null,
                    $product->description,
                    $product->name
                );
            } else {
                $encoded_name = preg_replace('/\(+|\)+|\/+|\s+|_+/', '-', $product->name);
                $encoded_name = urlencode($encoded_name);
                Log::info('unos u seo');
                Log::info($product->name);
                SEOService::createSEO(
                    'product_' . $product->artid,
                    $category->url . $encoded_name,
                    null,
                    $product->description,
                    $product->name
                );
            }
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Kreira vrednost atributa
     * @param   int                 $attribute_id       Id atributa
     * @param   string              $value              Vrednost atributa
     * @return  bool                Vraća true ako je sve prošlo uredu ili neki error message
     */
    private static function createAttributeValue($attribute, $value) {
        $entities = self::$queued_entities;
        $value_name = strtolower($attribute->id . '_' . $value);
        $attribute_value = array_key_exists($value_name, $entities) ? $entities[$value_name] : false;

        if ($attribute_value === false) {
            $attribute_value = new AttributeValue();

            $attribute_value->value = $value;
            $attribute_value->setAttribute($attribute);
            self::$queued_entities[$value_name] = $attribute_value;
        }

        return $attribute_value;
    }

    /**
     * Kreira vrednost za proizvod
     * @param   Product         $product
     * @param   AttributeValue  $attribute_value
     * @return  void
     */
    private static function createProductValue($product, $attribute_value) {
        $product_attribute = new ProductAttribute();
        $product_attribute->setProduct($product);
        $product_attribute->setAttributeValue($attribute_value);

        self::$entity_manager->persist($product_attribute);
        self::$entity_manager->flush();
    }

    /**
     * Kreira unos za stanje proizvoda u magacinu
     *
     * @param   Product     $product        Proizvod za koji se kreira unos
     * @return  void
     */
    public static function createStockWarehouse(Product $product): void {
        $product->stock_warehouse = 1;
        self::$entity_manager->persist($product);
        self::$entity_manager->flush();
    }

    /**
     * Uvozi slike u bazu
     * @param   Product     $product        Objekat proizvoda
     * @return  void
     */
    public static function importImages($images, $product) {
        $image_names = [];
        $prev_name   = "";
        foreach ($images as $image) {
            $product_picture = new ProductPicture();
            if (preg_match("/\?context\=.*/", $image['fajl'])) {
                $name = preg_replace("/\?context\=.*/", '', $image['fajl']);
            } elseif (preg_match("/\%253Fcontext\%253.*/", $image['fajl'])) {
                $name = preg_replace("/\%253Fcontext\%253.*/", '', $image['fajl']);
            } else {
                $name  = $image['fajl'];
            }

            if (!preg_match("/\.$/", $name)) {
                $image_names [] = $name;
                if ($prev_name !== $name) {
                    $product_picture->product       = $product;
                    $product_picture->name          = $name;
                    $product_picture->position      = $image['rb'];
                    $product_picture->transfered    = 0;
                    self::$entity_manager->persist($product_picture);
                }
                $prev_name = $name;
            } else {
                Log::info("Losa ekstenzija slike:" . $name);
            }
        }

        return $image_names;
    }



    /**
     * Dohvata slike određene veliličine
     * @param   int         $artid      Artid proizvoda
     * @param   int         $width      Širina slike
     * @param   int         $height     Visina slike
     * @return  array       Vraća niz slika sa putanjama
     */
    public static function getPictures($artid, $width, $height) {
        try {
            $pictures_originals = '../../photos/';
            $product            = self::getProductByArtid($artid);
            $product_pictures   = self::getProductPictures($product->id);
            $pictures           = [];
            $debugger = '';
            $type = self::getPictureTypeByWidthHeight($width, $height);
            if ($type === null) {
                throw new \Exception('Picture type nije pronadjen sa velcinama ' . $width . 'x' . $height, 14002);
            }
            if (is_dir($pictures_originals) && !empty($product_pictures)) {
                foreach ($product_pictures as $picture) {
                    $picture_transfered = self::isPictureTypeTransfered($picture->id, $type->id);
                    if ($picture_transfered === false) {
                        Log::info($pictures_originals . $artid . '/' . $picture->name);
                        if (is_file($pictures_originals . $artid . '/' . $picture->name)) {
                            $new_picture = ImageService::getImageBySize(
                                $pictures_originals . $artid . '/' . $picture->name,
                                $width,
                                $height,
                                'uploads_static'
                            );
                            $pictures[] =  $new_picture;
                            $picture_type = new ProductPictureImageType();
                            $picture_type->picture_id = $picture->id;
                            $picture_type->type_id = $type->id;
                            self::$entity_manager->persist($picture);
                            self::$entity_manager->persist($picture_type);
                        } else {
                            $pictures [] = "/default_pictures/default_product.png";
                        }
                    } else {
                        $pictures [] = 'uploads_static/' . $width . 'x' . $height . '/' . urlencode($picture->name);
                    }
                }
            } else {
                $pictures [] = "/default_pictures/default_product.png";
            }

            if (empty($product_pictures)) {
                $pictures [] = "/default_pictures/default_product.png";
            } else {
                self::$entity_manager->flush();
            }

            return $pictures;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return $e->getCode();
        }
    }

    public static function getPictureTypeByWidthHeight($width, $height) {
        $qb = self::$entity_manager->createQueryBuilder();
        $type = $qb->select('it')->from('App\Models\ImageType', 'it');
        $type->where('it.width = :width')
            ->setParameter('width', $width);
        $type->andWhere('it.height = :height')
            ->setParameter('height', $height);

        return $type
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    private static function isPictureTypeTransfered(int $picture_id, int $type_id): bool {
        $qb = self::$entity_manager->createQueryBuilder();

        $transfered = $qb->select('pt')->from('App\Models\ProductPictureImageType', 'pt');
        $transfered->where('pt.picture_id = :picture_id')
            ->setParameter('picture_id', $picture_id)
        ;

        $transfered->andWhere('pt.type_id = :type_id')
            ->setParameter('type_id', $type_id)
        ;

        return $transfered
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult() !== null
        ;
    }

    /**
     * Upisuje da li je proizvod na stanju
     *
     * @param   int         $product_id     Id proizvoda
     * @param   int         $shop_id        Id prodavnice opcionalno
     * @return  bool        Vraća true ako je sve prošlo uredu ili error message
     */
    private static function createStockShop($product, $shop_id = null) {
        //Novi objekat
        $current_stock_shop = self::getStockShop($product);
        $stock_shop = new StockShop();
        if (empty($shop_id)) {
            $shop = ShopService::getShopById();
            $shop_id = $shop->id;
        }

        if ($current_stock_shop === null) {
            //Setujem propertije
            $stock_shop->shop_id = $shop_id;
            $stock_shop->setProduct($product);
            //Upisujem u bazu
            self::$entity_manager->persist($stock_shop);
            //self::$entity_manager->flush();
            //self::$entity_manager->clear();
        }

        return true;
    }

    /**
     * Pamti korisnikovo glasanje
     * @param   int     $rating         Ocena
     * @param   int     $product_id     Id proizvoda
     * @return  void
     */
    private static function userRememberVote($rating, $product_id) {
        $user_id = null;
        $ip_address = null;

        $user_id = UserService::getCurrentUserId();
        if ($user_id === false) {
            $ip_address = self::getUserIpAddress();
        }

        $userRememberVote = new UserProductVote();

        $userRememberVote->product_id = $product_id;
        $userRememberVote->vote = $rating;

        if (!empty($user_id)) {
            $userRememberVote->user_id = $user_id;
        }

        if (!empty($ip_address)) {
            $userRememberVote->ip_address = $ip_address;
        }

        self::$entity_manager->persist($userRememberVote);
        self::$entity_manager->flush();
    }










    /**
     *
     * READ
     *
     */





    public static function getStockShop($product) {
        $product_local = self::getProductByArtid($product->artid);
        if (empty($shop_id)) {
            $shop = ShopService::getShopById();
            $shop_id = $shop->id;
        }
        $stock_shop = null;
        if ($product_local !== null) {
            $qb = self::$entity_manager->createQueryBuilder();
            $stock_shop = $qb
                ->select('ss')
                ->from('App\Models\StockShop', 'ss')
                ->where('ss.shop_id = ?1')
                ->andWhere('ss.product_id = ?2')
                ->setParameter(1, $shop_id)
                ->setParameter(2, $product_local->id)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }

        return $stock_shop;
    }




    public static function getAllProducts($category_id) {
        $qb = self::$entity_manager->createQueryBuilder();

        $products = $qb->select('p')->from('App\Models\Product', 'p');
        $products->where('p.category_id = :category_id')
        ->setParameter('category_id', $category_id);

        return $products
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Dohvata sve objavljene proizvode
     * (koristi se za generisanje IT svet listinga)
     *
     * @return  array                       Svi objavljeni proizvodi
     */
    public static function getAllProductsPublished(): array {
        return self::$entity_manager->createQueryBuilder()
            ->select('p')
            ->from('App\Models\Product', 'p')
            ->leftJoin('p.shops', 'ps')
            ->leftJoin('p.pictures', 'pp')
            ->where('p.stock_warehouse = 1 OR ps IS NOT NULL')
            ->andWhere('pp IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Dohvata proizvode i filtrira
     * @param   int             $category_id        Id kategorije
     * @param   string          $sort_by            Po čemu se sortira
     * @param   boolean         $direction          Pravac sortiranja
     * @param   array           $filter_params      Niz sa parametrima po kojima se filtrira
     * @return  array           Vraća niz sa objektima
     */
    public static function getProducts(
        $category_id = null,
        $direction = true,
        $filter_params = null,
        $sort_by = null,
        $limit = null,
        $search = '',
        $last = null,
        $sale = false,
        $stock = '',
        $presales = '',
        $on_sale = ''
    ) {
        //$filter_params          = null;
        $max_limit              = 100;
        $product_mappings       = array_column(Product::getMappingImport(), 'name_local');
        $limit                  = empty($limit) ? self::$limit : $limit;
        $limit                  = $max_limit < $limit ? $max_limit : $limit;
        $direction              = $direction === true ? 'ASC' : 'DESC';
        $direction              = $sort_by === null ? 'DESC' : $direction;
        $sort_by                = $sort_by === null ? 'artid' : $sort_by;
        $sort_by                = in_array($sort_by, $product_mappings) ? $sort_by : 'artid';
        $sort_by                = 'p.' . $sort_by;
        $qb                     = self::$entity_manager->createQueryBuilder();
        $mapping_filter         = false;
        //var_dump($filter_params);die;
        if (!empty($filter_params)) {
            // Kreiram orX
            // (grupa) OR (grupa2)
            $orX = $qb->expr()->orX();

            // Posto zadajem imena parametara u foreach da se ne bi desilo da su ista
            // stavljam brojac koji lepim na kraj parametra.
            $i                      = 0;
            // vrednost je 10 000 jer se gazi sa i u dodeli parametara
            // van foreach-a je zato sto se resetovao i gazio sam sa sobom u unutrasnjem foreach-u
            $j                      = 10000;
            $dinamic_filter_count   = 0;
            // Prolaz kroz sve filtere da izvucemo koje tipove filtriramo
            foreach ($filter_params as $name_import => $value) {
                if (in_array($name_import, $product_mappings)) {
                    $mapping_filter = true;
                    $maping_and = $qb->expr()->andX();
                    if (array_key_exists('min', $value) && array_key_exists('max', $value)) {
                        $maping_and->add($qb->expr()->lte("p." . $name_import, ":max"));
                        $qb->setParameter('max', $value['max']);
                        $maping_and->add($qb->expr()->gte("p." . $name_import, ":min"));
                        $qb->setParameter('min', $value['min']);
                    } else {
                        $maping_and->add($qb->expr()->eq("p." . $name_import, ":value"));
                        $qb->setParameter('value', $value);
                    }
                } else {
                    $dinamic_filter_count++;

                    // Unutrasnji and za
                    //(naziv_filtera and (vrednost_filtera))
                    $andX =  $qb->expr()->andX();
                    // Dodajem za proizvodjaca vrednost i setujem parametar
                    // (Naziv_filtera AND)

                    $name_import = preg_replace('/_/', ' ', $name_import);
                    $andX->add($qb->expr()->eq("a.name_import", "?$i"));
                    $qb->setParameter($i, $name_import);

                    // Prolazim kroz sve vrednosti odabrane za filter
                    $addon = false;
                    if (array_key_exists('min', $value) && array_key_exists('max', $value)) {
                        $addon = true;
                        $min = intval($value['min']);
                        $max = intval($value['max']);
                        $filter_and = $qb->expr()->andX();
                        $filter_and->add($qb->expr()->lte("av.value", $max));
                        $filter_and->add($qb->expr()->gte("av.value", $min));
                        $j++;
                    } else {
                        $filter_or = $qb->expr()->orX();
                        foreach ($value as $filter_val) {
                            $filter_or->add($qb->expr()->eq("av.value", "?$j"));
                            $qb->setParameter($j, $filter_val);
                            $j++;
                        }
                    }

                    // Dodajem vrednosti (AND (vrednost_filtera OR vrednost_filtera))

                    $filter_addon = $addon ? $filter_and : $filter_or;
                    $andX->add($filter_addon);

                    // Spajam sve u kranji or
                    // (naziv_filtera AND (vrednost_filtera OR vrednost_filtera)) OR (grupa2)
                    $orX->add($andX);

                    $i++;
                }
            }
        }

        $products = $qb
            ->select('p.id')
            ->from('App\Models\Category', 'c')
            ->join('c.attributes', 'a')
            ->join('a.attribute_values', 'av')
            ->join('av.product_attributes', 'pa')
            ->join('pa.product', 'p')
            // ->where('c.id = :category_id')
            // ->setParameter('category_id', $category_id)
        ;

        // Proizvodi koji imaju cenu 0 se dohvataju samo ukoliko su na pretprodaji
        $products
            ->where('p.price_discount > 0 OR p.presales = 1')
            ->andWhere('p.price_retail > 0 OR p.presales = 1')
        ;

        if (!empty($category_id)) {
            $products
                ->andWhere('p.category_id = :category_id')
                ->setParameter('category_id', $category_id);
        }

        if ($mapping_filter) {
            $products->andWhere($maping_and);
        }

        if (!empty($filter_params)) {
            $products
                ->andWhere($orX)
                ->groupBy('p.id')
            ;

            if ($dinamic_filter_count > 0) {
                $products
                    ->having($qb->expr()->eq($qb->expr()->count('p.id'), $dinamic_filter_count))
                ;
            }
        }

        if ($search !== '') {
            $products
                ->andWhere('p.name LIKE :search')
                ->setParameter('search', '%' . $search . '%')
            ;
        }

        if ($stock !== '') {
            $subquery = self::$entity_manager->createQueryBuilder();
            // $subquery->expr()->exists('
            // $subquery->expr('
            //     SELECT *
            //     FROM StockShops sp
            //     WHERE sp.product_id = p.id
            // ');

            $subquery
                ->select('sp')
                ->from('App\Models\StockShop', 'sp')
                ->where('sp.product_id = p.id')
            ;


            $stockOr = $qb->expr()->orX();
            $stockOr->add($qb->expr()->gt('p.stock_warehouse', 0));
            $stockOr->add($qb->expr()->exists($subquery->getDql()));
            // $queryBuilder->expr()->exists($subQueryBuilder->getDql());
            // $stockOr->add($qb->expr()->exists(

                // echo $stockOr; die();
            // );

            $products
                // ->andWhere('p.stock_warehouse > 0')
                ->andWhere($stockOr)
            ;
        }

        if ($presales !== '') {
            $products
                ->andWhere('p.presales = :presales')
                ->setParameter('presales', 1)
            ;
        }

        if ($on_sale !== '') {
            $products
                ->andWhere('p.on_sale = :on_sale')
                ->setParameter('on_sale', 1)
            ;
        }

        if ($sale === true) {
            $products
                ->andWhere('p.on_sale = :sale')
                ->setParameter('sale', $sale)
            ;
        }

        if ($last !== null && $last !== '') {
            $dir = $direction === "ASC" ? ">=" : "<=";
            $products
                ->andWhere($sort_by . $dir . ":last")
               // ->setParameter('sort_by', $sort_by)
                ->setParameter('last', $last)
            ;
        }
        $products->andWhere('p.published = 1');
        $products->orderBy($sort_by, $direction);
        $products->setMaxResults($limit);

        if (empty($filter_params)) {
            $products ->groupBy('p.id');
        }

        $ids = array_column($products->getQuery()->getResult(), 'id');

        $products = self::getProductsByIds($ids);
        $products->orderBy($sort_by, $direction);
        return $products->getQuery()->getResult();
    }

    /**
     * Dohvata istaknute proizvode po id-u kategorije
     * @param   int         $category_id        Id kategorije
     * @return  array       Vraća niz objekata
     */
    public static function getFeaturedProductsByCategoryId($category_id = null, $limit = 10) {
        Product::enableImageFormatThumbnail();
        $qb = self::$entity_manager->createQueryBuilder();

        $featured_products = $qb
            ->select('p')
            ->from('App\Models\Product', 'p')
            ->where('p.category_id = :category_id')
            ->andwhere('p.is_featured = :is_featured')
            ->andwhere('p.published = 1')
            ->setParameter('category_id', $category_id)
            ->setParameter('is_featured', true)
            ->setMaxResults($limit)
        ;
        return $featured_products
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Dohvata slične proizvode po ceni
     * @param   integer         $category_id        Id kategorije
     * @param   integer         $product_id         Id proizvoda po kom se dohvata slični
     * @param   integer         $price_retail       Cena po kojoj se dohvataju slični proizvodi
     * @param   integer         $limit              Limit koliko dohvata
     * @return  array           Vraća niz objekata
     */
    public static function getSimilarProducts($category_id, $product_id, $price_discount, $limit = 12) {
        Product::enableImageFormatThumbnail();
        $rsm = new ResultSetMappingBuilder(self::$entity_manager);
        $rsm->addRootEntityFromClassMetadata('App\Models\Product', 'p');
        $query = self::$entity_manager->createNativeQuery("
            SELECT p.*
            FROM Products p
            WHERE p.category_id = :category_id
            AND p.id != :product_id
            AND (p.stock_warehouse > 0 OR EXISTS (
                SELECT *
                FROM StockShops ss
                WHERE ss.product_id = p.id
            ))
            AND p.published = 1
            ORDER BY ABS(CAST(p.price_discount AS SIGNED) - :price_discount) ASC
            LIMIT :limit
        ", $rsm);

        $query
            ->setParameter('category_id', $category_id)
            ->setParameter('price_discount', $price_discount)
            ->setParameter('product_id', $product_id)
            ->setParameter('limit', $limit)
        ;

        return $query->getResult();
    }

    /**
     * Dohvata proizvode na akciji
     * @param   intefer     $category_id    Id kategorije
     * @param   integer     $limit          Limit koliko dohvata
     * @return  array       Niz proizvoda
     */
    public static function getProductsOnSale($category_id = null, $limit = 10) {
        $qb = self::$entity_manager->createQueryBuilder();

        $products = $qb->select('p')->from('App\Models\Product', 'p');

        if (!empty($category_id)) {
            $products->where('p.category_id = :category_id')
            ->setParameter('category_id', $category_id);
        }

        return $products
            ->andWhere('p.on_sale = 1')
            ->andwhere('p.published = 1')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Dohvata istaknute proizvode
     * @param   int     $category_id    Id kategorije opcionalni
     * @param   int     $limit          Limit koliko dohvata proizvoda
     * @return  array   Vraća niz proizvoda koji su istaknuti
     */
    public static function getFeaturedProducts($categories = [], $limit = 10) {
        $products_array = [];

        if (!empty($categories)) {
            foreach ($categories as $category_name) {
                $category = CategoryService::getCategoryByName($category_name);
                if (!empty($category)) {
                    $products = self::getFeaturedProductsByCategoryId($category->id, $limit);

                    $products_array[$category_name] = $products;
                }
            }
        }

        return $products_array;
    }

    /**
     * Dohvata atribut po imenu i kategoriji
     * @param       int         $category_id        Id kategorije
     * @param       string      $attribute_name     Naziv atributa
     * @return      Attribute   Vraća atribut model
     */
    public static function getAttributeByName(int $category_id, string $attribute_name): ?Attribute {
        $qb = self::$entity_manager->createQueryBuilder();
        $attribute = $qb
            ->select('a')
            ->from('App\Models\Attribute', 'a')
            ->where('a.machine_name = ?1')
            ->setParameter(1, $attribute_name)
            ->andWhere('a.category_id = ?2')
            ->setParameter(2, $category_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
        return $attribute;
    }

    public static function getAttributeValueById($attribute_value_id) {
        return self::$entity_manager->find('App\Models\AttributeValue', $attribute_value_id);
    }

    /**
     * Dohvata vrednost attributa po id atributa i vrednosti
     * @param   int                 $attribute_id       Id attributa
     * @param   string              $value              Vrednost attributa
     * @return  AttributeValue      Vrača objekat vrednost atributa
     */
    public static function getAttributeValueByAttributeIdAndValue($attribute_id, $value) {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('av')
            ->from('App\Models\AttributeValue', 'av')
            ->where('av.attribute_id = ?1')
            ->setParameter(1, $attribute_id)
            ->andWhere('av.value = ?2')
            ->setParameter(2, $value)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Dohvata vrednost atributa za dati proizvod
     * prema machine_name-u atributa
     *
     * @param   int         $product_id     ID proizvoda za koji se vrednost dohvata
     * @param   string      $machine_name   Naziv atributa koji se dohvata
     * @return  string|null                 Vrednost atributa (ili null ako nije pronađen)
     */
    public static function getAttributeValueByMachineName(
        int $product_id,
        string $machine_name
    ): ?string {
        // SELECT av.value
        // FROM Products p
        // JOIN ProductAttributes pa ON pa.product_id = p.id
        // JOIN AttributeValues av ON av.id = pa.attribute_value_id
        // JOIN Attributes a ON a.id = av.attribute_id
        // WHERE p.artid = 59060 AND a.machine_name = 'field_procesor_podnozje'
        try {
            return self::$entity_manager->createQueryBuilder()
                ->select('av.value')
                ->from('App\Models\Product', 'p')
                ->join('p.product_attributes', 'pa')
                ->join('pa.attribute_value', 'av')
                ->join('av.attribute', 'a')
                ->where('p.id = ?1')
                ->setParameter(1, $product_id)
                ->andWhere('a.machine_name = ?2')
                ->setParameter(2, $machine_name)
                ->getQuery()
                ->getSingleScalarResult()
            ;
        } catch (NoResultException $exception) {
            return null;
        }
    }

    /**
     * Dohvata vrednosti atributa za datu kategoriju
     * prema machine_name-u atributa
     *
     * @param   int         $category_id    ID kategorije za koji se vrednost dohvata
     * @param   string      $machine_name   Naziv atributa koji se dohvata
     * @return  array|null                  Vrednost atributa (ili null ako nije pronađen)
     */
    public static function getAttributeValueByMachineNameCategoryId(
        int $category_id,
        string $machine_name
    ): ?array {
        // SELECT av.value
        // FROM Attributes a
        // JOIN AttributeValues av ON av.attribute_id = a.id
        // WHERE a.category_id = 28 AND a.machine_name = 'field_procesor_podnozje'
        try {
            return self::$entity_manager->createQueryBuilder()
                ->select('av.value')
                ->from('App\Models\Attribute', 'a')
                ->join('a.attribute_values', 'av')
                ->where('a.category_id = ?1')
                ->setParameter(1, $category_id)
                ->andWhere('a.machine_name = ?2')
                ->setParameter(2, $machine_name)
                ->distinct()
                ->getQuery()
                ->getResult()
            ;
        } catch (NoResultException $exception) {
            return null;
        }
    }

    /**
     * Dohvata proizvod po id-u
     * @param   int         $product_id     Id proizvoda
     * @return  Product     Vraća objekat
     */
    public static function getProductById($product_id) {
        return self::$entity_manager->find('App\Models\Product', $product_id);
    }

    public static function getProductsByIds($product_ids) {
        Product::enableImageFormatThumbnail();
        $qb = self::$entity_manager->createQueryBuilder();
        $products = $qb
            ->select('p')
            ->from('App\Models\Product', 'p')
            ->where('p.id IN (:ids)')
            ->setParameter(':ids', $product_ids)
        ;
        return $products;
    }

    public static function getProductsByArtids($artids) {
        Product::enableImageFormatThumbnail();
        $qb = self::$entity_manager->createQueryBuilder();
        $products = $qb
            ->select('p')
            ->from('App\Models\Product', 'p')
            ->where('p.artid IN (:artids)')
            ->setParameter(':artids', $artids)
            ->getQuery()
            ->getResult()
        ;
        return $products;
    }

    /**
     * Dohvata proizvod prema artid
     * @param   int         $artid      Artid prozvoda
     * @return  Product     Vraća product objekat ako je pronađe ili null
     */
    public static function getProductByArtid($artid) {

        Product::enableImageFormatThumbnail();
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('p')
            ->from('App\Models\Product', 'p')
            ->where('p.artid = ?1')
            ->setParameter(1, $artid)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Dohvata referencu proizvoda i vrednosti atributa
     * @param   int                 $product_id             Id proizvoda
     * @param   int                 $attribute_value_id     Id vrednosti atributa
     * @return  ProductAttribute    Vraća objekat ProducteAttribute
     */
    public static function getProductAttribute(
        int $product_id,
        int $attribute_value_id
    ): ?ProductAttribute {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('pa')
            ->from('App\Models\ProductAttribute', 'pa')
            ->where('pa.product_id = ?1')
            ->setParameter(1, $product_id)
            ->andWhere('pa.attribute_value_id = ?2')
            ->setParameter(2, $attribute_value_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Dohvata atribute proizvoda i njegove vrednosti
     * @param   itn         $product_id         Id proizvoda
     * @return  array       Vraća niz
     */
    public static function getProductAttributes($product_id, $order_by = null) {
        $product = self::getProductById($product_id);
        if (empty($product)) {
            throw new ValidationException('Proizvod sa tim id-om nije pronađen', 14001);
        }

        $qb = self::$entity_manager->createQueryBuilder();

        $query = $qb
            ->select('av')
            ->from('App\Models\AttributeValue', 'av')
            ->join('av.attribute', 'a')
            ->join('av.product_attributes', 'pa')
            ->where('pa.product_id = :product_id')
            ->setParameter('product_id', $product_id)
        ;

        if (!empty($order_by)) {
            $order_by = 'a.' . $order_by;

            $query
                ->andWhere($order_by . ' IS NOT NULL')
                ->orderBy($order_by, 'ASC')
            ;
        }

        return $query->getQuery()->getResult();
    }

    /**
     * Dohvata headline sliku za proizvod
     * @param   int         $product_id     Id proizvoda
     * @return  ProductPicture
     */
    public static function getProductImagePosition($product_id) {
        $qb = self::$entity_manager->createQueryBuilder();
        return $qb
            ->select('pp.name')
            ->from('App\Models\ProductPicture', 'pp')
            ->where('pp.product_id = :product_id')
            ->setParameter('product_id', $product_id)
            ->andWhere('pp.position = :position')
            ->setParameter('position', 1)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public static function enableImageFormatThumbnail() {
        Product::enableImageFormatThumbnail();
    }

    /**
     * Dohvata slike proizvoda
     * @param   int     $product_id     Id proizvoda
     * @return  array vraca niz
     */
    public static function getProductPictures($product_id) {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('pp')
            ->from('App\Models\ProductPicture', 'pp')
            ->where('pp.product_id = :product_id')
            ->setParameter('product_id', $product_id)
            ->orderBy('pp.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Dohvata ime atributa i njegovu vrednost za određeni proizvod
     *
     * @param   int         $product_id         Id proizvoda
     * @param   string      $machine_name       Naziv atributa
     * @return  App\Models\ProductAttribute[]   Vraća niz sa imenom atributa i vrednost
     */
    public static function getProductAttributeValue(int $product_id, string $machine_name): array {
        $qb = self::$entity_manager->createQueryBuilder();
        return $qb
            ->select('pa')
            ->from('App\Models\ProductAttribute', 'pa')
            ->join('pa.attribute_value', 'av')
            ->join('av.attribute', 'a')
            ->where('pa.product_id = ?1')
            ->setParameter(1, $product_id)
            ->andWhere('a.machine_name = ?2')
            ->setParameter(2, $machine_name)
            ->getQuery()
            ->getResult()
        ;
    }

    public static function getProductAttributeByValueId($attribute_value_id) {
        $qb = self::$entity_manager->createQueryBuilder();
        return $qb
            ->select('pa')
            ->from('App\Models\ProductAttribute', 'pa')
            ->where('pa.attribute_value_id = ?1')
            ->setParameter(1, $attribute_value_id)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Dohvatanje pojedinačnog atributa za proizvod po ID-ju atributa
     *
     * @param   int                 $product_attribute_id   ID atributa
     * @return  ?ProductAttribute                           Atribut ukoliko je pronađen ili null ukoliko nije
     */
    public static function getProductAttributeById(int $product_attribute_id): ?ProductAttribute {
        return self::$entity_manager->find('App\Models\ProductAttribute', $product_attribute_id);
    }

    /**
     * Dohvata atribut po id-u
     * @param   int         $attribute_id       Id atributa
     * @return  Object      $attribute          Vraća objekat atributa
     */
    public static function getAttributeById($attribute_id) {
        return self::$entity_manager->find('App\Models\Attribute', $attribute_id);
    }

    /**
     * Dohvata proizvode koju su odabrani za poređenje
     * @return  array       Vraća niz objekata
     */
    public static function getComparingProducts() {
        $product_ids = self::getSessionKeySubKeyValue('product_ids');

        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('p')
            ->from('App\Models\Product', 'p')
            ->where('p.id IN (:product_ids)')
            ->setParameter('product_ids', $product_ids)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Dohvata glasanje na proizvoda korisnika
     * @param   int                 $product_id     Id proizvoda
     * @param   int                 $user_id        Id korisnika
     * @return  UserProductVote     Vraća model u suprontom null
     */
    private static function getUserProductVote($product_id) {
        $user_id = null;
        $ip_address = null;

        $user_id = UserService::getCurrentUserId();
        if ($user_id === false) {
            $ip_address = self::getUserIpAddress();
        }

        $qb = self::$entity_manager->createQueryBuilder();

        $query = $qb
            ->select('upv')
            ->from('App\Models\UserProductVote', 'upv')
            ->where('upv.product_id = :product_id')
            ->setParameter('product_id', $product_id)
        ;

        if (!empty($user_id)) {
            $query
                ->andWhere('upv.user_id = :user_id')
                ->setParameter('user_id', $user_id)
            ;
        }

        if (!empty($ip_address)) {
            $query
                ->andWhere('upv.ip_address = :ip_address')
                ->setParameter('ip_address', $ip_address)
            ;
        }

        return $query
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Dohvata težinu proizvoda
     * @param   int     $product_id     Id proizvoda
     * @return  int     Vraća težinu porizvoda
     */
    public static function getProductWeight($product_id) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('weight', 'weight');

        $query = self::$entity_manager->createNativeQuery("
            SELECT av.value as 'weight' FROM Attributes a
            INNER JOIN AttributeValues av ON a.id = av.attribute_id
            INNER JOIN ProductAttributes pa ON av.id = pa.attribute_value_id AND pa.product_id = :product_id
            WHERE a.machine_name = 'weight_kg';
            ", $rsm);

        $query->setParameter('product_id', $product_id);

        $result = $query->getOneOrNullResult();

        return $result !== null ? floatval($result['weight']) : null;
    }

    /**
     *
     * UPDATE
     *
     */

    public static function updateProduct($product_id, $product_attributes, $additional_attributes, $artid = false) {
        //Dohvata proizvod
        try {
            $product = $artid === false ? self::getProductById($product_id) : self::getProductByArtid($product_id);
            if (!$product) {
                throw new \Exception('Proizvod sa id-jem' . $product_id . 'nije pronadjen');
            }

            foreach ($product_attributes as $key => $value) {
                if ($value === null || $key === 'stock_shop') {
                    continue;
                }

                if ($value === null || $key === 'voucher') {
                    continue;
                }
                $product->$key = $value;
            }

            //Upisujem proizvod u bazu
            self::$entity_manager->persist($product);
            self::$entity_manager->flush();

            $stock_shop = $product_attributes['stock_shop'];

            //Dohvatam kategoriju
            $category = $product->category;
            if (array_key_exists('stock_shop', $product_attributes)) {
                if ($stock_shop > 0 && $stock_shop <= 10000) {
                    self::createStockWarehouse($product);
                }

                if ($stock_shop > 20000 && $stock_shop <= 30000) {
                    self::createStockWarehouse($product);
                    self::deleteStockShop($product);
                }

                if ($stock_shop > 0 && $stock_shop <= 20000) {
                    self::deleteStockWarehouse($product);
                    self::createStockShop($product);
                }

                if ($stock_shop <= 0) {
                    self::deleteStockWarehouse($product);
                    self::deleteStockShop($product);
                }
            }

            if (array_key_exists('dimensions', $additional_attributes)) {
                $value = $additional_attributes['dimensions'];
                if (array_key_exists('mass_weight', $value)) {
                    $weight = $value['mass_weight'];
                    $product->weight = $weight;
                }

                if (array_key_exists('size_width', $value)) {
                    $width  = $value['size_width'];
                    $product->width  = $width;
                }

                if (array_key_exists('size_height', $value)) {
                    $height = $value['size_height'];
                    $product->height = $height;
                }

                if (array_key_exists('size_length', $value)) {
                    $length = $value['size_length'];
                    $product->length = $length;
                }
            }

            foreach ($additional_attributes as $key => $value) {
                if ($key !== 'dimensions') {
                    $attribute = self::getAttributeByName($category->id, $key);
                    if (empty($attribute)) {
                        // LogService::createNewImportLog($key);
                    } else {
                        $attribute_value = self::getAttributeValueByAttributeIdAndValue($attribute->id, $value);
                        if (empty($attribute_value)) {
                            $attribute_value = self::createAttributeValue($attribute, $value);
                        }

                        $product_attributes = self::getProductAttributeValue($product->id, $key);
                        foreach ($product_attributes as $product_attribute) {
                            if (!empty($product_attribute)
                                && $product_attribute->attribute_value_id === $attribute_value->id
                            ) {
                                $attribute_value_old = self::getAttributeValueById(
                                    $product_attribute->attribute_value_id
                                );

                                self::deleteProductAttributeValue($product_attribute->id);
                                $product_values = self::getProductAttributeByValueId($attribute_value_old->id);
                                if (empty($product_values)) {
                                    self::deleteAttributeValue($attribute_value_old->id);
                                }
                            }
                            $exists = self::getProductAttribute($product->id, $attribute_value->id);
                            // radim proveru da li postoji pre upisa
                            if ($exists === null) {
                                self::createProductValue($product, $attribute_value);
                            }
                        }
                    }
                }
            }


            return true;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return true;
        }
    }

    /**
     * Ocenjivanje proizvoda
     * @param   int         $product_id         Id proizvoda
     * @param   int         $rating             Ocena proizvoda
     * @return  void        Ako je sve prošlo uredu ne vraća ništa u suprotnom vraća error_code
     */
    public static function vote($product_id, $rating) {
        $user_vote = self::getUserProductVote($product_id);
        $product = self::getProductById($product_id);
        if (empty($user_vote)) {
            if (empty($product)) {
                throw new ValidationException('Proizvod sa tim id-om nije pronađen', 14001);
            }

            $product->rating_count ++;
            $product->rating_sum += $rating;

            self::$entity_manager->persist($product);
            self::$entity_manager->flush();

            self::userRememberVote($rating, $product_id);
        } else {
            $product->rating_sum -= $user_vote->vote;
            $product->rating_sum += $rating;
            $user_vote->vote     = $rating;
            self::$entity_manager->persist($product);
            self::$entity_manager->persist($user_vote);
            self::$entity_manager->flush();
        }

        return $product->rating;
    }

    /**
     * Dodaje u session id proizvoda za upoređenje
     * @param   int      $product_id     Id proizvoda
     */
    public static function addProductIdForComparison($product_id) {
        self::setSession('product_ids', $product_id, true);
    }

    public static function updateProductImage($image) {
    }

    /**
     *
     * DELETE
     *
     */

     /**
      * Setujem stock warehouse na 0 zato sto ga nema na stanju u magacinu
      *
      * @param Product $product
      * @return void
      */
    public static function deleteStockWarehouse(Product $product): void {
        $product->stock_warehouse = 0;
        self::$entity_manager->persist($product);
        self::$entity_manager->flush();
    }

    public static function deleteProductAttributeValue($product_attribute_id) {
        //Dohvata referencu proizvod/vrednost
        $product_attribute = self::getProductAttributeById($product_attribute_id);
        //Briše referencu proizvoda i vrednosti
        self::$entity_manager->remove($product_attribute);
        self::$entity_manager->flush();
        return true;
    }

    public static function deleteAttributeValue($attribute_value_id) {
        //Dohvata vrednost atributa
        $attribute_value = self::getAttributeValueById($attribute_value_id);

        //Briše vrednost atribut
        self::$entity_manager->remove($attribute_value);
        self::$entity_manager->flush();

        return true;
    }

    /**
     * Briše proizvod po id-u
     * @param   int         $product_id     Id proizvoda
     * @return  bool        Vraća true ili neki error kod
     */
    public static function deleteProduct($product_id) {
        //Dohvata proizvod po id-u
        $product = self::getProductById($product_id);
        if (empty($product)) {
            throw new ValidationException('Proizvod pod tim id-om nije pronađen', 14001);
        }

        //Briše productistratora
        self::$entity_manager->remove($product);
        self::$entity_manager->flush();

        return true;
    }

    /**
     * Briše slike za određen model
     * @param   int         $product_id         Id proizvoda
     * @return  void
     */
    public static function deletePictures($product_id, $artid = false) {
        $product = $artid === true ? self::getProductByArtid($product_id) : self::getProductById($product_id);
        if ($product) {
            $pictures = self::getProductPictures($product->id);
            foreach ($pictures as $picture) {
                // ImageService::deletePictures($picture->name, self::$static_folder);
            }
            self::$entity_manager->createQueryBuilder()
                ->delete('App\Models\ProductPicture', 'pp')
                ->where('pp.product_id = ?1')
                ->setParameter(1, $product->id)
                ->getQuery()
                ->getResult()
            ;
        }
    }



    /**
     * Briše id proizvoda iz poređenja
     * @param   int         $product_id         Id proizvoda
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprotnom vraća neki error_code
     */
    public static function removeProductIdFromComparison($product_id) {
        return self::removeValueFromSessionSubkey('product_ids', $product_id);
    }

    public static function deleteStockShop($product) {
        $stock_shop = self::getStockShop($product);

        if (empty($shop_id)) {
            $shop = ShopService::getShopById();
            $shop_id = $shop->id;
        }

        if ($stock_shop !== null) {
            self::$entity_manager->createQueryBuilder()
                ->delete('App\Models\StockShop', 'ss')
                ->where('ss.shop_id = ?1')
                ->andWhere('ss.product_id = ?2')
                ->setParameter(1, $shop_id)
                ->setParameter(2, $product->id)
                ->getQuery()
                ->getResult()
            ;
        }
    }
}
