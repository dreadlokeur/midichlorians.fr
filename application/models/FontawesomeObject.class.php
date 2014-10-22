<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelObject;

class FontawesomeObject extends Model implements IModelObject {

    protected $_id = null;
    protected $_iconName = null;
    protected $_iconId = null;
    protected $_iconUnicode = null;
    protected $_iconCreated = null;
    protected $_iconCategorie = null;

    public function __construct() {
        
    }

}

?>
