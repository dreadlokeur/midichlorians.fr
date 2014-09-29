<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelManager;
use models\PrestationObject;
use framework\Database;

class PrestationManager extends Model implements IModelManager {

    public function __construct() {
        
    }

    public function create(PrestationObject $prestation, $returnLastId = true) {
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("' . $prestation->content . '", "' . $prestation->icon . '")';
        return $this->execute($sql, array(), $returnLastId, true);
    }

    public function read($id) {
        $engine = $this->getDb(true);
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE id = ?';
        $this->execute($sql, array($id => Database::PARAM_INT));
        $data = $engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('prestation', $data[0]);
    }

    public function update(PrestationObject $prestation) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' content = "' . $prestation->content . '", icon = "' . $prestation->icon . '" WHERE id = "' . $prestation->id . '"';
        $this->execute($sql, array(), false, true);
    }

    public function delete($id) {
        $sql = 'DELETE FROM ' . $this->getModelDBTable() . ' WHERE id = "' . $id . '"';
        $this->execute($sql, array(), false, true);

        return true;
    }

    public function readAll() {
        $all = array();
        $engine = $this->getDb(true);
        $sql = 'SELECT * FROM ' . $this->getModelDBTable();
        $this->execute($sql);
        $datas = $engine->fetchAll(Database::FETCH_ASSOC);

        foreach ($datas as $data)
            $all[] = self::factoryObject('prestation', $data);

        return $all;
    }

}

?>