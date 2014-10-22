<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelManager;
use models\BacklinkObject;
use framework\Database;

class BacklinkManager extends Model implements IModelManager {

    public function __construct() {
        
    }

    public function create(BacklinkObject $backlink, $returnLastId = true) {
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("", "' . $backlink->name . '", "' . $backlink->descr . '", "' . $backlink->link . '")';
        return $this->execute($sql, array(), $returnLastId, true, false);
    }

    public function read($id) {
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE id = ?';
        $this->execute($sql, array($id => Database::PARAM_INT));
        $data = $this->_engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('backlink', $data[0]);
    }

    public function update(BacklinkObject $backlink) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET name = "' . $backlink->name . '",descr = "' . $backlink->descr . '",link = "' . $backlink->link . '" WHERE id = "' . $backlink->id . '"';
        $this->execute($sql, array(), false, true, false);
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


        $backlinks = array();
        foreach ($datas as $data)
            $backlinks[] = self::factoryObject('backlink', $data);

        return $backlinks;
    }

}

?>