<?php

namespace controllers\backoffice;

use controllers\Backoffice;
use framework\network\Http;
use framework\mvc\Model;

class Skill extends Backoffice {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function view() {
        //define tpl vars
        $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'skill.tpl.php', false, true);
        $this->tpl->setVar('skills', $this->_readAll('skill'), false, true);
        //ajax datas
        if ($this->isAjaxController()) {
            $this->tpl->setFile('blocks' . DS . 'skill.tpl.php');
            $this->setAjaxAutoAddDatas(true);
        }
    }

    public function add() {
        // POST with AJAX
        if (Http::isPost() && $this->isAjaxController()) {
            // load model
            $manager = Model::factoryManager('skill');
            //insert
            $id = $manager->create(Model::factoryObject('skill', array(
                        'name' => Http::getPost('name'),
                        'value' => Http::getPost('value')
            )));
            if (!is_null($id)) {
                //cache
                $this->_cache->delete('skill' . 'List');

                //update content
                $this->tpl->setVar('skills', $this->_readAll('skill'), false, true);
                $this->tpl->setFile('tables' . DS . 'skill.tpl.php');
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
            $style = $this->_read('skill', $id);
            if ($style) {
                $manager = Model::factoryManager('skill');
                $manager->delete($id);
                //cache
                $this->_cache->delete('skill' . $id);
                $this->_cache->delete('skill' . 'List');

                //update content
                $this->tpl->setVar('skills', $this->_readAll('skill'), false, true);
                $this->tpl->setFile('tables' . DS . 'skill.tpl.php');
                $this->setAjaxAutoAddDatas(true);
                $this->addAjaxDatas('success', true);
            }
        }
    }

    public function update($id) {
        // POST with AJAX
        if (Http::isPost() && $this->isAjaxController()) {
            //load modal
            $manager = Model::factoryManager('skill');
            $manager->update(Model::factoryObject('skill', array(
                        'id' => $id,
                        'name' => Http::getPost('name'),
                        'value' => Http::getPost('value')
            )));

            //cache
            $this->_cache->delete('skill' . $id);
            $this->_cache->delete('skill' . 'List');
            $this->addAjaxDatas('success', true);
        }
    }

}

?>