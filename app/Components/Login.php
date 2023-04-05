<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Http\Controllers\BaseController;
use App\Providers\UserService;
use App\Components\SocialShare;
use App\Providers\ConfigService;

/**
*
*/
class Login extends BaseComponent {
    protected $css = ['Login/css/Login.css'];
    protected $js = [
        'Login/js/LoginRegistration.js',
        'Login/js/LoginResetPassword.js',
        'Login/js/LoginForgotPassword.js',
    ];


    protected $icons = [
        'Login/templates/icons',
    ];

    private $error_code = 0;
    private $form_state = '';










    public function __construct($form_state = 'login', $error_code = 0) {
        if (!empty($error_code)) {
            $this->error_code = $error_code;
        }

        if (!empty($form_state)) {
            $this->form_state = $form_state;
        }
    }










    /**
     * Provera da li je mejl zauzet
     *
     * @param   array       $params             Prosleđeni parametri
     * @param   string      $params['email']    Mejl koji se proverava
     * @return  boolean                         Da li je mejl zauzet
    */
    public function checkEmailTaken(array $params): bool {
        $email = $params['email'];
        return UserService::isLocalEmailTaken($email);
    }

    /**
     * Provera da li je korisničko ime zauzeto
     *
     * @param   array       $params             Prosleđeni parametri
     * @param   string      $params['username'] Korisničko ime koje se proverava
     * @return  boolean                         Da li je korisničko ime zauzeto
    */
    public function checkUsernameTaken(array $params): bool {
        $username = $params['username'];
        return UserService::isLocalUsernameTaken($username);
    }










    private function getErrorMessage() {
        $messages = [
            'forgot_password' => [
                0       => '',
                4       => 'Link je istekao',
                7       => 'Recaptcha nije prošla validaciju',
                22024   => 'Korisnik sa tom adresom ne postoji',
            ],

            'forgot_success' => [
                0 => '',
            ],

            'login' => [
                0       => '',
                1       => 'Korisnik sa tim username/email ne postoji',
                10      => 'Pogrešna lozinka',
                18      => 'Ovaj nalog je trajno blokiran',
                19      => 'Ovaj nalog je blokiran na određeno vreme',
                22011   => 'Korisnik sa tim username/email ne postoji',
                22012   => 'Pogrešna lozinka',
                22013   => 'Zbog promene sistema kao stari korisnik morate resetovati lozinku. Mail za reset je poslat',
            ],

            'register' => [
                0       => '',
                3       => 'Email je neispravan',
                5       => 'Niste prihvatili uslove korišćenja',

                1062    => 'Korisnik sa tim email-om ili korisničkim imenom već postoji',
                // Postoji ista greška dva puta jer sam metode pisao različito
                // i u nekim vraća 1062 a u nekim 23000 u suštini znači duplikat
                23000   => 'Korisnik sa tim email-om ili korisničkim imenom već postoji',
                22002   => 'Email nije odgovarajućeg formata',
                23001   => 'Lozinka mora da sadrži minimum ' . UserService::$PASSWORD_MIN_LENGTH . ' karaktera',
                23002   => 'Lozinka mora da sadrži barem jedan broj',
                23003   => 'Lozinka mora da sadrži barem jedno malo slovo',
                23004   => 'Lozinka mora da sadrži barem jedno veliko slovo',
                23005   => 'Lozinka je predugačka',
            ],

            'reset'  => [
                0       => '',
                1       => 'Neispravan link',
                22025   => 'Korisnik sa tim tokenom nije pronađen',
                22026   => 'Token je istekao, ponovo pošalji te zahtev za reset lozinke',
                23001   => 'Lozinka mora da sadrži minimum ' . UserService::$PASSWORD_MIN_LENGTH . ' karaktera',
                23002   => 'Lozinka mora da sadrži barem jedan broj',
                23003   => 'Lozinka mora da sadrži barem jedno malo slovo',
                23004   => 'Lozinka mora da sadrži barem jedno veliko slovo',
                23005   => 'Lozinka je predugačka',
                22027   => 'Lozinke se ne podudaraju',
            ],

        ];

        return $messages[$this->form_state][$this->error_code];
    }










    public function renderHTML() {
        return view('Login/templates/Login', [
            'contact_email'         => ConfigService::getEmailContact(),
            'csrf_field'            => BaseController::getCsrfField(),
            'error'                 => $this->getErrorMessage(),
            'password_max_length'   => UserService::$PASSWORD_MAX_LENGTH,
            'password_min_length'   => UserService::$PASSWORD_MIN_LENGTH,
            'site_key'              => ConfigService::getGoogleSiteKey(),
            'social_share'          => new SocialShare(),
            'view'                  => $this->form_state,
        ]);
    }
}
