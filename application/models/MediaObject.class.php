<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelObject;
use framework\mvc\Router;
use framework\network\Http;

class MediaObject extends Model implements IModelObject {

    CONST TYPE_IMAGE = 'image';
    CONST TYPE_AUDIO = 'audio';
    CONST TYPE_VIDEO = 'video';

    protected $_id = null;
    protected $_filename = null;
    protected $_type = self::TYPE_IMAGE;
    protected $_mime = null;
    protected $_title = null;
    protected $_alt = null;

    public function __construct() {
        
    }

    public function setType($type) {
        if ($type != self::TYPE_AUDIO && $type != self::TYPE_IMAGE && $type != self::TYPE_VIDEO)
            throw new \Exception('Invalid media type : ' . $type);

        $this->_type = $type;
    }

    public function getFilename($withUrl = true) {
        if (!$withUrl)
            return $this->_filename;

        return Router::getHost(true, Http::isHttps()) . str_replace(PATH_ROOT, '', MediaManager::getDatasPath()) . $this->_filename;
    }

    public function isImage() {
        return $this->_type == self::TYPE_IMAGE;
    }

    public function getThumbType($withUrl = true) {
        if (!$withUrl)
            return $this->_type . '.png';

        return Router::getHost(true, Http::isHttps()) . str_replace(PATH_ROOT, '', MediaManager::getDatasPath()) . $this->_type . '.png';
    }

}

?>