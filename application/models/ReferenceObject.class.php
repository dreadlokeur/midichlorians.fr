<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelObject;
use framework\utility\Date;

class ReferenceObject extends Model implements IModelObject {

    protected $_id = null;
    protected $_name = null;
    protected $_content = null;
    protected $_date = null;
    protected $_link = null;
    protected $_technology = null;
    protected $_online = false;
    protected $_mediaId = null;
    protected $_media = null;

    public function __construct() {
        
    }

    public function setLink($link) {
        if (stripos($link, 'http://') === false && stripos($link, 'https://') === false)
            $link = 'http://' . $link;

        $this->_link = $link;
        return $this;
    }

    public function getDate($convert = false) {
        if ($convert) {
            return Date::dateFromUsFormat($this->_date);
        }
        return $this->_date;
    }

}

?>
