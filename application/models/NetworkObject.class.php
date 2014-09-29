<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelObject;

class NetworkObject extends Model implements IModelObject {

    protected $_id = null;
    protected $_link = null;
    protected $_icon = null;

    public function __construct() {
        
    }

}

?>
