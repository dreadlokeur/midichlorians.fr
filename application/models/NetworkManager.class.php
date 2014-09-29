<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelManager;
use models\NetworkObject;
use framework\Database;

class NetworkManager extends Model implements IModelManager {

    public function __construct() {
        
    }

    public function create(NetworkObject $network, $returnLastId = true) {
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("' . $network->link . '", "' . $network->icon . '")';
        return $this->execute($sql, array(), $returnLastId, true);
    }

    public function read($id) {
        $engine = $this->getDb(true);
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE id = ?';
        $this->execute($sql, array($id => Database::PARAM_INT));
        $data = $engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('network', $data[0]);
    }

    public function update(NetworkObject $network) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' link = "' . $network->link . '", icon = "' . $network->icon . '" WHERE id = "' . $network->id . '"';
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
            $all[] = self::factoryObject('network', $data);

        return $all;
    }

}

?>