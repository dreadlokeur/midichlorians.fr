<?php

namespace controllers\backoffice;

use controllers\Backoffice;
use framework\network\Http;
use framework\mvc\Model;
use framework\utility\Tools;

class Media extends Backoffice {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function view() {
        //define tpl vars
        $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'media.tpl.php', false, true);
        $this->tpl->setVar('medias', $this->_readAll('media'), false, true);
        //ajax datas
        if ($this->isAjaxController()) {
            $this->tpl->setFile('blocks' . DS . 'media.tpl.php');
            $this->setAjaxAutoAddDatas(true);
        }
    }

    public function add() {
        // POST with AJAX
        if (Http::isPost() && $this->isAjaxController()) {
            $file = Http::getFile('file');
            if (!is_null($file)) {
                if (isset($file['error']) && $file['error'] == 0) {

                    try {
                        // load model
                        $manager = Model::factoryManager('media');
                        $type = explode('/', $file['type']);
                        //insert
                        $id = $manager->create(Model::factoryObject('media', array(
                                    'type' => (string) $type[0],
                                    'mime' => $file['type'],
                        )));
                        if (!is_null($id)) {
                            //save file
                            $filename = Tools::stringToUrl($file['name']);
                            Tools::saveFile($file['tmp_name'], $manager::getDatasPath() . $id . '-' . $filename);
                            $media = $manager->read($id);
                            $media->filename = $id . '-' . $filename;
                            $manager->update($media);


                            //cache
                            $this->_cache->delete('media' . 'List');
                            //update content
                            $this->tpl->setVar('medias', $this->_readAll('media'), false, true);
                            $this->tpl->setFile('tables' . DS . 'media.tpl.php');
                            $this->setAjaxAutoAddDatas(true);

                            //put ajax datas
                            $this->addAjaxDatas('success', true);
                        }
                    } catch (Exception $e) {
                        //TODO rollback...
                    }
                }
            }
        }
    }

    public function delete($id) {
        // POST with AJAX
        if (Http::isPost() && $this->isAjaxController()) {
            $media = $this->_read('media', $id);
            if ($media) {
                $manager = Model::factoryManager('media');
                try {
                    //delete file
                    $file = $manager::getDatasPath() . $media->getFilename(false);
                    if (file_exists($file) && is_file($file)) {
                        unlink($file);
                        $this->log->debug('Delete media : ' . $file);
                    }
                    // delete into database
                    $manager->delete($id);

                    //cache
                    $this->_cache->delete('media' . $id);
                    $this->_cache->delete('media' . 'List');

                    //update content
                    $this->tpl->setVar('medias', $this->_readAll('media'), false, true);
                    $this->tpl->setFile('tables' . DS . 'media.tpl.php');
                    $this->setAjaxAutoAddDatas(true);
                    $this->addAjaxDatas('success', true);
                } catch (Exception $e) {
                    //TODO rollback...
                }
            }
        }
    }

    public function update($id) {
        // POST with AJAX
        if (Http::isPost() && $this->isAjaxController()) {
            //load modal
            $manager = Model::factoryManager('media');
            $manager->update(Model::factoryObject('media', array(
                        'id' => $id,
                        'alt' => Http::getPost('alt'),
                        'title' => Http::getPost('title')
            )));

            //cache
            $this->_cache->delete('media' . $id);
            $this->_cache->delete('media' . 'List');
            $this->addAjaxDatas('success', true);
        }
    }

}

?>