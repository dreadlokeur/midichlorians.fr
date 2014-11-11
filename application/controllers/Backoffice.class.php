<?php

/* TODO:
 */

namespace controllers;

use MidiChloriansPHP\mvc\Controller;
use MidiChloriansPHP\network\Http;
use MidiChloriansPHP\Session;
use MidiChloriansPHP\mvc\Router;
use MidiChloriansPHP\mvc\Template;
use MidiChloriansPHP\utility\Tools;
use MidiChloriansPHP\Application;
use MidiChloriansPHP\utility\Cookie;
use MidiChloriansPHP\security\cryptography\Hash;
use MidiChloriansPHP\Cache;
use libs\Model;

class Backoffice extends Controller {

    protected $_security = null;
    protected $_crsf = null;
    protected $_modelManager;
    protected $_modelObject;

    public function __construct() {
        // set backoffice template
        Template::setTemplate('backoffice');

        //cache
        $this->_cache = Cache::getCache('bdd');

        //check login
        $this->_checkLogin();

        // define template file
        $this->tpl->setFile('controllers' . DS . 'Backoffice' . DS . 'index.tpl.php');
    }

    public function login() {
        //already logged
        if (Session::getInstance()->get('admin'))
            Http::redirect(Router::getUrl('backoffice'));
        else {
            // POST LOGIN with AJAX
            if (Http::isPost() && $this->isAjaxController()) {
                //check password, username
                if (Application::getDebug() || (sha1(Http::getPost('admin-password')) == ADMIN_PASSWORD && Http::getPost('admin-username') == ADMIN_NAME)) {
                    $this->_connect();
                    $this->notifySuccess('Connecté avec success');
                }
            }

            // define login template
            $this->tpl->setFile('controllers' . DS . 'Backoffice' . DS . 'login.tpl.php', false, true);
        }
    }

    public function logout() {
        //delete session
        $this->session->delete('admin', true)->regenerateId();
        $this->log->debug('Session admin deleted');
        //delete cookie
        if (Cookie::get('login')) {
            $cookie = new Cookie('login', null, false);
            $cookie->delete();
            $this->log->debug('Cookie login deleted');
        }

        $this->notifySuccess('Déconnecté avec success');
    }

    public function home() {
        //define tpl vars
        $this->tpl->setVar('block', $this->tpl->getPath() . 'blocks' . DS . 'home.tpl.php', false, true);

        //ajax datas
        if ($this->isAjaxController())
            $this->tpl->setFile('blocks' . DS . 'home.tpl.php');
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
            if ($this->router->getCurrentRouteName() != 'login')
                Http::redirect(Router::getUrl('login'));
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