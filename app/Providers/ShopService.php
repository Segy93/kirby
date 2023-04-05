<?php

namespace App\Providers;

use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;
use App\Models\Cart;
use App\Models\CartOrder;
use App\Models\City;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderUpdate;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Providers\AddressService;
use App\Providers\AdminService;
use App\Providers\EmailService;
use App\Providers\PermissionService;
use App\Providers\ProductService;
use App\Providers\SessionService;
use App\Providers\ShippingService;
use App\Providers\UserService;
use App\Providers\ValidationService;
use Doctrine\ORM\Events;
use Doctrine\ORM\Query\ResultSetMapping;

class ShopService extends BaseService {

    protected static $service = 'ShopService';

    /**
     *
     * CREATE
     *
     */

    /**
     * Kreira porudžbenicu
     * @param   int         $user_id                Id korisnika
     * @param   int         $payment_method_id      Id načina plaćanja
     * @param   int         $delivery_shop_id       Id dostave u radnji(opcinalno)
     * @param   int         $delivery_address_id    Id dostave adrese(opcionalno)
     * @param   int         $billing_shop_id        Id računa ako plaća u radnji(opcionalno)
     * @param   int         $billing_address_id     Id računa ako plaća prilikom dostave(opcionalno)
     * @param   bool        $billing_online         Ako plaća online(opcionalno)
     * @param   DateTime    $date_delivery          Datum dostave(opcionalno)
     * @param   string      $note                   Napomena(opcionalno)
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprotnom vraća error_code
     */
    private static function createOrder(
        $user_id,
        $payment_method_id,
        $delivery_address_id,
        $billing_address_id = null,
        $online_token = null,
        $date_delivery = null,
        $note = null
    ) {
        $order = new Order();

        $used = 1;

        $user = UserService::getUserById($user_id);
        if (empty($user_id)) {
            throw new ValidationException('Korisnik sa tim id-om nije pronađen', 19002);
        }
        $order->user = $user;

        $payment_method = self::getPaymentMethodById($payment_method_id);
        if (empty($payment_method)) {
            throw new ValidationException('Način plaćanja nije pronađen', 19003);
        }
        $order->payment_method = $payment_method;

        $delivery_address = AddressService::getAddressById($delivery_address_id);
        $delivery_address->status = $used;
        if (empty($delivery_address)) {
            throw new ValidationException('Adresa dostave sa tim id-om nije pronađena', 19004);
        }
        $order->delivery_address = $delivery_address;

        if (!empty($billing_address_id)) {
            $billing_address =  AddressService::getAddressById($billing_address_id);
            $billing_address->status = $used;
            if (empty($billing_address)) {
                throw new ValidationException('Adresa plaćanja sa tim id-om nije pronađena', 19005);
            }
            $order->billing_address = $billing_address;
        }

        if (!empty($online_token)) {
            if (ValidationService::validateString($online_token, 255) === false) {
                throw new ValidationException('Token nije odgovarajućeg formata', 19006);
            }
            $order->online_token = $online_token;
        }

        if (!empty($date_delivery)) {
            $date_delivery = ValidationService::validateDate($date_delivery);
            if ($date_delivery === false) {
                throw new ValidationException('Datum dostave nije odgovarajućeg formata', 19007);
            }
            $order->date_delivery = $date_delivery;
        }

        if (!empty($note)) {
            $note = ValidationService::validateString($note);
            if ($note === false) {
                throw new ValidationException('Napomena nije odgovarajućeg formata', 19008);
            }
            $order->note = $note;
        }
        self::$entity_manager->persist($order);
        self::$entity_manager->persist($delivery_address);

        if (!empty($billing_address_id)) {
            self::$entity_manager->persist($billing_address);
        }

        self::$entity_manager->flush();

        return $order;
    }

    /**
     * Kreira statusnu promenu narudžbenice
     * @param   int         $order_id           Id porudžbenice
     * @param   int         $admin_id           Id administratora(opcionalno)
     * @param   string      $status             Status(opcionalno)
     * @param   string      $status_comment     Komentar status(opcinalno)
     * @return  void
     */
    public static function createOrderUpdate(
        $order_id,
        $admin_id = null,
        $status = null,
        $comment_admin = null,
        $comment_user = null,
        $user_notified = false
    ) {
        $order = self::getOrderById($order_id);

        if (PermissionService::checkPermission('order_update') === false
            && $order->user->id !== UserService::getCurrentUserId()
        ) {
            throw new PermissionException('Nemate dozvolu da kreirate promenu statusa', 19035);
        }

        $order_update = new OrderUpdate();
        $order_update->order = $order;

        if (!empty($admin_id)) {
            $admin = AdminService::getAdminById($admin_id);
            if (empty($admin)) {
                throw new ValidationException('Admin nije pronađen', 19036);
            }

            $order_update->admin = $admin;
        }

        if (!empty($status)) {
            if (ValidationService::validateString($status) === false) {
                throw new ValidationException('Status nije odgovarajućeg formata', 19037);
            }

            $order_update->status = $status;
        }

        if (!empty($comment_admin)) {
            if (ValidationService::validateString($comment_admin) === false) {
                throw new ValidationException('Komentar interni nije odgovarajućeg formata', 19038);
            }

            $order_update->comment_admin = $comment_admin;
        }

        if (!empty($comment_user)) {
            if (ValidationService::validateString($comment_user) === false) {
                throw new ValidationException('Komentar korisniku nije odgovarajućeg formata', 19039);
            }

            $order_update->comment_user = $comment_user;
        }

        $user_notified = ValidationService::validateBoolean($user_notified);
        if (ValidationService::validateBoolean($user_notified) !== false) {
            $order_update->user_notified = $user_notified;
            EmailService::sendNotificationOrderUpdated([
                'order_update' => $order_update
            ], $order->user->email);
        }



        if ($status === 'potvrđeno') {
            $params = [
                "link"          => "//" . $_SERVER['HTTP_HOST']  . "/admin/narudzbina/" . $order->id,
                "order"         => $order,
                'order_update'  => $order_update,
                "user_email"    => $order->user->email
            ];
            EmailService::sendNotificationOrderConfirmed($params);
            self::updateOrderProductPrices($order->order_products);
            self::changeTotalPrice($order->id);
        }

        $public_key = config(php_uname('n') . '.PUSH_PUBLIC');
        $link       = "//" . $_SERVER['HTTP_HOST'] . '/narudzbine' . '/' . $order->id;
        $notification_data ['action_url'] = $link;
        NotificationService::sendOrderStatusUpdateNotification($public_key, $order->user->id, $notification_data);
        self::$entity_manager->persist($order_update);
        self::$entity_manager->flush();

        if ($order_update->status === 'potvrđeno') {
            EmailService::sendOrderCreated(
                [
                    'order'         => $order,
                    'cart'          => $order->order_products,
                    'order_update'  => $order_update
                ],
                $order->user->email
            );
        }
        return $order_update;
    }

    /**
     * Dodaje proizvode u porudžbenicu
     * @param   int     $order_id       Id porudžbenice
     * @param   array   $product_data   Niz sa id-om proizvoda i količinom
     * @return  void
     */
    private static function addProductsToOrder($order_id, $product_data) {
        $order = self::getOrderById($order_id);
        $total_price = 0;

        foreach ($product_data as $data) {
            $order_product = new OrderProduct();

            $product = ProductService::getProductById($data['product_id']);

            $order_product->order    =   $order;
            $order_product->product  =   $product;
            $order_product->quantity =   $data['quantity'];

            if ($order->payment_method->method === 'Keš'
            || $order->payment_method->method === 'Virmanski'
            ) {
                $price = $order_product->product->price_discount;
            } else {
                $price = $order_product->product->price_retail;
            }
            $order_product->price    =   $price;

            self::$entity_manager->persist($order_product);

            if ($order->payment_method->method === 'Keš'
            || $order->payment_method->method === 'Virmanski'
            ) {
                $total_price += $product->price_discount * $data['quantity'];
            } else {
                $total_price += $product->price_retail * $data['quantity'];
            }
        }
        $order->total_price = $total_price;
        self::$entity_manager->persist($order);
        self::$entity_manager->flush();
    }

    /**
     * Kreira porudžbenicu i ubaciju proizvode iz korpe u p
     * @param   int         $user_id                Id korisnika
     * @param   int         $payment_method_id      Id načina plaćanja
     * @param   int         $delivery_shop_id       Id dostave u radnji(opcinalno)
     * @param   int         $delivery_address_id    Id dostave adrese(opcionalno)
     * @param   int         $billing_shop_id        Id računa ako plaća u radnji(opcionalno)
     * @param   int         $billing_address_id     Id računa ako plaća prilikom dostave(opcionalno)
     * @param   bool        $billing_online         Ako plaća online(opcionalno)
     * @param   DateTime    $date_delivery          Datum dostave(opcionalno)
     * @param   string      $note                   Napomena(opcionalno)
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprotnom vraća error_code
     */
    public static function placeOrder(
        $user_id,
        $payment_method_id,
        $delivery_address_id,
        $billing_address_id = null,
        $online_token = null,
        $date_delivery = null,
        $note = null,
        $type = 'cart',
        $configuration_id = null
    ) {
        $user_cart = self::getUserCartByUserId($user_id);
        $configuration_products = ConfigurationService::getConfigurationArrayById($configuration_id);
        $product_data = [];
        if ($type === 'cart' && empty($user_cart)) {
            throw new ValidationException('Korpa je prazna', 19009);
        } else if ($type === 'configuration' && empty($configuration_products['products'])) {
            throw new ValidationException('Konfigurator je prazan', 19009);
        } else if ($type === 'cart') {
            //Dohvata id prizvoda koji treba da se unesu
            foreach ($user_cart as $cart) {
                $product_data[] = [
                    'product_id'    =>  $cart->product_id,
                    'quantity'      =>  $cart->quantity,
                ];
            }
        } else {
            //Dohvata id prizvoda koji treba da se unesu
            foreach ($configuration_products['products'] as $configuration_product) {
                $product_data[] = [
                    'product_id'    =>  $configuration_product['product_id'],
                    'quantity'      =>  $configuration_product['quantity'],
                ];
            }
        }

        // Kreira porudžbenicu
        $order = self::createOrder(
            $user_id,
            $payment_method_id,
            $delivery_address_id,
            $billing_address_id,
            $online_token,
            $date_delivery,
            $note
        );

        //Kreira promenu statusa
        $order_update = self::createOrderUpdate($order->id);

        //Unosi proizvode
        self::addProductsToOrder($order->id, $product_data);

        $delivery_address = AddressService::getAddressById($delivery_address_id);
        //Radi kalkulaciju poštarine
        $silent = true;
        if ($delivery_address->address_type !== 'shop') {
            self::calculateOrderShippingFee($order->id, $user_id, $silent);
        }

        //Briše sve iz korpe
        self::deleteCartByUserId($user_id);

        return $order;
       // }
    }

    /**
     * Dodaje u korpu proizvod
     * @param   int         $user_id        Id korisnika
     * @param   int         $product_id     Id proizvoda
     * @param   int         $quantity       Količina
     * @return  bool/int    True ako je sve prošlo uredu u suprotnom vraća neki error_code
     */
    private static function addToCart($user_id, $product_id, $quantity) {
        $user = UserService::getUserById($user_id);
        if (empty($user)) {
            throw new ValidationException('Korisnik sa tim id-om nije pronađen', 19010);
        }

        $product = ProductService::getProductById($product_id);
        if (empty($product)) {
            throw new ValidationException('Proizvod pod tim id-om nije pronađen', 19011);
        }

        $quantity = ValidationService::validateInteger(
            $quantity,
            ValidationService::$RANGE_INTEGER_UNSIGNED['min'],
            ValidationService::$RANGE_INTEGER_UNSIGNED['max']
        );

        if ($quantity === false) {
            throw new ValidationException('Količina nije odgovarajućeg formata', 19012);
        }

        $in_stock = $product->in_stock;
        if ($in_stock === false) {
            throw new ValidationException('Proizvod nije na stanju');
        }

        $cart = new Cart();

        $cart->user     =   $user;
        $cart->product  =   $product;
        $cart->quantity =   $quantity;

        self::$entity_manager->persist($cart);
        self::$entity_manager->flush();
    }

    /**
     *
     * READ
     *
     */

    /**
     * Dohvata sve načine plaćanja
     * @return  array      Vraća niz sa načinima plaćanja
     */
    public static function getPaymentMethods() {
        return self::$entity_manager->getRepository('App\Models\PaymentMethod')->findAll();
    }

    /**
     * Dohvata način plaćanja po id-u
     * @param   int             $payment_method_id      Id načina plaćanja
     * @return  PaymentMethod   Vraća objekat načina plaćanja
     */
    public static function getPaymentMethodById($payment_method_id) {
        return self::$entity_manager->find('App\Models\PaymentMethod', $payment_method_id);
    }

    /**
     * Dohvata sve prodavnice
     * @return  array      Vraća niz sa prodavnicama
     */
    public static function getShops() {
        return self::$entity_manager->getRepository('App\Models\Shop')->findAll();
    }

    /**
     * Dohvata prodavnicu po id-u
     * @param   int             $shop_id        Id prodavnice
     * @return  Shop            Vraća objekat prodavnice
     */
    public static function getShopById($shop_id = null) {
        if (empty($shop_id)) {
            $shop_id = 1;
        }

        return self::$entity_manager->find('App\Models\Shop', $shop_id);
    }

    /**
     * Dohvata porudžbenicu po id-u
     * @param   int         $order_id       Id porudžbenice
     * @return  Order       Vraća objekat order
     */
    public static function getOrderById($order_id) {
        $order = self::$entity_manager->find('App\Models\Order', $order_id);
        if (empty($order)) {
            throw new ValidationException('Porudžbenica sa tim id-om nije pronađena', 19013);
        }

        if (UserService::getCurrentUserId() !== $order->user->id
            && PermissionService::checkPermission('order_read') === false
        ) {
            throw new PermissionException('Nemate dozvolu za pretragu narudžbina po id-u', 19014);
        }

        return $order;
    }

    /**
     * Dohvata korpu odnosno jedan element iz korpe
     * @param   int         $cart_id        Id korpe(elementa)
     * @return  Cart        Vraća korpu(element) objekat
     */
    public static function getCartById($cart_id) {
        $cart = self::$entity_manager->find('App\Models\Cart', $cart_id);

        if (!empty($cart)) {
            if (PermissionService::checkPermission('order_read') === false
                && UserService::getCurrentUserId() !== false
                && UserService::getCurrentUserId() !== $cart->user->id
            ) {
                throw new PermissionException('Nemate dozvolu za dohvatanje proizvoda iz korpe po id-u', 19016);
            }
        } else {
            //throw new ValidationException('Proizvod iz korpe sa tim id-om nije pronađen', 19015);
        }


        return $cart;
    }

    public static function getCartByUserIdProductId($user_id, $product_id) {
        if (empty($user_id)) {
            $user_id = UserService::getCurrentUserId();
        }

        $cart_objects = [];

        $qb = self::$entity_manager->createQueryBuilder();
        if ($user_id !== false) {
            $cart_objects = $qb
                ->select('c')
                ->from('App\Models\Cart', 'c')
                ->where('c.product_id = ?1')
                ->setParameter(1, $product_id)
                ->andWhere('c.user_id = ?2')
                ->setParameter(2, $user_id)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        } else {
            $cart = self::getSessionKeySubKeyValue('cart');

            if (!empty($cart)) {
                foreach ($cart as $key => $item) {
                    if ($item['product_id'] === $product_id) {
                        $product = ProductService::getProductById($item['product_id']);
                        $cart_object = new Cart();

                        $cart_object->id            = $key;
                        $cart_object->product       = $product;
                        $cart_object->product_id    = $product->id;
                        $cart_object->user_id       = null;

                        $cart_objects[] = $cart_object;
                    }
                }
            }
        }

        return $cart_objects;
    }

    /**
     * Dohvata narudžbine
     * @param   int         $order_id       Id porudžbenice
     * @param   string      $search         Dodatna pretraga(još uvek treba da se dogovori šta će pretraživati)
     * @param   boolean     $direction      Smer u kojem dohvata proizvode (manje ili više od prosleđenog id-a)
     * @param   int         $limit          Limit koliko proizvoda dohvata
     * @return  array/int   $orders         Vraća niz objekata ili neki error_code ako nešto nije prošlo kako treba
     */
    public static function getOrders(
        $order_id = null,
        $search = null,
        $filter_status = null,
        $direction = true,
        $limit = null,
        $as_array = false
    ) {
        if (PermissionService::checkPermission('order_read') === false) {
            throw new PermissionException('Nemate dozvolu za pretragu narudžbina', 19017);
        }

        $orderParameter = $direction ? 'DESC' : 'ASC';

        $qb = self::$entity_manager->createQueryBuilder();

        $orders = $qb
            ->select('o')
            ->from('App\Models\Order', 'o')
            ->orderBy('o.id', $orderParameter)
        ;

        if (!empty($order_id)) {
            $direction = $direction ? '<' : '>';

            $query = 'o.id ' . $direction . ' :order_id';

            $orders
                ->where($query)
                ->setParameter('order_id', $order_id)
            ;
        }

        if (!empty($search)) {
            $orders
                ->andWhere('o.id = :search')
                ->setParameter('search', $search)
            ;
        }

        if ($filter_status !== null) {
            $qb2 = self::$entity_manager->createQueryBuilder();
            $subquery =  $qb2
                ->select('MAX(upd.date)')
                ->from('App\Models\OrderUpdate', 'upd')
                ->where('upd.order_id = o.id')
            ;
            $orders
                ->join('o.updates', 'up', 'WITH', $orders->expr()->eq('up.date', '(' . $subquery->getDQL() . ')'))
                ->andWhere('up.status_code = :status')
                ->setParameter('status', $filter_status)
            ;
        }

        if (!empty($limit)) {
            $orders
                ->setMaxResults($limit)
            ;
        }

        $result = $as_array ? $orders->getQuery()->getArrayResult() : $orders->getQuery()->getResult();

        if ($orderParameter === 'ASC') {
            $result = array_reverse($result);
        }

        return $result;
    }

    public static function getOrdersByUserId($user_id) {
        if (PermissionService::checkPermission('order_read') === false
            && UserService::getCurrentUserId() !== $user_id
        ) {
            throw new PermissionException('Nemate dozvolu za pretragu narudžbina po id-u korisnika', 19018);
        }

        $qb = self::$entity_manager->createQueryBuilder();

        $orders = $qb
            ->select('o')
            ->from('App\Models\Order', 'o')
            ->where('o.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult()
        ;

        return $orders;
    }


    /**
     * Dohvata nepotvrđenu porudžbenicu po id-u korisnika
     * @param   int         $user_id        Id korisinka
     * @param   int         $status         Status porudžbenice
     * @return  Order       $order          Vraća objekat porudžbenice
     */
    public static function getOrderByUserIdStatus($user_id) {
        $unconfirmed_order = null;
        if (UserService::getCurrentUserId() !== $user_id
            && PermissionService::checkPermission('order_read') === false
        ) {
            throw new PermissionException('Nemate dozvolu za pretragu narudžbina po id-u korisnika', 19018);
        }

        $user = UserService::getUserById($user_id);
        $last_order = $user->orders->last();

        if ($last_order->last_update->status_code === 0) {
            $unconfirmed_order = $last_order;
        } else {
            foreach ($user->orders as $order) {
                if ($order->last_update->status_code === 0) {
                    $unconfirmed_order = $order;
                }
            }
        }

        return $unconfirmed_order;
    }

    /**
     * Proverava da li korisnik ima nepotvrdjenih porudzbina
     *
     * @param   integer     $user_id        Korisnik koga proveravamo
     *                                      (default je trenutni korisnik)
     * @return  boolean
     */
    public static function hasUnconfirmedOrders($user_id = null) {
        $has_orders = false;
        $current_user_id = UserService::getCurrentUserId();

        if ($user_id === null) {
            $user_id = $current_user_id;
        }

        if ($user_id !== $current_user_id && PermissionService::checkPermission('user_read') === false) {
            $user_id = null;
        }

        if ($user_id !== null) {
            $user = UserService::getUserById($user_id);
            foreach ($user->orders as $order) {
                if ($order->last_update) {
                    if ($order->last_update->status_code === 0) {
                        $has_orders = true;
                    }
                }
            }
            //$last_uncofirmed_order = self::getOrderByUserIdStatus($user_id);
            //$has_orders = $last_uncofirmed_order !== null;
        }

        return $has_orders;
    }

    public static function getUserCartCurrent() {
        $user_id = UserService::getCurrentUserId();
        return self::getUserCartByUserId($user_id);
    }

    /**
     * Dohvata korpu korisnika
     * @param   int      $user_id        Id korisnika
     * @return  array    Vraća niz elemenata iz korpe
     */
    public static function getUserCartByUserId($user_id = null) {
        Product::enableImageFormatThumbnail();
        if (empty($user_id)) {
            $user_id = UserService::getCurrentUserId();
        }

        $cart_objects = [];

        if ($user_id !== false) {
            if (PermissionService::checkPermission('order_read') === false
                && UserService::getCurrentUserId() !== $user_id
            ) {
                throw new PermissionException('Nemate dozvolu za dohvatanje korpe korisnika', 19001);
            }

            $qb = self::$entity_manager->createQueryBuilder();

            $cart_objects = $qb
                ->select('c')
                ->from('App\Models\Cart', 'c')
                ->where('c.user_id = :user_id')
                ->setParameter('user_id', $user_id)
                ->getQuery()
                ->getResult()
            ;
        }

        if ($user_id === false || empty($cart_objects)) {
            $cart = self::getSessionKeySubKeyValue('cart');

            if (!empty($cart)) {
                //var_dump($_SESSION);die;
                foreach ($cart as $key => $item) {
                    $cart_object = new Cart();
                    //$product_id = array_key_exists('product_id', $item) ? $item['product_id'] : $item;
                    $product = ProductService::getProductById($item['product_id']);

                    $cart_object->id            = $key;
                    $cart_object->product       = $product;
                    $cart_object->product_id    = $product->id;
                    $cart_object->quantity      = $item['quantity'];
                    $cart_object->user_id       = null;

                    $cart_objects[] = $cart_object;
                }
            }
        }

        // print_r($cart_objects[0]->id);
        // die();
        return $cart_objects;
    }

    /**
     * Dohvata sve proizvode za određenu porudžbenicu
     * @param   int         $order_id       Id porudžbenice
     * @return  array       Vraća niz objekata
     */
    public static function getOrderProductsByOrderId($order_id) {
        $order = self::getOrderById($order_id);

        if (UserService::getCurrentUserId() !== $order->user->id
            && PermissionService::checkPermission('order_read') === false
        ) {
            throw new PermissionException('Nemate dozvolu za dohvatanje korpe', 19019);
        }

        return $order->order_products->getValues();
    }

    /**
     * Dohvata ukupnu cenu korpe. Priliko dohvatanja sračuna ukupnu cenu.
     * @param   int             $user_id        Id korisnika
     * @return  string/int      Ukupna cena korpe
     */
    public static function getCartTotalPrice($user_id) {
        if (UserService::getCurrentUserId() !== $user_id
            && PermissionService::checkPermission('order_read') === false
        ) {
            throw new PermissionException('Nemate dozvolu za dohvatanje ukupne cene korpe', 19020);
        }

        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('SUM(c.quantity * p.price_retail)')
            ->from('App\Models\Cart', 'c')
            ->join('c.product', 'p')
            ->where('c.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Lista statusa
     * @return      array       Vraća listu mogućih statusa za porudžbenicu
     */
    public static function getAllOrderStatuses() {
        return [
            0   =>  'nepotvrđeno',
            1   =>  'potvrđeno',
            2   =>  'poslata profaktura',
            3   =>  'obrađena uplata',
            4   =>  'spremno za slanje',
            5   =>  'poslato',
            6   =>  'stiglo',
            7   =>  'otkazano',
            8   =>  'u obradi',
            9   =>  'spremno za preuzimanje',
            10  =>  'preuzeto',
            11  =>  'stornirano',
        ];
    }

    /**
     * Dohvata sve statuse porudžbenice
     * @param   int         $order_id       Id order-a
     * @return  array       Vraća niz objekata statusa
     */
    public static function getOrderUpdates($order_id) {
        $order = self::getOrderById($order_id);

        if (UserService::getCurrentUserId() !== $order->user_id
            && PermissionService::checkPermission('order_read') === false
        ) {
            throw new PermissionException('Nemate dozvolu da vidite statuse porudžbenice', 19044);
        }

        $qb = self::$entity_manager->createQueryBuilder();

        return $order->updates->getValues();
    }

    /**
     * Radi kalkulaciju poštarine i odmah radi izmene
     * @param   int     $order_id       Id porudžbenice
     * @param   int     $total_price    Ukupna cena porudžbenice
     * @return  void    Ne vraća ništa
     */
    public static function calculateShippingFee($order_id, $user_id) {
        if (self::getCartTotalPrice($user_id) < config(php_uname('n') . '.FREE_SHIPPING_FEE_OVER')) {
            self::updateOrder($order_id, [
                'shipping_fee' => config(php_uname('n') . '.SHIPPING_FEE')
            ]);
        } else {
            self::updateOrder($order_id, [
                'shipping_fee' => 0,
            ]);
        }
    }

    /**
     * Računa totalnu težinu paketa
     * @param   int     $order_id       Id ordera za koju računa totalnu težinu
     * @return  int     Težina paketa u gramima
     */
    public static function calculateShippingWeight($order_id) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('total_weight', 'total_weight');

        $query = self::$entity_manager->createNativeQuery("
            SELECT (ROUND(SUM(t1.value), 2) * 1000) AS 'total_weight' FROM OrderProducts op
            INNER JOIN Products p ON op.product_id = p.id
            INNER JOIN Attributes a ON p.category_id = a.category_id
            LEFT JOIN (
                SELECT av.attribute_id as t1_attribute_id, pa.product_id as t1_product_id, av.value as value
                FROM AttributeValues av
                INNER JOIN ProductAttributes pa ON av.id = pa.attribute_value_id
            ) as t1 ON t1.t1_attribute_id = a.id AND t1.t1_product_id = p.id
            WHERE op.order_id = :order_id
            AND a.machine_name = 'weight_kg';
            ", $rsm);

        $query->setParameter('order_id', $order_id);

        return intval($query->getSingleScalarResult());
    }

    public static function calculateOrderShippingFee($order_id, $total_price, $silent = false) {
        if ($total_price < config(php_uname('n') . '.FREE_SHIPPING_FEE_OVER')) {
            self::updateOrder($order_id, [
                'shipping_fee' => config(php_uname('n') . '.SHIPPING_FEE')
            ], $silent);
        } else {
            self::updateOrder($order_id, [
                'shipping_fee' => 0,
            ], $silent);
        }
    }

    public static function isShippingFree($price) {
        return $price > config(php_uname('n') . '.FREE_SHIPPING_FEE_OVER');
    }

    public static function getShippingFee($price) {
        $shipping_fee = 0;
        if ($price < config(php_uname('n') . '.FREE_SHIPPING_FEE_OVER')) {
            $shipping_fee = config(php_uname('n') . '.SHIPPING_FEE');
        }

        return $shipping_fee;
    }

    public static function changeTotalPrice($order_id, $silent = false) {
        $order = self::getOrderById($order_id);
        $total_price = 0;
        foreach ($order->order_products as $order_product) {
            if ($order->payment_method->method === 'Keš'
            || $order->payment_method->method === 'Virmanski'
            ) {
                $total_price += ($order_product->product->price_discount) * $order_product->quantity;
            } else {
                $total_price += ($order_product->product->price_retail) * $order_product->quantity;
            }
        }

        if ($order->delivery_address->address_type !== 'shop') {
            self::calculateOrderShippingFee($order_id, $total_price, $silent);
        }

        $order->total_price = $total_price;
        self::$entity_manager->persist($order);
        self::$entity_manager->flush();

        return $total_price;
    }

    /**
     * Dohvata stavku iz porudžbenice
     * @param   int             $order_product_id       Id OrderProduct-a
     * @return  OrderProduct    $order_product          Vraća objekat
     */
    private static function getOrderProductById($order_product_id) {
        $order_product = self::$entity_manager->find('App\Models\OrderProduct', $order_product_id);

        if (empty($order_product)) {
            throw new ValidationException('Stavka iz narudžbenice pod tim id-om nije pronađena', 19041);
        }

        return $order_product;
    }

    /**
     *
     * UPDATE
     *
     */

    /**
     * Izmena narudžbine
     * @param   int         $order_id       Id porudžbenice
     * @param   array       $updates        Niz sa izmenama
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprotnom vraća neki error_code
     */
    public static function updateOrder($order_id, $updates, $silent = false) {
        $order = self::getOrderById($order_id);

        $used = 1;

        $permission = PermissionService::checkPermission('order_update');

        $email_data = [];

        if ($permission !== true &&
            UserService::getCurrentUserId() !== $order->user->id &&
            $order->last_update->status !== 'nepotvrđeno'
        ) {
            throw new PermissionException('Nemate dozvolu za izmenu narudžbine', 19021);
        }

        if (array_key_exists('payment_method_id', $updates)) {
            $payment_method = self::getPaymentMethodById($updates['payment_method_id']);
            if (empty($payment_method)) {
                throw new ValidationException('Način plaćanja pod tim id-om nije pronađen', 19022);
            }

            $email_data ['Tip plaćanja:'] = $payment_method->method;
            $order->payment_method = $payment_method;
        }

        if (array_key_exists('delivery_address_id', $updates)) {
            $delivery_address = AddressService::getAddressById($updates['delivery_address_id']);
            $delivery_address->status = $used;

            if (empty($delivery_address)) {
                throw new ValidationException('Adresa dostave sa tim id-om nije pronađena', 19023);
            }

            if ($delivery_address->address_type !== 'shop') {
                self::calculateOrderShippingFee($order->id, $order->total_price);
            } else {
                $order->shipping_fee = 0;
            }
            if ($order->delivery_address->id !== $updates['delivery_address_id']) {
                if ($delivery_address->address_type !== 'shop') {
                    $email_data ['Ime'] = $delivery_address->contact_name;
                    $email_data ['Prezime'] = $delivery_address->contact_surname;
                    $email_data ['Telefon'] = $delivery_address->phone_nr;

                    if ($delivery_address->company !== null) {
                        $email_data ['Kompanija '] = $delivery_address->company;
                    }
                } else {
                    $email_data['Email'] = $delivery_address->email;
                    $email_data['Fax'] = $delivery_address->fax;
                    $email_data['Radno vreme'] = str_replace(' \n', ', ', $delivery_address->open_hours);
                }

                $email_data ['Adresa za dostavu'] = $delivery_address->address;
                $email_data ['Grad'] = $delivery_address->city;
                $email_data ['Poštanski broj'] = $delivery_address->postal_code;
                $email_data ['Link narudžbine'] = "//"
                    . $_SERVER['HTTP_HOST']
                    . "/"
                    . "korisnik/"
                    . $order->user->username
                    . "/"
                    . "narudzbine/"
                    . $order->id
                ;
            }

            $order->delivery_address = $delivery_address;
            self::$entity_manager->persist($delivery_address);
        }

        if (array_key_exists('billing_address_id', $updates)) {
            $billing_address = AddressService::getAddressById($updates['billing_address_id']);
            $billing_address->status = $used;

            if (empty($billing_address)) {
                throw new ValidationException('Adresa naplate sa tim id-om nije pronađena', 19024);
            }

            if ($order->billing_address->id !== $updates['billing_address_id']) {
                if ($billing_address->address_type !== 'shop') {
                    $email_data ['Ime'] = $billing_address->contact_name;
                    $email_data ['Prezime'] = $billing_address->contact_surname;
                    $email_data ['Telefon'] = $billing_address->phone_nr;

                    if ($billing_address->company !== null) {
                        $email_data ['Kompanija'] = $billing_address->company;
                    }
                } else {
                    $email_data ['Email'] = $billing_address->email;
                    $email_data ['Fax'] = $billing_address->fax;
                    $email_data ['Radno vreme'] = str_replace(' \n', ', ', $billing_address->open_hours);
                }

                $email_data ['Adresa za plaćanje'] = $billing_address->address;
                $email_data ['Grad'] = $billing_address->city;
                $email_data ['Poštanski broj'] = $billing_address->postal_code;
                $email_data ['Link narudžbine'] = "//"
                    . $_SERVER['HTTP_HOST']
                    . "/"
                    . "korisnik/"
                    . $order->user->username
                    . "/"
                    . "narudzbine/"
                    . $order->id
                ;
            }

            $order->billing_address = $billing_address;
            self::$entity_manager->persist($billing_address);
        }

        if (array_key_exists('date_delivery', $updates)) {
            $updates['date_delivery'] = ValidationService::validateDate($updates['date_delivery']);
            if ($updates['date_delivery'] === false) {
                throw new ValidationException('Datum dostave nije odgovarajućeg formata', 19025);
            }

            $date = new \DateTime();
            $email_data ['Datum dostave:'] = $updates['date_delivery'];
            $order->date_delivery = $date->modify($updates['date_delivery']);
        }

        if (array_key_exists('note', $updates)) {
            $updates['note'] = ValidationService::validateString($updates['note']);
            if ($updates['note'] === false) {
                throw new ValidationException('Napomena nije odgovarajućeg formata', 19026);
            }

            $email_data ['Poruka:'] = $updates['note'];
            $order->note = $updates['note'];
        }

        if (array_key_exists('shipping_fee', $updates)) {
            $updates['shipping_fee'] = ValidationService::validateInteger(
                $updates['shipping_fee'],
                ValidationService::$RANGE_INTEGER_UNSIGNED['min'],
                ValidationService::$RANGE_INTEGER_UNSIGNED['max']
            );

            if ($updates['shipping_fee'] === false) {
                throw new ValidationException('Poštarina nije odgovarajućeg formata', 19027);
            }

            if ($order->shipping_fee !== $updates['shipping_fee']) {
                $email_data ['Cena dostave:'] = $updates['shipping_fee'];
            }

            $order->shipping_fee = $updates['shipping_fee'];
        }

        if (array_key_exists('status', $updates)) {
            if ($permission !== true &&
                $updates['status']['code'] !== 'potvrđeno'
            ) {
                throw new PermissionException('Nemate dozvolu za izmenu statusa narudžbine', 19028);
            }

            $order_id = $order->id;

            $admin_id = $updates['status']['admin_id'] !== null
                ? $updates['status']['admin_id']
                : null
            ;

            $status = $updates['status']['code'] !== null
                ? $updates['status']['code']
                : null
            ;

            $comment_admin = $updates['status']['comment_admin'] !== null
                ? $updates['status']['comment_admin']
                : null
            ;

            $comment_user = $updates['status']['comment_user'] !== null
                ? $updates['status']['comment_user']
                : null
            ;

            $user_notified = $updates['status']['user_notified'] !== null
                ? $updates['status']['user_notified']
                : null
            ;

            self::createOrderUpdate(
                $order_id,
                $admin_id,
                $status,
                $comment_admin,
                $comment_user,
                $user_notified
            );

            if ($order->last_update->status === 'spremno za slanje' &&
                $order->delivery_address->address_type !== 'shop'
            ) {
                ShippingService::generateCSV($order);
            }
        }

        if (!empty($updates)) {
            self::$entity_manager->persist($order);

            self::$entity_manager->flush();
            if (!empty($email_data) && $silent === false) {
                EmailService::orderUpdated($email_data, $order->user->email);
            }
            $public_key = config(php_uname('n') . '.PUSH_PUBLIC');
            $link       = "//" . $_SERVER['HTTP_HOST'] . '/narudzbine' . '/' . $order->id;
            $notification_data ['action_url'] = $link;
            if ($silent === false) {
                NotificationService::sendOrderUpdatedNotification($public_key, $order->user->id, $notification_data);
            }
        }
        return true;
    }

    /**
     * Menja količinu proizvoda ili dodaje u narudžbenicu
     * @param   int     $order_id       Id porudžbenice
     * @param   int     $product_id     Id proizvoda
     * @param   int     $quantity       Količina
     * @return  bool    Vraća true
     */
    public static function changeOrderProduct($order_id, $product_id, $quantity) {
        if (PermissionService::checkPermission('order_update') === false) {
            throw new PermissionException('Nemate dozvolu za promenu stanja proizvoda u porudžbenici', 19040);
        }

        $qb = self::$entity_manager->createQueryBuilder();

        $order_product = $qb
            ->select('op')
            ->from('App\Models\OrderProduct', 'op')
            ->where('op.order_id = :order_id')
            ->setParameter('order_id', $order_id)
            ->andWhere('op.product_id = :product_id')
            ->setParameter('product_id', $product_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
        //$order_id   = $order_product->order_id;
        $silent = true;
        $order_product === null
            ? self::addProductsToOrder($order_id, [['product_id' => $product_id, 'quantity' => $quantity]])
            : self::updateOrderProduct($order_product->id, ['quantity' => $quantity], $silent)
        ;
        self::changeTotalPrice($order_id, true);
        $order = self::getOrderById($order_id);
        $silent = true;
        ShopService::calculateOrderShippingFee($order->id, $order->total_price, $silent);
        if ($order_product !== null) {
            $email_data['Količina proizvoda:'] = "Proizvodu "
                . $order_product->product->name
                . " je promenjena količina na "
                . $quantity
            ;
            $email_data['Dostava']             = "Dostava narudžbine je:" . $order->shipping_fee;
            $email_data['Cena']                = "Ukupna cena narudžbine je:" . $order->total_price;
            EmailService::orderUpdated($email_data, $order_product->order->user->email);
        } else {
            $product = ProductService::getProductById($product_id);
            $order   = self::getOrderById($order_id);
            $email_data['Dodat proizvod:'] = "Proizvod " . $product->name . " je dodat u količini " . $quantity;
            $email_data['Dostava']         = "Dostava narudžbine je:" . $order->shipping_fee;
            $email_data['Cena']            = "Ukupna cena narudžbine je:" . $order->total_price;
            EmailService::orderUpdated($email_data, $order->user->email);
        }

        $order   = self::getOrderById($order_id);
        $public_key = config(php_uname('n') . '.PUSH_PUBLIC');
        $link       = "//" . $_SERVER['HTTP_HOST'] . '/narudzbine' . '/' . $order->id;
        $notification_data ['action_url'] = $link;
        NotificationService::sendOrderUpdatedNotification($public_key, $order->user->id, $notification_data);
        return true;
    }

    /**
     * Izmena stavke u porudžbenici
     * @param   int     $order_product_id   Id stavke
     * @param   array   $updates            Niz sa izmenama
     * @param   bool    $silent             Obavestava metodu i dalje metode da li da salju mail obavestenja
     * @return  bool    Vraća true
     */
    private static function updateOrderProduct($order_product_id, $updates, $silent = false) {
        if (PermissionService::checkPermission('order_update') === false) {
            throw new PermissionException('Nemate dozvolu za promenu stanja proizvoda u porudžbenici', 19040);
        }

        $order_product = self::getOrderProductById($order_product_id);
        $order_id      = $order_product->order_id;
        if (array_key_exists('quantity', $updates)) {
            if (ValidationService::validateInteger(
                $updates['quantity'],
                ValidationService::$RANGE_INTEGER_UNSIGNED['min'],
                ValidationService::$RANGE_INTEGER_UNSIGNED['max']
            ) === false) {
                throw new ValidationException('Količina nije odgovarajućeg formata', 19042);
            }

            $order_product->quantity = $updates['quantity'];
        }

        if (!empty($updates)) {
            self::$entity_manager->persist($order_product);
            self::$entity_manager->flush();
        }

        self::changeTotalPrice($order_id, $silent);
        return true;
    }

    private static function updateOrderProductPrices($order_products) {
        foreach ($order_products as $order_product) {
            $order = self::getOrderById($order_product->order_id);
            if ($order->payment_method->method === 'Keš'
            || $order->payment_method->method === 'Virmanski'
            ) {
                $price = $order_product->product->price_discount;
            } else {
                $price = $order_product->product->price_retail;
            }
            $order_product->price = $price * $order_product->quantity;
            self::$entity_manager->persist($order_product);
        }
        self::$entity_manager->flush();
    }

    /**
     * Izmena korpe
     * @param   int         $cart_id    Id korpe
     * @param   array       $updates    Niz sa izmenama
     * @return  bool/int    Vraća true ako je sve prošlo uredu inače vraća error_code
     */
    private static function updateCart($cart_id, $updates) {
        $cart = self::getCartById($cart_id);
        if (empty($cart)) {
            throw new ValidationException('Korpa sa tim id-om nije pronađena', 19029);
        }

        if (PermissionService::checkPermission('order_update') === false
            && UserService::getCurrentUserId() !== $cart->user->id
        ) {
            throw new PermissionException('Nemate dozvolu za izmenu korpe', 19030);
        }

        if (array_key_exists('quantity', $updates)) {
            $updates['quantity'] = ValidationService::validateInteger(
                $updates['quantity'],
                ValidationService::$RANGE_INTEGER_UNSIGNED['min'],
                ValidationService::$RANGE_INTEGER_UNSIGNED['max']
            );
            if ($updates['quantity'] !== false || $updates['quantity'] !== null && $updates['quantity'] > 0) {
                $cart->quantity = $updates['quantity'];
            }
        }

        if (!empty($updates)) {
            self::$entity_manager->persist($cart);
            self::$entity_manager->flush();
        }

        return true;
    }

    /**
     * Izmena korpe
     * @param   int         $product_id         Id proizvoda
     * @param   int         $quantity           Količina
     * @param   int         $user_id            Id korisnika(opcionalno)
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprotnom vraća error_code
     */
    public static function changeCart($product_id, $quantity, $user_id = null) {
        if (empty($user_id)) {
            $user_id = UserService::getCurrentUserId();
        }
        // Da ne bi mogli  negativan broj prozvoda da posalju
        $quantity = abs($quantity);
        if (ValidationService::validateInteger(
            $quantity,
            ValidationService::$RANGE_INTEGER_UNSIGNED['min'],
            ValidationService::$RANGE_INTEGER_UNSIGNED['max']
        ) === false || $quantity === 0) {
            throw new ValidationException('Količina nije odgovataućeg formata', 19042);
        }

        if ($user_id !== false) {
            if (UserService::getCurrentUserId() !== $user_id) {
                throw new PermissionException('Nemate dozvolu za izmenu korpe', 19030);
            }

            $qb = self::$entity_manager->createQueryBuilder();

            $cart = $qb
                ->select('c')
                ->from('App\Models\Cart', 'c')
                ->where('c.user_id = :user_id')
                ->setParameter('user_id', $user_id)
                ->andWhere('c.product_id = :product_id')
                ->setParameter('product_id', $product_id)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;

            empty($cart)
                ? self::addToCart($user_id, $product_id, $quantity)
                : self::updateCart($cart->id, ['quantity' => $quantity])
            ;

            // self::cancelUnconfirmedOrder($user_id);
        } else {
            $product = ProductService::getProductById($product_id);
            $in_stock = $product->in_stock;
            if ($in_stock === false) {
                throw new ValidationException('Proizvod nije na stanju');
            }
            $product_cart = [
                'product_id'    =>  $product_id,
                'quantity'      =>  $quantity,
            ];

            $cart = self::getSessionKeySubKeyValue('cart');

            if (empty($cart)) {
                self::setSession('cart', $product_cart, true);
            } else {
                $product_array_key = array_search($product_id, array_column($cart, 'product_id'));

                if ($product_array_key !== false) {
                    self::updateValueOfSubkeyOfSubkey('cart', $product_array_key, $product_cart);
                } else {
                    self::setSession('cart', $product_cart, true);
                }
            }
        }

        return true;
    }

    /**
     * Prebacuje proizvode iz porudžbenice u korpu i briše nepotvrđenu porudžbenicu
     * @param   int         $user_id        Id korisnika
     * @return  bool        Vraća true ako je sve uspešno prošlo
     */
    public static function cancelUnconfirmedOrder($user_id = null, $order_id = null) {
        if ($order_id === null && $user_id !== null) {
            $order = self::getOrderByUserIdStatus($user_id);
        } elseif ($order_id !== null && $user_id === null) {
            $order = self::getOrderById($order_id);
            $user_id = UserService::getCurrentUserId();
        }

        if (!empty($order)) {
            if (PermissionService::checkPermission('order_update') === false && $order->user->id !== $user_id) {
                throw new PermissionException('Nemate dozvolu za poništavanje porudžbenice', 19045);
            }

            $cart = [];
            foreach ($order->order_products as $order_product) {
                $cart[] = [
                    'product_id'    =>  $order_product->product_id,
                    'quantity'      =>  $order_product->quantity,
                ];
            }

            self::deleteOrder($order);

            foreach ($cart as $item) {
                self::changeCart($item['product_id'], $item['quantity']);
            }
        }

        return true;
    }

    /**
     *
     * DELETE
     *
     */

    /**
     * Briše porudžbenicu
     * @param   int         $order_id       Id porudžbenice
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprotnom vraća error_code
     */
    public static function deleteOrder($order_id) {
        $order = self::getOrderById($order_id);
        if (empty($order)) {
            throw new ValidationException('Porudžbenica sa tim id-om nije pronađena', 19031);
        }

        if (PermissionService::checkPermission('order_delete') === false
            && UserService::getCurrentUserId() !== $order->user_id
            && $order->last_update->status_code !== 0
        ) {
            throw new PermissionException('Nemate dozvolu za brisanje porudžbenice', 19032);
        }

        self::$entity_manager->remove($order);
        self::$entity_manager->flush();

        return true;
    }

    /**
     * Briše proizvod iz korpe, prema ID-ju reda
     * @param   int         $cart_id    Id unosa
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprotnom vraća neke error_code
     */
    public static function deleteCart($cart_id) {
        $cart = self::getCartById($cart_id);
        if (UserService::getCurrentUserId() !== false && !empty($cart)) {
            if (PermissionService::checkPermission('order_delete') === false
                && UserService::getCurrentUserId() !== $cart->user->id
            ) {
                throw new PermissionException('Nemate dozvolu za brisanje proizvoda iz korpe', 19034);
            }

            self::$entity_manager->remove($cart);
            self::$entity_manager->flush();
        } else {
            $cart = self::getSessionKeySubKeyValue('cart', $cart_id);
            if (!empty($cart)) {
                self::deleteSessionSubkeyOfSubkey('cart', $cart_id);
            }
        }

        return true;
    }

    public static function deleteCartItem($user_id, $product_id) {
        $cart = self::getCartByUserIdProductId($user_id, $product_id);
        if (empty($cart)) {
            throw new ValidationException('Element u listi nije pronađen', 1);
        }

        $admin = AdminService::isAdminLoggedIn();

        if (UserService::getCurrentUserId() !== false || $admin) {
            self::$entity_manager->remove($cart);
            self::$entity_manager->flush();
        } else {
            foreach ($cart as $row) {
                self::deleteSessionSubkeyOfSubkey('cart', $row->id);
            }
        }

        return true;
    }


    /**
     * Briše stavku iz porudžbenice
     * @param   int     $order_product_id   Id stavke iz porudžbenice
     * @return  bool    Vraća true
     */
    public static function deleteOrderProduct($order_product_id) {
        if (PermissionService::checkPermission('order_delete') === false) {
            throw new PermissionException('Nemate dozvolu za brisanje stavke iz porudžbenice', 19043);
        }

        $order_product = self::getOrderProductById($order_product_id);
        $product_name = $order_product->product->name;
        $order_id = $order_product->order_id;
        self::$entity_manager->remove($order_product);
        self::$entity_manager->flush();

        $email_data['Obrisan proizvod:'] = "Proizvod " . $product_name . " je obrisan iz narudžbine!";
        EmailService::orderUpdated($email_data, $order_product->order->user->email);

        self::changeTotalPrice($order_id);
        return true;
    }

    /**
     * Briše sve iz korpe za određenog korisnika
     * @param   int         $user_id        Id korisnika
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprotnom vraća error_code
     */
    private static function deleteCartByUserId($user_id) {
        $qb = self::$entity_manager->createQueryBuilder();

        $qb
            ->delete('App\Models\Cart', 'c')
            ->where('c.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult()
        ;

        return true;
    }
}
