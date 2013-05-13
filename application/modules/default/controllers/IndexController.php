<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        $this->view->headTitle("Home Page", TRUE);
        parent::init();
    }

    public function indexAction() {

//        $dbcache = $this->getInvokeArg('bootstrap')->getResource('cachemanager')->getCache('dbcache');
//        $id = 'list_user';
//
//        if (!$dbcache->load($id)) {
//            $muser = new Nop_Db_Table_User();
//            $data = $muser->listUser();
//            $dbcache->save($data, $id);
//            $this->view->data = $dbcache->load($id);
//        }
//        $this->view->data = $dbcache->load($id);
    }

    public function testAction() {
        $this->_helper->viewRenderer->setNoRender();
        $frontendOptions = array(
            'lifetime' => 3,
            'automatic_serialization' => true
        );
        $backendOptions = array(
            'cache_dir' => CACHE_PATH
        );
        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        $cachedAction = $cache->load($this->_request->getActionName());

        if (!$cachedAction) {
            //$this->view->foo = 'bar';
            $cachedAction = $this->view->render($this->getViewScript());            
            $cache->save($cachedAction, $this->_request->getActionName());
        } else {
            $this->view->placeholder('cache')->set($cachedAction);
            $this->_helper->layout()->cache = $cachedAction;
            /*
             * Here the view script must not be executed, but the view must be
             * replaced by $cachedAction. It should be placed in layout as
             * $this->layout()->content
             */
        }
    }

}