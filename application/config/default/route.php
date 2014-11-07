<?php

$config = array(
    /*
     * 'routeName' => array(
     *      //controller name class  (case-insensitive), based on controllers namespace
     *      'controller' => 'index',
     *      //optionals
     *      //rules
     *      'rules' => array(
     *          'ruleName',
     *          'ruleName/([0-9a-zA-Z]+)/([a-z]+)/([0-9]+)'
     *      ),
     *      //methods into controller called... (possibility pass arguments)
     *      'methods' => array(
     *          'captcha' => array('[[1]]', '[[2]]', '[[3]]')
     *      ),
     *      'regex' => true, (true|false, check regex into rules default is false)
     *      'requireSsl' => false,  (true|false, default is false)
     *      'requireAjax' => false,  (true|false, default is false)
     *      'autoSetAjax' => true,  (true|false, turn on ajax controller, when request is ajax, optional default is true)
     *      'requireHttpMethod' => 'POST', (GET, HEAD, POST, PUT', DELETE, TRACE, OPTIONS, CONNECT, PATCH, optional default is null (all))
     *      'httpResponseStatusCode' => code (must be an integer, default is null),
     *      'httpProtocol' => protocol (must be a string, default is null)
     *      'security' => array('name', 'name') (Security names, must be a array, default is empty)
     * 
     *  ),
     */
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
    // route name => array(options)
    'github' => array(
        'rules' => array(
            'github'
        ),
        'controller' => 'index',
        'requireAjax' => true,
        'methods' => array(
            'github'
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
        ),
        'security' => array('csrf') // security names (optionnal)
    ),
    'login' => array(
        'rules' => array(
            'backoffice/login'
        ),
        'controller' => 'backoffice',
        'methods' => array(
            'login'
        ),
        'security' => array('csrf', 'flooder') // security names (optionnal)
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
        'security' => array('csrf') // security names (optionnal)
    ),
    //medias
    'media' => array(
        'rules' => array(
            'backoffice/media'
        ),
        'controller' => 'backoffice\media',
        'methods' => array(
            'all'
        ),
        'security' => array('csrf') // security names (optionnal)
    ),
    'mediaView' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/page/media/([a-zA-Z0-9_-]+)',
        ),
        'controller' => 'backoffice\media',
        'methods' => array(
            'view' => array('[[1]]')
        ),
        'security' => array('csrf') // security names (optionnal)
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
        'security' => array('csrf') // security names (optionnal)
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
        'security' => array('csrf') // security names (optionnal)
    ),
    'mediaUpdate' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/media/update/([a-zA-Z0-9_-]+)',
            'backoffice/media/update/([a-zA-Z0-9_-]+)/([0-9]+)'
        ),
        'controller' => 'backoffice\media',
        'methods' => array(
            'update' => array('[[1]]', '[[2]]')
        ),
        'requireAjax' => true,
        'requireHttpMethod' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
    //pages
    'page' => array(
        'rules' => array(
            'backoffice/page',
        ),
        'controller' => 'backoffice\page',
        'methods' => array(
            'all'
        ),
        'security' => array('csrf') // security names (optionnal)
    ),
    'pageView' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/page/view/([a-zA-Z0-9_-]+)',
        ),
        'controller' => 'backoffice\page',
        'methods' => array(
            'view' => array('[[1]]')
        ),
        'security' => array('csrf') // security names (optionnal)
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
        'security' => array('csrf') // security names (optionnal)
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
        'security' => array('csrf') // security names (optionnal)
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
        'security' => array('csrf') // security names (optionnal)
    ),
    //references
    'reference' => array(
        'rules' => array(
            'backoffice/reference',
        ),
        'controller' => 'backoffice\reference',
        'methods' => array(
            'all'
        ),
        'security' => array('csrf') // security names (optionnal)
    ),
    'referenceView' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/reference/view/([a-zA-Z0-9_-]+)',
        ),
        'controller' => 'backoffice\reference',
        'methods' => array(
            'view' => array('[[1]]')
        ),
        'security' => array('csrf') // security names (optionnal)
    ),
    'referenceAdd' => array(
        'rules' => array(
            'backoffice/reference/add'
        ),
        'controller' => 'backoffice\reference',
        'methods' => array(
            'add',
        ),
        'requireAjax' => true,
        'requireHttpMethod' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
    'referenceDelete' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/reference/delete/([a-zA-Z0-9]+)'
        ),
        'controller' => 'backoffice\reference',
        'methods' => array(
            'delete' => array('[[1]]')
        ),
        'requireAjax' => true,
        'requireHttpMethod' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
    'referenceUpdate' => array(
        'regex' => true,
        'rules' => array(
            'backoffice/reference/update/([a-zA-Z0-9_-]+)',
            'backoffice/reference/update/([a-zA-Z0-9_-]+)/([0-9]+)'
        ),
        'controller' => 'backoffice\reference',
        'methods' => array(
            'update' => array('[[1]]', '[[2]]')
        ),
        'requireAjax' => true,
        'requireHttpMethod' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
);
?>
