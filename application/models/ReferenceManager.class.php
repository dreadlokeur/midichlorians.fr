<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelManager;
use models\ReferenceObject;
use framework\Database;

class ReferenceManager extends Model implements IModelManager {

    protected static $_datasPath = PATH_DATA_REFERENCE;

    public function __construct() {
        
    }

    public function create(ReferenceObject $reference, $returnLastId = true) {
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("", "' . $reference->name . '", "' . $reference->descr . '", "' . $reference->date . '", "' . $reference->link . '", "' . $reference->getThumb(false) . '", "' . $reference->getImage(false) . '")';
        return $this->execute($sql, array(), $returnLastId, true);
    }

    public function read($id) {
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE id = ?';
        $this->execute($sql, array($id => Database::PARAM_INT));
        $data = $this->_engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('reference', $data[0]);
    }

    public function update(ReferenceObject $reference, $updateReference = true, $updateThumb = false, $updateImage = false) {
        if ($updateReference) {
            $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET name = "' . $reference->name . '", descr = "' . $reference->descr . '", date = "' . $reference->date . '", link = "' . $reference->link . '" WHERE id = "' . $reference->id . '"';
            $this->execute($sql, array(), false, true);
        }
        if ($updateThumb) {
            $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET thumb = "' . $reference->getThumb(false) . '" WHERE id = "' . $reference->id . '"';
            $this->execute($sql, array(), false, true);
        }
        if ($updateImage) {
            $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET image = "' . $reference->getImage(false) . '" WHERE id = "' . $reference->id . '"';
            $this->execute($sql, array(), false, true);
        }
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

        $references = array();
        foreach ($datas as $data)
            $references[] = self::factoryObject('reference', $data);

        return $references;
    }

    public static function setDatasPath($dir, $forceCreate = true) {
        if ($forceCreate && !is_dir($dir)) {
            if (!mkdir($dir, 0775, true))
                throw new \Exception('Error on creating "' . $dir . '" directory');
        }else {
            if (!is_dir($dir))
                throw new \Exception('Directory "' . $dir . '" do not exists');
        }
        if (!is_writable($dir))
            throw new \Exception('Directory "' . $dir . '" is not writable');
        self::$_datasPath = realpath($dir) . DS;
    }

    public static function getDatasPath() {
        return self::$_datasPath;
    }

}

?>