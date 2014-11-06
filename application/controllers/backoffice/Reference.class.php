<?php

namespace controllers\backoffice;

use controllers\Backoffice;
use framework\mvc\Model;
use framework\mvc\Router;
use framework\network\Http;
use models\MediaObject;

class Reference extends Backoffice {

    public function __construct() {
        parent::__construct();
    }

    public function all() {
        //define tpl vars
        $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'references.tpl.php', false, true);
        $this->tpl->setVar('references', $this->_readAll('reference'), false, true);
        //ajax datas
        if ($this->isAjaxController())
            $this->tpl->setFile('blocks' . DS . 'references.tpl.php');
    }

    public function add() {
        // load model
        $manager = Model::factoryManager('reference');
        //insert
        $id = $manager->create(Model::factoryObject('reference', array(
                    'name' => Http::getPost('name'),
                    'date' => Http::getPost('date') != null ? Http::getPost('date') : date('y-m-d'),
                    'link' => Http::getPost('link'),
                    'technology' => Http::getPost('technology'),
                    'online' => Http::getPost('online') == 'on' ? 1 : 0
        )));
        if (!is_null($id)) {
            //cache
            $this->_cache->delete('reference' . 'List');

            //update content
            $this->tpl->setVar('references', $this->_readAll('reference'), false, true);
            $this->tpl->setFile('tables' . DS . 'references.tpl.php');

            //put ajax datas
            $this->addAjaxDatas('id', $id);
            $this->notifySuccess('Référence ajoutée');
        }
    }

    public function delete($id) {
        $reference = $this->_read('reference', $id);
        if ($reference) {
            $manager = Model::factoryManager('reference');
            if ($manager->delete($id)) {
                //cache
                $this->_cache->delete('reference' . $id);
                $this->_cache->delete('reference' . 'List');

                //update content
                $this->tpl->setVar('references', $this->_readAll('reference'), false, true);
                $this->tpl->setFile('tables' . DS . 'references.tpl.php');
                $this->notifySuccess('Référence supprimée');
            }
        }
    }

    public function update($id, $fullUpdate = false) {
        $reference = $this->_read('reference', $id);
        if ($reference) {
            $reference->name = Http::getPost('name');
            $reference->date = Http::getPost('date');
            $reference->link = Http::getPost('link');
            $reference->technology = Http::getPost('technology');
            $reference->online = Http::getPost('online') == 'on' ? 1 : 0;
            if ($fullUpdate) {
                $reference->content = Http::getPost('content', null, true);
                $reference->mediaId = Http::getPost('mediaId');
            }

            //load model
            $manager = Model::factoryManager('reference');
            if ($manager->update($reference)) {
                //cache
                $this->_cache->delete('reference' . $id);
                $this->_cache->delete('reference' . 'List');
                $this->notifySuccess('Référence editée');
            }
        }
    }

    public function view($name) {
        $reference = $this->_read('reference', $name);
        if ($reference) {
            $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'reference.tpl.php', false, true);
            $this->tpl->setVar('reference', $reference, false, true);
            $this->tpl->setVar('medias', $this->_readAll('media', MediaObject::TYPE_IMAGE), false, true);

            //ajax datas
            if ($this->isAjaxController())
                $this->tpl->setFile('blocks' . DS . 'reference.tpl.php');
        } else
            Http::redirect(Router::getUrl('error', array('404')));
    }

}

?>