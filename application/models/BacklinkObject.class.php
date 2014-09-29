<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelObject;

class BacklinkObject extends Model implements IModelObject {

    protected $_id = null;
    protected $_name = null;
    protected $_descr = null;
    protected $_link = null;

    public function __construct() {
        
    }

    public function setLink($link) {
        if (stripos($link, 'http://') === false && stripos($link, 'https://') === false)
            $link = 'http://' . $link;

        $this->_link = $link;
        return $this;
    }

}

?>
