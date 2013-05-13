<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_AdminPermissionListController extends Zend_Controller_Action {

    protected $_arrParam;
    protected $_currentController;
    protected $_actionMain;
    protected $_paginator = array(
        'itemCountPerPage' => 20,
        'pageRange' => 5,
    );
    protected $_namespace;

    public function init() {

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
        $this->view->headTitle("Auth Permission", true);
        $this->view->add = "Add new";
        $group = new Nop_Db_Table_AuthPermission();
        $total = $group->totalPermission();
        $list = $group->listPermission($this->_arrParam);
        $this->view->list = $list;
        if ($total) {
            $paginator = new Nop_Paginator();
            $this->view->paginator = $paginator->createPaginator($total, $this->_paginator, null);
            $this->view->total = $total;
        }
    }

    public function createAction() {
        $form = new Nop_Form_Model_AuthPermission();
        $permis = new Nop_Db_Table_AuthPermission();
        $this->view->form = $form;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            
            if ($form->isValid($data)) {
                if ($data['action'] == null) {
                    $data['action'] = "index";
                }
                unset($data['submit']);
                if ($permis->insert($data)) {
                    $this->_redirect($this->_currentController);
                }
            }
        }
    }

    public function editAction() {
        $id = $this->_arrParam['id'];
        $form = new Nop_Form_Model_AuthPermission();
        $permis = new Nop_Db_Table_AuthPermission();
        $values = $permis->getPermis($id);
        $form->populate($values);
        $this->view->form = $form;
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            
            if ($form->isValid($data)) {
                
                unset($data['submit']);
                if ($data['action'] == null) {
                    $data['action'] = "index";                    
                }
                $where = "id =" . $id;
                if ($permis->update($data, $where)) {
                    $this->view->update = "Cập nhật thành công";
                }
            }
        }
    }

}