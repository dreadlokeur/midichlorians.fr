<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelObject;

class SkillObject extends Model implements IModelObject {

    protected $_id = null;
    protected $_name = null;
    protected $_value = null;

    public function __construct() {
        
    }

}

?>
