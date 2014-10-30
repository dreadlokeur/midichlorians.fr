<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelManager;
use models\ReferenceObject;
use framework\Database;

class ReferenceManager extends Model implements IModelManager {

    public function __construct() {
        
    }

    public function create(ReferenceObject $reference, $returnLastId = true) {
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("", "' . $reference->name . '", "' . $reference->content . '", "' . $reference->date . '", "' . $reference->link . '", "' . $reference->technology . '", "' . $reference->online . '", "' . $reference->mediaId . '")';
        //if is null foreign_key
        $this->execute('SET FOREIGN_KEY_CHECKS=0', array(), false, true);
        return $this->execute($sql, array(), $returnLastId, true);
    }

    public function read($id) {
        $sql = 'SELECT
            R.`id`, R.`name`, R.`content`, R.`date`, R.`link`, R.`online`, R.`mediaId`, R.`technology`,
            MEDIA.`id` AS `MEDIAid`, MEDIA.`filename`, MEDIA.`title`, MEDIA.`alt`
            FROM `' . $this->getModelDBTable() . '` R
            LEFT JOIN `media` MEDIA
            ON MEDIA.`id` = R.`mediaId`
            WHERE R.`id` = ?';
        $this->execute($sql, array($id => Database::PARAM_INT));
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

    public function update(ReferenceObject $reference) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET name = "' . $reference->name . '", content =  ?, date = "' . $reference->date . '", link = "' . $reference->link . '", technology = "' . $reference->technology . '", online = "' . $reference->online . '", mediaId = "' . $reference->mediaId . '" WHERE id = "' . $reference->id . '"';
        $this->execute($sql, array($reference->content => Database::PARAM_STR), false, true);
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
            $references[] = $this->read($data['id']);

        return $references;
    }

}

?>