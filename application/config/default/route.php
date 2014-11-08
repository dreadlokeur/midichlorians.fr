<?php

$config = array(
    /*
     * 'routeName' => array(
     *      controller name class  (case-insensitive), based on controllers namespace, possible set a full namespace
     *      'controller' => 'index',
     * 
     *      Optionals
     *      'regex' => true, (true|false, check regex into rules default is true)
     *      'requireSsl' => false,  (true|false, default is false)
     *      'requireAjax' => false,  (true|false, default is false)
     *      'autoSetAjax' => true,  (true|false, turn on ajax controller, when request is ajax, optional default is true)
     *      'httpResponseStatusCode' => 200 (must be an integer, default is null),
     *      'httpProtocol' => protocol (must be a string, default is null)
     * 
     *      rules, must be a array or string (for one)n possibility regex, if is activate
     *      'rules' => array(
     *          'ruleName',
     *          'ruleName/([0-9a-zA-Z]+)/([a-z]+)/([0-9]+)'
     *      ),
     *      'rules' => 'ruleName',
     * 
     *      actions functions in controller, must be a array or string (for one)
     *      possibility pass arguments match into regex rules, format '[[1]]', '[[2]]' (number of match)
     *      'actions' => array(
     *          'function1' => array('[[1]]', '[[2]]', '[[3]]'),
     *          'function2',
     *      ),
     *      'actions' => 'function1',
     * 
     *      http methods require, must be a array or string (for one) (GET, HEAD, POST, PUT', DELETE, TRACE, OPTIONS, CONNECT, PATCH, optional default is null (all))
     *      'requireHttpMethods' => 'POST', 
     *      'requireHttpMethods' => array(
     *          'POST',
     *          'GET'
     *      ),
     * 
     *      security, must be a array or string (for one), default is empty
     *      'security' => array(
     *          'security1',
     *          'security2'
     *      ),
     *      'security' => 'name',
     * 
     *  ),
     */
    'index' => array(
        'controller' => 'index',
    ),
    'captcha' => array(
        'rules' => array(
            'captcha/([0-9a-zA-Z]+)/([a-z]+)',
            'captcha/([0-9a-zA-Z]+)/([a-z]+)/([0-9]+)'
        ),
        'controller' => 'index',
        'actions' => array(
            'captcha' => array('[[1]]', '[[2]]', '[[3]]')
        )
    ),
    'language' => array(
        'rules' => array(
            'language/([A-Za-z0-9_]+)'
        ),
        'controller' => 'index',
        'actions' => array(
            'language' => array('[[1]]')
        )
    ),
    'error' => array(
        'rules' => array(
            'error/([0-9]+)'
        ),
        'controller' => 'error',
        'actions' => array(
            'show' => array('[[1]]')
        )
    ),
    'debugger' => array(
        'rules' => array(
            'error/debugger/([a-z]+)'
        ),
        'controller' => 'error',
        'actions' => array(
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
        'actions' => array(
            'github'
        )
    ),
    //backoffice
    'backoffice' => array(
        'rules' => array(
            'backoffice'
        ),
        'controller' => 'backoffice',
        'actions' => array(
            'home'
        ),
        'security' => array('csrf') // security names (optionnal)
    ),
    'login' => array(
        'rules' => array(
            'backoffice/login'
        ),
        'controller' => 'backoffice',
        'actions' => 'test',
        'actions' => array(
            'login'
        ),
        'security' => array('csrf', 'flooder') // security names (optionnal)
    ),
    'logout' => array(
        'rules' => array(
            'backoffice/logout'
        ),
        'controller' => 'backoffice',
        'actions' => array(
            'logout'
        ),
        'requireAjax' => true,
        'requireHttpMethods' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
    //medias
    'media' => array(
        'rules' => array(
            'backoffice/media'
        ),
        'controller' => 'backoffice\media',
        'actions' => array(
            'all'
        ),
        'security' => array('csrf') // security names (optionnal)
    ),
    'mediaView' => array(
        'rules' => array(
            'backoffice/page/media/([a-zA-Z0-9_-]+)',
        ),
        'controller' => 'backoffice\media',
        'actions' => array(
            'view' => array('[[1]]')
        ),
        'security' => array('csrf') // security names (optionnal)
    ),
    'mediaAdd' => array(
        'rules' => array(
            'backoffice/media/add'
        ),
        'controller' => 'backoffice\media',
        'actions' => array(
            'add',
        ),
        'requireAjax' => true,
        'requireHttpMethods' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
    'mediaDelete' => array(
        'rules' => array(
            'backoffice/media/([a-zA-Z0-9]+)'
        ),
        'controller' => 'backoffice\media',
        'actions' => array(
            'delete' => array('[[1]]')
        ),
        'requireAjax' => true,
        'requireHttpMethods' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
    'mediaUpdate' => array(
        'rules' => array(
            'backoffice/media/update/([a-zA-Z0-9_-]+)',
            'backoffice/media/update/([a-zA-Z0-9_-]+)/([0-9]+)'
        ),
        'controller' => 'backoffice\media',
        'actions' => array(
            'update' => array('[[1]]', '[[2]]')
        ),
        'requireAjax' => true,
        'requireHttpMethods' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
    //pages
    'page' => array(
        'rules' => array(
            'backoffice/page',
        ),
        'controller' => 'backoffice\page',
        'actions' => array(
            'all'
        ),
        'security' => array('csrf') // security names (optionnal)
    ),
    'pageView' => array(
        'rules' => array(
            'backoffice/page/view/([a-zA-Z0-9_-]+)',
        ),
        'controller' => 'backoffice\page',
        'actions' => array(
            'view' => array('[[1]]')
        ),
        'security' => array('csrf') // security names (optionnal)
    ),
    'pageAdd' => array(
        'rules' => array(
            'backoffice/page/add'
        ),
        'controller' => 'backoffice\page',
        'actions' => array(
            'add',
        ),
        'requireAjax' => true,
        'requireHttpMethods' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
    'pageDelete' => array(
        'rules' => array(
            'backoffice/page/delete/([a-zA-Z0-9_-]+)'
        ),
        'controller' => 'backoffice\page',
        'actions' => array(
            'delete' => array('[[1]]')
        ),
        'requireAjax' => true,
        'requireHttpMethods' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
    'pageUpdate' => array(
        'rules' => array(
            'backoffice/page/update/([a-zA-Z0-9_-]+)',
            'backoffice/page/update/([a-zA-Z0-9_-]+)/([0-9]+)'
        ),
        'controller' => 'backoffice\page',
        'actions' => array(
            'update' => array('[[1]]', '[[2]]')
        ),
        'requireAjax' => true,
        'requireHttpMethods' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
    //references
    'reference' => array(
        'rules' => array(
            'backoffice/reference',
        ),
        'controller' => 'backoffice\reference',
        'actions' => array(
            'all'
        ),
        'security' => array('csrf') // security names (optionnal)
    ),
    'referenceView' => array(
        'rules' => array(
            'backoffice/reference/view/([a-zA-Z0-9_-]+)',
        ),
        'controller' => 'backoffice\reference',
        'actions' => array(
            'view' => array('[[1]]')
        ),
        'security' => array('csrf') // security names (optionnal)
    ),
    'referenceAdd' => array(
        'rules' => array(
            'backoffice/reference/add'
        ),
        'controller' => 'backoffice\reference',
        'actions' => array(
            'add',
        ),
        'requireAjax' => true,
        'requireHttpMethods' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
    'referenceDelete' => array(
        'rules' => array(
            'backoffice/reference/delete/([a-zA-Z0-9]+)'
        ),
        'controller' => 'backoffice\reference',
        'actions' => array(
            'delete' => array('[[1]]')
        ),
        'requireAjax' => true,
        'requireHttpMethods' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
    'referenceUpdate' => array(
        'rules' => array(
            'backoffice/reference/update/([a-zA-Z0-9_-]+)',
            'backoffice/reference/update/([a-zA-Z0-9_-]+)/([0-9]+)'
        ),
        'controller' => 'backoffice\reference',
        'actions' => array(
            'update' => array('[[1]]', '[[2]]')
        ),
        'requireAjax' => true,
        'requireHttpMethods' => 'POST',
        'security' => array('csrf') // security names (optionnal)
    ),
);
?>
