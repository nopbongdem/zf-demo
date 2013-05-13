<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_LoginController extends Zend_Controller_Action {

    protected $_arrParam;
    protected $_currentController;
    protected $_actionMain;
    protected $_paginator = array(
        'itemCountPerPage' => 5,
        'pageRange' => 3,
    );
    protected $_namespace;

    public function init() {
        $this->_helper->layout->setLayout("login");
        $this->view->headTitle("Login Admin managerment", true);
        $this->_arrParam = $this->_request->getParams();

        $this->_currentController = '/' . $this->_arrParam['module'] . '/' . $this->_arrParam['controller'];

        $this->_actionMain = '/' . $this->_arrParam['module'] . '/' . $this->_arrParam['controller'] . '/index';


        $this->_paginator['currentPage'] = $this->_request->getParam('page', 1);
        $this->_arrParam['paginator'] = $this->_paginator;

        $this->_namespace = $this->_arrParam['module'] . '-' . $this->_arrParam['controller'];
        $ssFilter = new Zend_Session_Namespace($this->_namespace);

        if (empty($ssFilter->col)) {
            $ssFilter->keywords = '';
            $ssFilter->col = 'g.id';
            $ssFilter->order = 'DESC';
        }
        $this->_arrParam['ssFilter']['keywords'] = $ssFilter->keywords;
        $this->_arrParam['ssFilter']['col'] = $ssFilter->col;
        $this->_arrParam['ssFilter']['order'] = $ssFilter->order;

        $this->view->arrParam = $this->_arrParam;
        $this->view->currentController = $this->_currentController;
        $this->view->actionMain = $this->_actionMain;


        parent::init();
    }

    public function indexAction() {
        $authA = new Nop_AuthAdapter();
        $encode = new Nop_Encode();
        $user = new Nop_Db_Table_User();
        $authAdapter = $authA->getAuthAdapter();
        $form = new Nop_Form_Model_Login();
        $form->submit->setLabel("Login");
        $this->view->form = $form;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($form->isValid($data)) {
                unset($data['submit']);
                $pass = $encode->encode($data['password']);
                $authAdapter->setIdentity($data['username'])
                        ->setCredential($pass)
                ;
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);
                
                if ($result->isValid()) {
                    $username = $auth->getIdentity();
                    $check = $user->checkUser($username);
                    if ($check != null) {
                        $identity = $authAdapter->getResultRowObject();
                        $authStorage = $auth->getStorage();
                        $authStorage->write($identity);
                        $this->_redirect("/admin");
                    } else {
                        $auth->clearIdentity();
                        echo "Tài khoản của bạn tạm khóa";
                    }
                } else {
                    echo "Sai tên đăng nhập hoặc mật khẩu";
                }
            }
        }
    }

    public function logoutAction() {
        $this->_helper->viewRenderer->setNoRender();
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_redirect("/admin");
    }

}
