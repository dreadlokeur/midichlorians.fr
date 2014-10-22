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
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("", "' . $prestation->content . '", "' . $prestation->icon . '")';
        return $this->execute($sql, array(), $returnLastId, true);
    }

    public function read($id) {
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE id = ?';
        $this->execute($sql, array($id => Database::PARAM_INT));
        $data = $this->_engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('prestation', $data[0]);
    }

    public function update(PrestationObject $prestation) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET content = "' . $prestation->content . '", icon = "' . $prestation->icon . '" WHERE id = "' . $prestation->id . '"';
        $this->execute($sql, array(), false, true);
    }

    public function delete($id) {
        $sql = 'DELETE FROM ' . $this->getModelDBTable() . ' WHERE id = "' . $id . '"';
        $this->execute($sql, array(), false, true);

        return true;
    }

    public function readAll() {
        $sql = 'SELECT * FROM ' . $this->getModelDBTable();
        $this->execute($sql);
        $datas = $this->_engine->fetchAll(Database::FETCH_ASSOC);

        $prestations = array();
        foreach ($datas as $data)
            $prestations[] = self::factoryObject('prestation', $data);

        return $prestations;
    }

}

?>