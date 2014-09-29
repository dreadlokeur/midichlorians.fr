<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelObject;

class ConfigObject extends Model implements IModelObject {
    
    protected $_name = null;
    protected $_value = null;

    public function __construct() {
        
    }

}

?>
