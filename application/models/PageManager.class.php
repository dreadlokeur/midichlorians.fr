<?php

namespace models;

use libs\Model;
use libs\IModelManager;
use models\PageObject;
use MidiChloriansPHP\Database;

class PageManager extends Model implements IModelManager {

    public function __construct() {
        
    }

    public function create(PageObject $page, $returnLastId = true) {
        $name = $page->generateName();
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES(?,?,?,?,?)';
        $this->_engine->prepare($sql);
        $this->_engine->bind($name, Database::PARAM_STR);
        $this->_engine->bind($page->content, Database::PARAM_STR);
        $this->_engine->bind($page->title, Database::PARAM_STR);
        $this->_engine->bind($page->menu, Database::PARAM_STR);
        $this->_engine->bind($page->deletable, Database::PARAM_INT);
        $this->_engine->execute();
        if ($returnLastId)
            return $this->_engine->lastInsertId();
    }

    public function update(PageObject $page) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET content = ?, title = ?, menu = ?, deletable = ? WHERE name = ?';
        $this->_engine->prepare($sql);
        $this->_engine->bind($page->content, Database::PARAM_STR);
        $this->_engine->bind($page->title, Database::PARAM_STR);
        $this->_engine->bind($page->menu, Database::PARAM_STR);
        $this->_engine->bind($page->deletable, Database::PARAM_INT);
        $this->_engine->bind($page->name, Database::PARAM_STR);
        return $this->_engine->execute();
    }

    public function delete($name) {
        $sql = 'DELETE FROM ' . $this->getModelDBTable() . ' WHERE name = ?';
        $this->_engine->prepare($sql);
        $this->_engine->bind($name, Database::PARAM_STR);
        return $this->_engine->execute();
    }

    public function read($name) {
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE name = ?';
        $this->_engine->prepare($sql);
        $this->_engine->bind($name, Database::PARAM_STR);
        $this->_engine->execute();
        $data = $this->_engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('page', $data[0]);
    }

    public function readAll() {
        $sql = 'SELECT * FROM ' . $this->getModelDBTable();
        $this->_engine->prepare($sql);
        $this->_engine->execute();
        $datas = $this->_engine->fetchAll(Database::FETCH_ASSOC);

        $pages = array();
        foreach ($datas as $data)
            $pages[$data['name']] = self::factoryObject('page', $data);

        return $pages;
    }

    public function existsName($name, $lastName = null) {
        $sql = 'SELECT name FROM ' . $this->getModelDBTable() . ' WHERE name = ?';
        if (!is_null($lastName))
            $sql .= ' AND name != ?';
        $this->_engine->prepare($sql);
        $this->_engine->bind($name, Database::PARAM_STR);
        if (!is_null($lastName))
            $this->_engine->bind($lastName, Database::PARAM_STR);

        $this->_engine->execute();
        return $this->_engine->rowCount();
    }

}

?>