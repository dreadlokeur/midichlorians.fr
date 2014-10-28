<?php

namespace controllers;

use framework\Application;
use framework\Cache;
use framework\Security;
use framework\mvc\Controller;
use framework\mvc\Model;
use framework\network\Http;
use framework\utility\Cookie;
use framework\security\Form;

class Index extends Controller {

    protected $_cache = null;

    public function __construct() {
        //cache
        $this->_cache = Cache::getCache('bdd');
        //assigns vars
        $this->tpl->setVar('pages', $this->_readAll('page'), false, true);
        $this->tpl->setVar('references', $this->_readAll('reference'), false, true);
        // set template file
        $this->tpl->setFile('controllers' . DS . 'Index' . DS . 'index.tpl.php');
    }

    public function language($language) {
        if (!is_string($language))
            $language = (string) $language;

        $this->session->add('language', $language, true, false);
        $this->addAjaxDatas('updated', true);

        //create cookie
        new Cookie('language', $language, true, Cookie::EXPIRE_TIME_INFINITE, str_replace(Http::getServer('SERVER_NAME'), '', $this->router->getHost()));
    }

    public function captcha($formName, $type) {
        $captcha = Security::getSecurity(Security::TYPE_FORM)->getProtection($formName, Form::PROTECTION_CAPTCHA);
        if (!$captcha)
            $this->router->show404(true);

        if ($type == 'refresh') {
            $this->setAjaxController();
            $captcha->flush();
            $this->addAjaxDatas('imageUrl', $captcha->get('image', true));
            $this->addAjaxDatas('audioUrl', $captcha->get('audio', true));
        } else {
            if ($type == 'image') {
                if (!$captcha->getImage())
                    $this->router->show404(true);
                $captcha->get('image');
            } elseif ($type == 'audio') {
                if (!$captcha->getAudio())
                    $this->router->show404(true);
                $captcha->get('audio');
            } else
                $this->router->show404(true);

            $this->setAutoCallDisplay(false);
        }
    }

    private function _readAll($modelType) {
        $cache = $this->_cache->read($modelType . 'List');
        if (!is_null($cache) && !Application::getDebug())
            $datas = $cache;
        else {
            $manager = Model::factoryManager($modelType);
            $datas = $manager->readAll();
            if (!is_null($datas) && !Application::getDebug())
                $this->_cache->write($modelType . 'List', $datas, true);
        }

        return $datas;
    }

}

?>