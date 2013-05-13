<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Controller_Plugin_Error extends Zend_Controller_Plugin_Abstract {

    public function __construct() {
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array(
            'module' => 'error',
            'controller' => 'error',
            'action' => 'error'
        )));
    }

}
