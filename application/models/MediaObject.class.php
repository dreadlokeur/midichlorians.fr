<?php

namespace models;

use libs\Model;
use libs\IModelObject;
use MidiChloriansPHP\mvc\Router;
use MidiChloriansPHP\network\Http;

class MediaObject extends Model implements IModelObject {

    CONST TYPE_IMAGE = 'image';
    CONST TYPE_AUDIO = 'audio';
    CONST TYPE_VIDEO = 'video';
    CONST SIZE_ORIGINAL = 'original';
    CONST SIZE_MEDIUM = 'medium';
    CONST SIZE_SMALL = 'small';
    CONST SIZE_PORTFOLIO = 'portfolio';

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

    public function getFilename($withUrl = true, $withPath = false, $size = false) {
        if ($this->isImage() && $size !== false) {
            if ($size != self::SIZE_MEDIUM && $size != self::SIZE_SMALL && $size != self::SIZE_ORIGINAL && $size != self::SIZE_PORTFOLIO)
                throw new \Exception('Invalid media size : ' . $size);
        }
        $filename = $this->_filename;
        if ($this->isImage() && $size !== false)
            $filename = $size . '-' . $filename;

        if (!$withUrl)
            return $withPath ? MediaManager::getDatasPath() . $filename : $filename;

        return Router::getHost(true, Http::isHttps()) . str_replace(PATH_ROOT, '', MediaManager::getDatasPath()) . $filename;
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
