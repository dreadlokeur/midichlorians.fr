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
            if (!$this->_crsf->check(Http::getPost('backoffice-token'))) {
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
        //update token into session
        if ($this->_crsf) {
            $this->_crsf->set();
            $this->log->debug('Token security updated');
        }
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
        $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'home.tpl.php', false, true);
        //ajax datas
        if ($this->isAjaxController()) {
            $this->tpl->setFile('blocks' . DS . 'home.tpl.php');
            $this->setAjaxAutoAddDatas(true);
        }
    }

    private function _assignToken() {
        //assign token value
        if ($this->isAjaxController() || Http::isAjax())
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
        if ($this->isAjaxController() || Http::isAjax())
            $this->notifyError('redirecting', array('url' => $url));

        if (!$this->isAjaxController() || !Http::isAjax() || $allowAjaxRedirect)
            Http::redirect($url);
    }

    protected function _read($modelType, $id) {
        $manager = Model::factoryManager($modelType);
        return $manager->read($id);
    }

    protected function _readAll($modelType, $option = '') {
        $manager = Model::factoryManager($modelType);
        return $manager->readAll($option);
    }

}

?>