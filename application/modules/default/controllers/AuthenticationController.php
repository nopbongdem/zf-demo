<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AuthenticationController extends Zend_Controller_Action {

    public function signinAction() {
        $this->_helper->viewRenderer->setNoRender();
    }
    
    public function indexAction() {
        $this->_helper->viewRenderer->setNoRender();
    }

    public function signoutAction() {
        $this->_helper->viewRenderer->setNoRender();
    }

}
