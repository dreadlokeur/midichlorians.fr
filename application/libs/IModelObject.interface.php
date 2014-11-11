<?php

namespace libs;

interface IModelObject {

    public function __construct();

    public function hydrate($datas = array());
}

?>
