<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Controller_Plugin_Navigation extends Zend_Controller_Plugin_Abstract {

    private $_name = "auth_navigation";

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        $dbOptions = Zend_Controller_Front::getInstance()
                        ->getParam('bootstrap')->getOption('resources');
        $dbOption = $dbOptions['db'];

        // Setup database
        $db = Zend_Db::factory($dbOption['adapter'], $dbOption['params']);

        $db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $db->query("SET NAMES 'utf8'");
        $db->query("SET CHARACTER SET 'utf8'");

        $select = $db->select()
                ->from($this->_name)
                ->where("status =?", 1)
                ->order('rang ASC');
        $result = $db->fetchAll($select);
        
        $re = new Nop_System_Recursive($result);
        $pages = $re->buildRecursive(0);

        //echo "<pre>";
        //print_r($pages);exit;
        $container = new Zend_Navigation($pages);

        $config = Zend_Controller_Front::getInstance()
                ->getParam('bootstrap');
        //$config->bootstrap('view');
        //$view = $config->getResource('view');
        $config->bootstrap('layout');
        $layout = $config->getResource('layout');
        $view = $layout->getView();
// Store the container in the proxy helper:
        //$view->getHelper('navigation')->setContainer($container);
// ...or simply:
        $view->navigation($container)
                ->setTranslator(Zend_Registry::get('translate'));
    }

}
