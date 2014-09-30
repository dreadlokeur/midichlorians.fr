<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelManager;
use models\ConfigObject;
use framework\Database;

class ConfigManager extends Model implements IModelManager {

    public function __construct() {
        
    }

    public function create(ConfigObject $config, $returnLastId = true) {
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("' . $config->name . '", "' . $config->value . '")';
        return $this->execute($sql, array(), $returnLastId, true);
    }

    public function read($name) {
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE name = ?';
        $this->execute($sql, array($name => Database::PARAM_STR));
        $data = $this->_engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('config', $data[0]);
    }

    public function update(ConfigObject $config) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET value = "' . $config->value . '" WHERE name = "' . $config->name . '"';
        $this->execute($sql, array(), false, true);
    }

    public function delete($name) {
        $sql = 'DELETE FROM ' . $this->getModelDBTable() . ' WHERE name = "' . $name . '"';
        $this->execute($sql, array(), false, true);

        return true;
    }

    public function readAll() {
        $all = array();
        $sql = 'SELECT * FROM ' . $this->getModelDBTable();
        $this->execute($sql);
        $datas = $this->_engine->fetchAll(Database::FETCH_ASSOC);

        foreach ($datas as $data)
            $all[$data['name']] = self::factoryObject('config', $data);

        return $all;
    }

}

?>