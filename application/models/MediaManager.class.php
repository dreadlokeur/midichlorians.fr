<?php

namespace models;

use libs\Model;
use libs\IModelManager;
use models\MediaObject;
use MidiChloriansPHP\Database;

class MediaManager extends Model implements IModelManager {

    protected static $_datasPath = PATH_DATA_MEDIA;

    public function __construct() {
        
    }

    public function create(MediaObject $media, $returnLastId = true) {
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("",?,?,?,?,?,?,?,?,?)';
        $this->_engine->prepare($sql);
        $this->_engine->bind($media->getFilename(false), Database::PARAM_STR);
        $this->_engine->bind($media->type, Database::PARAM_STR);
        $this->_engine->bind($media->mime, Database::PARAM_STR);
        $this->_engine->bind($media->title, Database::PARAM_STR);
        $this->_engine->bind($media->alt, Database::PARAM_STR);
        $this->_engine->bind($media->height, Database::PARAM_STR);
        $this->_engine->bind($media->width, Database::PARAM_STR);
        $this->_engine->bind($media->size, Database::PARAM_STR);
        $this->_engine->bind($media->date, Database::PARAM_STR);
        $this->_engine->execute();
        if ($returnLastId)
            return $this->_engine->lastInsertId();
    }

    public function update(MediaObject $media) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET filename = ?, title = ?, alt = ?, height = ?, width = ?, size = ?, date = ? WHERE id = ?';
        $this->_engine->prepare($sql);
        $this->_engine->bind($media->getFilename(false), Database::PARAM_STR);
        $this->_engine->bind($media->title, Database::PARAM_STR);
        $this->_engine->bind($media->alt, Database::PARAM_STR);
        $this->_engine->bind($media->height, Database::PARAM_STR);
        $this->_engine->bind($media->width, Database::PARAM_STR);
        $this->_engine->bind($media->size, Database::PARAM_STR);
        $this->_engine->bind($media->date, Database::PARAM_STR);
        $this->_engine->bind($media->id, Database::PARAM_INT);
        return $this->_engine->execute();
    }

    public function delete($id) {
        $sql = 'DELETE FROM ' . $this->getModelDBTable() . ' WHERE id = "' . $id . '"';
        $this->_engine->prepare($sql);
        $this->_engine->bind($id, Database::PARAM_INT);
        return $this->_engine->execute();
    }

    public function read($id) {
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE id = ?';
        $this->_engine->prepare($sql);
        $this->_engine->bind($id, Database::PARAM_INT);
        $this->_engine->execute();
        $data = $this->_engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('media', $data[0]);
    }

    public function readAll($type = '') {
        $sql = 'SELECT * FROM ' . $this->getModelDBTable();
        if ($type != '')
            $sql .= ' WHERE type = "' . $type . '"';
        $this->_engine->prepare($sql);
        if ($type != '')
            $this->_engine->bind($type, Database::PARAM_STR);

        $this->_engine->execute();
        $datas = $this->_engine->fetchAll(Database::FETCH_ASSOC);

        $medias = array();
        foreach ($datas as $data)
            $medias[] = self::factoryObject('media', $data);

        return $medias;
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