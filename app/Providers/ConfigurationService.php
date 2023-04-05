<?php

namespace App\Providers;

use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;
use App\Models\Configuration\ConfigurationMain;
use App\Models\Configuration\ConfigurationProduct;
use App\Providers\ProductService;
use Illuminate\Support\Facades\Log;

// Manipulacija podacima za konfigurator
class ConfigurationService extends BaseService {
    private static $visibility = [
        'private'   => 0,
        'public'    => 1,
    ];

    public static $reserved_name = 'trenutni';

    private static $service_name = 'ConfigurationService';

    /**
     * Kreira konfiguraciju
     *
     * @param   int                 $user_id                Id korisnika
     * @param   string              $name                   Naziv konfiguracije
     * @param   int                 $visibility             Privatno ili javno
     * @return  ConfigurationMain
     */
    public static function createConfiguration(
        string $name,
        ?int $user_id = null,
        int $visibility = 0
    ): ConfigurationMain {
        $configuration = new ConfigurationMain();

        if (empty($user_id)) {
            $user_id = UserService::getCurrentUserId();
        }

        if ($user_id !== false) {
            $user = UserService::getUserById($user_id);
            if (empty($user)) {
                throw new ValidationException('Korisnik sa tim id-om nije pronađen', 19002);
            }
            $name = ValidationService::validateString($name, 255);

            if ($name === false) {
                throw new ValidationException('Naziv konfiguratora nije u dobrom formatu', 19003);
            } elseif (is_string($name)) {
                if ($name === self::$reserved_name || self::isConfigurationNameTaken($name)) {
                    throw new ValidationException('Naziv konfiguratora je zauzet', 19003);
                }
            }




            $configuration->user = $user;
            $configuration->name = $name;
            $configuration->visibility = $visibility;
            $configuration->date_created = new \DateTime();

            self::$entity_manager->persist($configuration);
            self::$entity_manager->flush();
            $session_key_configuration = 'configuration_name_0';
            SessionService::deleteSession($session_key_configuration, 'ConfiguratorSetup');
        }

        return $configuration;
    }

    /**
     * Dodaje proizvode u konfiguraciju
     *
     * @param   int     $configuration_id   Id konfiguracije
     * @param   int     $product_id         Id proizvoda
     * @param   int     $quantity           Kolicina
     * @return  void
     */
    private static function addProductToConfiguration(int $configuration_id, int $product_id, int $quantity = 1): void {
        $configuration = self::getConfigurationById($configuration_id);
        if ($configuration === null) {
            throw new ValidationException('Konfiguracija sa tim id-om nije pronađena', 19013);
        }

        $product = ProductService::getProductById($product_id);
        if ($product === null) {
            throw new ValidationException('Proizvod sa tim id-om nije pronađen', 19013);
        }
        $configuration_product = new ConfigurationProduct();
        $configuration_product->configuration = $configuration;
        $configuration_product->product = $product;
        $configuration_product->quantity = $quantity;

        self::$entity_manager->persist($configuration_product);
        self::$entity_manager->flush();
    }

    /**
     * Kreira konfiguraciju i prebacuje iz sesije u bazu
     *
     * @param   int         $user_id               Id korisnika
     * @param   string      $configuration_name    Naziv konfiguracije
     */
    public static function placeConfiguration(int $user_id, string $configuration_name): ConfigurationMain {
        $configurations = self::getActiveConfiguration();
        if (empty($configurations)) {
            throw new ValidationException('Konfiguracija je prazna', 19009);
        }

        //Dohvata id prizvoda koji treba da se unesu
        $product_data = [];
        if ($configurations['products'] !== null) {
            foreach ($configurations['products'] as $configuration_single) {
                $product_data[] = [
                    'product_id'        =>  $configuration_single['product_id'],
                    'quantity'          =>  $configuration_single['quantity'],
                ];
            }
        }

        $configuration = self::createConfiguration($configuration_name, $user_id);

        foreach ($product_data as $data) {
            self::addProductToConfiguration($configuration->id, $data['product_id'], $data['quantity']);
        }

        return $configuration;
    }

    /**
     * Dohvata aktivnu konfiguraciju
     *
     * @return  array       Niz sa podacima konfiguracije (ime, vidljivost, proizvodi)
     */
    public static function getActiveConfiguration(): array {
        return [
            'products'      => self::getSessionKeySubKeyValue('configuration_products'),
        ];
    }

    /**
     * Vraca niz proizvoda iz konfiguracije
     *
     * @param string|null $configuration_name   Naziv konfiguracije
     * @return array
     */
    public static function getConfigurationArray(?string $configuration_name = null): array {
        $configuration_array = [];
        $user_configurations = $configuration_name === null
            ? self::getActiveConfiguration()
            : self::getConfigurationByName($configuration_name)
        ;
        if ($configuration_name !== null) {
            $configuration_array['products'] = [];
            foreach ($user_configurations->products as $key => $user_configuration) {
                $configuration_array['products'][$key] ['product_id'] = $user_configuration->product_id;
                $configuration_array['products'][$key] ['quantity']   = $user_configuration->quantity;
            }
        } else {
            $configuration_array = $user_configurations;
        }
        return $configuration_array;
    }

    /**
     * Vraca niz proizvoda iz konfiguracije
     *
     * @param int|null $configuration_id   Id konfiguracije
     * @return array
     */
    public static function getConfigurationArrayById(?int $configuration_id = null): array {
        $configuration_array = [];
        $user_configurations = $configuration_id === null
            ? self::getActiveConfiguration()
            : self::getConfigurationById($configuration_id)
        ;
        if ($configuration_id !== null) {
            $configuration_array['products'] = [];
            foreach ($user_configurations->products as $key => $user_configuration) {
                $configuration_array['products'][$key] ['product_id'] = $user_configuration->product_id;
                $configuration_array['products'][$key] ['quantity']   = $user_configuration->quantity;
            }
        } else {
            $configuration_array = $user_configurations;
        }
        return $configuration_array;
    }

    /**
     * Vraca niz sa id-evima proizvoda u konfiguraciji
     *
     * @param integer|null $configuration_id    Id konfiguracije
     * @return array                            Niz id-eva proizvoda
     */
    public static function getProductConfigurationArray(?int $configuration_id = null): array {
        $configuration_name = null;
        if ($configuration_id !== null) {
            $configuration_name = self::getConfigurationById($configuration_id)->name;
        }
        $configuration_products = self::getConfigurationArray($configuration_name);
        $products = [];
        if (!empty($configuration_products['products'])) {
            foreach ($configuration_products['products'] as $configuration_product) {
                $products[] = $configuration_product['product_id'];
            }
        }

        return $products;
    }

    /**
     * Dohvata konfiguraciju po id-u
     *
     * @param   int                 $configuration_id       Id konfiguracije
     * @return  ConfigurationMain
     */
    public static function getConfigurationById(int $configuration_id): ConfigurationMain {
        $configuration = self::$entity_manager->find('App\Models\Configuration\ConfigurationMain', $configuration_id);
        if (empty($configuration)) {
            throw new ValidationException('Konfiguracija sa tim id-om nije pronađena', 19013);
        }

        if (UserService::getCurrentUserId() !== $configuration->user_id
            && $configuration->visibility === self::$visibility['private']
            && PermissionService::checkPermission('order_read')
        ) {
            Log::info('Id korisnika sesija: '. UserService::getCurrentUserId() . 'Id korisnika konfigurator: ' . $configuration->user_id);
            // throw new PermissionException('Nemate dozvolu za pretragu konfiguracija po id-u', 19014);
        }

        return $configuration;
    }

    /**
     * Dohvata konfiguraciju po nazivu
     *
     * @param   string                 $configuration_name       Naziv konfiguracije
     * @return  ConfigurationMain
     */
    public static function getConfigurationByName(string $configuration_name): ConfigurationMain {
        $qb = self::$entity_manager->createQueryBuilder();

        $configuration = $qb
            ->select('cm')
            ->from('App\Models\Configuration\ConfigurationMain', 'cm')
            ->where('cm.name = ?1')
            ->setParameter(1, $configuration_name)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if (empty($configuration)) {
            throw new ValidationException('Konfiguracija sa tim id-om nije pronađena', 19013);
        }

        if (UserService::getCurrentUserId() !== $configuration->user_id
            && $configuration->visibility === self::$visibility['private']
            && PermissionService::checkPermission('order_read')
        ) {
            Log::info('Id korisnika sesija: '. UserService::getCurrentUserId() . 'Id korisnika konfigurator: ' . $configuration->user_id);
            // throw new PermissionException('Nemate dozvolu za pretragu konfiguracija po imenu', 19014);
        }

        return $configuration;
    }


    /**
     * Dohvata id konfiguracije po imenu konfiguracije
     *
     * @param string $configuration_name        Ime konfiguracije
     * @return integer                          Id konfiguracije
     */
    public static function getConfigurationIdByName(string $configuration_name): int {
        return self::getConfigurationByName($configuration_name)->id;
    }

    /**
     * Dohvata konfiguracije
     *
     * @param   int         $configuration_id       Id konfiguracije
     * @param   string      $search                 Dodatna pretraga
     * @param   bool        $direction              Smer u kojem dohvata proizvode (manje ili više od prosleđenog id-a)
     * @param   int         $limit                  Limit koliko proizvoda dohvata
     * @return  array       $configurations         Niz konfiguracija
     */
    public static function getConfigurations(
        ?int $configuration_id = null,
        ?string $search = null,
        bool $direction = true,
        ?int $limit = null,
        $as_array = false
    ): array {
        if (PermissionService::checkPermission('order_read') === false) {
            throw new PermissionException('Nemate dozvolu za pretragu konfiguracija', 19017);
        }

        $order_parameter = $direction ? 'DESC' : 'ASC';

        $qb = self::$entity_manager->createQueryBuilder();

        $orders = $qb
            ->select('cm')
            ->from('App\Models\Configuration\ConfigurationMain', 'cm')
            ->orderBy('cm.id', $order_parameter)
        ;

        if (!empty($configuration_id)) {
            $direction = $direction ? '<' : '>';

            $query = 'cm.id ' . $direction . ' :configuration_id';

            $orders
                ->where($query)
                ->setParameter('configuration_id', $configuration_id)
            ;
        }

        if (!empty($search)) {
            $orders
                ->andWhere('cm.id = :search')
                ->setParameter('search', $search)
            ;
        }

        if (!empty($limit)) {
            $orders
                ->setMaxResults($limit)
            ;
        }

        $result = $as_array ? $orders->getQuery()->getArrayResult() : $orders->getQuery()->getResult();

        if ($order_parameter === 'ASC') {
            $result = array_reverse($result);
        }

        return $result;
    }

    /**
     * Dohvata konfiguracije za trenutnog korisnika
     *
     * @return array
     */
    public static function getConfigurationsForCurrentUser(): array {
        $user_id = UserService::getCurrentUserId();
        return self::getConfigurationsByUserId($user_id);
    }

    /**
     * Dohvata konfiguracije po id-u korisnika
     *
     * @param integer|null $user_id     Id korisnika
     * @return array
     */
    public static function getConfigurationsByUserId(?int $user_id = null): array {
        if ($user_id === null) {
            $user_id === UserService::getCurrentUserId();
        }

        if ($user_id === false) {
            throw new PermissionException('Korisnik nije prijavljen', 19020);
        }

        $qb = self::$entity_manager->createQueryBuilder();
        $configuration_objects = $qb
            ->select('cm')
            ->from('App\Models\Configuration\ConfigurationMain', 'cm')
            ->where('cm.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult()
        ;

        return $configuration_objects;
    }

    /**
     * Dohvata konfiguracije po user_id-u
     *
     * @param integer $user_id          id konfiguracije
     * @return array
     */
    public static function getConfigurationByUserIdConfigurationId(?int $user_id = null, ?int $configuration_id = null): array {
        if (empty($user_id)) {
            $user_id = UserService::getCurrentUserId();
        }

        $configuration_objects = [];

        if ($user_id !== false && $configuration_id !== null) {
            if (PermissionService::checkPermission('order_read') === false
                && UserService::getCurrentUserId() !== $user_id
            ) {
                throw new PermissionException('Nemate dozvolu za dohvatanje konfiguracije korisnika', 19001);
            }
            $qb = self::$entity_manager->createQueryBuilder();
            $configuration_objects = $qb
                ->select('cp')
                ->from('App\Models\Configuration\ConfigurationProduct', 'cp')
                ->where('cp.configuration_id = :configuration_id')
                ->setParameter('configuration_id', $configuration_id)
                ->getQuery()
                ->getResult()
            ;
        }

        if ($user_id === false || empty($configuration_objects)) {
            $configuration_products = self::getSessionKeySubKeyValue('configuration_products');

            if (!empty($configuration_products)) {
                foreach ($configuration_products as $key => $item) {
                    $configuration_object = new ConfigurationProduct();
                    $product = ProductService::getProductById($item['product_id']);

                    $configuration_object->id            = $key;
                    $configuration_object->product       = $product;
                    $configuration_object->product_id    = $product->id;
                    $configuration_object->quantity      = $item['quantity'];
                    $configuration_object->user_id       = null;

                    $configuration_objects[] = $configuration_object;
                }
            }
        }


        return $configuration_objects;
    }

    /**
     * Dohvata sve proizvode za određenu konfiguraciju
     *
     * @param   int         $configuration_id       Id konfiguracije
     * @return  array       Vraća niz objekata
     */
    public static function getConfigurationProductsByConfigurationId(int $configuration_id): array {
        $configuration = self::getConfigurationById($configuration_id);

        if (UserService::getCurrentUserId() !== $configuration->user_id
            && PermissionService::checkPermission('order_read') === false
            && $configuration->visibility === self::$visibility['private']
        ) {
            throw new PermissionException('Nemate dozvolu za dohvatanje konfiguracije', 19019);
        }

        return $configuration->products->getValues();
    }

    /**
     * Dohvata sve proizvode za određenu konfiguraciju
     *
     * @param   int                     $product_id             Id proizvoda
     * @param   int                     $configuration_id       Id konfiguracije
     * @return  ?ConfigurationProduct                           Vraća niz objekata
     */
    private static function getConfigurationProductByProductIdConfigurationId(
        int $product_id,
        ?int $configuration_id = null
    ): ?ConfigurationProduct {
        $user_id = UserService::getCurrentUserId();

        $qb = self::$entity_manager->createQueryBuilder();
        if ($user_id !== false && $configuration_id !== null) {
            $configuration_object = $qb
                ->select('cp')
                ->from('App\Models\Configuration\ConfigurationProduct', 'cp')
                ->where('cp.configuration_id = ?1')
                ->setParameter(1, $configuration_id)
                ->andWhere('cp.product_id = ?2')
                ->setParameter(2, $product_id)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        } else {
            $configuration = self::getSessionKeySubKeyValue('configuration_products');
            $configuration_object = null;
            if (!empty($configuration)) {
                foreach ($configuration as $key => $item) {
                    if ($item['product_id'] === $product_id) {
                        $product = ProductService::getProductById($item['product_id']);
                        $configuration_object = new ConfigurationProduct();

                        $configuration_object->id            = $key;
                        $configuration_object->product       = $product;
                        $configuration_object->product_id    = $product->id;
                        $configuration_object->quantity      = $item['quantity'];
                        $configuration_object->user_id       = null;
                    }
                }
            }
        }

        return $configuration_object;
    }

    /**
     * Dohvata stavku iz konfiguracije proizvoda
     *
     * @param   int                     $configuration_product_id       Id ConfigurationProduct-a
     * @return  ConfigurationProduct    $configuration_product          Vraća objekat
     */
    public static function getConfigurationProductById(int $configuration_product_id): ConfigurationProduct {
        $configuration_product = self::$entity_manager
            ->find('App\Models\Configuration\ConfigurationProduct', $configuration_product_id)
        ;

        if (empty($configuration_product)) {
            throw new ValidationException('Stavka iz konfiguracije pod tim id-om nije pronađena', 19041);
        }
        $permission = PermissionService::checkPermission('order_read');

        if ($permission !== true &&
            UserService::getCurrentUserId() !== $configuration_product->configuration->user_id
        ) {
            throw new PermissionException('Nemate dozvolu za čitanje konfiguracije', 19021);
        }

        return $configuration_product;
    }

    /**
     * Dohvata proizvode koje je korisnik vec selektovao za konfigurator
     *
     * @param array $user_configuration
     * @return array
     */
    public static function getUsersSelectedProducts(array $user_configuration): array {
        $selected_products = [];
        $total_price = 0;
        if ($user_configuration['products'] !== null) {
            foreach ($user_configuration['products'] as $product) {
                $product_data = ProductService::getProductById($product['product_id']);
                $total_price += $product_data->price_discount * $product['quantity'];

                $selected_products[$product_data->category->name_import][]  = [
                    'product'  => $product_data,
                    'quantity' => $product['quantity'],
                ];
            }
        }
        $selected_products['total_price'] = number_format($total_price, 2, ',', '.');
        return $selected_products;
    }

    /**
     * Provera dali postoji konfiguracija sa tim imenom
     *
     * @param   string      $name       Ime kategorije
     * @param   int         $id         Id kategorije
     *
     * @return  boolean     Vraća true ako postoji ili false ako ne postoji
     */
    public static function isConfigurationNameTaken(string $name, ?int $id = null): bool {
        SessionService::deleteSession('configuration_name_taken', self::$service_name);
        $query = self::$entity_manager->createQueryBuilder()
            ->select('cm')
            ->from('App\Models\Configuration\ConfigurationMain', 'cm')
            ->where('cm.name = :name')
            ->setParameter('name', $name)
            ->andWhere('cm.user_id = :user_id')
            ->setParameter('user_id', UserService::getCurrentUserId())
        ;

        if ($id !== null) {
            $query->andWhere('cm.id != :id')
                ->setParameter('id', $id)
            ;
        }

        $result = !empty($query->setMaxResults(1)->getQuery()->getOneOrNullResult()) || $name === self::$reserved_name;
        if ($result === true) {
            $message = 'Naziv konfiguracije je zauzet!';
            SessionService::setSessionForService('configuration_name_taken', $message, false, self::$service_name);
        }

        return $result;
    }

    /**
     * Izmena konfiguracije
     *
     * @param   int         $configuration_id           Id konfiguracije
     * @param   array       $updates                    Niz sa izmenama
     *  param   string      $updates['name']            Naziv
     *  param   string      $updates['date_updated']    Datum izmene
     *
     * @return  void
     */
    public static function updateConfiguration(int $configuration_id, array $updates): void {
        $configuration = self::getConfigurationById($configuration_id);

        $permission = PermissionService::checkPermission('order_update');

        if ($permission !== true &&
            UserService::getCurrentUserId() !== $configuration->user_id
        ) {
            throw new PermissionException('Nemate dozvolu za izmenu konfiguracije', 19021);
        }
        if (array_key_exists('name', $updates)) {
            $name = ValidationService::validateString($updates['name'], 255);
            if ($name === false) {
                throw new ValidationException('Naziv konfiguratora nije u dobrom formatu', 19003);
            } elseif (is_string($name)) {
                if ($name === self::$reserved_name || self::isConfigurationNameTaken($name, $configuration_id)) {
                    throw new ValidationException('Naziv konfiguratora je zauzet', 19003);
                }
            }
            $configuration->name = $name;
        }
        if (!empty($updates)) {
            $configuration->date_updated = new \DateTime();
            self::$entity_manager->persist($configuration);
            self::$entity_manager->flush();
        }
    }

    /**
     * Vrsi izmenu naziva konfiguracije unutar sesije
     *
     * @param string $name              Naziv konfiguracije
     * @param int    $id                Id konfiguracije
     * @param string $component_name    Naziv komponente koja zahteva setovanje sesije
     * @return void
     */
    public static function updateConfigurationNameSession(string $name, int $id, string $component_name): void {
        $is_array = false;
        $session_key = 'configuration_name_' . $id;
        SessionService::setSessionForService($session_key, $name, $is_array, $component_name);
    }

    /**
     * Menja proizvod ili dodaje u konfiguraciju
     *
     * @param   int     $configuration_id   Id konfiguracije
     * @param   int     $product_id         Id proizvoda
     * @param   int     $quantity           Količina
     * @return  void
     */
    public static function changeConfigurationProduct(
        int $product_id,
        ?int $configuration_id = null,
        int $quantity = 1
    ): void {
        $user_id = UserService::getCurrentUserId();
        // Da ne bi mogli negativan broj prozvoda da posalju
        $quantity = abs($quantity);
        if (ValidationService::validateInteger(
            $quantity,
            ValidationService::$RANGE_INTEGER_UNSIGNED['min'],
            ValidationService::$RANGE_INTEGER_UNSIGNED['max']
        ) === false || $quantity === 0) {
            throw new ValidationException('Količina nije odgovataućeg formata', 19042);
        }

        if ($user_id !== false && $configuration_id !== null) {
            $configuration = self::getConfigurationById($configuration_id);
            if ($user_id !== $configuration->user_id
                && PermissionService::checkPermission('order_update') === false) {
                throw new PermissionException('Nemate dozvolu za izmenu konfiguracije', 19030);
            }

            $qb = self::$entity_manager->createQueryBuilder();

            $configuration_product = $qb
                ->select('cp')
                ->from('App\Models\Configuration\ConfigurationProduct', 'cp')
                ->where('cp.configuration_id = :configuration_id')
                ->setParameter('configuration_id', $configuration_id)
                ->andWhere('cp.product_id = :product_id')
                ->setParameter('product_id', $product_id)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;

            empty($configuration_product)
                ? self::addProductToConfiguration($configuration_id, $product_id)
                : self::updateConfigurationProduct($configuration_product->configuration->id, $product_id, $quantity)
            ;

        } else {
            $product = ProductService::getProductById($product_id);
            $in_stock = $product->in_stock;
            if ($in_stock === false) {
                $configuration_key = $configuration_id === null ? 0 : $configuration_id;
                $session_key = 'configuration_error_' . $configuration_key;
                $is_array = true;
                $error = [
                    'product_id' => $product_id,
                    'message'    => 'Nije raspoloživo!',
                ];
                SessionService::setSessionForService($session_key, $error, $is_array, 'ConfigurationService');
            }
            $product_configuration = [
                'product_id'    =>  $product_id,
                'quantity'      =>  $quantity,
            ];

            $configuration = self::getSessionKeySubKeyValue('configuration_products');

            if (empty($configuration)) {
                self::setSession('configuration_products', $product_configuration, true);
            } else {
                $product_array_key = array_search($product_id, array_column($configuration, 'product_id'));

                if ($product_array_key !== false) {
                    $product_configuration['quantity'] = $configuration[$product_array_key]['quantity'] + 1;
                    self::updateValueOfSubkeyOfSubkey('configuration_products', $product_array_key, $product_configuration);
                } else {
                    self::setSession('configuration_products', $product_configuration, true);
                }
            }
        }
    }

    /**
     * Izmena stavke u konfiguraciji
     *
     * @param   int     $configuration_id           Id konfiguracije
     * @param   int     $product_id                 Id proizvoda
     * @param   int     $quantity                   Kvantitet
     * @return  void
     */
    private static function updateConfigurationProduct(
        int $configuration_id,
        int $product_id,
        int $quantity
    ): void {
        $configuration_product = self::getConfigurationProductByProductIdConfigurationId($product_id, $configuration_id);

        $configuration_product->product_id = $product_id;
        $configuration_product->quantity += $quantity;

        self::$entity_manager->persist($configuration_product);
        self::$entity_manager->flush();
    }

    /**
     * Briše konfiguraciju
     *
     * @param   int         $configuration_id       Id konfiguracije
     * @return  void
     */
    public static function deleteConfiguration(?int $configuration_id = null): void {
        if (UserService::getCurrentUserId() !== false
            && $configuration_id !== null
        ) {
            $configuration = self::getConfigurationById($configuration_id);
            if (PermissionService::checkPermission('order_delete') === false
                && UserService::getCurrentUserId() !== $configuration->user_id
            ) {
                throw new PermissionException('Nemate dozvolu za brisanje konfiguratora', 19034);
            }

            self::$entity_manager->remove($configuration);
            self::$entity_manager->flush();
        } else {
            self::deleteSessionSubkey('configuration_products');
            $configuration_key = $configuration_id === null ? 0 : $configuration_id;
            $session_key = 'configuration_error_' . $configuration_key;
            self::deleteSessionSubkey($session_key);
        }
    }

    /**
     * Briše stavku iz konfiguracije
     *
     * @param   int     $product_id         Id proizvoda
     * @param   int     $configuration_id   Id konfiguracije
     * @return  void
     */
    public static function deleteConfigurationProduct(int $product_id, ?int $configuration_id = null): void {
        $configuration_product = self::getConfigurationProductByProductIdConfigurationId(
            $product_id,
            $configuration_id
        );
        if (empty($configuration_product)) {
            throw new ValidationException('Element u listi nije pronađen', 1);
        }

        $permission = PermissionService::checkPermission('order_delete');

        if ((UserService::getCurrentUserId() !== false || $permission)
            && $configuration_id !== null
        ) {
            if ($configuration_product->quantity > 1) {
                $configuration_product->quantity -= 1;
                self::$entity_manager->persist($configuration_product);
            } else {
                self::$entity_manager->remove($configuration_product);
            }
            self::$entity_manager->flush();
        } else {
            if ($configuration_product->quantity > 1) {
                $product_configuration = [
                    'product_id' => $configuration_product->product_id,
                    'quantity'   => $configuration_product->quantity - 1,
                ];
                self::updateValueOfSubkeyOfSubkey('configuration_products', $configuration_product->id, $product_configuration);
            } else {
                self::deleteSessionSubkeyOfSubkey('configuration_products', $configuration_product->id);
            }
        }
        $configuration_key = $configuration_id === null ? 0 : $configuration_id;
        $session_key = 'configuration_error_' . $configuration_key;
        SessionService::deleteSession($session_key, 'ConfigurationService');
    }
}
