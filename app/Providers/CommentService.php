<?php

namespace App\Providers;

use App\Providers\UserService;
use App\Providers\ProductService;
use App\Providers\ValidationService;
use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;
use App\Models\Comments\CommentArticle;
use App\Models\Comments\CommentProduct;

class CommentService extends BaseService {

    //Limit koliko komentara dohvata
    private static $fetch_limit = 3;

    /**
     *
     * CREATE
     *
     */

    /**
     * Kreira komentar za članak
     * @param   int         $user_id        Id korisnika
     * @param   int         $article_id     Id članka
     * @param   string      $text           Tekst komentara
     * @return  bool        Vraća true ako je sve prošlo uredu
     */
    public static function createArticleComment($user_id, $article_id, $text, $parent_id = null) {
        return self::createComment($user_id, 'article', $article_id, $text, $parent_id);
    }

    /**
     * Kreira komentar za proizvod
     * @param   int         $user_id        Id korisnika
     * @param   int         $product_id     Id proizvoda
     * @param   string      $text           Tekst komentara
     * @return  bool        Vraća true ako je sve prošlo uredu
     */
    public static function createProductComment($user_id, $product_id, $text, $parent_id = null) {
        $qbp = self::$entity_manager->createQueryBuilder();
        $product = $qbp
            ->select('p')
            ->from('App\Models\Product', 'p')
            ->where('p.artid  = :node_id')
            ->setParameter('node_id', $product_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        $product_id = $product->id;

        return self::createComment($user_id, 'product', $product_id, $text, $parent_id);
    }

    /**
     * Kreira komentar
     * @param   int         $user_id        Id korisnika
     * @param   string      $type           Tip komentara
     * @param   int         $type_id        Id tipa komentara
     * @param   string      $text           Tekst komentara
     * @param   int         $parent_id      Id roditelja(odgovar na komentar već postojeći)
     * @return  bool        Vraća true ako je uspešno kreiran komentar
     */
    private static function createComment($user_id, $type, $type_id, $text, $parent_id = null) {
        $user = UserService::getUserById($user_id);

        if ($type === 'article') {
            $type_object = ArticleService::getByID($type_id);
        } elseif ($type === 'product') {
            $type_object = ProductService::getProductById($type_id);
        }

        if (ValidationService::validateString($text, 8000) === false) {
            throw new ValidationException('Tekst komentara nije odgovarajućeg formata', 8001);
        }

        $text = ValidationService::validateString($text, 8000, true);
        if ($type === 'article') {
            $comment = new CommentArticle();
        } elseif ($type === 'product') {
            $comment = new CommentProduct();
        }

        $comment->user       =   $user;
        $comment->{$type}    =   $type_object;
        $comment->text       =   $text;

        if (!empty($parent_id)) {
            if (self::checkCommentReply($parent_id) !== false) {
                throw new ValidationException('Ne može se odgovoriti na odgovor', 8002);
            }

            $comment->parent_id = $parent_id;
            self::commentAnswered($parent_id, $comment);
        }

        self::$entity_manager->persist($comment);
        self::$entity_manager->flush();

        return true;
    }

    /**
     *
     * READE
     *
     */

    /**
     * Pretraga komentara
     * @param   string      $type               Tip komentara koje pretražuje.
     * @param   date        $comment_date       Datum komentara od kog ide pretraga
     * @param   string      $search             Parametar po kom se ptražuju komentari
     * @param   boolean     $direction          Pravac pretrge manje od prosleđenog datuma ili veći
     * @param   int         $limit              Limit koliko dohvata komentara
     * @return  array       $comments           Vraća niz objekata komentara
     */
    public static function getAll(
        $type,
        $comment_date = null,
        $search = null,
        $direction = true,
        $limit = null,
        $approved = true,
        $node_id = 0
    ) {
        $current_user_id = UserService::getCurrentUserId();
        $permission = PermissionService::checkPermission('comment_read');
        $qb = self::$entity_manager->createQueryBuilder();
        $type_upper = strtoupper($type);
        $comment_type = $type_upper === 'ARTICLE'
            ? 'App\Models\Comments\CommentArticle'
            : 'App\Models\Comments\CommentProduct'
        ;

        if ($type === 'Product' && $node_id !== 0) {
            $qbp = self::$entity_manager->createQueryBuilder();
            $product = $qbp
                ->select('p')
                ->from('App\Models\Product', 'p')
                ->where('p.artid  = :node_id')
                ->setParameter('node_id', $node_id)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;

            $node_id = $product->id;
        }
        $comments = $qb
            ->select('c')
            ->from($comment_type, 'c')
            ->orderBy('c.date', 'DESC')
            ->setMaxResults($limit === null ? self::$fetch_limit : $limit)
        ;

        if ($permission === true && $approved === false) {
            $comments
                ->where('c.approved = false')
            ;
        } elseif ($permission === false) {
            $comments
                ->where('c.approved = true')
                ->orWhere('c.user_id = :current_user_id')
                ->setParameter('current_user_id', $current_user_id)
            ;
        }

        if (!empty($comment_date)) {
            $direction = $direction ? '<' : '>';

            $query = 'c.date ' . $direction . ' :comment_date';

            $comments
                ->andWhere($query)
                ->setParameter('comment_date', $comment_date)
            ;
        }

        if (!empty($search)) {
            $comments
                ->leftJoin('c.user', 'u')
                ->leftJoin('u.local', 'ul')
                ->orWhere('ul.username LIKE :search')
            ;

            if ($type === 'Article') {
                $comments
                    ->leftJoin('c.article', 'a')
                    ->orWhere('a.title LIKE :search')
                ;
            } else {
                $comments
                    ->leftJoin('c.product', 'p')
                    ->orWhere('p.name LIKE :search')
                ;
            }

            $comments
                ->setParameter('search', '%' . $search . '%')
            ;
        }
        if ($node_id !== 0 && $type === 'Product') {
            $comments
                ->andWhere('c.product_id = :node_id')
                ->setParameter('node_id', $node_id)
                ->andWhere('c.parent_id IS NULL')
            ;
        }

        if ($node_id !== 0 && $type === 'Article') {
            $comments
                ->andWhere('c.article_id = :node_id')
                ->setParameter('node_id', $node_id)
                ->andWhere('c.parent_id IS NULL')
            ;
        }

        //var_dump($comments->getQuery()->getSql());die;
        return $comments
            ->getQuery()
            ->getResult()
        ;
    }

    public static function getAll1($comment_id = null, $search = null, $direction = true, $limit = null) {
        return self::$entity_manager->getRepository('App\Models\Comments\Comment')->findAll();
    }

    public static function getAllReplies($comment_id, $type) {
        $qb = self::$entity_manager->createQueryBuilder();

        $type_upper = strtoupper($type);
        $comment_type = $type_upper === 'ARTICLE'
            ? 'App\Models\Comments\CommentArticle'
            : 'App\Models\Comments\CommentProduct'
        ;

        $comments = $qb
            ->select('c')
            ->from($comment_type, 'c')
            ->where('c.parent_id = :comment_id')
            ->setParameter('comment_id', $comment_id)
            ->getQuery()
            ->getResult()
        ;

        return $comments;
    }

    /**
     * Dohvata komentar po id-u
     * @param   int         $comment_id     Id komentara
     * @return  Comment     Vraća objekat komentara
     */
    public static function getCommentById($comment_id) {
        return self::$entity_manager->find('App\Models\Comments\Comment', $comment_id);
    }

    /**
     * Proverava dali postoji za taj komentar pod komentar
     * @param   int         $parent_id      Id komentara
     * @return  bool        Vraća true ako postoji u suprotnom vraća false
     */
    private static function checkCommentReply($comment_id) {
        return self::getCommentById($comment_id) === null;
    }

    private static function commentAnswered($parent_id, $answer) {
        $comment = self::getCommentById($parent_id);
        $user_email = $comment->user->email;
        $link       = "//" . $_SERVER['HTTP_HOST'];
        $link       .= get_class($answer) === "App\Models\Comments\CommentProduct"
            ? "/" . $answer->product->url
            : "/" . $answer->article->url
        ;

        if ($answer->approved) {
            EmailService::commentAnswered([
                'comment' => $comment,
                'answer' => $answer,
                'link' => $link
            ], $user_email);
            $public_key = config(php_uname('n') . '.PUSH_PUBLIC');
            $notification_data ['action_url'] = $link . '?tab=Komentari';
            NotificationService::sendCommentAnsweredNotification($public_key, $comment->user->id, $notification_data);
        }
    }

    /**
     *
     * UPDATE
     *
     */

    /**
     * Izmena komentara
     * @param   int         $comment_id     Id komentra
     * @param   array       $updates        Niz sa izemanama
     * @return  Comment     $comment        Vraća objekat komentara
     */
    public static function updateComment($comment_id, $updates) {
        $comment = self::getCommentById($comment_id);
        $permission = PermissionService::checkPermission('comment_update');

        if ($permission === false && $comment->user->id !== UserService::getCurrentUserId()) {
            throw new PermissionException('Nemate dozvolu za izmenu komentara', 8003);
        }

        if (array_key_exists('text', $updates)) {
            if (ValidationService::validateString($updates['text']) === false) {
                throw new ValidationException('Tekst komentara nije odgovarajućeg formata', 8004);
            }

            $comment->text = $updates['text'];
        }

        if (array_key_exists('approved', $updates)) {
            if ($permission === false) {
                throw new PermissionException('Nemate dozvolu za odobravanje komentara', 8005);
            }
            $comment->approved = ValidationService::validateBoolean($updates['approved']);

            if ($comment->parent_id !== null && $comment->approved) {
                $parent     = self::getCommentById($comment->parent_id);
                $user_email = $parent->user->email;
                $link       = "//" . $_SERVER['HTTP_HOST'];
                $link       .= get_class($comment) === "App\Models\Comments\CommentProduct"
                    ? "/" . $comment->product->url
                    : "/" . $comment->article->url
                ;

                EmailService::commentAnswered([
                    'comment' => $parent,
                    'answer' => $comment,
                    'link' => $link,
                ], $user_email);

                $public_key = config(php_uname('n') . '.PUSH_PUBLIC');
                $notification_data ['action_url'] = $link . '?tab=Komentari';
                NotificationService::sendCommentAnsweredNotification(
                    $public_key,
                    $comment->user->id,
                    $notification_data
                );
            }
        }

        if (!empty($updates)) {
            self::$entity_manager->persist($comment);
            self::$entity_manager->flush();
        }

        return $comment;
    }

    /**
     *
     * DELETE
     *
     */

    public static function deleteComment($comment_id) {
        $comment = self::getCommentById($comment_id);

        if (PermissionService::checkPermission('comment_delete') === false
            && $comment->user->id !== UserService::getCurrentUserId()
        ) {
            throw new PermissionException('Nemate dozvolu za izmenu komentara', 8006);
        }

        self::$entity_manager->remove($comment);
        self::$entity_manager->flush();
    }
}
