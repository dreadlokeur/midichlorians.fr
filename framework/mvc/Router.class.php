<?php

namespace framework\mvc;

use framework\Application;
use framework\Cli;
use framework\Language;
use framework\Logger;
use framework\Security;
use framework\mvc\Template;
use framework\mvc\router\Route;
use framework\network\Http;
use framework\network\http\ResponseCode;
use framework\network\http\Header;
use framework\network\http\Protocol;
use framework\utility\Benchmark;

class Router {

    use \framework\pattern\Singleton;

    protected static $_routes = array();
    protected static $_host = '';
    protected $_controllersNamespace = 'controllers';
    protected $_namespaceSeparator = '\\';
    protected $_urlParameterKey = false;
    protected $_currentRouteName = null;
    protected $_currentRoute = null;
    protected $_currentRule = null;
    protected $_currentController = null;
    protected $_currentControllerName = null;

    protected function __construct() {
        if (Application::getProfiler())
            Benchmark::getInstance('router')->startTime()->startRam();

        Logger::getInstance()->addGroup('router', 'Router Benchmark and Informations', true);
    }

    public static function addRoute(Route $route, $forceReplace = false) {
        $name = $route->getName();
        if (!is_string($name) && !is_int($name))
            throw new \Exception('Route name must be string or integer');

        if (array_key_exists($name, self::$_routes)) {
            if (!$forceReplace)
                throw new \Exception('Route : "' . $name . '" already defined');

            Logger::getInstance()->debug('Route : "' . $name . '" already defined, was overloaded');
        }

        self::$_routes[$name] = $route;
    }

    public static function getRoute($routeName) {
        if (array_key_exists($routeName, self::$_routes))
            return self::$_routes[$routeName];

        return false;
    }

    public static function getRoutes() {
        return self::$_routes;
    }

    public function runRoute($routeName, $vars = array(), $die = false) {
        $route = self::getRoute($routeName);
        if ($route) {
            $this->_setCurrentRoute($route);
            Logger::getInstance()->debug('Run route : "' . $routeName . '"', 'router');
            $this->_runController($vars);
        }
        if ($die)
            exit();
    }

    public static function getUrl($routeName, $vars = array(), $lang = null, $ssl = false, $ruleNumber = null, $varsSeparator = '/', $default = '') {
        $route = self::getRoute($routeName);

        if (!is_array($vars))
            throw new \Exception('Route : "' . $routeName . '" vars must be an array');

        //no exist route
        if (!$route)
            return $default;

        //config lang and ssl
        if ($lang === null)
            $lang = Language::getInstance()->getLanguage();
        if ($route->getRequireSsl())
            $ssl = true;

        $rules = $route->getRules();
        if (empty($rules))
            return self::getHost(true, $ssl);

        $ruleCount = 0;
        foreach ($rules as &$rule) {
            $matchedRule = self::_matchRule($route, $rule, $lang, $vars, $varsSeparator, $ruleNumber, $ruleCount);
            if ($matchedRule !== false)
                break;

            $ruleCount++;
        }
        // no matched rule, return rule 1
        $matchedRule = self::_matchRule($route, $rule, $lang, $vars, $varsSeparator, 1, 1);

        if ($matchedRule !== false)
            return self::getHost(true, $ssl) . $matchedRule;

        return $default;
    }

    public static function getUrls($lang = null, $ssl = false) {
        $urls = new \stdClass();
        foreach (self::$_routes as $route)
            $urls->{$route->getName()} = self::getUrl($route->getName(), array(), $lang, $ssl);

        return $urls;
    }

    public static function setHost($host) {
        if (!is_string($host))
            throw new \Exception('Host must a string');

        self::$_host = $host . ((substr($host, -1) != '/') ? '/' : '');
    }

    public static function getHost($url = false, $ssl = false, $stripLastSlash = false, $stripFirstSlash = false) {
        $host = self::$_host;
        if ($stripLastSlash)
            $host = rtrim($host, '/');
        if ($stripFirstSlash)
            $host = ltrim($host, '/');

        if ($url)
            return 'http' . ($ssl ? 's' : '') . '://' . $host;

        return $host;
    }

    public function setControllersNamespace($namespace, $namespaceSeparator = '\\') {
        if (!is_string($namespace))
            throw new \Exception('Controllers namespace must a string');
        $this->_controllersNamespace = $namespace;
        if (!is_string($namespaceSeparator))
            throw new \Exception('Namespace separator must be must a string');
        $this->_namespaceSeparator = $namespaceSeparator;
    }

    public function getControllersNamespace($withSeparator = false) {
        $ns = $this->_controllersNamespace;
        if ($withSeparator)
            $ns .= $this->getNamespaceSeparator();
        return $ns;
    }

    public function getNamespaceSeparator() {
        return $this->_namespaceSeparator;
    }

    public function setUrlParameterKey($key) {
        if (!is_int($key) && !Validate::isVariableName($key))
            throw new \Exception('Url parameter name must be an integer or a valid variable name');
        $this->_urlParameterKey = $key;
    }

    public function getCurrentRule() {
        return $this->_currentRule;
    }

    public function getCurrentRouteName() {
        return $this->_currentRouteName;
    }

    public function getCurrentRoute() {
        return $this->_currentRoute;
    }

    public function getCurrentController() {
        return $this->_currentController;
    }

    public function getCurrentControllerName() {
        return $this->_currentControllerName;
    }

    public function run() {
        if (empty(self::$_routes))
            throw new \Exception('No routes defined');

        //get http request URI (delete hostname)
        if (!$this->_urlParameterKey)
            $request = str_replace(self::getHost(), '', Http::getServer('HTTP_HOST') . Http::getServer('REQUEST_URI'));
        else//Or get url key parameter
            $request = Http::getQuery($this->urlParameterKey, '');

        Logger::getInstance()->debug('Run router for request : "' . $request . '"', 'router');
        $routeMatch = false;
        $routeIndex = self::getRoute('index');
        if ($request === '' && $routeIndex) {
            $routeMatch = true;
            $this->runRoute('index');
        } else {
            // each routes
            foreach (self::$_routes as $route) {
                $vars = array();
                // Check if have rules
                if (!$route->getRules())
                    continue;

                // each route rules
                $rules = $route->getRules();
                foreach ($rules as &$rule) {
                    Logger::getInstance()->debug('Try rule: "' . $rule . '"', 'router');
                    if ($route->getRegex())
                        $routeMatch = (boolean) preg_match('`^' . $rule . '$`iu', $request, $vars);
                    else
                        $routeMatch = ($request == $rule);


                    if ($routeMatch) {
                        $this->_setCurrentRule($rule);
                        Logger::getInstance()->debug('Match route : "' . $route->getName() . '" with rule : "' . $rule . '"', 'router');
                        break;
                    }
                }
                // If don't match, pass to next route
                if (!$routeMatch)
                    continue;

                // run route, and break
                if ($routeMatch) {
                    $this->runRoute($route->getName(), $vars);
                    break;
                }
            }
        }

        if (!$routeMatch) {
            Logger::getInstance()->debug('No route find', 'router');
            $this->show404();
        }
    }

    public function show400($die = false) {
        Header::setResponseStatusCode(ResponseCode::CODE_BAD_REQUEST, true);
        $this->runRoute('error', array(1 => 400), $die);
    }

    public function show401($die = false) {
        Header::setResponseStatusCode(ResponseCode::CODE_UNAUTHORIZED, true);
        $this->runRoute('error', array(1 => 401), $die);
    }

    public function show403($die = false) {
        Header::setResponseStatusCode(ResponseCode::CODE_FORBIDDEN, true, true, Protocol::PROTOCOL_VERSION_1_0);
        $this->runRoute('error', array(1 => 403), $die);
    }

    public function show404($die = false) {
        // Set Header
        // Use http protocol 1.0 look this : http://stackoverflow.com/questions/2769371/404-header-http-1-0-or-1-1
        // And http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
        // If use Http 1.1 protocol, header connection is keep-alive, else is close
        Header::setResponseStatusCode(ResponseCode::CODE_NOT_FOUND, true, true, Protocol::PROTOCOL_VERSION_1_0);
        $this->runRoute('error', array(1 => 404), $die);
    }

    public function show405($die = false) {
        Header::setResponseStatusCode(ResponseCode::CODE_METHOD_NOT_ALLOWED, true, true, Protocol::PROTOCOL_VERSION_1_0);
        $this->runRoute('error', array(1 => 404), $die);
    }

    public function show500($die = false) {
        Header::setResponseStatusCode(ResponseCode::CODE_INTERNAL_SERVER_ERROR, true);
        $this->runRoute('error', array(1 => 500), $die);
    }

    public function show503($die = false) {
        Header::setResponseStatusCode(ResponseCode::CODE_SERVICE_UNAVAILABLE, true);
        $this->runRoute('error', array(1 => 503), $die);
    }

    public function showDebugger($isException, $die = false) {
        Header::setResponseStatusCode(ResponseCode::CODE_INTERNAL_SERVER_ERROR, true);
        $this->runRoute('debugger', array(1 => $isException), $die);
    }

    protected function _runController($vars = array()) {
        $controllerExplode = explode($this->getNamespaceSeparator(), $this->getCurrentRoute()->getController());
        if (is_array($controllerExplode) && count($controllerExplode) > 1) {
            $controllerName = $this->getNamespaceSeparator() . ucfirst(array_pop($controllerExplode));
            $controllerName = implode($this->getNamespaceSeparator(), $controllerExplode) . $controllerName;
        } else
            $controllerName = ucfirst($this->getCurrentRoute()->getController());


        // Check if controller exists
        $classExist = false;

        //with controllers namespace
        $class = $this->getControllersNamespace(true) . $controllerName;
        if (class_exists($class)) {
            $classExist = true;
            $controllerClass = $this->getControllersNamespace(true) . $controllerName;
        } else {
            //without controllers namespace
            $class = $controllerName;
            if (class_exists($controllerName)) {
                $classExist = true;
                $controllerClass = $controllerName;
            }
        }
        if (!$classExist)
            throw new \Exception('Controller "' . $class . '" not found');




        Logger::getInstance()->debug('Try run controller : "' . $controllerClass . '"', 'router');
        if (!is_array($vars))
            throw new \Exception('Controller : "' . $controllerClass . '" vars must be an array');
        if (!is_array($this->getCurrentRoute()->getActions()))
            throw new \Exception('Controller : "' . $controllerClass . '" actions must be an array');

        $inst = new \ReflectionClass($controllerClass);
        if ($inst->isInterface() || $inst->isAbstract())
            throw new \Exception('Controller "' . $controllerClass . '" cannot be an interface of an abstract class');

        $this->_setCurrentController($inst->newInstance(), $controllerClass);
        if ($this->getCurrentController()->getAutoCallDisplay()) {
            if (!$inst->hasMethod('display'))
                throw new \Exception('Controller "' . $controllerClass . '" must be implement method "Diplay');
            if (!$inst->hasMethod('initTemplate'))
                throw new \Exception('Controller "' . $controllerClass . '" must be implement method "initTemplate');
        }

        if (!Cli::isCli()) {
            if (!Http::isHttps() && $this->getCurrentRoute()->getRequireSsl()) {
                Logger::getInstance()->debug('Route "' . $this->getCurrentRoute()->getName() . '" need ssl http request', 'router');
                $this->show400(true);
            }
            $httpMethods = $this->getCurrentRoute()->getRequireHttpMethods();
            if (!empty($httpMethods)) {
                if (!in_array(Http::getMethod(), $httpMethods)) {
                    Logger::getInstance()->debug('Route "' . $this->getCurrentRoute()->getName() . '" method: "' . Http::getMethod() . '" not allowed', 'router');
                    $this->show405(true);
                }
            }
            if (!Http::isAjax() && $this->getCurrentRoute()->getRequireAjax()) {
                Logger::getInstance()->debug('Route "' . $this->getCurrentRoute()->getName() . '" need ajax http request', 'router');
                $this->show400(true);
            }
            if (Http::isAjax() && $this->getCurrentRoute()->getAutoSetAjax())
                $this->getCurrentController()->setAjaxController();

            if (!is_null($this->getCurrentRoute()->getHttpResponseStatusCode()) || !is_null($this->getCurrentRoute()->getHttpProtocol()))
                Header::setResponseStatusCode(is_null($this->getCurrentRoute()->getHttpResponseStatusCode()) ? 200 : $this->getCurrentRoute()->getHttpResponseStatusCode(), true, true, $this->getCurrentRoute()->getHttpProtocol());
        }

        // security
        if (!is_array($this->getCurrentRoute()->getSecurity()))
            throw new \Exception('Controller : "' . $controllerClass . '" security must be an array');

        $security = $this->getCurrentRoute()->getSecurity();
        foreach ($security as &$secu) {
            $se = Security::getSecurity($secu);
            if ($se && !$se->getAutorun())
                $se->run();
        }

        if ($this->getCurrentRoute()->getActions()) {
            $actions = $this->getCurrentRoute()->getActions();
            foreach ($actions as $actionName => $actionParams) {
                Logger::getInstance()->debug('Call action : "' . $actionName . '"', 'router');
                if (!method_exists($this->getCurrentController(), $actionName) || !$inst->getMethod($actionName)->isPublic())
                    throw new \Exception('Action "' . $actionName . '" don\'t exists or isn\'t public on controller "' . $controllerClass . '"');

                $args = array();
                if (!is_array($actionParams))
                    $args[] = $actionParams;
                else {
                    foreach ($actionParams as $parameter) {
                        //check if is [['key']] type, or direct value
                        if (stripos($parameter, '[[') === false)
                            $args[] = $parameter;
                        else {
                            if (count($vars) > 0) {
                                $key = (int) str_replace(array('[', ']'), '', $parameter);
                                if (array_key_exists($key, $vars))
                                    $args[] = $vars[$key];
                            } else
                                $args[] = $parameter;
                        }
                    }
                }

                foreach ($args as $arg)
                    Logger::getInstance()->debug('Add argument : "' . $arg . '"', 'router');
                // Call method with $args
                \call_user_func_array(array($this->getCurrentController(), $actionName), $args);
            }
        }

        //call display only when have a template
        if ($this->getCurrentController()->getAutoCallDisplay() && Template::getTemplate()) {
            Logger::getInstance()->debug('Call action "display"', 'router');
            $this->getCurrentController()->display();
        }
    }

    protected static function _matchRule($route, $rule, $lang, $vars, $varsSeparator, $ruleNumber, $ruleCount) {
        $matched = false;
        $url = '';
        $args = preg_split('#(\(.+\))#iuU', $rule);
        foreach ($args as $key => $value) {
            //match by lang
            if ($lang !== null && $key == 0 && (stripos($value, $lang . $varsSeparator) !== false || $lang . $varsSeparator == $value || $lang == $value))
                $matched = true;
            // only one rule or rule number
            elseif (count($route->getRules()) == 1 || $ruleNumber === $ruleCount)
                $matched = true;

            //add argument (if exist)
            if ($matched) {
                $arg = array_key_exists($key, $vars) && $route->getRegex() ? rawurlencode($vars[$key]) : '';
                //empty arg
                if ($arg == '' && $value == $varsSeparator)
                    continue;

                $url .= $value . $arg;
            }
        }
        if (!empty($url))
            return rtrim($url, $varsSeparator);

        return $matched;
    }

    protected function _setCurrentRoute($currentRoute) {
        $this->_currentRoute = $currentRoute;
        $this->_currentRouteName = $this->_currentRoute->getName();
    }

    protected function _setCurrentRule($currentRule) {
        $this->_currentRule = $currentRule;
    }

    protected function _setCurrentController($controller, $name) {
        $this->_currentController = $controller;
        $this->_currentControllerName = $name;
    }

}

?>