<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelManager;
use models\PageObject;
use framework\Database;

class PageManager extends Model implements IModelManager {

    public function __construct() {
        
    }

    public function create(PageObject $page, $returnLastId = true) {
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("' . $page->name . '", "' . $page->content . '", "' . $page->title . '", "' . $page->menu . '")';
        return $this->execute($sql, array(), $returnLastId, true);
    }

    public function read($name) {
        $engine = $this->getDb(true);
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE name = ?';
        $this->execute($sql, array($name => Database::PARAM_STR));
        $data = $engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('page', $data[0]);
    }

    public function update(PageObject $page) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' content = "' . $page->content . '", title = "' . $page->title . '", menu = "' . $page->menu . '" WHERE name = "' . $page->name . '"';
        $this->execute($sql, array(), false, true);
    }

    public function delete($name) {
        $sql = 'DELETE FROM ' . $this->getModelDBTable() . ' WHERE name = "' . $name . '"';
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
            $all[$data['name']] = self::factoryObject('page', $data);

        return $all;
    }

}

?>