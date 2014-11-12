<?php

/**
 * Include necessary files, and setting class autoloader
 *
 * @copyright  Copyright 2014 - MidichlorianPHP and contributors
 * @author     NAYRAND Jérémie (dreadlokeur) <dreadlokeur@gmail.com>
 * @version    1.0.1dev2
 * @license    GNU General Public License 3 http://www.gnu.org/licenses/gpl.html
 * @package    MidichloriansPHP
 */
use MidiChloriansPHP\Autoloader;

// Checking
if (!version_compare(PHP_VERSION, '5.4.0', '>='))
    throw new \Exception('You must have at least PHP 5.4.0');

// Include neccesary files
require_once 'paths.php';

// Composer autoloader
require_once PATH_VENDOR . 'autoload.php';

// Autoloader configuration
$autoloader = new Autoloader();
$autoloader->setAutoloadExtensions(array(
    'class.php',
    'abstract.php',
    'final.php',
    'interface.php',
    'trait.php',
    'php'
));
$autoloader->addNamespaces(array(
    'MidiChloriansPHP' => PATH_MIDICHLORIANSPHP,
    'libs' => PATH_LIBS,
    'controllers' => PATH_CONTROLLERS,
    'models' => PATH_MODELS,
));

// Include autoloaders adaptaters
$autoloader->registerAutoloaders(array(
    'Includer' => array('prepend' => true),
    'Cache' => array('prepend' => true),
    'Finder' => array('prepend' => true),
));
?>
