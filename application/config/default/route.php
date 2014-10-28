<?php

$config = array(
    // route name => array(options)
    'index' => array(
        'controller' => 'index',
    ),
    'captcha' => array(
        'regex' => true,
        'rules' => array(
            'captcha/([0-9a-zA-Z]+)/([a-z]+)',
            'captcha/([0-9a-zA-Z]+)/([a-z]+)/([0-9]+)'
        ),
        'controller' => 'index',
        'methods' => array(
            'captcha' => array('[[1]]', '[[2]]', '[[3]]')
        )
    ),
    'language' => array(
        'regex' => true,
        'rules' => array(
            'language/([A-Za-z0-9_]+)'
        ),
        'controller' => 'index',
        'methods' => array(
            'setAjax' => true,
            'language' => array('[[1]]')
        )
    ),
    'error' => array(
        'regex' => true,
        'rules' => array(
            'error/([0-9]+)'
        ),
        'controller' => 'error',
        'methods' => array(
            'show' => array('[[1]]')
        )
    ),
    'debugger' => array(
        'regex' => true,
        'rules' => array(
            'error/debugger/([a-z]+)'
        ),
        'controller' => 'error',
        'methods' => array(
            'debugger' => array('[[1]]')
        )
    ),
    //backoffice
    'backoffice' => array(
        'rules' => array(
            'backoffice'
        ),
        'controller' => 'backoffice',
        'methods' => array(
            'home'
        )
    ),
    'login' => array(
        'rules' => array(
            'backoffice/login'
        ),
        'controller' => 'backoffice',
        'methods' => array(
            'login'
        )
    ),
    'logout' => array(
        'rules' => array(
            'backoffice/logout'
        ),
        'controller' => 'backoffice',
        'methods' => array(
            'logout'
        ),
        'requireAjax' => true,
        'requireHttpMethod' => 'POST',
    ),
    //medias
    'media' => array(
        'rules' => array(
            'backoffice/media'
        ),
        'controller' => 'backoffice\media',
        'methods' => array(
            'all'
        )
    ),
    'mediaAdd' => array(
        'rules' => array(
            'backoffice/media/add'
        ),
        'controller' => 'backoffice\media',
        'methods' => array(
            'add',
        ),
        'requireAjax' => true,
        'requireHttpMethod' => 'POST',
    ),
    'mediaDelete' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/media/([a-zA-Z0-9]+)'
        ),
        'controller' => 'backoffice\media',
        'methods' => array(
            'delete' => array('[[1]]')
        ),
        'requireAjax' => true,
        'requireHttpMethod' => 'POST',
    ),
    'mediaUpdate' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/media/update/([a-zA-Z0-9]+)'
        ),
        'controller' => 'backoffice\media',
        'methods' => array(
            'update' => array('[[1]]')
        ),
        'requireAjax' => true,
        'requireHttpMethod' => 'POST',
    ),
    //pages
    'page' => array(
        'rules' => array(
            'backoffice/page',
        ),
        'controller' => 'backoffice\page',
        'methods' => array(
            'all'
        )
    ),
    'pageView' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/page/view/([a-zA-Z0-9_-]+)',
        ),
        'controller' => 'backoffice\page',
        'methods' => array(
            'view' => array('[[1]]')
        )
    ),
    'pageAdd' => array(
        'rules' => array(
            'backoffice/page/add'
        ),
        'controller' => 'backoffice\page',
        'methods' => array(
            'add',
        ),
        'requireAjax' => true,
        'requireHttpMethod' => 'POST',
    ),
    'pageDelete' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/page/delete/([a-zA-Z0-9_-]+)'
        ),
        'controller' => 'backoffice\page',
        'methods' => array(
            'delete' => array('[[1]]')
        ),
        'requireAjax' => true,
        'requireHttpMethod' => 'POST',
    ),
    'pageUpdate' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/page/update/([a-zA-Z0-9_-]+)',
            'backoffice/page/update/([a-zA-Z0-9_-]+)/([0-9]+)'
        ),
        'controller' => 'backoffice\page',
        'methods' => array(
            'update' => array('[[1]]', '[[2]]')
        ),
        'requireAjax' => true,
        'requireHttpMethod' => 'POST',
    ),
);
?>
