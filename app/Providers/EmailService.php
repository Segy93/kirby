<?php

namespace App\Providers;

use App\Components\Email;
use Illuminate\Support\Facades\Log;

class EmailService extends BaseService {

    /**
     * Šalje email
     * @param   string      $email_type     Tip email-a (odredjuje koji blade se ucitaba)
     * @param   array       $params         Parametri koji ce biti prosledjeni u blade
     * @param   string      $email_to       Kome se šalje
     * @param   string      $email_from     Opcionalno od koga se šalje
     * @return  bool                        Da li je slanje uspesno
     */
    private static function send($email_type, $params, $email_to, $email_from = null) {
        $reply_to   =   !empty($email_from) ? $email_from : config(php_uname('n') . '.EMAIL_SALES');
        $m          =   new Email($email_type, $params);
        $subject    =   self::getEmailSubjectByEmailType($email_type, $params);
        $message    =   $m->renderHTML()->render();
        $headers    =   'From:' . $reply_to . "\n" .
                        'Reply-To:' . $reply_to . "\n" .
                        'Content-Type: text/html; charset=UTF-8';

        Log::info(print_r([
            'email_to' => $email_to,
            'subject' => $subject,
            'email_type' => $email_type,
        ], true));

        return config(php_uname('n') . '.SKIP_EMAILS')
            ? true
            : mail($email_to, $subject, $message, $headers)
        ;
    }

    /**
     * Dohvata naslov email-a u odnosu na tip
     * @param   string      $email_type     Tim email-a
     * @return  string      Naslov email-a
     */
    private static function getEmailSubjectByEmailType($email_type, $params) {
        if (array_key_exists('order', $params)) {
            $email = $params['order']->user->email;
        } else {
            $email = '';
        }
        $subjects = [
            'Activation'                 =>  'Aktivacija email-a',
            'ResetPassword'              =>  'Promena lozinke',
            'NotificationOrder'          =>  'Obeveštenje o narudžbini',
            'NotificationOrderConfirmed' =>  'Narudžbina potvrđena',
            'OrderCreatedAdmin'          =>  'Narudžbina kreirana ' . $email,
        ];

        return array_key_exists($email_type, $subjects) ? $subjects[$email_type] : "Mail od monitor.rs";
    }

    /**
     * Šalje email za aktivaciju email-a korisnika
     */
    public static function sendEmailValidation($params, $email_to, $email_from = null) {
        return self::send('Activation', $params, $email_to, $email_from);
    }

    /**
     * Šalje email sa linkom za promenu lozinke
     */
    public static function sendEmailResetPassword($params, $email_to, $email_from = null) {
        return self::send('ResetPassword', $params, $email_to, $email_from);
    }

    /**
     * Šalje obaveštenje o promeni statusa narudžbine
     */
    public static function sendNotificationOrderUpdated($params, $email_to, $email_from = null) {
        return self::send('NotificationOrder', $params, $email_to, $email_from);
    }

    public static function sendNotificationOrderConfirmed($params) {
        $sales = config(php_uname('n') . '.EMAIL_SALES');
        return self::send('NotificationOrderConfirmed', $params, $sales, $sales);
    }

    public static function sendOrderCreated($params, $email_to, $email_from = null) {
        //$sales = config(php_uname('n') . '.EMAIL_SALES');
        //$params['link'] = "//" . $_SERVER['HTTP_HOST']  . "/admin/narudzbina/";
        //self::send('OrderCreatedAdmin', $params, $sales, $sales);
        return self::send('OrderCreated', $params, $email_to, $email_from);
    }

    public static function sendUserBanned($params, $email_to, $email_from = null) {
        return self::send('UserBanned', $params, $email_to, $email_from);
    }

    public static function orderUpdated($params, $email_to, $email_from = null) {
        return self::send('OrderUpdated', $params, $email_to, $email_from);
    }


    public static function passwordUpdated($params, $email_to, $email_from = null) {
        return self::send('PasswordUpdated', $params, $email_to, $email_from);
    }


    public static function commentAnswered($params, $email_to, $email_from = null) {
        return self::send('CommentAnswered', $params, $email_to, $email_from);
    }

    public static function oldUserPassword($params, $email_to, $email_from = null) {
        return self::send('OldUserPassword', $params, $email_to, $email_from);
    }
}
