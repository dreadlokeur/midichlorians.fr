<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelManager;
use models\ImageObject;
use framework\Database;

class ImageManager extends Model implements IModelManager {

    protected static $_datasPath = PATH_DATA_IMAGE;

    public function __construct() {
        
    }

    public function create(ImageObject $image, $returnLastId = true) {
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("", "' . $image->name . '","' . $image->getFile(false) . '")';
        return $this->execute($sql, array(), $returnLastId, true);
    }

    public function read($name) {
        $engine = $this->getDb(true);
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE name = ?';
        $this->execute($sql, array($name => Database::PARAM_STR));
        $data = $engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('image', $data[0]);
    }

    public function update(ImageObject $image) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET file = "' . $image->getFile(false) . '" WHERE id = "' . $image->name . '"';
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
            $all[$data['name']] = self::factoryObject('image', $data);

        return $all;
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