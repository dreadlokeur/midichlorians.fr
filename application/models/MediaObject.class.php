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
    protected $_height = null;
    protected $_width = null;
    protected $_size = null;
    protected $_date = null;

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

    public function isAudio() {
        return $this->_type == self::TYPE_AUDIO;
    }

    public function isVideo() {
        return $this->_type == self::TYPE_VIDEO;
    }

    public function getThumbType($withUrl = true) {
        if (!$withUrl)
            return $this->_type . '.png';

        return Router::getHost(true, Http::isHttps()) . str_replace(PATH_ROOT, '', MediaManager::getDatasPath()) . $this->_type . '.png';
    }

    public function getSize($mb = false) {
        if ($mb)
            return round($this->_size / 1048576, 2); //in MB

        return $this->_size;
    }

}

?>
