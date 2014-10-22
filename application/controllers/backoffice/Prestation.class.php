<?php

namespace controllers\backoffice;

use controllers\Backoffice;
use framework\network\Http;
use framework\mvc\Model;

class Prestation extends Backoffice {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function view() {
        //define tpl vars
        $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'prestation.tpl.php', false, true);
        $this->tpl->setVar('prestations', $this->_readAll('prestation'), false, true);
        $this->tpl->setVar('icons', $this->_readAll('fontawesome'), false, true);
        //ajax datas
        if ($this->isAjaxController()) {
            $this->tpl->setFile('blocks' . DS . 'prestation.tpl.php');
            $this->setAjaxAutoAddDatas(true);
        }
    }

    public function add() {
        // POST with AJAX
        if (Http::isPost() && $this->isAjaxController()) {
            // load model
            $manager = Model::factoryManager('prestation');
            //insert
            $id = $manager->create(Model::factoryObject('prestation', array(
                        'content' => Http::getPost('content', null, true),
                        'icon' => Http::getPost('icon')
            )));
            if (!is_null($id)) {
                //cache
                $this->_cache->delete('prestation' . 'List');

                //update content
                $this->tpl->setVar('prestations', $this->_readAll('prestation'), false, true);
                $this->tpl->setVar('icons', $this->_readAll('fontawesome'), false, true);
                $this->tpl->setFile('tables' . DS . 'prestation.tpl.php');
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
            $style = $this->_read('prestation', $id);
            if ($style) {
                $manager = Model::factoryManager('prestation');
                $manager->delete($id);
                //cache
                $this->_cache->delete('prestation' . $id);
                $this->_cache->delete('prestation' . 'List');

                //update content
                $this->tpl->setVar('prestations', $this->_readAll('prestation'), false, true);
                $this->tpl->setVar('icons', $this->_readAll('fontawesome'), false, true);
                $this->tpl->setFile('tables' . DS . 'prestation.tpl.php');
                $this->setAjaxAutoAddDatas(true);
                $this->addAjaxDatas('success', true);
            }
        }
    }

    public function update($id) {
        // POST with AJAX
        if (Http::isPost() && $this->isAjaxController()) {
            //load modal
            $manager = Model::factoryManager('prestation');
            $manager->update(Model::factoryObject('prestation', array(
                        'id' => $id,
                        'content' => Http::getPost('content', null, true),
                        'icon' => Http::getPost('icon')
            )));

            //cache
            $this->_cache->delete('prestation' . $id);
            $this->_cache->delete('prestation' . 'List');
            $this->addAjaxDatas('success', true);
        }
    }

}

?>