<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelObject;
use framework\mvc\Router;
use framework\network\Http;

class ImageObject extends Model implements IModelObject {

    protected $_name = null;
    protected $_file = 'no-thumb.png';

    public function __construct() {
        
    }

    public function setFile($file) {
        if (!is_null($file))
            $this->_file = $file;

        return $this;
    }

    public function getFile($withUrl = true) {
        if (!$withUrl)
            return $this->_file;

        return Router::getHost(true, Http::isHttps()) . str_replace(PATH_ROOT, '', ImageManager::getDatasPath()) . $this->_file;
    }

}

?>
