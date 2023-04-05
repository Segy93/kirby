<?php

namespace App\Providers;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Providers\FolderService;


/**
 * Servis za sve vrste manipulacija sa slikama
 */
class ImageService extends BaseService {
    /**
     *
     * CREATE
     *
     */

    /**
     * Upload slike
     * @param   UploadedFile    $image                  Objekat slike
     * @param   string          $image_destination      Destinacija slike gde se čuva opcionalni paramater
     * @param   integer         $quality                Kvalitet slike postavljen na 75% od originala
     * @return  bool            Vraća true ako je sve prošlo uredu inače vraća error message
     */
    public static function uploadImage($image, $image_destination = null, $quality = 85) {
        try {
            $path = self::getPath();

            if (!empty($image_destination)) {
                FolderService::createFolder($image_destination);
                chmod($image_destination, 0755);
            }

            $image_destination = !empty($image_destination)
                ? $path . '/' . $image_destination
                : $path
            ;

            $image_name = Hash::make($image->getClientOriginalName() . date("Y-m-d h:i:sa"));
            $image_name = str_replace('/', '-', $image_name);
            $image_name = str_replace('.', '-', $image_name);
            //var_dump($image);die;
            $img = Image::make($image);
            $extension = '.' . explode('/', $img->mime())[1];

            $full_image_path = $image_destination . '/' . $image_name . $extension;

            $img->save($full_image_path, $quality);

            chmod($full_image_path, 0644);
            return $image_name . $extension;
        } catch (\Exception $e) {
            Log::warning($e->getMessage());
            throw new \Exception('Greška pri upisu slike', 11001);
        }
    }

    /**
     *
     * READ
     *
     */

    /**
     * Vraća ime slike
     * @param   string      $image_path                 Putanja do slike
     * @return  string      Vraća ime slike
     */
    public static function getImageName($image_path) {
        return Image::make($image_path)->basename;
    }

    /**
     * Dohvata sliku po određenoj veličini
     * @param   string          $image                  Putanja do trenutne slike
     * @param   int             $width                  Širina slike
     * @param   int             $height                 Visina slike
     * @param   string          $image_destination      Nova destinacija slike
     * @param   boolean         $crop                   Dali seče sliku ili ne
     * @return  string          Vraća putanju do nove slike u suprotnom vraća neki error_code
     */
    public static function getImageBySize(
        $image,
        $width,
        $height,
        $image_destination = null,
        $crop = false,
        $default_type = 'product'
    ) {
        try {
            chmod($image, 0777);
            $path = self::getPath();

            $full_image_path = $image;
            if ($default_type === 'product') {
                $image_path   = "/default_pictures/default_product.png";
            } else {
                $image_path   = "/default_pictures/default_user_picture.jpg";
            }
            if (file_exists($image)) {
                $type = null;
                // if (preg_match("/\.$/", $image)) {
                //    $type  =  exif_imagetype($image);
                //    $type =  self::$image_types[$type];
                //    rename ( $image, $image.$type) ;
                //    $image = $image.$type;
                // }
                $img = Image::make($image);
                $image_name = $type === null ? $img->basename : $img->basename . $type;

                if ($crop === true) {
                    $img->crop($width, $height);
                } else {
                    $img->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->resizeCanvas($width, $height, 'center', false, '#ffffff');
                }

                $image_destination = !empty($image_destination)
                    ? $image_destination . '/' . $width . 'x' . $height
                    : $width . 'x' . $height
                ;
                FolderService::createFolder($image_destination);

                chmod($image_destination, 0755);
                $full_image_path = $path . '/' . $image_destination . '/' . $image_name;
                $image_path = $image_destination . '/' . $image_name;

                $img->save($full_image_path);
                chmod($full_image_path, 0644);
            }

            return $image_path;
        } catch (\Exception $e) {
            //echo $e;
            if ($default_type === 'product') {
                return "/default_pictures/default_product.png";
            } else {
                return "/default_pictures/default_user_picture.jpg";
            }
            //return "/default_pictures/default_product.png";
        }
    }

    /**
     *
     * UPDATE
     *
     */

    /**
     * Izmena slike
     * @param   string      $old_name       Stara slika
     * @param   string      $new_name       Nova slika
     * @return  string      $new_name       Vraća ime nove slike
     */
    public static function updateImage($old_name, $new_name) {
        self::deleteImage($old_name);
        self::uploadImage($new_name);

        return $new_name;
    }

    /**
     *
     * DELETE
     *
     */

    public static function deleteImage($image_name) {
        $full_path = self::getPath() . '/' . $image_name;

        if (file_exists($full_path)) {
            unlink($full_path);
        }
    }

    /**
     * Briše sliku i sve njene dimenzije
     * @param   string      $image_name             Naziv slike
     * @param   string      $image_destination      Gde se slika nalazi
     * @return  void
     */
    public static function deletePictures($image_name, $image_destination) {
        $path = self::getPath();
        $full_image_destination = !empty($image_destination) ? $path . '/' . $image_destination : $path;
        $content = scandir($full_image_destination);

        foreach ($content as $node) {
            if ($node === '.' || $node === '..') {
                continue;
            }

            $full_image_path = $image_destination . '/' . $node . '/' . $image_name;
            self::deleteImage($full_image_path);
        }
    }
}
