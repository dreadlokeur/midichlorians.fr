<?php

namespace controllers\backoffice;

use controllers\Backoffice;
use framework\network\Http;
use framework\mvc\Model;

class Cv extends Backoffice {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function view() {
        //define tpl vars
        $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'cv.tpl.php', false, true);
        $this->tpl->setVar('medias', $this->_readAll('media'), false, true);
        $this->tpl->setVar('cvs', $this->_readAll('cv'), false, true);
        //ajax datas
        if ($this->isAjaxController()) {
            $this->tpl->setFile('blocks' . DS . 'cv.tpl.php');
            $this->setAjaxAutoAddDatas(true);
        }
    }

    public function add() {
        // POST with AJAX
        if (Http::isPost() && $this->isAjaxController()) {
            // load model
            $manager = Model::factoryManager('cv');
            //insert
            $id = $manager->create(Model::factoryObject('cv', array(
                        'name' => Http::getPost('name'),
                        'descr' => Http::getPost('descr'),
                        'link' => Http::getPost('link')
            )));
            if (!is_null($id)) {
                //cache
                $this->_cache->delete('cv' . 'List');

                //update content
                $this->tpl->setVar('cvs', $this->_readAll('cv'), false, true);
                $this->tpl->setFile('tables' . DS . 'cv.tpl.php');
                $this->setAjaxAutoAddDatas(true);

                //put ajax datas
                $this->addAjaxDatas('id', $id);
                $this->addAjaxDatas('success', true);
            }
        }
    }

    public function delete($id) {
        // POST with AJAX
        if (Http::isPost() && $this->isAjaxController()) {
            $style = $this->_read('cv', $id);
            if ($style) {
                $manager = Model::factoryManager('cv');
                $manager->delete($id);
                //cache
                $this->_cache->delete('cv' . $id);
                $this->_cache->delete('cv' . 'List');

                //update content
                $this->tpl->setVar('cvs', $this->_readAll('cv'), false, true);
                $this->tpl->setFile('tables' . DS . 'cv.tpl.php');
                $this->setAjaxAutoAddDatas(true);
                $this->addAjaxDatas('success', true);
            }
        }
    }

    public function update($id) {
        // POST with AJAX
        if (Http::isPost() && $this->isAjaxController()) {
            //load modal
            $manager = Model::factoryManager('cv');
            $manager->update(Model::factoryObject('cv', array(
                        'id' => $id,
                        'name' => Http::getPost('name'),
                        'descr' => Http::getPost('descr'),
                        'link' => Http::getPost('link')
            )));

            //cache
            $this->_cache->delete('cv' . $id);
            $this->_cache->delete('cv' . 'List');
            $this->addAjaxDatas('success', true);
        }
    }

}

?>