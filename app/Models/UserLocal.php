<?php

namespace App\Models;

use App\Providers\ImageService;
use App\Providers\UserService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="UsersLocal")
 */
class UserLocal extends User {

    /**
     * @ORM\Column(type="string")
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $profile_picture;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     */
    private $activation_token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $activation_token_expired;

    /**
     * @ORM\Column(type="string")
     */
    private $password_reset_token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $password_reset_expired;


    /**
     * @ORM\Column(type="boolean")
     */
    private $cookies_accepted;


    public function __construct() {
        parent::__construct();
        $this->cookies_accepted =   0;
    }

    /**
     * json Serilizacija
     * @return mixed
     */
    public function jsonSerialize(): mixed {
        $json = [
            'username'                  =>  $this->username,
            'email'                     =>  $this->email,
            'profile_picture'           =>  $this->profile_picture,
            'profile_picture_full'      =>  $this->profile_picture_full,
            'profile_picture_small'     =>  $this->profile_picture_small,
            'cookies_accepted'          =>  $this->cookies_accepted,
        ];

        return array_merge(parent::jsonSerialize(), $json);
    }

    protected $user_type = 'local';

    public function __get($fieldName) {
        if ($fieldName === 'profile_picture_full') {
            $path = UserService::getProfilePicturePath() . $this->profile_picture;
            if ($this->profile_picture === null) {
                return UserService::getProfilePicturePathDefault();
            } else {
                if (file_exists($path)) {
                    chmod($path, 0644);
                }
                return $path;
            }
        }

        if ($fieldName === 'profile_picture_small') {
            $current_image  = UserService::getProfilePicturePath() . $this->profile_picture;
            $path           = ImageService::getImageBySize($current_image, 100, 100, 'uploads_static', false, 'user');

            return $path;
        }

        return $this->{$fieldName};
    }

    public function isActivated() {
        return $this->activation_token !== null;
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
