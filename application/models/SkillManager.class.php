<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelManager;
use models\SkillObject;
use framework\Database;

class SkillManager extends Model implements IModelManager {

    public function __construct() {
        
    }

    public function create(SkillObject $skill, $returnLastId = true) {
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("' . $skill->name . '", "' . $skill->value . '")';
        return $this->execute($sql, array(), $returnLastId, true);
    }

    public function read($id) {
        $engine = $this->getDb(true);
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE id = ?';
        $this->execute($sql, array($id => Database::PARAM_INT));
        $data = $engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('skill', $data[0]);
    }

    public function update(SkillObject $skill) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' name = "' . $skill->name . '", value = "' . $skill->value . '" WHERE id = "' . $skill->id . '"';
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
            $all[] = self::factoryObject('skill', $data);

        return $all;
    }

}

?>