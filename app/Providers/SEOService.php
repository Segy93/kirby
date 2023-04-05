<?php

namespace App\Providers;

use App\Models\SEO;
use App\Providers\ImageService;
use App\Providers\PermissionService;
use App\Providers\ValidationService;
use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;
use App\Components\Sitemap;
use Illuminate\Support\Facades\Log;


class SEOService extends BaseService{

    //Ime foldera
    private static $folder_path_root = 'uploads_static';

    private static $folder_path_originals = 'originals';

    /**
     *
     * CREATE
     *
     */

    /**
     * Kreira seo
     * @param   string          $machine_name               Mašinsko ime
     * @param   string          $url                        Link
     * @param   string          $keywords                   Ključne reči
     * @param   string          $descritpion                Opis
     * @param   string          $title                      Naslov
     * @param   string          $thumbnail_twitter          Twiter sličica
     * @param   string          $image_open_graph           Slika
     * @return  SEO             $seo                        Vraća objekat seo
     */
    public static function createSEO(
        $machine_name,
        $url,
        $keywords = null,
        $description = null,
        $title = null,
        $picture = null,
        $flush = false
    ) {
        $i = 0;
        $seo = self::getSEObyURL($url);
        $url_new = '';
        while (!empty($seo)) {
            $url_new = $url . $i;
            $seo = self::getSEObyURL($url_new);
            $i++;
        }

        $url = $url_new !== '' ? $url_new : $url;
        $seo                =   new SEO();
        $seo->machine_name  =   $machine_name;
        $seo->url           =   ValidationService::validateURL($url);
        if (!empty($keywords)) {
            $seo->keywords = $keywords;
        }

        if (!empty($description)) {
            $seo->description = $description;
        }

        if (!empty($title)) {
            $seo->title = $title;
        }

        if (!empty($picture)) {
            $images                     =   self::createPicture($picture);
            $seo->thumbnail_twitter     =   $images['thumbnail_twitter'];
            $seo->image_twitter         =   $images['image_twitter'];
            $seo->image_open_graph      =   $images['image_open_graph'];
        }

        self::$entity_manager->persist($seo);
        if ($flush) {
            self::$entity_manager->flush();
        }
        return $seo;
    }

    public static function createSitemap() {
        $content = (new Sitemap())->renderHTML();
        $file = 'sitemap.xml';
        $nr_written = file_put_contents($file, $content->render());
        chmod($file, 644);
    }

    /**
     * Kreira različite veličine slika za seo
     * @param   file        $picture            Prima objekat slike
     * @return  array       Vraća imena slika
     */
    private static function createPicture($picture) {
        $picture            =   ImageService::uploadImage(
            $picture,
            self::$folder_path_root . '/' . self::$folder_path_originals
        );
        $picture            =   self::$folder_path_root . '/' . self::$folder_path_originals . '/' . $picture;
        $thumbnail_twitter  =   ImageService::getImageBySize($picture, 120, 120, self::$folder_path_root);
        $image_twitter      =   ImageService::getImageBySize($picture, 280, 170, self::$folder_path_root);
        $image_open_graph   =   ImageService::getImageBySize($picture, 1200, 630, self::$folder_path_root);

        return [
            'original'          =>  ImageService::getImageName($picture),
            'thumbnail_twitter' =>  ImageService::getImageName($thumbnail_twitter),
            'image_twitter'     =>  ImageService::getImageName($image_twitter),
            'image_open_graph'  =>  ImageService::getImageName($image_open_graph),
        ];
    }

    /**
     *
     * READ
     *
     */

    /**
     * Proverava dozvolu
     * @param   string      $machine_name       Mašinsko ime
     * @param   string      $action             Akcija
     * @return  bool        Vraća true ako ima dozvolu u suprontom slučaju vraća false
     */
    private static function checkPermission($machine_name, $action) {
        return PermissionService::checkPermission(explode('_', $machine_name)[0] . '_' . $action);
    }

    /**
     * Nalazi seo po mašinskom imenu
     * @param   string      $machine_name   Mašinsko ime za seo
     * @return  SEO                         SEO objekat
     */
    public static function getSEO($machine_name) {
        return self::$entity_manager->createQueryBuilder()
            ->select('s')
            ->from('App\Models\SEO', 's')
            ->where('s.machine_name = ?1')
            ->setParameter(1, $machine_name)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult()
        ;
    }

    public static function getProductSEO() {
        $result =  self::$entity_manager->createQueryBuilder()
            ->select('p.updated_at', 's.url')
            ->from('App\Models\Product', 'p')
            ->join('App\Models\SEO', 's', 'WITH', 's.machine_name = CONCAT(?1, p.artid)')
            ->setParameter(1, "product_")
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    public static function getStaticPageSEO() {
        $result =  self::$entity_manager->createQueryBuilder()
            ->select('p.updated_at', 's.url')
            ->from('App\Models\StaticPages\Page', 'p')
            ->join('App\Models\SEO', 's', 'WITH', 's.machine_name = CONCAT(?1, p.id)')
            ->setParameter(1, "page_")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Nalazi seo po url-u
     * @param   string  $url    Url po kom se pretažuje seo
     * @return  SEO     $seo    Seo objekat
     */
    public static function getSEObyURL(string $url_raw): ?SEO {
        $result = self::$entity_manager->createQueryBuilder()
            ->select('s')
            ->from('App\Models\SEO', 's')
            ->where('s.url = ?1')
            ->orWhere('s.url = ?2')
            ->setParameter(1, $url_raw)
            ->setParameter(2, $url_raw . '/')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if ($result === null) {
            // Log::error('SEO za stranicu: "' . $url_raw . '" nije pronađen');
        }

        return $result;
    }

    /**
     * Ukoliko slike za seo proizvoda ne postoje generise ih po machine-name-u
     *
     * @param string $machine_name
     * @return void
     */
    public static function generateImages(string $machine_name): void {
        $seo = self::getSEObyMachineName($machine_name);
        $type = explode('_', $machine_name)[0];
        if ($type === 'product') {
            $artid = explode('_', $machine_name)[1];
            $thumbnail_twitter  =   ProductService::getPictures($artid, 120, 120)[0];
            $image_twitter      =   ProductService::getPictures($artid, 280, 170)[0];
            $image_open_graph   =   ProductService::getPictures($artid, 1200, 630)[0];
            $seo->thumbnail_twitter = $thumbnail_twitter;
            $seo->image_twitter = $image_twitter;
            $seo->image_open_graph = $image_open_graph;
            self::$entity_manager->persist($seo);
            self::$entity_manager->flush();
        }
    }

    /**
     * Dohvata seo po mašinskom imenu
     *
     * @param   string  $machine_name       Mašinsko ime
     * @return  SEO     SEO objekat
     */
    public static function getSEObyMachineName($machine_name) {
        return self::$entity_manager->createQueryBuilder()
            ->select('s')
            ->from('App\Models\SEO', 's')
            ->where('s.machine_name = ?1')
            ->setParameter(1, $machine_name)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Proverava dali je url zauzet
     * @param   string      $url        Url koji se proverava
     * @return  boolean     Vraća false ako nije ili true ako jeste zauzet
     */
    public static function isUrlTaken($url) {
        $return = false;

        $urls = [
            'odjava',
            'pretraga',
            'prijava',
            'profil',
            'registracija',
            'registracijakorisnik',
            'zaboravljena-lozinka',
            'reset-lozinke-proverimail',
            'reset-lozinke-post',
            'shop',
            'test',
            'uploadImage',
        ];

        $urls_containing = [
            'informacije',
            'reset-lozinke',
            'aktivacija',
            'ajax',
            'admin',
        ];

        $url_array = explode('/', $url);

        foreach ($url_array as $input) {
            if (!empty(self::getSEOByURL($input))) {
                $return = true;
            }

            $input = '/' . $input . '/';

            if (!empty(preg_grep($input, $urls))) {
                $return = true;
            }

            if (!empty(preg_grep($input, $urls_containing))) {
                $return = true;
            }
        }

        return $return;
    }

    /**
     *
     * UPDATE
     *
     */

     /**
     * Služi za update SEO
     * @param    int        $id         Id SEO
     * @param    array      $updates    Niz sa izmenama
     * @return   SEO        $seo        Vraća objekat ako je sve prošlo uredu inače vraća error_code
     */
    public static function updateSEO($machine_name, $updates) {
        if (self::checkPermission($machine_name, 'update') === false) {
            throw new PermissionException('Nemate dozvolu da menjate SEO', 17001);
        }

        $seo = self::getSEO($machine_name);

        if (empty($seo)) {
            $seo = self::createSEO(
                $machine_name,
                $updates['url'],
                $updates['keywords'],
                $updates['description'],
                $updates['title'],
                $updates['picture']
            );
        } else {
            if (array_key_exists('keywords', $updates)) {
                if (ValidationService::validateString($updates['keywords'], 255) === false) {
                    throw new ValidationException('Ključne reči nisu odgovarajućeg formata', 17002);
                }

                $seo->keywords = $updates['keywords'];
            }

            if (array_key_exists('description', $updates)) {
                if (ValidationService::validateString($updates['description'], 255) === false) {
                    throw new ValidationException('Opis nije odgovarajućeg formata', 17003);
                }

                $seo->description = $updates['description'];
            }

            if (array_key_exists('title', $updates)) {
                if (ValidationService::validateString($updates['title'], 255) === false) {
                    throw new ValidationException('Naslov nije odgovarajućeg formata', 17004);
                }

                $seo->title = $updates['title'];
            }

            if (array_key_exists('url', $updates)) {
                if (ValidationService::validateString($updates['url'], 255) === false) {
                    throw new ValidationException('Url nije odgovarajućeg formata', 17005);
                }

                if (self::isUrlTaken($updates['url'])) {
                    throw new ValidationException('Url je zauzet', 17006);
                }

                $seo->url = $updates['url'];
            }

            if (array_key_exists('picture', $updates)) {
                if (!empty($seo->thumbnail_twitter)) {
                    self::deletePicture($seo->thumbnail_twitter);
                }

                if (!empty($seo->image_twitter)) {
                    self::deletePicture($seo->image_twitter);
                }

                if (!empty($seo->image_open_graph)) {
                    self::deletePicture($seo->image_open_graph);
                }

                $images                     =   self::createPicture($updates['picture']);
                $seo->thumbnail_twitter     =   $images['thumbnail_twitter'];
                $seo->image_twitter         =   $images['image_twitter'];
                $seo->image_open_graph      =   $images['image_open_graph'];
            }

            if (!empty($updates)) {
                self::$entity_manager->persist($seo);
                self::$entity_manager->flush();
            }
        }

        return $seo;
    }

    /**
     *
     * DELETE
     *
     */

    /**
     * Briše seo po mašinskom imenu
     * @param   string      $machine_name       Mašinsko ime
     * @return  void
     */
    public static function deleteByMachineName($machine_name) {
        if (self::checkPermission($machine_name, 'delete') === false) {
            throw new PermissionException('Nemate dozvolu za brisanje SEO-a', 17007);
        }

        $seo = self::getSEO($machine_name);
        if (empty($seo)) {
            throw new ValidationException('SEO nije pronađen', 17008);
        }

        self::deletePicture($seo->thumbnail_twitter);
        self::deletePicture($seo->image_twitter);
        self::deletePicture($seo->image_open_graph);

        self::$entity_manager->remove($seo);
        self::$entity_manager->flush();
    }

    /**
     * Briše sliku original i sve njene dimenzije
     * @param   string      $image_name     Ime slike
     * @return  void
     */
    private static function deletePicture($image_name) {
        ImageService::deletePictures($image_name, self::$folder_path_root);
    }
}
