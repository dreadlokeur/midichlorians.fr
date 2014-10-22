<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelManager;
use framework\Database;

class FontawesomeManager extends Model implements IModelManager {

    public function __construct() {
        
    }

    public function readAll() {
        $sql = 'SELECT * FROM ' . $this->getModelDBTable();
        $this->execute($sql);
        $datas = $this->_engine->fetchAll(Database::FETCH_ASSOC);


        $fontAwesomes = array();
        foreach ($datas as $data)
            $fontAwesomes[] = self::factoryObject('fontawesome', $data);

        return $fontAwesomes;
    }

}

?>