<?php

namespace controllers\backoffice;

use controllers\Backoffice;
use libs\Model;
use MidiChloriansPHP\network\Http;
use MidiChloriansPHP\mvc\Router;

class Page extends Backoffice {

    public function __construct() {
        parent::__construct();
    }

    public function all() {
        //define tpl vars
        $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'pages.tpl.php', false, true);
        $this->tpl->setVar('pages', $this->_readAll('page'), false, true);
        //ajax datas
        if ($this->isAjaxController())
            $this->tpl->setFile('blocks' . DS . 'pages.tpl.php');
    }

    public function add() {
        // load model
        $manager = Model::factoryManager('page');
        //insert
        $id = $manager->create(Model::factoryObject('page', array(
                    'name' => Http::getPost('name'),
                    'title' => Http::getPost('title'),
                    'menu' => Http::getPost('menu')
        )));
        if (!is_null($id)) {
            //cache
            $this->_cache->delete('page' . 'List');

            //update content
            $this->tpl->setVar('pages', $this->_readAll('page'), false, true);
            $this->tpl->setFile('tables' . DS . 'pages.tpl.php');

            //put ajax datas
            $this->addAjaxDatas('id', $id);
            $this->notifySuccess('Page ajoutée');
        }
    }

    public function delete($name) {
        $page = $this->_read('page', $name);
        if ($page) {
            $manager = Model::factoryManager('page');
            if ($manager->delete($name)) {
                //cache
                $this->_cache->delete('page' . $name);
                $this->_cache->delete('page' . 'List');

                //update content
                $this->tpl->setVar('pages', $this->_readAll('page'), false, true);
                $this->tpl->setFile('tables' . DS . 'pages.tpl.php');
                $this->notifySuccess('Page supprimée');
            }
        }
    }

    public function update($name, $fullUpdate = false) {
        $page = $this->_read('page', $name);
        if ($page) {
            $page->title = Http::getPost('title');
            $page->menu = Http::getPost('menu');
            if ($fullUpdate) {
                $page->content = Http::getPost('content', null, true);
                $page->deletable = Http::getPost('deletable') == 'on' ? 1 : 0;
            }

            //load model
            $manager = Model::factoryManager('page');
            if ($manager->update($page)) {
                //cache
                $this->_cache->delete('page' . $name);
                $this->_cache->delete('page' . 'List');
                $this->notifySuccess('Page editée');
            }
        }
    }

    public function view($name) {
        $page = $this->_read('page', $name);
        if ($page) {
            $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'page.tpl.php', false, true);
            $this->tpl->setVar('page', $page, false, true);

            //ajax datas
            if ($this->isAjaxController())
                $this->tpl->setFile('blocks' . DS . 'page.tpl.php');
        } else
            Http::redirect(Router::getUrl('error', array('404')));
    }

}

?>