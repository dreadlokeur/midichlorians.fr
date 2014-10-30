<?php

namespace controllers\backoffice;

use controllers\Backoffice;
use framework\mvc\Model;
use framework\mvc\Router;
use framework\network\Http;
use framework\utility\Tools;
use models\MediaManager;

class Media extends Backoffice {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function all() {
        //define tpl vars
        $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'medias.tpl.php', false, true);
        $this->tpl->setVar('medias', $this->_readAll('media'), false, true);
        //ajax datas
        if ($this->isAjaxController()) {
            $this->tpl->setFile('blocks' . DS . 'medias.tpl.php');
            $this->setAjaxAutoAddDatas(true);
        }
    }

    public function add() {
        ini_set('upload_max_filesize', MEDIA_MAXSIZE);
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
                                'date' => date('d-m-Y'),
                    )));
                    if (!is_null($id)) {
                        //save file
                        $ex = Tools::getFileExtension($file['name']);
                        $filename = $id . '-' . Tools::stringToUrl(str_replace($ex, '', $file['name'])) . '.' . $ex;
                        $filePath = $manager::getDatasPath() . $filename;
                        Tools::saveFile($file['tmp_name'], $filePath);
                        $media = $manager->read($id);
                        $media->filename = $filename;


                        if ($media->isImage()) {
                            $sizes = getimagesize($filePath);
                            $media->width = $sizes[0];
                            $media->height = $sizes[1];
                        }
                        $media->size = filesize($filePath);

                        //update
                        $manager->update($media);


                        //cache
                        $this->_cache->delete('media' . 'List');
                        //update content
                        $this->tpl->setVar('medias', $this->_readAll('media'), false, true);
                        $this->tpl->setFile('tables' . DS . 'medias.tpl.php');
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

    public function delete($id) {
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
                $success = $manager->delete($id);
                if ($success) {
                    //cache
                    $this->_cache->delete('media' . $id);
                    $this->_cache->delete('media' . 'List');

                    //update content
                    $this->tpl->setVar('medias', $this->_readAll('media'), false, true);
                    $this->tpl->setFile('tables' . DS . 'medias.tpl.php');
                    $this->setAjaxAutoAddDatas(true);
                }
                $this->addAjaxDatas('success', $success);
            } catch (Exception $e) {
                //TODO rollback...
            }
        }
    }

    public function update($id, $fullUpdate = false) {
        $media = $this->_read('media', $id);
        if ($media) {
            $media->alt = Http::getPost('alt');
            $media->title = Http::getPost('title');
            if ($fullUpdate) {
                $filePath = MediaManager::getDatasPath() . $media->getFilename(false);
                // manipulate image
                if ($media->isImage()) {
                    $imagine = new \Imagine\Gd\Imagine();
                    $image = $imagine->open($filePath);
                    //rotate
                    $image->rotate(Http::getPost('rotate'));
                    //flip
                    $flipH = Http::getPost('flipH');
                    $flipV = Http::getPost('flipV');
                    if ($flipH)
                        $image->flipHorizontally();
                    if ($flipV)
                        $image->flipVertically();

                    //resize
                    $width = Http::getPost('width');
                    $height = Http::getPost('height');
                    $size = new \Imagine\Image\Box($width, $height);
                    $image->resize($size);
                    //crop
                    if (Http::getPost('x1') && Http::getPost('y1') && Http::getPost('w') && Http::getPost('h'))
                        $image->crop(new \Imagine\Image\Point(Http::getPost('x1'), Http::getPost('y1')), new \Imagine\Image\Box(Http::getPost('w'), Http::getPost('h')));

                    // save
                    $image->save($filePath);

                    //updates media size
                    $sizes = getimagesize($filePath);
                    $media->width = $sizes[0];
                    $media->height = $sizes[1];
                    $media->size = filesize($filePath);
                    // add to ajax
                    $this->addAjaxDatas('mediaImageSrc', $media->getFilename());
                    $this->addAjaxDatas('mediaImageWidth', $media->width);
                    $this->addAjaxDatas('mediaImageHeight', $media->height);
                    $this->addAjaxDatas('mediaImageSize', $media->size);
                }
            }
            //load model
            $manager = Model::factoryManager('media');
            $success = $manager->update($media);
            if ($success) {
                //cache
                $this->_cache->delete('media' . $id);
                $this->_cache->delete('media' . 'List');
            }
            $this->addAjaxDatas('success', $success);
        }
    }

    public function view($id) {
        $media = $this->_read('media', $id);
        if ($media) {
            $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'media.tpl.php', false, true);
            $this->tpl->setVar('media', $media, false, true);

            //ajax datas
            if ($this->isAjaxController()) {
                $this->tpl->setFile('blocks' . DS . 'media.tpl.php');
                $this->setAjaxAutoAddDatas(true);
            }
        } else {
            $this->_redirect(Router::getUrl('error', array('404')), true);
        }
    }

}

?>