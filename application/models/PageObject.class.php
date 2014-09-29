<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelObject;

class PageObject extends Model implements IModelObject {
    
    protected $_name = null;
    protected $_content = null;
    protected $_title = null;
    protected $_menu = null;

    public function __construct() {
        
    }

}

?>
