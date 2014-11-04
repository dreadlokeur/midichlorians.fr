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
        $this->tpl->setVar('references', $this->_readAll('reference', true), false, true);
        // set template file
        $this->tpl->setFile('controllers' . DS . 'Index' . DS . 'index.tpl.php');
    }

    public function github() {
        $cache = $this->_cache->read('github');
        if ($cache) {
            $commitsCount = $cache['commitCount'];
            $commits = $cache['commits'];
            $reposteries = $cache['reposteries'];
            $reposteriesCount = $cache['reposteriesCount'];
            $forks = $cache['forks'];
            $forksCount = $cache['forksCount'];
            $nodes = $cache['nodes'];
            $edges = $cache['edges'];
        } else {
            $client = new \GitHubClient();
            $reposteriesDatas = $client->repos->listUserRepositories(GITHUB_USER);
            $commitsCount = 0;
            $commits = array();
            $reposteries = array();
            $reposteriesCount = 0;
            $forks = array();
            $forksCount = 0;
            foreach ($reposteriesDatas as $repostery) {
                $client->setPage();
                $commitsRepo = $client->repos->commits->listCommitsOnRepository(GITHUB_USER, $repostery->getName(), null, null, GITHUB_USER);
                if ($repostery->getFork()) {
                    $forksCount++;
                    $forks[$repostery->getName()] = array('repo' => $repostery, 'commitsCount' => count($commitsRepo));
                } else {
                    $reposteriesCount++;
                    $reposteries[$repostery->getName()] = array('repo' => $repostery, 'commitsCount' => count($commitsRepo));
                }

                $commitsCount = $commitsCount + count($commitsRepo);
                $commits[$repostery->getName()] = $commitsRepo;

                $nodes = array();
                $edges = array();
                $i = 0;
                foreach ($reposteries as $repo) {
                    $nodes [] = array(
                        'id' => $repo['repo']->getName(),
                        'label' => $repo['repo']->getName(),
                        'x' => rand() + $i + 500,
                        'y' => 50,
                        'size' => $repo['commitsCount']
                    );
                    $i++;
                }
                foreach ($forks as $fork) {
                    $nodes [] = array(
                        'id' => $fork['repo']->getName(),
                        'label' => $fork['repo']->getName(),
                        'x' => rand() + $i + 500,
                        'y' => 50,
                        'size' => $fork['commitsCount']
                    );
                    $i++;
                }


                foreach ($commits as $commitRepoName => $commitDatas) {
                    foreach ($commitDatas as $commit) {
                        $nodes [] = array(
                            'id' => $commit->getSha(),
                            'label' => $commit->getCommit()->getMessage() == '' ? 'No commit message' : $commit->getCommit()->getMessage(),
                            'x' => rand() + $i + 500,
                            'y' => 50 + rand(),
                            'size' => 1,
                            'color' => 'red'
                        );
                        $edges[] = array('id' => $commit->getSha(), 'source' => $commit->getSha(), 'target' => $commitRepoName);
                        $i++;
                    }
                }
                $this->_cache->write('github', array(
                    'commitCount' => $commitsCount,
                    'commits' => $commits,
                    'reposteries' => $reposteries,
                    'reposteriesCount' => $reposteriesCount,
                    'forks' => $forks,
                    'forksCount' => $forksCount,
                    'nodes' => $nodes,
                    'edges' => $edges), true, Cache::EXPIRE_DAY);
            }
        }

        //put ajax datas
        $this->addAjaxDatas('commitsCount', $commitsCount);
        $this->addAjaxDatas('reposteriesCount', $reposteriesCount);
        $this->addAjaxDatas('forksCount', $forksCount);
        $this->addAjaxDatas('graph', array('nodes' => $nodes, 'edges' => $edges));
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

    private function _readAll($modelType, $option = false) {
        $cache = $this->_cache->read($modelType . 'List');
        if (!is_null($cache) && !Application::getDebug())
            $datas = $cache;
        else {
            $manager = Model::factoryManager($modelType);
            $datas = $manager->readAll($option);
            if (!is_null($datas) && !Application::getDebug())
                $this->_cache->write($modelType . 'List', $datas, true);
        }

        return $datas;
    }

}

?>