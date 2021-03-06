<?php
  require_once Context::get('tpl_path') . 'methods.php';

  // xe context get
  $is_logged = Context::get('is_logged');
  $logged_info = Context::get('logged_info');

  $B2 = new stdClass();
  $B2->Route = Context::get('B2-route');
  $route = explode('.', Context::get('B2-route'));
  $B2->Module = $route[0];
  $B2->Action = $route[1];
  unset($route);

  $B2->RequestType = 'html';
  $B2->DisplayType = 'default'; // 스킨
  $B2->Device = 'PC'; // PC / Mobile

  // request type check
  if ($_GET['requestType'] === 'json') {
    $B2->RequestType = 'json';
  }

  // load development config
  if (file_exists(Context::get('tpl_path') . 'development.php')) {
    require_once Context::get('tpl_path') . 'development.php';
  }

  $module_info = Context::get('module_info');
  $grant = Context::get('grant');
  $page_navigation = Context::get('page_navigation');

  $ModuleModel = getModel('module');
  $additionalSetup = (object)[
    'document'  => $ModuleModel->getModulePartConfig('document', $module_info->module_srl),
    'comment'   => $ModuleModel->getModulePartConfig('comment', $module_info->module_srl)
  ];

  $B2->Using = (object)[
    'category'    => $module_info->use_category === 'Y',
    'vote'        => (object)[
      'documentUpvote'    => $additionalSetup->document->use_vote_up !== 'N',
      'documentDownvote'  => $additionalSetup->document->use_vote_down !== 'N',
      'commentUpvote'     => $additionalSetup->comment->use_vote_up !== 'N',
      'commentDownvote'   => $additionalSetup->comment->use_vote_down !== 'N',
      'cancel'            => $module_info->cancel_vote === 'Y',
      'guestAllow'        => $module_info->non_login_vote === 'Y'
    ],
    'feed'        => $module_info->open_rss === 'Y',
    'timeline'    => isset($timeline_info),
    'sticker'     => false,
    'anonymous'   => $module_info->use_anonymous === 'Y'
  ];

  $B2->Grant = $grant;

  $B2->Permissions = (object)[
    'access'          => $grant->access,
    'list'            => $grant->list,
    'view'            => $grant->view,
    'document'        => (object)[
      'write'           => $grant->write_document,
      'upvote'          => $additionalSetup->document->use_vote_up === 'Y' || 'S',
      'upvoteList'      => $additionalSetup->document->use_vote_up === 'S' && $grant->vote_log_view,
      'downvote'        => $additionalSetup->document->use_vote_down === 'Y' || 'S',
      'downvoteList'    => $additionalSetup->document->use_vote_down === 'S' && $grant->vote_log_view,
    ],
    'comment'         => (object)[
      'write'           => $grant->write_comment,
      'upvote'          => $additionalSetup->comment->use_vote_up === 'Y' || 'S',
      'upvoteList'      => $additionalSetup->comment->use_vote_up === 'S' && $grant->vote_log_view,
      'downvote'        => $additionalSetup->comment->use_vote_down === 'Y' || 'S',
      'downvoteList'    => $additionalSetup->comment->use_vote_down === 'S' && $grant->vote_log_view,
    ],
    'report'          => $is_logged,
    'editHistory'     => $grant->update_view,
    'privateDocument' => $grant->consultation_read,
    'moderator'       => $grant->is_admin
  ];

  $B2->Variables = (object)[
    'browserTitle'    => $module_info->browser_title,
    'categories'      => (object)[
      'list'              => [],
      'current'           => ''
    ],
    'list'            => (object)[
      'content'         => $module_info->list,
      'sort'            => (object)[
        'target'          => $module_info->order_target,
        'orderBy'         => $module_info->order_type
      ],
      'count'             => $page_navigation->total_count,
      'displayCount'      => $module_info->list_count
    ],
    'comment'         => (object)[ // 미작동
      'itemCount'         => $additionalSetup->comment->comment_count,
      'pageCount'         => $additionalSetup->comment->comment_page_count
    ],
    'pagination'      => (object)[
      'current'         => $page_navigation->cur_page,
      'lastPage'        => $page_navigation->total_page,
      'displayLength'   => $module_info->page_count,
    ],
    'extraVariables'  => [], // 미구현
  ];

  $B2->Config = (object)[
    'customContent'       => (object)[
      'Header' => $module_info->header_text,
      'Footer' => $module_info->footer_text
    ], // xml 파일과 설정 병합
    'usingSticker'        => false,
    'customDisplayTitle'  => '',
    'listUpvoteHighlight' => [
      'base'  => 'count-rate', // count, count-rate
      'count' => 6,
      'rate'  => 0.6
    ],
    'downvoteBlindCount'  => 5,
  ];

  $B2->Methods = new Methods();
  
  $B2->Session = new StdClass();
  $B2->Session->isLogin = $is_logged === true;

  if ($B2->Session->isLogin === true) {
    $B2->Session->groupList = $logged_info->group_list;
    $B2->Session->isAdmin = $logged_info->is_admin;
  } else {
    // permission rechecked
    if ($B2->using->vote->allowGuest === false) {
      $B2->Permissions->document->upvote = false;
      $B2->Permissions->document->downvote = false;
      $B2->Permissions->comment->upvote = false;
      $B2->Permissions->comment->downvote = false;
    }
  }

  Context::set('B2', $B2);
?>