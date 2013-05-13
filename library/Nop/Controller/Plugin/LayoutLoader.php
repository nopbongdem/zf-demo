<?php

class Nop_Controller_Plugin_LayoutLoader extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $config = Zend_Controller_Front::getInstance()
                        ->getParam('bootstrap')->getOptions();
//        $bootstrap = $this->getActionController()->getInvokeArg('bootstrap');
//        $config = $bootstrap->getOptions();
        $module = $request->getModuleName();
        if (isset($config[$module]['resources']['layout']['layout'])) {
            $layoutScript = $config[$module]['resources']['layout']['layout'];
            Zend_Layout::getMvcInstance()->setLayout($layoutScript);
        } else {
            $layoutScript = $config['resources']['layout']['layout'];
            Zend_Layout::getMvcInstance()->setLayout($layoutScript);
        }

        if (isset($config[$module]['resources']['layout']['layoutPath'])) {
            $Path = $config[$module]['resources']['layout']['layoutPath'];
            $layoutPath = APPLICATION_PATH . "/../public/templates/" . $Path;
            Zend_Layout::getMvcInstance()->setLayoutPath($layoutPath);
        } else {
            $Path = $config['resources']['layout']['layoutPath'];
            $layoutPath = APPLICATION_PATH . "/../public/templates/" . $Path;
            Zend_Layout::getMvcInstance()->setLayoutPath($layoutPath);
        }
    }

}