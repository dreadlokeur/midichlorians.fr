<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelObject;
use framework\mvc\Router;
use framework\network\Http;

class CvObject extends Model implements IModelObject {

    protected $_id = null;
    protected $_name = null;
    protected $_link = null;
    protected $_thumb = 'no-thumb.png';

    public function __construct() {
        
    }

    public function setThumb($thumb) {
        if (!is_null($thumb))
            $this->_thumb = $thumb;

        return $this;
    }

    public function getThumb($withUrl = true) {
        if (!$withUrl)
            return $this->_thumb;

        return Router::getHost(true, Http::isHttps()) . str_replace(PATH_ROOT, '', CvManager::getDatasPath()) . $this->_thumb;
    }

}

?>
