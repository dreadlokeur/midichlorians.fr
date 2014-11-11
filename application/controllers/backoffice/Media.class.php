<?php

namespace controllers\backoffice;

use controllers\Backoffice;
use libs\Model;
use MidiChloriansPHP\mvc\Router;
use MidiChloriansPHP\network\Http;
use MidiChloriansPHP\utility\Tools;
use \Imagine\Gd\Imagine;
use \Imagine\Image\Box;
use \Imagine\Image\Point;
use models\MediaObject;

class Media extends Backoffice {

    public function __construct() {
        parent::__construct();
        $this->_modelManager = Model::factoryManager('media');
        $this->_modelObject = Model::factoryObject('media');
    }

    public function all() {
        //define tpl vars
        $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'medias.tpl.php', false, true);
        $this->tpl->setVar('medias', $this->_modelManager->readAll(), false, true);
        //ajax datas
        if ($this->isAjaxController())
            $this->tpl->setFile('blocks' . DS . 'medias.tpl.php');
    }

    public function add() {
        ini_set('upload_max_filesize', MEDIA_MAXSIZE);
        $file = Http::getFile('file');
        if (!is_null($file)) {
            if (isset($file['error']) && $file['error'] == 0) {
                try {
                    //insert
                    $this->_modelObject->type = (string) explode('/', $file['type'])[0];
                    $this->_modelObject->mime = $file['type'];
                    $this->_modelObject->date = date('y-m-d');
                    $this->_modelObject->id = $this->_modelManager->create($this->_modelObject);
                    if (!is_null($this->_modelObject->id)) {
                        // save file
                        $this->_saveFile($file);

                        // update bdd
                        $this->_modelManager->update($this->_modelObject);

                        // update cache
                        $this->_cache->delete('media' . 'List');

                        // update template content
                        $this->tpl->setVar('medias', $this->_modelManager->readAll(), false, true);
                        $this->tpl->setFile('tables' . DS . 'medias.tpl.php');
                        $this->notifySuccess('Media ajouté');
                    }
                } catch (Exception $e) {
                    //TODO rollback...
                }
            }
        }
    }

    public function delete($id) {
        $this->_modelObject = $this->_modelManager->read($id);
        if ($this->_modelObject) {
            try {
                //delete file
                $this->_deleteFile();

                // delete into database
                if ($this->_modelManager->delete($id)) {
                    // update cache
                    $this->_cache->delete('media' . $id);
                    $this->_cache->delete('media' . 'List');

                    //update template content
                    $this->tpl->setVar('medias', $this->_readAll('media'), false, true);
                    $this->tpl->setFile('tables' . DS . 'medias.tpl.php');
                    $this->notifySuccess('Media supprimé');
                }
            } catch (Exception $e) {
                //TODO rollback...
            }
        }
    }

    public function update($id, $fullUpdate = false) {
        $this->_modelObject = $this->_modelManager->read($id);
        if ($this->_modelObject) {
            $this->_modelObject->alt = Http::getPost('alt');
            $this->_modelObject->title = Http::getPost('title');
            if ($fullUpdate) {
                // manipulate image
                if ($this->_modelObject->isImage()) {
                    $imagine = new Imagine();
                    $image = $imagine->open($this->_modelObject->getFilename(false, true));

                    //rotate
                    $image->rotate(Http::getPost('rotate'));
                    //flip
                    if (Http::getPost('flipH'))
                        $image->flipHorizontally();
                    if (Http::getPost('flipV'))
                        $image->flipVertically();

                    //resize
                    $image->resize(new Box(Http::getPost('width'), Http::getPost('height')));

                    //crop
                    if (Http::getPost('x1') && Http::getPost('y1') && Http::getPost('w') && Http::getPost('h'))
                        $image->crop(new Point(Http::getPost('x1'), Http::getPost('y1')), new Box(Http::getPost('w'), Http::getPost('h')));

                    // save
                    $image->save();

                    //updates media sizes
                    $sizes = getimagesize($this->_modelObject->getFilename(false, true));
                    $this->_modelObject->width = $sizes[0];
                    $this->_modelObject->height = $sizes[1];
                    $this->_modelObject->size = filesize($this->_modelObject->getFilename(false, true));

                    // save Thumbnails
                    $this->_saveThumbnails();

                    // add to ajax
                    $this->addAjaxDatas('mediaImageSrc', $this->_modelObject->filename);
                    $this->addAjaxDatas('mediaImageWidth', $this->_modelObject->width);
                    $this->addAjaxDatas('mediaImageHeight', $this->_modelObject->height);
                    $this->addAjaxDatas('mediaImageSize', $this->_modelObject->getSize(true));
                }
            }
            // update into bdd
            if ($this->_modelManager->update($this->_modelObject)) {
                // update cache
                $this->_cache->delete('media' . $id);
                $this->_cache->delete('media' . 'List');

                $this->notifySuccess('Media edité');
            }
        }
    }

    public function view($id) {
        $media = $this->_modelManager->read($id);
        if ($media) {
            $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'media.tpl.php', false, true);
            $this->tpl->setVar('media', $media, false, true);

            //ajax datas
            if ($this->isAjaxController())
                $this->tpl->setFile('blocks' . DS . 'media.tpl.php');
        } else
            Http::redirect(Router::getUrl('error', array('404')));
    }

    private function _saveFile($file) {
        // set filename
        $ex = Tools::getFileExtension($file['name']);
        $this->_modelObject->filename = $this->_modelObject->id . '-' . Tools::stringToUrl(str_replace($ex, '', $file['name'])) . '.' . $ex;
        // save file
        Tools::saveFile($file['tmp_name'], $this->_modelObject->getFilename(false, true));

        // set h and w for image
        if ($this->_modelObject->isImage()) {
            $sizes = getimagesize($this->_modelObject->getFilename(false, true));
            $this->_modelObject->width = $sizes[0];
            $this->_modelObject->height = $sizes[1];

            // save Thumbnails
            $this->_saveThumbnails();

            // clone for conserve original file
            Tools::saveFile($this->_modelObject->getFilename(false, true), $this->_modelObject->getFilename(false, true, MediaObject::SIZE_ORIGINAL));
        }

        //weight
        $this->_modelObject->size = filesize($this->_modelObject->getFilename(false, true));
    }

    private function _saveThumbnails() {
        $imagine = new Imagine();
        // save 50%
        $image = $imagine->open($this->_modelObject->getFilename(false, true));
        $image->resize(new Box($this->_modelObject->width / 2, $this->_modelObject->height / 2));
        $image->save($this->_modelObject->getFilename(false, true, MediaObject::SIZE_MEDIUM));

        // save 25%
        $image = $imagine->open($this->_modelObject->getFilename(false, true));
        $image->resize(new Box($this->_modelObject->width / 4, $this->_modelObject->height / 4));
        $image->save($this->_modelObject->getFilename(false, true, MediaObject::SIZE_SMALL));



        // save portfolio size
        $image = $imagine->open($this->_modelObject->getFilename(false, true));
        $w = MEDIA_SIZE_PORTFOLIO_W;
        $h = MEDIA_SIZE_PORTFOLIO_H;
        if (MEDIA_SIZE_PORTFOLIO_PROPORTION) {
            $sourceSize = $image->getSize();
            $sourceW = $sourceSize->getWidth();
            $sourceH = $sourceSize->getHeight();
            $sourceRatio = $sourceW / $sourceH;
            $h = $w / $sourceRatio;
        }
        $image->resize(new Box($w, $h));
        $image->save($this->_modelObject->getFilename(false, true, MediaObject::SIZE_PORTFOLIO));
    }

    private function _deleteFile() {
        $file = $this->_modelObject->getFilename(false, true);
        if (file_exists($file) && is_file($file)) {
            unlink($file);
            $this->log->debug('Delete media : ' . $file);
        }
        if ($this->_modelObject->isImage()) {
            // delete original
            $fileOriginal = $this->_modelObject->getFilename(false, true, MediaObject::SIZE_ORIGINAL);
            if (file_exists($fileOriginal) && is_file($fileOriginal)) {
                unlink($fileOriginal);
                $this->log->debug('Delete media : ' . $fileOriginal);
            }
            // delete Thumbnails
            $fileMedium = $this->_modelObject->getFilename(false, true, MediaObject::SIZE_MEDIUM);
            if (file_exists($fileMedium) && is_file($fileMedium)) {
                unlink($fileMedium);
                $this->log->debug('Delete media : ' . $fileMedium);
            }
            $fileSmall = $this->_modelObject->getFilename(false, true, MediaObject::SIZE_SMALL);
            if (file_exists($fileSmall) && is_file($fileSmall)) {
                unlink($fileSmall);
                $this->log->debug('Delete media : ' . $fileSmall);
            }
            $filePortfolio = $this->_modelObject->getFilename(false, true, MediaObject::SIZE_PORTFOLIO);
            if (file_exists($filePortfolio) && is_file($filePortfolio)) {
                unlink($filePortfolio);
                $this->log->debug('Delete media : ' . $filePortfolio);
            }
        }
    }

}

?>