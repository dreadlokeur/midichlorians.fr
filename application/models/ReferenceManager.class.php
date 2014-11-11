<?php

namespace models;

use libs\Model;
use libs\IModelManager;
use models\ReferenceObject;
use MidiChloriansPHP\Database;

class ReferenceManager extends Model implements IModelManager {

    public function __construct() {
        
    }

    public function create(ReferenceObject $reference, $returnLastId = true) {
        // if mediaId is null foreign_key
        $this->execute('SET FOREIGN_KEY_CHECKS=0');

        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("",?,?,?,?,?,?,?)';
        $this->_engine->prepare($sql);
        $this->_engine->bind($reference->name, Database::PARAM_STR);
        $this->_engine->bind($reference->content, Database::PARAM_STR);
        $this->_engine->bind($reference->date, Database::PARAM_STR);
        $this->_engine->bind($reference->link, Database::PARAM_STR);
        $this->_engine->bind($reference->technology, Database::PARAM_STR);
        $this->_engine->bind($reference->online, Database::PARAM_INT);
        $this->_engine->bind($reference->mediaId, Database::PARAM_INT);
        $this->_engine->execute();
        if ($returnLastId)
            return $this->_engine->lastInsertId();
    }

    public function update(ReferenceObject $reference) {
        // if mediaId is null foreign_key
        $this->execute('SET FOREIGN_KEY_CHECKS=0');

        $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET name = ?, content =  ?, date = ?, link = ?, technology = ?, online = ?, mediaId = ? WHERE id = ?';
        $this->_engine->prepare($sql);
        $this->_engine->bind($reference->name, Database::PARAM_STR);
        $this->_engine->bind($reference->content, Database::PARAM_STR);
        $this->_engine->bind($reference->date, Database::PARAM_STR);
        $this->_engine->bind($reference->link, Database::PARAM_STR);
        $this->_engine->bind($reference->technology, Database::PARAM_STR);
        $this->_engine->bind($reference->online, Database::PARAM_INT);
        $this->_engine->bind($reference->mediaId, Database::PARAM_INT);
        $this->_engine->bind($reference->id, Database::PARAM_INT);
        return $this->_engine->execute();
    }

    public function delete($id) {
        $sql = 'DELETE FROM ' . $this->getModelDBTable() . ' WHERE id = "' . $id . '"';
        $this->_engine->prepare($sql);
        $this->_engine->bind($id, Database::PARAM_INT);
        return $this->_engine->execute();
    }

    public function read($id) {
        $sql = 'SELECT
            R.`id`, R.`name`, R.`content`, R.`date`, R.`link`, R.`online`, R.`mediaId`, R.`technology`,
            MEDIA.`id` AS `MEDIAid`, MEDIA.`filename`, MEDIA.`title`, MEDIA.`alt`
            FROM `' . $this->getModelDBTable() . '` R
            LEFT JOIN `media` MEDIA
            ON MEDIA.`id` = R.`mediaId`
            WHERE R.`id` = ?';
        $this->_engine->prepare($sql);
        $this->_engine->bind($id, Database::PARAM_INT);
        $this->_engine->execute();
        $data = $this->_engine->fetchAll(Database::FETCH_ASSOC);
        if (empty($data))
            return null;

        // set media object
        if ($data[0]['mediaId']) {
            $data[0]['media'] = self::factoryObject('media', array(
                        'id' => $data[0]['MEDIAid'],
                        'filename' => $data[0]['filename'])
            );
        } else
            $data[0]['media'] = self::factoryObject('media', array('filename' => 'no-image.png'));

        return self::factoryObject('reference', $data[0]);
    }

    public function readAll($onlineOnly = false) {
        $sql = 'SELECT id, date FROM ' . $this->getModelDBTable();
        if ($onlineOnly) {
            $sql .= ' WHERE online = 1';
        }
        $sql .= ' ORDER BY DATE(date) DESC, id DESC';

        $this->_engine->prepare($sql);
        $this->_engine->execute();
        $datas = $this->_engine->fetchAll(Database::FETCH_ASSOC);

        $references = array();
        foreach ($datas as $data)
            $references[] = $this->read($data['id']);

        return $references;
    }

}

?>