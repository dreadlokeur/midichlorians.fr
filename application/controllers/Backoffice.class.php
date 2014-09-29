<?php

/* TODO:
 */

namespace controllers;

use framework\mvc\Controller;
use framework\Security;
use framework\security\Form;
use framework\network\Http;
use framework\Session;
use framework\mvc\Router;
use framework\mvc\Template;
use framework\utility\Tools;
use framework\Application;
use framework\utility\Cookie;
use framework\security\cryptography\Hash;
use framework\Cache;
use framework\mvc\Model;

class Backoffice extends Controller {

    protected $_isUpload = false;
    protected $_security = null;
    protected $_crsf = null;

    public function __construct() {
        // set backoffice template
        Template::setTemplate('backoffice');

        //cache
        $this->_cache = Cache::getCache('bdd');

        //check login
        $this->_checkLogin();

        // init security
        $this->_security = Security::getSecurity(Security::TYPE_FORM);
        $this->_crsf = $this->_security->getProtection('backoffice', Form::PROTECTION_CSRF);
        $this->_crsf->create();

        //check security
        if (Http::isPost()) {
            $token = $this->router->getCurrentRoute() != 'upload' ? Http::getPost('backoffice-token') : Http::getServer('HTTP_TOKEN');
            if (!$this->_crsf->check($token)) {
                $this->log->debug('Invalid token security');
                $this->_redirect(Router::getUrl('error', array('404')), true);
            }
        }


        //assign token value into tpl
        $this->_assignToken();

        // define template file
        $this->tpl->setFile('controllers' . DS . 'Backoffice' . DS . 'index.tpl.php');
    }

    public function __destruct() {
        //flushing upload directory
        if (!$this->_isUpload) {
            $this->_flushTmpDirectory();
            $this->log->debug('Upload tmp directory purged');
        }

        //update token into session
        if ($this->_crsf) {
            $this->_crsf->set();
            $this->log->debug('Token security updated');
        }
    }

    public function setAjax($check = false) {
        if (!Http::isAjaxRequest() && $check)
            $this->_redirect(Router::getUrl('backoffice'), true);

        if (Http::isAjaxRequest())
            $this->setAjaxController();
    }

    public function login() {
        //already logged
        if (Session::getInstance()->get('admin'))
            $this->_redirect(Router::getUrl('backoffice'), true);
        else {
            // POST LOGIN with AJAX
            if (Http::isPost() && $this->isAjaxController()) {
                //check password, username
                if (Application::getDebug() || (sha1(Http::getPost('admin-password')) == ADMIN_PASSWORD && Http::getPost('admin-username') == ADMIN_NAME)) {
                    $this->_connect();
                    $this->addAjaxDatas('success', true);
                }
            }

            // define login template
            $this->tpl->setFile('controllers' . DS . 'Backoffice' . DS . 'login.tpl.php', false, true);
        }
    }

    public function logout() {
        // POST with AJAX
        if (Http::isPost() && $this->isAjaxController()) {
            //delete session
            $this->session->delete('admin', true)->regenerateId();
            $this->log->debug('Session admin deleted');
            //delete cookie
            if (Cookie::get('login')) {
                $cookie = new Cookie('login', null, false);
                $cookie->delete();
                $this->log->debug('Cookie login deleted');
            }

            $this->addAjaxDatas('success', true);
        }
    }

    public function home() {
        //define tpl vars
        $this->tpl->setVar('block', $this->tpl->getPath() .'blocks' . DS . 'home.tpl.php', false, true);
        //ajax datas
        if ($this->isAjaxController()) {
            $this->tpl->setFile('blocks' . DS . 'home.tpl.php');
            $this->setAjaxAutoAddDatas(true);
        }
    }

    public function upload() {
        $this->setAjaxController();
        //desactivate logger display
        $displayer = $this->log->getObservers('display');
        if (!is_null($displayer))
            $this->log->detach($displayer);

        $file = Http::getServer('HTTP_NAME'); //get filename
        if ($file) {
            $path = PATH_TMP . $this->session->getId() . DS;
            if (!is_dir($path))
                mkdir($path, 0770);

            $fileInfos = explode('-', $file);
            //delete last thumb or original into tmp path
            if ($fileInfos[1] == 'thumb' || $fileInfos[1] == 'original')
                $this->_flushTmpDirectory($fileInfos[1]);


            $ex = Tools::getFileExtension($file);
            $salt = $this->session->increment('idUpload', 1, 1, true);
            $randName = $salt . '-' . $fileInfos[0] . '-' . $fileInfos[1] . '-' . rand() . '.' . $ex;
            //create tmp file, with php flux content
            file_put_contents($path . $randName, file_get_contents('php://input'));
            $this->log->debug('Uploaded file ' . $randName);
            $this->addAjaxDatas('url', str_replace(DS, '/', Router::getHost(true, Http::isHttps()) . str_replace(PATH_ROOT, '', $path)) . $randName);
            $this->addAjaxDatas('name', $randName);
            $this->addAjaxDatas('token', $this->_crsf->get());
        }
        $this->_isUpload = true;
    }

    private function _flushTmpDirectory($filterType = null) {
        $path = PATH_TMP /* . $this->session->getId() . DS */;
        if (is_dir($path)) {
            $dir = Tools::cleanScandir($path);
            foreach ($dir as &$f) {
                if ($filterType && stripos($f, $filterType) === false)
                    continue;
                if (is_file($path . $f) && $f != '.htaccess')
                    unlink($path . $f);

                if (is_dir($path . $f))
                    Tools::deleteTreeDirectory($path . $f);
            }
        }
    }

    private function _assignToken() {
        //assign token value
        if ($this->isAjaxController() || Http::isAjaxRequest())
            $this->addAjaxDatas('token', $this->_crsf->get());
        else
            $this->tpl->setVar('token', $this->_crsf->get());
    }

    private function _checkLogin() {
        $isAdmin = false;
        $withCookie = false;
        if (is_null($this->session->get('admin'))) {
            //check cookie
            $cookie = Cookie::get('login');
            $cache = Cache::getCache('default');
            if ($cache->read('login-agent') == Http::getServer('HTTP_USER_AGENT') && $cache->read('login-cookie') == $cookie) {
                $isAdmin = true;
                $withCookie = true;
            }
        } else
            $isAdmin = true;

        if ($isAdmin)
            $this->_connect($withCookie);
        else {
            //redirect
            if ($this->router->getCurrentRoute() != 'login')
                $this->_redirect(Router::getUrl('login'), true);
        }
    }

    private function _connect($createCookie = false) {
        //set admin session
        $this->session->add('admin', true, true, true)/* ->regenerateId() */;

        // create cookie
        if (Http::getPost('admin-cookie') == 'on' || $createCookie) {
            $token = Tools::generateInt();
            $agent = Http::getServer('HTTP_USER_AGENT');
            $cookie = Hash::hashString($token . $agent, Hash::ALGORITHM_SHA256, false, 10);
            new Cookie('login', $cookie, true, Cookie::EXPIRE_TIME_WEEK, null, null, false, true);
            //create cache informations
            $cache = Cache::getCache('default');
            $cache->write('login-cookie', $cookie, true);
            $cache->write('login-agent', $agent, true);
        }
    }

    protected function _redirect($url, $allowAjaxRedirect = false) {
        if ($this->isAjaxController() || Http::isAjaxRequest())
            $this->notifyError('redirecting', array('url' => $url));

        if (!$this->isAjaxController() || !Http::isAjaxRequest() || $allowAjaxRedirect)
            Http::redirect($url);
    }

    protected function _read($modelType, $id) {
        $cache = $this->_cache->read($modelType . $id);
        if (!is_null($cache) && !Application::getDebug())
            $data = $cache;
        else {
            $manager = Model::factoryManager($modelType, 'default', $modelType);
            $data = $manager->read($id);
            if (!is_null($data))
                $this->_cache->write($modelType . $id, $data, true);
        }

        return $data;
    }

    protected function _readAll($modelType) {
        $cache = $this->_cache->read($modelType . 'List');
        if (!is_null($cache) && !Application::getDebug())
            $datas = $cache;
        else {
            $manager = Model::factoryManager($modelType, 'default', $modelType);
            $datas = $manager->readAll();
            if (!is_null($datas))
                $this->_cache->write($modelType . 'List', $datas, true);
        }

        return $datas;
    }

}

?>