<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelManager;
use models\CvObject;
use framework\Database;

class CvManager extends Model implements IModelManager {

    protected static $_datasPath = PATH_DATA_CV;

    public function __construct() {
        
    }

    public function create(CvObject $cv, $returnLastId = true) {
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("", "' . $cv->name . '", "' . $cv->link . '", "' . $cv->getThumb(false) . '")';
        return $this->execute($sql, array(), $returnLastId, true);
    }

    public function read($id) {
        $engine = $this->getDb(true);
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE id = ?';
        $this->execute($sql, array($id => Database::PARAM_INT));
        $data = $engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('cv', $data[0]);
    }

    public function update(CvObject $cv, $updateCv = true, $updateThumb = false) {
        if ($updateCv) {
            $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET name = "' . $cv->name . '", link = "' . $cv->link . '" WHERE id = "' . $cv->id . '"';
            $this->execute($sql, array(), false, true);
        }
        if ($updateThumb) {
            $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET thumb = "' . $cv->getThumb(false) . '" WHERE id = "' . $cv->id . '"';
            $this->execute($sql, array(), false, true);
        }
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
            $all[] = self::factoryObject('cv', $data);

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