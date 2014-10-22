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
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("' . $page->name . '", "' . $page->content . '", "' . $page->title . '", "' . $page->menu . '", "' . $page->deletable . '")';
        return $this->execute($sql, array(), $returnLastId, true);
    }

    public function read($name) {
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE name = ?';
        $this->execute($sql, array($name => Database::PARAM_STR));
        $data = $this->_engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('page', $data[0]);
    }

    public function update(PageObject $page) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET content = "' . $page->content . '", title = "' . $page->title . '", menu = "' . $page->menu . '", deletable = "' . $page->deletable . '" WHERE name = "' . $page->name . '"';
        $this->execute($sql, array(), false, true);
    }

    public function delete($name) {
        $sql = 'DELETE FROM ' . $this->getModelDBTable() . ' WHERE name = "' . $name . '"';
        $this->execute($sql, array(), false, true);

        return true;
    }

    public function readAll() {
        $sql = 'SELECT * FROM ' . $this->getModelDBTable();
        $this->execute($sql);
        $datas = $this->_engine->fetchAll(Database::FETCH_ASSOC);

        $pages = array();
        foreach ($datas as $data)
            $pages[$data['name']] = self::factoryObject('page', $data);

        return $pages;
    }

}

?>