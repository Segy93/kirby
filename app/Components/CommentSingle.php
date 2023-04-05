<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\CommentService;
use App\Providers\PermissionService;
use App\Providers\SessionService;
use App\Providers\UserService;

/**
*
*/
class CommentSingle extends BaseComponent {
    private $node_id = 0;
    protected $css = ['CommentSingle/css/CommentSingle.css'];
    protected $js = ['CommentSingle/js/CommentSingle.js', 'CommentSingle/js/timeago.min.js'];

    public function renderHTML($comment = null, $type = 'Product') {
        $replies = [];
        $this->type = $type;
        if ($comment !== null) {
            $this->node_id = $type === 'Product' ? $comment->product->artid : $comment->article->id;
            $replies    = CommentService::getAllReplies($comment->id, $type);
        }

        $args = [
            'comment'               => $comment,
            'node_id'               => $this->node_id,
            'is_logged'             => UserService::isUserLoggedIn(),
            'js_template'           => $comment === null,
            'comment_read'          => PermissionService::checkPermission('comment_read'),
            'comment_update'        => PermissionService::checkPermission('comment_update'),
            'csrf_field'            => SessionService::getCsrfField(),
            'type'                  => $type,
            'replies'               => $replies,
        ];

        if (isset($_SESSION['CommentsList']['error_comment'])) {
            $args['error_comment'] = $_SESSION['CommentsList']['error_comment'];
            unset($_SESSION['CommentsList']['error_comment']);
        }

        if (isset($_SESSION['CommentsList']['error_reply'])) {
            $args['error_reply'] = $_SESSION['CommentsList']['error_reply'];
            $args['error_reply_id'] = $_SESSION['CommentsList']['error_reply_id'];
            unset($_SESSION['CommentsList']['error_reply'], $_SESSION['CommentsList']['error_reply_id']);
        }

        return view('CommentSingle/templates/CommentSingle', $args);
    }









    /*CREATE*/










    public function postMessage($params) {
        $node_id    = intval($params['node_id']);
        $message    = $params['message'];
        $parent_id  = array_key_exists('parent_id', $params) ? intval($params['parent_id']) : null;
        $type       = $params['type'];
        $user_id    = UserService::getCurrentUserId();
        $comment = $type === 'Product' ?
            CommentService::createProductComment($user_id, $node_id, $message, $parent_id) :
            CommentService::createArticleComment($user_id, $node_id, $message, $parent_id) ;
        return $comment;
    }









    /*READ*/










    /*UPDATE*/

    public function changeCommentStatus($params) {
        $comment_id = $params['comment_id'];
        $status     = $params['status'];

        return CommentService::updateComment($comment_id, ['approved' => $status]);
    }
}
