<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\CommentService;
use App\Providers\PermissionService;
use App\Providers\SessionService;

/**
*
*/
class AdminComments extends BaseComponent {
    protected $js = [
        'AdminComments/js/AdminCommentsCreate.js',
        'AdminComments/js/AdminCommentsList.js',
        'AdminComments/js/AdminCommentsDelete.js'
    ];

    protected $css = [
        'AdminComments/css/AdminComments.css'
    ];

    public function renderHTML() {
        $args = [
            'permissions'   => [
                'comment_create'        => PermissionService::CheckPermission('comment_create'),
                'comment_read'          => PermissionService::CheckPermission('comment_read'),
                'comment_update_author' => PermissionService::CheckPermission('comment_update_author'),
                'comment_update'        => PermissionService::CheckPermission('comment_update'),
                'comment_delete'        => PermissionService::CheckPermission('comment_delete'),
            ],
            'csrf_field'    => SessionService::getCsrfField(),
        ];
        return
             view('AdminComments/templates/AdminComments', $args)
            . view('AdminComments/templates/AdminCommentsDelete', $args)
        ;
    }









    /*CREATE*/









    /*READ*/










    public function fetchData($params) {
        $date           = $params['date'];
        $direction      = boolval($params['direction']);
        $limit          = intval($params['limit']);
        $show_status    = boolval($params['show_status']);
        $search         = $params['search'];
        $type           = $params['type'];

        return CommentService::getAll($type, $date, $search, $direction, $limit, $show_status);
    }











    /*UPDATE*/








    public function changeCommentStatus($params) {
        $comment_id = $params["comment_id"];
        $status     = $params["status"];

        return CommentService::updateComment($comment_id, ["approved" => $status]);
    }



    /*DELETE*/

    public function deleteComment($params) {
        $comment_id = $params["comment_id"];

        return CommentService::deleteComment($comment_id);
    }
}
