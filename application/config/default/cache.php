<?php

$config = array(
    'core' => array(
        'adaptater' => 'file', // class name (must be implement \framework\cache\IAdaptater)
        'prefix' => '_', // prefix string
        'path' => '[PATH_CACHE_CORE]',
        'gc' => 'time', // Garbage collection : time/number => toutes les x secondes, ou toutes les x requests
        'gcOption' => 86400, // seconds/request
        'groups' => 'autoloader,logger,security', // group list separated by ","
        'allowReplace' => true, //(true|false, overload route possibility default is true)
    ),
    'default' => array(
        'adaptater' => 'file', // class name (must be implement \framework\cache\IAdaptater)
        'prefix' => '_',
        'path' => '[PATH_CACHE_DEFAULT]',
        'gc' => 'time',
        'gcOption' => 86400,
        'groups' => 'template' // group list separated by ","
    ),
    'bdd' => array(
        'adaptater' => 'file', //file/apc
        'prefix' => '_', // prefix string
        'path' => '[PATH_CACHE_BDD]',
        'gc' => 'time', // Garbage collection : time/number => toutes les x secondes, ou toutes les x requests
        'gcOption' => 86400, // seconds/request
    ),
);
?>
