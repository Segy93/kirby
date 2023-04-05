<?php

namespace App\Components;


use App\Components\BaseComponent;
use App\Providers\PermissionService;
use App\Providers\SessionService;
use App\Providers\TagService;



class AdminTags extends BaseComponent {


    protected $js = [];
    protected $css = [
        'AdminTags/css/AdminTags.css',
    ];

    public function renderHTML() {
        $args = [
            'permissions' => [
                'tag_create' => PermissionService::checkPermission('tag_create'),
                'tag_read'   => PermissionService::checkPermission('tag_read'),
                'tag_update' => PermissionService::checkPermission('tag_update'),
                'tag_delete' => PermissionService::checkPermission('tag_delete'),
            ],
            'csrf_field'    => SessionService::getCsrfField(),
        ];
        return
            view('AdminTags/templates/AdminTagsCreate', $args)
            . view('AdminTags/templates/AdminTagsList', $args)
            . view('AdminTags/templates/AdminTagsChange', $args)
            . view('AdminTags/templates/AdminTagsDelete', $args)
        ;
    }
    public function __construct() {
        if (PermissionService::checkPermission('tag_update')) {
            $this->js[] = 'AdminTags/js/AdminTagsChange.js';
        }

        if (PermissionService::checkPermission('tag_delete')) {
            $this->js[] = 'AdminTags/js/AdminTagsDelete.js';
        }

        if (PermissionService::checkPermission('tag_create')) {
            $this->js[] = 'AdminTags/js/AdminTagsCreate.js';
        }

        if (PermissionService::checkPermission('tag_read')) {
            $this->js[] = 'AdminTags/js/AdminTagsList.js';
        }
    }









    /**
     * Pravi novu znacku
     * @param   string  $params['name']     Ime taga
     * @return  int                         error_code prilikom izvrsavanja funkcije
     */
    public function createTag($params) {
        $name = $params['name'];

        return TagService::create($name);
    }











    /**
     * Dohvata podatke neophodne za funkcionisanje komponenti
     * @return array   Vraca niz koji ima kljuc tags, koji je onda niz tagova
     */
    public function fetchData() {
        return [
            'tags'  => TagService::getAll(),
        ];
    }

    /**
     * Proverava da li postoji tag sa datim  imenom
     * @param   string      $params['name'] Ime koje proveravamo
     * @return  boolean                         Da li postoji ili ne
     */
    public function isNameTaken($params) {
        $name = $params['name'];
        return TagService::isNameTaken($name);
    }

    /**
     * Vraca tag koji se trazi
     * @param  string $params['tag_id'] ID taga cije podatke dobijamo
     * @return Boolean         vraca da li postoji ili ne
     */
    public function fetchTag($params) {
        $tag_id = intval($params['tag_id']);

        return TagService::getByID($tag_id);
    }











    public function updateName($params) {
        $tag_id = intval($params['tag_id']);
        $name   = $params['name'];

        return TagService::update($tag_id, [
            'name' => $name,
        ]);
    }











    public function deleteTag($params) {
        $tag_id = intval($params['tag_id']);

        return TagService::delete($tag_id);
    }
}
