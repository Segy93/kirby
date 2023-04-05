<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\CommentService;
use App\Providers\SessionService;
use App\Providers\UserService;

/**
*
*/
class CommentsList extends BaseComponent {
    private $node_id      = 0;
    private $comment_single = null;
    private $limit          = 20;
    private $discr          = 'product';
    protected $composite = true;

    protected $css = ['CommentsList/css/CommentsList.css'];
    protected $js = [
        'CommentsList/js/CommentsList.js',
        'libs/underscore-min.js'
    ];
    protected $icons = ['CommentsList/templates/icons'];

    protected $js_config = [];


    public function __construct($node_id = null, $comment_single = null, $discr = 'Product') {
        if ($comment_single !== null) {
            parent::__construct([$comment_single]);
        }

        $this->node_id              = $node_id;
        $this->js_config['node_id'] = $node_id;
        $this->comment_single       = $comment_single;
        $this->discr                = $discr;
    }

    public function renderHTML() {
        $comments = CommentService::getAll($this->discr, null, false, false, $this->limit, true, $this->node_id);
        $more = count($comments) >= $this->limit + 1;

        $args = [
            'comments'              => $comments,
            'node_id'               => $this->node_id,
            'comment_single'        => $this->comment_single,
            'comment_permission'    => UserService::isUserLoggedIn(),
            'more'                  => $more,
            'csrf_field'            => SessionService::getCsrfField(),
            'site_key'              => config(php_uname('n') . '.GOOGLE_SITE_KEY'),
            'type'                  => $this->discr,
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

        return view('CommentsList/templates/CommentsList', $args);
    }










    public function postMessage($params) {
        $node_id    = intval($params['node_id']);
        $message    = $params['message'];
        $parent_id  = array_key_exists('parent_id', $params) ? intval($params['parent_id']) : null;
        $user_id    = UserService::getCurrentUserId();
        $type       = $params['type'];
        if ($type === 'Product') {
            return CommentService::createProductComment($user_id, $node_id, $message, $parent_id);
        } else {
            return CommentService::createArticleComment($user_id, $node_id, $message, $parent_id);
        }
    }

    public function fetchData($params) {
        $node_id    = intval($params['node_id']);
        $limit      = intval($params['limit']);
        $type       = $params['type'];
        return CommentService::getAll($type, null, false, false, $limit, true, $node_id);
    }
}
