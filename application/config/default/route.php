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
    'backlink' => array(
        'rules' => array(
            'backoffice/backlink'
        ),
        'controller' => 'backoffice\backlink',
        'methods' => array(
            'setAjax' => false,
            'view'
        )
    ),
    'backlinkAdd' => array(
        'rules' => array(
            'backoffice/backlink/add'
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
            'backoffice/backlink/delete/([a-zA-Z0-9]+)'
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
            'backoffice/backlink/update/([a-zA-Z0-9]+)'
        ),
        'controller' => 'backoffice\backlink',
        'methods' => array(
            'setAjax' => true,
            'update' => array('[[1]]')
        )
    ),
    //prestations
    'prestation' => array(
        'rules' => array(
            'backoffice/prestation'
        ),
        'controller' => 'backoffice\prestation',
        'methods' => array(
            'setAjax' => false,
            'view'
        )
    ),
    'prestationAdd' => array(
        'rules' => array(
            'backoffice/prestation/add'
        ),
        'controller' => 'backoffice\prestation',
        'methods' => array(
            'setAjax' => true,
            'add',
        )
    ),
    'prestationDelete' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/prestation/delete/([a-zA-Z0-9]+)'
        ),
        'controller' => 'backoffice\prestation',
        'methods' => array(
            'setAjax' => true,
            'delete' => array('[[1]]')
        )
    ),
    'prestationUpdate' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/prestation/update/([a-zA-Z0-9]+)'
        ),
        'controller' => 'backoffice\prestation',
        'methods' => array(
            'setAjax' => true,
            'update' => array('[[1]]')
        )
    ),
);
?>
