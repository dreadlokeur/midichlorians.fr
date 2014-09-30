<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelManager;
use models\MediaObject;
use framework\Database;

class MediaManager extends Model implements IModelManager {

    protected static $_datasPath = PATH_DATA_MEDIA;

    public function __construct() {
        
    }

    public function create(MediaObject $media, $returnLastId = true) {
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("", "' . $media->getFilename(false) . '", "' . $media->type . '", "' . $media->mime . '", "' . $media->title . '", "' . $media->alt . '")';
        return $this->execute($sql, array(), $returnLastId, true);
    }

    public function read($id) {
        $sql = 'SELECT * FROM ' . $this->getModelDBTable() . ' WHERE id = ?';
        $this->execute($sql, array($id => Database::PARAM_INT));
        $data = $this->_engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        return self::factoryObject('media', $data[0]);
    }

    public function update(MediaObject $media) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET filename = "' . $media->getFilename(false) . '", title = "' . $media->title . '", alt = "' . $media->alt . '" WHERE id = "' . $media->id . '"';
        $this->execute($sql, array(), false, true);
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