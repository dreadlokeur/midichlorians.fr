<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelObject;

class PrestationObject extends Model implements IModelObject {

    protected $_id = null;
    protected $_content = null;
    protected $_icon = null;

    public function __construct() {
        
    }

}

?>
