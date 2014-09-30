<?php

namespace models;

use framework\mvc\Model;
use framework\mvc\IModelManager;
use models\CvObject;
use framework\Database;

class CvManager extends Model implements IModelManager {

    public function __construct() {
        
    }

    public function create(CvObject $cv, $returnLastId = true) {
        $sql = 'INSERT INTO ' . $this->getModelDBTable() . ' VALUES("", "' . $cv->name . '",  "' . $cv->descr . '","' . $cv->link . '", ""' . $cv->mediaId . '"")';
        //if is null foreign_key
        $this->execute('SET FOREIGN_KEY_CHECKS=0', array(), false, true);
        return $this->execute($sql, array(), $returnLastId, true);
    }

    public function read($id) {
        $sql = 'SELECT
            CV.`id`, CV.`name`, CV.`descr`, CV.`link`, CV.`mediaId`,
            MEDIA.`id` AS `MEDIAid`, MEDIA.`filename`, MEDIA.`title`, MEDIA.`alt`
            FROM `' . $this->getModelDBTable() . '` CV
            LEFT JOIN `media` MEDIA
            ON MEDIA.`id` = CV.`mediaId`
            WHERE CV.`id` = ?';
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
            //empty
        } else
            $data[0]['media'] = self::factoryObject('media', array('filename' => 'no-image.png'));

        return self::factoryObject('cv', $data[0]);
    }

    public function update(CvObject $cv) {
        $sql = 'UPDATE ' . $this->getModelDBTable() . ' SET name = "' . $cv->name . '", descr = "' . $cv->descr . '", link = "' . $cv->link . '", mediaId = "' . $cv->mediaId . '" WHERE id = "' . $cv->id . '"';
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

        $cvs = array();
        foreach ($datas as $data)
            $cvs[] = $this->read($data['id']);

        return $cvs;
    }

}

?>