<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    private $_auth;
    private $_acl;

    protected function _initDb() {

        $dbOptions = $this->getOption('resources');
        $dbOption = $dbOptions['db'];

        // Setup database
        $db = Zend_Db::factory($dbOption['adapter'], $dbOption['params']);

        $db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $db->query("SET NAMES 'utf8'");
        $db->query("SET CHARACTER SET 'utf8'");

        Zend_Registry::set('connectDB', $db);

        //Khi thiet lap che do nay model moi co the su dung duoc
        Zend_Db_Table::setDefaultAdapter($db);

        return $db;
    }

    protected function _initAcl() {

        $this->_acl = new Nop_Permission_Acl();
        $this->_auth = Zend_Auth::getInstance();
        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Nop_Controller_Plugin_AclCheck($this->_acl, $this->_auth));
    }

//    protected function _initFrontController() {
//        $front = Zend_Controller_Front::getInstance();
//        $front->addControllerDirectory(APPLICATION_PATH . "/controllers");
//
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini', 'thietlap');
//        $router = new Zend_Controller_Router_Rewrite();
//
//        $router = $router->addConfig($config, 'routes');
//
//        $front->setRouter($router);
//
//        return $front;
//    }

}
