<?php

namespace controllers\backoffice;

use controllers\Backoffice;
use framework\network\Http;
use framework\mvc\Model;

class Backlink extends Backoffice {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function view() {
        //define tpl vars
        $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'backlink.tpl.php', false, true);
        $this->tpl->setVar('backlinks', $this->_readAll('backlink'), false, true);
        //ajax datas
        if ($this->isAjaxController()) {
            $this->tpl->setFile('blocks' . DS . 'backlink.tpl.php');
            $this->setAjaxAutoAddDatas(true);
        }
    }

    public function add() {
        // POST with AJAX
        if (Http::isPost() && $this->isAjaxController()) {
            // load model
            $manager = Model::factoryManager('backlink');
            //insert
            $id = $manager->create(Model::factoryObject('backlink', array(
                        'name' => Http::getPost('name'),
                        'descr' => Http::getPost('descr'),
                        'link' => Http::getPost('link')
            )));
            if (!is_null($id)) {
                //cache
                $this->_cache->delete('backlink' . 'List');

                //update content
                $this->tpl->setVar('backlinks', $this->_readAll('backlink'), false, true);
                $this->tpl->setFile('tables' . DS . 'backlink.tpl.php');
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
            $style = $this->_read('backlink', $id);
            if ($style) {
                $manager = Model::factoryManager('backlink');
                $manager->delete($id);
                //cache
                $this->_cache->delete('backlink' . $id);
                $this->_cache->delete('backlink' . 'List');

                //update content
                $this->tpl->setVar('backlinks', $this->_readAll('backlink'), false, true);
                $this->tpl->setFile('tables' . DS . 'backlink.tpl.php');
                $this->setAjaxAutoAddDatas(true);
                $this->addAjaxDatas('success', true);
            }
        }
    }

    public function update($id) {
        // POST with AJAX
        if (Http::isPost() && $this->isAjaxController()) {
            //load modal
            $manager = Model::factoryManager('backlink');
            $manager->update(Model::factoryObject('backlink', array(
                        'id' => $id,
                        'name' => Http::getPost('name'),
                        'descr' => Http::getPost('descr'),
                        'link' => Http::getPost('link')
            )));

            //cache
            $this->_cache->delete('backlink' . $id);
            $this->_cache->delete('backlink' . 'List');
            $this->addAjaxDatas('success', true);
        }
    }

}

?>