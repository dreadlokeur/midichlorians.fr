<?php

namespace controllers\backoffice;

use controllers\Backoffice;
use framework\mvc\Model;
use framework\network\Http;
use framework\mvc\Router;

class Page extends Backoffice {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function all() {
        //define tpl vars
        $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'pages.tpl.php', false, true);
        $this->tpl->setVar('pages', $this->_readAll('page'), false, true);
        //ajax datas
        if ($this->isAjaxController()) {
            $this->tpl->setFile('blocks' . DS . 'pages.tpl.php');
            $this->setAjaxAutoAddDatas(true);
        }
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
            $this->setAjaxAutoAddDatas(true);

            //put ajax datas
            $this->addAjaxDatas('id', $id);
            $this->addAjaxDatas('success', true);
        }
    }

    public function delete($name) {
        $page = $this->_read('page', $name);
        if ($page) {
            $manager = Model::factoryManager('page');
            $success = $manager->delete($name);
            if ($success) {
                //cache
                $this->_cache->delete('page' . $name);
                $this->_cache->delete('page' . 'List');

                //update content
                $this->tpl->setVar('pages', $this->_readAll('page'), false, true);
                $this->tpl->setFile('tables' . DS . 'pages.tpl.php');
                $this->setAjaxAutoAddDatas(true);
            }
            $this->addAjaxDatas('success', $success);
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
            $success = $manager->update($page);

            if ($success) {
                //cache
                $this->_cache->delete('page' . $name);
                $this->_cache->delete('page' . 'List');
            }
            $this->addAjaxDatas('success', $success);
        }
    }

    public function view($name) {
        $page = $this->_read('page', $name);
        if ($page) {
            $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'page.tpl.php', false, true);
            $this->tpl->setVar('page', $page, false, true);

            //ajax datas
            if ($this->isAjaxController()) {
                $this->tpl->setFile('blocks' . DS . 'page.tpl.php');
                $this->setAjaxAutoAddDatas(true);
            }
        } else {
            $this->_redirect(Router::getUrl('error', array('404')), true);
        }
    }

}

?>