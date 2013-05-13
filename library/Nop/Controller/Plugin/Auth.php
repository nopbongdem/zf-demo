<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $module = $request->getModuleName();
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            if ($module == "admin") {
                $request->setModuleName('admin')
                        ->setControllerName('login')
                        ->setActionName('index');
            }
        }
    }

}
