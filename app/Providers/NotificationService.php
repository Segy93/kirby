<?php

namespace App\Providers;

use App\Models\NotificationsPreferences;
use App\Models\NotificationsSubscriptions;
use App\Providers\DeviceService;
use App\Providers\UserService;
use Exception;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class NotificationService extends BaseService {
    private static $images  =  [
        'comment_answered' => 'favicon-512x512.png',
        'order_updated'    => 'favicon-512x512.png',
        'order_status'     => 'favicon-512x512.png',
    ];
    public static function createSubscription($endpoint = '', $p256dh = '', $auth = '') {
        $user_id =  UserService::getCurrentUserId();

        if ($user_id === false) {
            throw new Exception('Morate se prijaviti da bi mogli da koristite notifikacije', 25001);
        }

        $exists = self::userEndpointExists($user_id, $endpoint);
        $notification_types = self::getAllNotificationTypes();
        $device = DeviceService::getDeviceInfo();
        if (!$exists && $device !== 'bot') {
            $user        = UserService::getUserById($user_id);
            $preferences = new NotificationsPreferences();
            $preferences->user              = $user;
            $preferences->endpoint          = $endpoint;
            $preferences->device            = $device;
            $preferences->p256dh            = $p256dh;
            $preferences->auth              = $auth;

            $subscriptions = self::getAllUserSubscriptions();
            if (empty($subscriptions)) {
                foreach ($notification_types as $type) {
                    $subscription = new NotificationsSubscriptions();
                    $subscription->user = $user;
                    $subscription->type = $type;
                    self::$entity_manager->persist($subscription);
                }
            }

            self::$entity_manager->persist($preferences);
            self::$entity_manager->flush();
        }

        return true;
    }

    public static function addTypeSubscription($type_id, $user_id = null) {
        if ($user_id === null) {
            $user = UserService::getCurrentUser();
        } else {
            $user = UserService::getUserById($user_id);
        }
        $type = self::getTypeById($type_id);
        $subscription = self::getNotificationTypeSubscription($type_id, $user->id);
        if ($subscription !== null) {
            throw new Exception('Korisnik je prijavljen na taj tip notifikacija', 25002);
        }

        $subscription = new NotificationsSubscriptions();
        $subscription->user = $user;
        $subscription->type = $type;
        self::$entity_manager->persist($subscription);
        self::$entity_manager->flush();
    }

    public static function getNotificationTypeSubscription($type_id, $user_id = null) {
        if ($user_id === null) {
            $user_id = UserService::getCurrentUserId();
        }

        $subscription = self::$entity_manager->createQueryBuilder()
            ->select('s')
            ->from('App\Models\NotificationsSubscriptions', 's')
            ->where('s.user_id = :user_id')
            ->andWhere('s.type_id = :type_id')
            ->setParameter('user_id', $user_id)
            ->setParameter('type_id', $type_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $subscription;
    }

    public static function getAllUserSubscriptions($user_id = null) {
        if ($user_id === null) {
            $user_id = UserService::getCurrentUserId();
        }

        $subscriptions = self::$entity_manager->createQueryBuilder()
            ->select('s')
            ->from('App\Models\NotificationsSubscriptions', 's')
            ->where('s.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult()
        ;

        return $subscriptions;
    }

    public static function getUserEndpoints($user_id = null) {
        if ($user_id === null) {
            $user_id =  UserService::getCurrentUserId();
        }
        $qb = self::$entity_manager->createQueryBuilder();
        $endpoints = $qb
            ->select('np')
            ->from('App\Models\NotificationsPreferences', 'np')
            ->where('np.user_id = ?1')
            ->setParameter(1, $user_id)
            ->getQuery()
            ->getResult()
        ;

        return $endpoints;
    }

    public static function getEndpointById($id) {
        $qb = self::$entity_manager->createQueryBuilder();
        $endpoint = $qb
            ->select('np')
            ->from('App\Models\NotificationsPreferences', 'np')
            ->where('np.id = ?1')
            ->setParameter(1, $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $endpoint;
    }

    public static function getAllNotificationTypes() {
        $qb = self::$entity_manager->createQueryBuilder();
        $types = $qb
            ->select('nt')
            ->from('App\Models\NotificationsType', 'nt')
            ->getQuery()
            ->getResult()
        ;

        return $types;
    }

    public static function getTypeByMachineName($machine_name) {
        $qb = self::$entity_manager->createQueryBuilder();
        $type = $qb
            ->select('nt')
            ->from('App\Models\NotificationsType', 'nt')
            ->where('nt.machine_name = :machine_name')
            ->setParameter('machine_name', $machine_name)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $type;
    }

    public static function getTypeById($id) {
        $qb = self::$entity_manager->createQueryBuilder();
        $type = $qb
            ->select('nt')
            ->from('App\Models\NotificationsType', 'nt')
            ->where('nt.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $type;
    }

    public static function isUserSubscribed($machine_name, $user_id = null) {
        if ($user_id === null) {
            $user_id = UserService::getCurrentUserId();
        }

        $type = self::getTypeByMachineName($machine_name);
        $subscription = self::$entity_manager->createQueryBuilder()
            ->select('s')
            ->from('App\Models\NotificationsSubscriptions', 's')
            ->where('s.user_id = :user_id')
            ->andWhere('s.type_id = :type_id')
            ->setParameter('user_id', $user_id)
            ->setParameter('type_id', $type->id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $subscription !== null;
    }

    public static function userEndpointExists($user_id, $endpoint) {
        $qb = self::$entity_manager->createQueryBuilder();
        $endpoint = $qb
            ->select('np')
            ->from('App\Models\NotificationsPreferences', 'np')
            ->where('np.user_id = ?1')
            ->setParameter(1, $user_id)
            ->andWhere('np.endpoint=?2')
            ->setParameter(2, $endpoint)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $endpoint === null ?  false : true;
    }

    public static function sendNotification($data = [], $public_key = '', $user_id = null, $additional_data = []) {
        $preferences = self::getUserEndpoints($user_id);
        $image       = '//' . $_SERVER['HTTP_HOST'] . '/' . $data['image'];
        $private_key = config(php_uname('n') . '.PUSH_PRIVATE');
        $is_subscribed = self::isUserSubscribed($data['machine_name']);
        foreach ($preferences as $preference) {
            $subscription = Subscription::create([
                'endpoint' => $preference->endpoint,
                'contentEncoding' => 'aesgcm',
                'keys'            => [
                    'p256dh' => $preference->p256dh,
                    'auth'   => $preference->auth,
                ],
            ]);
            $subject_vapid = config(php_uname('n') . '.VAPID_SUBJECT');
            $auth = array(
                'VAPID' => array(
                    'subject' => $subject_vapid,
                    'publicKey' => $public_key,
                    'privateKey' => $private_key,
                ),
            );
            $payload = [
                'subject' => $data['subject'],
                'message' => $data['message'],
                'image'   => $image,
            ];

            if (array_key_exists('action_url', $additional_data)) {
                $payload['action_url'] = $additional_data['action_url'];
            }

            $options = [
                'vapidDetails'    => $auth,
                'contentEncoding' => 'aesgcm'
            ];
            $payload = json_encode($payload);
            if ($is_subscribed) {
                $webPush = new WebPush($auth);
                $res = $webPush->sendNotification(
                    $subscription,
                    $payload,
                    false,
                    $options,
                    $auth
                );

                // handle eventual errors here, and remove the subscription from your server if it is expired
                foreach ($webPush->flush() as $report) {
                    $endpoint = $report->getRequest()->getUri()->__toString();
                    if ($report->isSuccess()) {
                        Log::info("[v] Message sent successfully for subscription {$endpoint}.");
                    } else {
                        Log::info("[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}");
                    }
                }
            }
        }
    }

    public static function sendDefaultNotification($public_key = '', $user_id = null) {
        $data['subject'] = 'Default notification subject';
        $data['message'] = 'Default notification message';
        self::sendNotification($data, $public_key, $user_id);
    }

    public static function sendOrderUpdatedNotification($public_key = '', $user_id = null, $additional_data = []) {
        $data['subject'] = 'Narudžbina je izmenjena';
        $data['message'] = 'Vaša narudžbina je promenjena';
        $data['machine_name']   = 'order_updated';
        $data['image']          = self::$images['order_status'];
        self::sendNotification($data, $public_key, $user_id, $additional_data);
    }

    public static function sendCommentAnsweredNotification($public_key = '', $user_id = null, $additional_data = []) {
        $data['subject'] = 'Komentar';
        $data['message'] = 'Neko je odgovorio na vaš komentar';
        $data['machine_name'] = 'comment_answered';
        $data['image']  = self::$images['comment_answered'];
        self::sendNotification($data, $public_key, $user_id, $additional_data);
    }

    public static function sendOrderStatusUpdateNotification($public_key = '', $user_id = null, $additional_data = []) {
        $data['subject'] = 'Narudžbina';
        $data['message'] = 'Vašoj narudžbini je promenjen status';
        $data['machine_name']   = 'order_status_updated';
        $data['image']          = self::$images['order_updated'];
        self::sendNotification($data, $public_key, $user_id, $additional_data);
    }

    public static function toggleUserSubscription($type_id, $user_id = null) {
        if ($user_id === null) {
            $user_id = UserService::getCurrentUserId();
        }

        $subscription   = self::getNotificationTypeSubscription($type_id, $user_id);
        if ($subscription === null) {
            self::addTypeSubscription($type_id, $user_id);
        } else {
            self::removeSubscription($type_id, $user_id);
        }

        return true;
    }


    public static function removeSubscription($type_id, $user_id = null) {
        if ($user_id === null) {
            $user_id = UserService::getCurrentUserId();
        }

        $subscription = self::getNotificationTypeSubscription($type_id, $user_id);
        if ($subscription !== null) {
            self::$entity_manager->remove($subscription);
            self::$entity_manager->flush();
        }
    }

    public static function removeAllUserSubscriptions($user_id = null) {
        if ($user_id === null) {
            $user_id = UserService::getCurrentUserId();
        }

        $subscriptions = self::getAllUserSubscriptions();

        foreach ($subscriptions as $subscription) {
            self::$entity_manager->remove($subscription);
        }

        self::$entity_manager->flush();

        return true;
    }

    public static function removeEndpoint($id) {
        $endpoint = self::getEndpointById($id);

        if ($endpoint !== null) {
            //self::removeAllUserSubscriptions();
            self::$entity_manager->remove($endpoint);
            self::$entity_manager->flush();
        }

        return true;
    }
}
