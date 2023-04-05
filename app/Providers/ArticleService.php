<?php

namespace App\Providers;

use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;
use App\Providers\AdminService;
use App\Providers\ValidationService;
use App\Providers\PermissionService;
use App\Providers\ArticleCategoryService;
use App\Providers\FolderService;
use App\Providers\SEOService;
use App\Models\Articles\Article;
use App\Models\Articles\ArticleTag;

class ArticleService extends BaseService {

    //Limit koliko članaka dohvata
    private static $fetch_limit = 10;

    //Ime foldera
    private static $folder_path = 'uploads_gallery/Clanci';

    private static $folder_path_originals = 'originals';

    /**
     *
     * CREATE
     *
     */

    /**
     * Služi za kreiranje članaka
     * @param   string      $title          Naslov članka
     * @param   string      $text           Teks članka
     * @param   int         $category_id    Id kategorije
     * @param   string      $picture        Ime slike
     * @param   date        $date           Datum
     * @param   string      $excerpt        Isečak
     * @param   int         $author_id      Id autora
     * @return  Article     $article        Vraća objekat članka
     */
    public static function create(
        $title,
        $text,
        $category_id,
        $picture,
        $date = null,
        $excerpt = null,
        $author_id = null
    ) {

        if (PermissionService::checkPermission('article_create') === false) {
            throw new PermissionException('Nemate dozvolu za kreiranje članka', 4001);
        }

        $title = ValidationService::validateString($title, 127);
        if ($title === false) {
            throw new ValidationException('Naslov članka nije odgovarajućeg formata', 4002);
        }

        $text = ValidationService::validateHTML($text, false);
        if ($text === false) {
            throw new ValidationException('Tekst članka nije odgovarajućeg formata', 4003);
        }

        if (empty($excerpt)) {
            $excerpt = $text;
        } else {
            $excerpt = ValidationService::validateHTML($excerpt, false);
            if ($excerpt === false) {
                throw new ValidationException('Isečak nije odgovarajućeg formata', 4004);
            }
        }

        if (!empty($date)) {
            $date = ValidationService::validateDate($date);
            if ($date === false) {
                throw new ValidationException('Datum nije odgovarajućeg formata', 4005);
            }
        }

        $category = ArticleCategoryService::getByID($category_id);

        $article = new Article();
        if ($author_id !== null) {
            $admin   = AdminService::getAdminById($author_id);
        } else {
            $admin = null;
        }

        $article->title         =   $title;
        $article->text          =   $text;
        $article->category      =   $category;
        $article->picture       =   ImageService::uploadImage($picture, self::$static_originals);
        $article->excerpt       =   $excerpt;
        $article->author        =   $admin;
        if (!empty($date)) {
            $article->published_at = $date;
        }

        self::$entity_manager->persist($article);
        self::$entity_manager->flush();

        $full_path = self::$folder_path . '/' . $category->name . '/' . $article->title;
        FolderService::createFolder($full_path);

        return $article->id;
    }

    /**
     *
     * READ
     *
     */

    /**
     * Dohvata članke
     * @param   date        $date_start     Datum članka
     * @param   int         $limit          Limit koliko dohvata članaka
     * @param   int         $category_id    Id kategorije
     * @param   int         $tag_id         Id taga
     * @param   boolean     $direction      Pravac kretanja
     * @return  array       $articles       Vraća niz objekata članaka
     */
    private static function getArticles(
        $date_start = null,
        $limit = null,
        $category_id = null,
        $tag_id = null,
        $direction = true,
        $with_author = false,
        $author_id = null
    ) {
        $permission = PermissionService::checkPermission('article_read');

        $qb = self::$entity_manager->createQueryBuilder();
        $is_admin_loggedin = AdminService::isAdminLoggedIn();

        $articles = $qb
            ->select('a')
            ->from('App\Models\Articles\Article', 'a')
            ->setMaxResults($limit === null ? self::$fetch_limit : $limit)
            ->orderBy('a.published_at', $direction ? 'DESC' : 'ASC')
            ->addOrderBy('a.id', $direction ? 'DESC' : 'ASC');

        ;

        if (!$is_admin_loggedin) {
            $articles
                ->where('a.status = :status')
                ->setParameter('status', Article::getStatusValues('published'))
            ;
        }

        if (!empty($date_start)) {
            if ($direction) {
                $articles->andWhere('a.published_at < :date_start');
            } else {
                $articles->andWhere('a.published_at > :date_start');
            }
            $articles->setParameter('date_start', $date_start);
        }

        if ($category_id !== null) {
            $articles
                ->andWhere('a.category_id = :category_id')
                ->setParameter('category_id', $category_id)
            ;
        }

        if ($tag_id !== null) {
            $articles
                ->join('a.tags', 't')
                ->andWhere('t.id = :tag_id')
                ->setParameter('tag_id', $tag_id)
            ;
        }
        if ($author_id !== null) {
            $articles
                ->andWhere('a.author_id = :author_id')
                ->setParameter('author_id', $author_id)
            ;
        }

        $results = $articles->getQuery()->getResult();

        if ($direction === false) {
            $results = array_reverse($results);
        }

        return $results;
    }

    /**
     * Vraća kolekciju članaka
     * @param   datetime     $article_date      Datum od koga ide pretraga
     * @param   int          $limit             Limit koliko podataka dohvata
     * @param   boolean      $direction         Smer u kome pretraga
     * @return  array        $articles          Vraća niz objekata članaka
     */
    public static function getAll(
        $article_date = null,
        $limit = null,
        $category_id = null,
        $tag_id = null,
        $direction = true
    ) {
        return self::getArticles(
            $article_date,
            $limit,
            $category_id,
            $tag_id,
            $direction
        );
    }

    /**
     * Vraća članak po id-u
     * @param   int             $article_id     Id članka
     * @return  Article/int     Vraća članak ako je sve poršlo uredu inače vraća error_code
     */
    public static function getByID($article_id) {
        $article_id = ValidationService::validateInteger(
            $article_id,
            ValidationService::$RANGE_INTEGER_UNSIGNED['min'],
            ValidationService::$RANGE_INTEGER_UNSIGNED['max']
        );

        if ($article_id === false) {
            throw new ValidationException('Id članka nije odgovarajućeg formata', 4006);
        }

        $permission = PermissionService::checkPermission('article_read');

        $article = self::$entity_manager->find('App\Models\Articles\Article', $article_id);

        if ($article->draft && $permission === false ||
            $article->published_at > new \DateTime() && $permission === false
        ) {
            throw new PermissionException('Nemate dozvolu za dohvatanje članka', 4007);
        }

        return $article;
    }

    /**
     * Vraća članke po kategoiji
     * @param   int             $category_id    Id kategorije
     * @return  array           Vraća niz objekata članaka
     */
    public static function getByCategoryID(
        $category_id,
        $article_date = null,
        $limit = 10,
        $direction = true,
        $with_author = false
    ) {
        return self::getArticles(
            $article_date,
            $limit,
            $category_id,
            null,
            $direction,
            $with_author
        );
    }

    /**
     * Dohvata članke po tagu
     * @param   date            $article_date       Datum članka
     * @param   int             $limit              Limit koliko članaka dohvata
     * @param   int             $tag_id             Id tag-a
     * @param   bool            $direction          Pravac kretanja(manje ili veće od datuma parametra)
     * @param   bool            $with_author        Da li nadovezati ceo admin model da bi se ime iskoristilo
     * @return  array           Vra'a niz objekata članaka
     */
    public static function getByTagID($article_date, $limit, $tag_id, $direction, $with_author = false) {
        return self::getArticles(
            $article_date,
            $limit,
            null,
            $tag_id,
            $direction,
            $with_author
        );
    }

    public static function getByAuthorID($article_date, $limit, $author_id, $direction, $with_author = false) {
        return self::getArticles(
            $article_date,
            $limit,
            null,
            null,
            $direction,
            $with_author,
            $author_id
        );
    }

    /**
     * Dohvata članke po pregledima u zadnjih mesec dana
     * @param   int             $limit  Limit koliko članaka dohvata
     * @return  Collection/int  Vraća kolekciju komentara ako je sve prošlo uredu inače vraća error_code
     */
    public static function getByViews($limit = null) {
        $permission = PermissionService::checkPermission('article_read');

        $date_start = new \DateTime();
        $interval = new \DateInterval('P1M');
        $date_start->sub($interval);

        $date_end = new \DateTime();

        $qb = self::$entity_manager->createQueryBuilder();

        $articles = $qb
            ->select('a')
            ->from('App\Models\Articles\Article', 'a')
            ->where('a.published_at > :date_start')
            ->setParameter('date_start', $date_start)
            ->andWhere('a.published_at < :date_end')
            ->setParameter('date_end', $date_end)
            ->orderBy('a.views', 'DESC')
        ;

        if ($permission === false) {
            $articles
                ->andWhere('a.status = 0')
            ;
        }

        if (!empty($limit)) {
            $articles
                ->setMaxResults($limit)
            ;
        }

        return $articles->getQuery()->getResult();
    }

    /**
     * Vraća sve tagove vezane za članak
     * @param   int         $article_id     Id članka
     * @return  array       Tagovi članaka
     */
    public static function getArticleTags($article_id) {
        $qb = self::$entity_manager->createQueryBuilder();

        $tags = $qb
            ->select('t')
            ->from('App\Models\Articles\Tag', 't')
            ->join('t.articles', 'a')
            ->where('a.id = :article_id')
            ->setParameter('article_id', $article_id)
            ->getQuery()
            ->getResult()
        ;
        return $tags;
    }

    /**
     * Dohvata najnovijih x clanaka
     * @param   integer     $limit          Koliko maksimalno clanaka da se dohvati
     * @param   string      $end_date       Datum najstarijeg dohvacenog
     * @return  COllection                  Kolekcija clanaka
     */
    public static function getNew($limit = 6, $end_date = null) {
        return self::getArticles(
            null,
            $limit,
            null,
            null,
            true
        );
    }

    /**
     * Dohvatra članke slične po tagu trenutnog
     * @param   int             $article_id     Id članka
     * @return  Collection
     */
    public static function getRecommendedArticles($article_id, $limit = 6) {
        $permission = PermissionService::checkPermission('article_read');

        $article_tags = self::getArticleTags($article_id);

        $tag_ids = [];
        if (!empty($article_tags)) {
            foreach ($article_tags as $tag) {
                $tag_ids[] = $tag->id;
            }
        }

        $qb = self::$entity_manager->createQueryBuilder();

        $articles = $qb
            ->select('a', 'COUNT(t.id) as tags_nr')
            ->from('App\Models\Articles\Article', 'a')
            ->join('a.tags', 't')
            ->where('a.id != :id')
            ->setParameter('id', $article_id)
            ->andWhere('t.id IN (:tag_ids)')
            ->setParameter('tag_ids', $tag_ids)
            ->groupBy('a.id')
            ->orderBy('tags_nr', 'DESC')
        ;

        return $articles->getQuery()->getResult();
    }

    /**
     * Proverava dali je naslov zauzet
     * @param   string      $title  Naslov članka
     * @return  boolean     Vraća true ako jeste ili false ako nije
     */
    public static function isTitleTaken($title) {
        $qb = self::$entity_manager->createQueryBuilder();

        $article = $qb
            ->select('a')
            ->from('App\Models\Articles\Article', 'a')
            ->where('a.title = :title')
            ->setParameter('title', ValidationService::validateString($title, 127))
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult()
        ;

        return !empty($article);
    }


    /**
     *
     * UPDATE
     *
     */

    /**
     * Radi izmeni članka
     * @param   int         $article_id     Id članka na kom radite izemene
     * @param   array       $updates        Niz sa izmenama
     * @return  Article     $article        Vraća članak ako je sve prošlo uredu inače vraća error_code
     */
    public static function update($article_id, $updates) {
        if (PermissionService::checkPermission('article_update') === false) {
            throw new PermissionException('Nemate dozvolu za izmenu članka', 4009);
        }

        $article = self::getById($article_id);

        if (array_key_exists('category_id', $updates)) {
            $category = ArticleCategoryService::getByID($updates['category_id']);
            if (empty($category)) {
                throw new ValidationException('Kategorija nije pronađena', 4010);
            }

            $path_old = self::$folder_path . '/' . $article->category->name . '/' . $article->title;
            $path_new = self::$folder_path . '/' . $category->name . '/' . $article->title;

            $article->category = $category;

            FolderService::updateFolder($path_old, $path_new);
            self::updateImagePath($path_old, $path_new);
        }

        if (array_key_exists('author_id', $updates)) {
            if (PermissionService::checkPermission('article_update_author')) {
                throw new PermissionException('Nemate dozvolu za izmenu autora', 4011);
            }

            $author = AdminService::getAdminById($updates['author_id']);
            if (empty($author)) {
                throw new ValidationException('Autor nije pronađen', 4012);
            }

            $article->author = $author;
        }

        if (array_key_exists('title', $updates)) {
            $updates['title'] = ValidationService::validateString($updates['title'], 127);
            if ($updates['title'] === false) {
                throw new ValidationException('Naslov nije odgovarajućeg formata', 4013);
            }

            $path_old = self::$folder_path . '/' . $article->category->name . '/' . $article->title;
            $path_new = self::$folder_path . '/' . $article->category->name . '/' . $updates['title'];

            $article->title = $updates['title'];

            FolderService::updateFolder($path_old, $path_new);
            self::updateImagePath($path_old, $path_new);
        }

        if (array_key_exists('text', $updates)) {
            $updates['text'] = ValidationService::validateHTML($updates['text'], false);
            if ($updates['text'] === false) {
                throw new ValidationException('Tekst nije odgovarajućeg formata', 4014);
            }

            $article->text = $updates['text'];
        }

        if (array_key_exists('excerpt', $updates)) {
            $updates['excerpt'] = ValidationService::validateHTML($updates['excerpt'], false);
            if ($updates['excerpt'] === false) {
                throw new ValidationException('Isečak nije odgovarajućeg formata', 4015);
            }

            $article->excerpt = $updates['excerpt'];
        }

        if (array_key_exists('picture', $updates)) {
            if (!empty($article->picture)) {
                ImageService::deletePictures($article->picture, self::$static_folder);
            }

            $image_path = self::$static_folder . '/' . self::$folder_path_originals;
            $picture = ImageService::uploadImage($updates['picture'], $image_path);

            $article->picture = ImageService::getImageName($image_path . '/' . $picture);
        }

        if (array_key_exists('date', $updates)) {
            $updates['date'] = ValidationService::validateDate($updates['date']);
            if ($updates['date'] === false) {
                throw new ValidationException('Datum nije odgovarajućeg formata', 4016);
            }
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $updates['date']);
            $article->published_at = $date;
        }

        if (array_key_exists('status', $updates)) {
            $updates['status'] = ValidationService::validateInteger(
                $updates['status'],
                ValidationService::$RANGE_SMALLINTEGER_UNSIGNED['min'],
                ValidationService::$RANGE_SMALLINTEGER_UNSIGNED['max']
            );
            if ($updates['status'] === false) {
                throw new ValidationException('Status nije odgovarajućeg formata', 4017);
            }

            $article->status = $updates['status'];
        }

        if (array_key_exists('views', $updates)) {
            $updates['views'] = ValidationService::validateInteger(
                $updates['views'],
                ValidationService::$RANGE_SMALLINTEGER_UNSIGNED['min'],
                ValidationService::$RANGE_SMALLINTEGER_UNSIGNED['max']
            );
            if ($updates['views'] === false) {
                throw new ValidationException('views nije odgovarajućeg formata', 4017);
            }

            $article->views = $updates['views'];
        }

        if (!empty($updates)) {
            self::$entity_manager->persist($article);
            self::$entity_manager->flush();
        }

        return $article->id;
    }

    /**
     * Radi izmenu putanja slika u tekstu članka ili odsečku
     * @param   string      $old_path       Stara putanja
     * @param   string      $new_path       Nova putanja
     * @return  void
     */
    public static function updateImagePath($path_old, $path_new) {
        if ($path_old !== $path_new) {
            $qb = self::$entity_manager->createQueryBuilder();

            $articles = $qb
                ->select('a')
                ->from('App\Models\Articles\Article', 'a')
                ->where('a.text LIKE :path_search')
                ->setParameter('path_search', '%' . $path_old . '%')
                ->getQuery()->getResult()
            ;

            foreach ($articles as $article) {
                $article->text = str_replace(
                    $path_old,
                    $path_new,
                    $article->text
                );

                self::$entity_manager->persist($article);
                self::$entity_manager->flush();
            }
        }
    }

     /**
     * Postavlja vezu između articla i taga
     * @param   int         $article_id     Id članka
     * @param   int         $tag_id         Id taga
     * @param   bool        $state          Stanje
     * @return  Void/int    Ništa ako je sve prošlo uredu inače vraća error_code
     */
    public static function setTag($article_id, $tag_id, $state) {
        if (PermissionService::checkPermission('article_update') === false) {
            throw new PermissionException('Nemate dozvolu za postavljanje taga', 4018);
        }

        if ($state === true) {
            $at = new ArticleTag();

            $at->article_id = $article_id;
            $at->tag_id = $tag_id;

            self::$entity_manager->persist($at);
            self::$entity_manager->flush();
        } else {
            $qb = self::$entity_manager->createQueryBuilder();

            $qb
                ->delete('App\Models\Articles\ArticleTag', 'at')
                ->where('at.article_id = :article_id')
                ->setParameter('article_id', $article_id)
                ->andWhere('at.tag_id = :tag_id')
                ->setParameter('tag_id', $tag_id)
                ->getQuery()
                ->getResult()
            ;
        }

        return true;
    }

    /**
     * Premešta slike članka
     * @param   string      $folder_origin      Folder iz kog premešta
     * @return  void
     */
    private static function movePictures($folder_origin) {
        $articles = self::$entity_manager->createQueryBuilder()
            ->select('a')
            ->from('App\Models\Articles\Article', 'a')
            ->where('a.text LIKE :path_search')
            ->setParameter('path_search', '%' . $folder_origin . '%')
            ->getQuery()->getResult()
        ;

        if (!empty($articles)) {
            $path = self::$folder_path;

            foreach ($articles as $article) {
                $folder_destination = $path . '/' . $article->category->name . '/' . $article->title;

                FolderService::moveFolderContent($folder_origin, $folder_destination);

                self::updateImagePath($folder_origin, $folder_destination);
            }
        }
    }

    /**
     *
     * DELETE
     *
     */

    /**
     * Briše članak
     * @param   int     $article_id     Id članka koji se briše
     * @return  bool    Vraća true ako je sve prošlo uredu
     */
    public static function delete($article_id) {
        if (PermissionService::checkPermission('article_delete') === false) {
            throw new PermissionException('Nemate dozvolu za brisanje članka', 4019);
        }

        $article    = self::getById($article_id);
        $full_path  = self::$folder_path . '/' . $article->category->name . '/' . $article->title;
        $picture    = $article->picture;

        self::$entity_manager->remove($article);
        self::$entity_manager->flush();

        SEOService::deleteByMachineName('article_' . $article_id);
        self::movePictures($full_path);
        FolderService::deleteFolder($full_path);
        ImageService::deletePictures($picture, self::$static_folder);

        return true;
    }
}
