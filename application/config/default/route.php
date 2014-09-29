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
            'setAjax' => false,
            'home'
        )
    ),
    'login' => array(
        'rules' => array(
            'login'
        ),
        'controller' => 'backoffice',
        'methods' => array(
            'setAjax' => false,
            'login'
        )
    ),
    'logout' => array(
        'rules' => array(
            'logout'
        ),
        'controller' => 'backoffice',
        'methods' => array(
            'setAjax' => true,
            'logout'
        )
    ),
    'upload' => array(
        'rules' => array(
            'upload'
        ),
        'controller' => 'backoffice',
        'methods' => array(
            'upload'
        )
    ),
    //backlinks
    'backlinkView' => array(
        'rules' => array(
            'backoffice/backlinkView',
            'backoffice/backlinkView'
        ),
        'controller' => 'backoffice\backlink',
        'methods' => array(
            'setAjax' => false,
            'view'
        )
    ),
    'backlinkAdd' => array(
        'rules' => array(
            'backoffice/backlinkAdd'
        ),
        'controller' => 'backoffice\backlink',
        'methods' => array(
            'setAjax' => true,
            'add',
        )
    ),
    'backlinkDelete' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/backlinkDelete/([a-zA-Z0-9]+)'
        ),
        'controller' => 'backoffice\backlink',
        'methods' => array(
            'setAjax' => true,
            'delete' => array('[[1]]')
        )
    ),
    'backlinkUpdate' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/backlinkUpdate/([a-zA-Z0-9]+)'
        ),
        'controller' => 'backoffice\backlink',
        'methods' => array(
            'setAjax' => true,
            'update' => array('[[1]]')
        )
    ),
);
?>
