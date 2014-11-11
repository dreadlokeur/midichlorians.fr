<?php

namespace libs;

interface IModelManager {

    public function __construct();

    public function setModelDBName($dbName);

    public function setModelDBTable($dbTable);

    public function getModelDBName();

    public function getModelDBTable();

    public function getDB();

    public function execute($query, $parameters = array(), $returnLastInsertId = false, $checkBindNumber = true);
}

?>