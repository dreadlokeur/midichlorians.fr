<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelObject;
use framework\utility\Tools;

class PageObject extends Model implements IModelObject {
    
    protected $_name = null;
    protected $_content = null;
    protected $_title = null;
    protected $_menu = null;
    protected $_deletable = true;

    public function __construct() {
        
    }
    
    public function generateName($lastSlug = null) {
        $manager = self::factoryManager('page');
        $exist = true;
        $salt = '';
        $i = 0;
        if(empty($this->_name))
            $this->_name = '0';
        while ($exist && $i < 50) {
            $this->_name = Tools::stringToUrl($this->_name, '-', 'UTF-8', true) . $salt;
            $count = $manager->existsName($this->_name, $lastSlug);
            $exist = $count >= 1 ? true : false;
            $salt = (string) $i;
            $i++;
        }
        

        return $this->_name;
    }

}

?>
