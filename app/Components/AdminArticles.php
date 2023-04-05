<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\AdminService;
use App\Providers\ArticleCategoryService;
use App\Providers\ArticleService;
use App\Providers\FolderService;
use App\Providers\PermissionService;
use App\Providers\SessionService;
use App\Providers\TagService;

/**
 *
 * Administracija clanaka
 *
 */
class AdminArticles extends BaseComponent {

    protected $css = [
        'AdminArticles/css/AdminArticlesCreate.css',
        'AdminArticles/css/AdminArticlesTagChange.css',
        'AdminArticles/css/AdminArticlesChange.css',
        'libs/unpkg.css',
    ];

    protected $js = [
        'libs/unpkg.js',
        'AdminArticles/libs/tinymce/tinymce.min.js',
        'AdminArticles/js/AdminArticlesCreate.js',
        'AdminArticles/js/AdminArticlesList.js',
        'AdminArticles/js/AdminArticlesDelete.js',
        'AdminArticles/js/AdminArticlesChange.js',
        'AdminArticles/js/AdminArticlesTagChange.js',
    ];

    public function renderHTML() {
        $args = [
            'permissions'   => [
                'admin_read'            => PermissionService::CheckPermission('admin_read'),
                'article_create'        => PermissionService::CheckPermission('article_create'),
                'article_read'          => PermissionService::CheckPermission('article_read'),
                'article_update_author' => PermissionService::CheckPermission('article_update_author'),
                'article_update'        => PermissionService::CheckPermission('article_update'),
                'article_delete'        => PermissionService::CheckPermission('article_delete'),

            ],
            'categories'    => ArticleCategoryService::getAll(),
            'csrf_field'    => SessionService::getCsrfField(),
            'tags'          => TagService::getAll(),
            'current'       => AdminService::getCurrentAdminId(),
            'authors'       => AdminService::getAdmins(),
        ];

        return
            view('AdminArticles/templates/AdminArticlesCreate', $args)
            . view('AdminArticles/templates/AdminArticlesList', $args)
            . view('AdminArticles/templates/AdminArticlesDelete', $args)
            . view('AdminArticles/templates/AdminArticlesChange', $args)
            . view('AdminArticles/templates/AdminArticlesTagChange', $args)
        ;
    }










    /**
     *
     * Create
     *
     */










    /**
     * Kreiranje clanka
     * @param   string  $params['heading']  Naslov clanka
     * @param   file    $params['image']    Slika clanka
     * @param   int     $params['category'] ID kategorije clanka
     * @param   string  $params['date']     Datum kreiranja clanka
     * @param   string  $params['text']     Tekst clanka
     * @param   string  $params['excerpt']  Isecak clanka
     * @param   array   $params['tags']     Niz ID-jeva tagova za clanak
     * @param   int     $params['author']   Id autora
     * @return  array                       Kreirani clanak
     */
    public function createArticle($params) {
        $heading    =   $params['heading'];
        $image      =   $params['image'];
        $category   =   intval($params['category']);
        $date       =   new \DateTime();
        $date->modify($params['date']);
        $text       =   $params['text'];
        $excerpt    =   empty($params['excerpt']) ? null : $params['excerpt'];
        $tags       =   empty($params['tags']) ? [] : array_map('intval', $params['tags']);
        $author_id  = intval($params['author']) === 0 ? null : intval($params['author']);
        $article = ArticleService::create(
            $heading,
            $text,
            $category,
            $image,
            $date,
            $excerpt,
            $author_id
        );
        foreach ($tags as $tag) {
            ArticleService::setTag($article, $tag, true);
        }
        return $article;
    }
    public function createFolder($params) {
        $name       = $params['name'];
        $category   = $params['category'];

        FolderService::createFolder($name, $category);
    }











    /**
     *
     * Read
     *
     */










    /**
     * Dohvatanje pojedinacnog clanka
     * @param   int     $params['article_id']   ID clanka
     * @return  array                           Niz sa kljucevima:
     *                                          'article' => dohvaceni clanak
     */
    public function fetchArticle($params) {
        $article_id = intval($params['article_id']);
        return [
            'article' => ArticleService::getById($article_id),
        ];
    }

    /**
     * Dohvatanje postojecih clanaka
     * @param   string  $params['date']         Datum od kog pocinje dohvatanje
     * @param   boolean $params['direction']    Smer dohvatanja
     *                                          (true novi->stari; false stari->novi)
     * @param   int     $params['limit']        Koliko najvise clanaka dohvatamo
     * @param   int     $params['filter_tag']   Filtriranje clanaka po tagu
     * @param   int     $params['filter_category'] Filtriranje clanaka po kategoriji
     * @return  array                           Niz clanaka
     */
    public function fetchData($params) {
        $date               =   $params['date'];
        $direction          =   boolval($params['direction']);
        $limit              =   intval($params['limit']);
        $filter_tag         =   intval($params['filter_tag']);
        $filter_category    =   intval($params['filter_category']);

        return ArticleService::getAll(
            $date,
            $limit,
            $filter_category !== 0 ? $filter_category : null,
            $filter_tag !== 0 ? $filter_tag : null,
            $direction
        );
    }

    /**
     * Dohvatanje tagova za dati clanak
     * @param   int     $params['article_id']   ID clanka za koji dohvatamo tagove
     * @return array                            Niz sa kljucevima:
     *                                          tags: Niz tagova clanka
     */
    public function fetchUsedTags($params) {
        $article_id = intval($params['article_id']);
        return [
            'tags' => ArticleService::getArticleTags($article_id),
        ];
    }
    /**
     * Proverava da li postoji clanak sa datim nazivom
     * @param   string  $params['heading']  Ime koje proveravamo
     * @return  boolean                     Da li postoji ili ne
     */
    public function isHeadingTaken($params) {
        $title = $params['heading'];
        return ArticleService::isTitleTaken($title);
    }










    /**
     *
     * Update
     *
     */











    /**
     * Promena kategorije clanka
     * @param   int     $params['article_id']   ID clanka
     * @param   int     $params['category_id']  ID kategorije
     * @return  array                           Izmenjeni clanak
     */
    public function changeCategory($params) {
        $article_id  = intval($params['article_id']);
        $category_id = intval($params['category_id']);
        return ArticleService::Update($article_id, [
            'category_id' => $category_id
        ]);
    }

    /**
     * Promena datuma clanka
     * @param   int     $params['article_id']   ID clanka
     * @param   string  $params['date']         Datum clanka
     * @return  array                           Izmenjeni clanak
     */
    public function changeDate($params) {
        $article_id = intval($params['article_id']);
        $date       = $params['date'];

        return ArticleService::update(
            $article_id,
            [
                'date' => $date,
            ]
        );
    }

    /**
     * Promena slike clanka
     * @param   int     $params['article_id']   ID clanka
     * @param   file    $params['image']        Slika clanka
     * @return  array                           Izmenjeni clanak
     */
    public function changeImage($params) {
        $article_id = intval($params['article_id']);
        $image      = $params['image'];

        return ArticleService::update($article_id, [
            'picture' => $image
        ]);
    }

    /**
     * Promena statusa clanka
     * @param   int     $params['article_id']   ID clanka
     * @param   int     $params['status']       Status clanka
     * @return  array                           Izmenjeni clanak
     */
    public function changeStatus($params) {
        $article_id = intval($params['article_id']);
        $status     = $params['status'];
        return ArticleService::update($article_id, [
            'status' => $status
        ]);
    }

    /**
     * Promena taga clanka
     * @param   int     $params['article_id']   ID clanka
     * @param   int     $params['tag_id']       ID taga
     * @param   bool    $params['state']        Kacimo (true) ili sklanjamo (false) tag
     * @return  array                           Izmenjeni clanak
     */
    public function changeTag($params) {
        $article_id = intval($params['article_id']);
        $tag_id = intval($params['tag_id']);
        $state  = boolval($params['state']);

        return ArticleService::setTag($article_id, $tag_id, $state);
    }

    /**
     * Azuriranje clanka
     * @param   int     $params['article_id']   ID clanka
     * @param   string  $params['heading']      Naslov clanka
     * @param   string  $params['text']         Tekst clanka
     * @param   string  $param['text']          Isecak clanka
     * @param   int     $param['author_id']     Id autora clanka
     * @return  array                           Izmenjeni clanak
     */
    public function updateArticle($params) {
        $article_id = intval($params['article_id']);
        $heading    = $params['heading'];
        $text       = $params['text'];
        $excerpt    = $params['excerpt'];

        $updates = [
            'title'     => $heading,
            'text'      => $text,
            'excerpt'   => $excerpt,
        ];

        //Author_id moze da bude 0
        if (array_key_exists('author_id', $params)) {
            $author_id  = intval($params['author_id']) === 0 ? null : intval($params['author_id']);
            $updates['author_id'] = $author_id;
        }


        return ArticleService::Update($article_id, $updates);
    }

    public function updateFolder($params) {
        $old_name   = $params['old_name'];
        $new_name   = $params['new_name'];
        $category   = $params['category'];

        FolderService::updateFolder($old_name, $new_name, $category);
    }










    /**
     *
     * Delete
     *
     */










    /**
     * Brisanje clanka
     * @param   int     $params['article_id']   ID clanka
     * @return  bool                            Da li je uspesno obrisan
     */
    public function deleteArticle($params) {
        $article_id = intval($params["article_id"]);
        return ArticleService::delete($article_id);
    }
    public function deleteFolder($params) {
        $name       = $params['name'];
        $category   = $params['category'];
        FolderService::deleteFolder($name, $category);
    }
}
