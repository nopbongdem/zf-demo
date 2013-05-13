<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_AdminUserManagementController extends Zend_Controller_Action {

    protected $_arrParam;
    protected $_currentController;
    protected $_actionMain;
    protected $_paginator = array(
        'itemCountPerPage' => 5,
        'pageRange' => 3,
    );
    protected $_namespace;

    public function init() {

        $this->view->headTitle("Quản lý user ", true);

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
        $this->view->add = "Add new";
        $user = new Nop_Db_Table_User();
        $this->view->list = $user->listUser();
    }

    public function createAction() {
        $encode = new Nop_Encode();
        $user = new Nop_Db_Table_User();
        $form = new Nop_Form_Model_User();
        $form->submit->setLabel("Save");
        $this->view->form = $form;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($form->isValid($data)) {
                unset($data['submit']);
                $data['created_at'] = date("Y-m-d H:i:s", time());
                $data['is_active'] = 1;
                $data['password'] = $encode->encode($data['password']);
                if ($user->insert($data)) {
                    $this->_redirect($this->_currentController);
                }
            }
        }
    }

    public function getAction() {
        $this->view->headTitle("Quản lý user ", true);        
        $id = $this->_arrParam['id'];
        $user = new Nop_Db_Table_User();
        $group = new Nop_Db_Table_AuthGroup();
        $belog = new Nop_Db_Table_AuthBelong();
        $values = $belog->getAuthBelogByUser($id);
        $list = $group->listGroups($id);
        foreach ($list as $item) {
            $value[$item['id']] = $item['name'];
        }
        $form = new Nop_Form_Model_AuthBelong(null, $value);
        if ($values != null) {
            $form->populate($values);
        }
        $this->view->form = $form;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($form->isValid($data)) {
                unset($data['submit']);
                $array = array(
                    'user_id' => $id,
                    "group_id" => $data['group_id']
                );
                if ($belog->savePermission($array)) {
                    $this->view->update = "Cập nhật thành công";
                }
            }
        }
        $info = $user->getUser($id);
        $this->view->user = $info;
        $this->view->group = $list;
    }

    public function editAction() {        
        $id = $this->_arrParam['id'];
        $encode = new Nop_Encode();
        $user = new Nop_Db_Table_User();
        $values = $user->getUser($id);
        unset($values['password']);
        $form = new Nop_Form_Model_User();
        $form->populate($values);
        $this->view->form = $form;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($form->isValid($data)) {
                unset($data['submit']);
                if ($data['password'] == null) {
                    unset($data['password']);
                } else {
                    $data['password'] = $encode->encode($data['password']);
                }
                $where = "id =" . $values['id'];
                if ($user->update($data, $where)) {
                    $this->view->update = "Cập nhật thành công";
                }
            }
        }
    }

    public function activeAction() {
        $this->_helper->layout->disableLayout();
        $id = $this->_arrParam['id'];
        $navigation = new Nop_Db_Table_User();
        $nav = $navigation->getUser($id);
        if ($nav) {
            $status = array();
            if ($nav['is_active'] == 1) {
                $data = array(
                    "is_active" => 0
                );
                $navigation->update($data, 'id =' . $id);
                $status['active'] = 'off';
                echo $this->_helper->json($status);
            } elseif ($nav['is_active'] == 0) {
                $data = array(
                    "is_active" => 1,
                );
                $navigation->update($data, 'id =' . $id);
                $status['active'] = 'on';
                echo $this->_helper->json($status);
            }
        }
    }

    public function supperAdminAction() {
        $this->_helper->layout->disableLayout();
        $id = $this->_arrParam['id'];
        $navigation = new Nop_Db_Table_User();
        $nav = $navigation->getUser($id);
        if ($nav) {
            $status = array();
            if ($nav['is_super_admin'] == 1) {
                $data = array(
                    "is_super_admin" => 0
                );
                $navigation->update($data, 'id =' . $id);
                $status['active'] = 'off';
                echo $this->_helper->json($status);
            } elseif ($nav['is_super_admin'] == 0) {
                $data = array(
                    "is_super_admin" => 1,
                );
                $navigation->update($data, 'id =' . $id);
                $status['active'] = 'on';
                echo $this->_helper->json($status);
            }
        }
    }

    public function addAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $belong = new Nop_Db_Table_AuthBelong();
        $id = $_POST['id'];
        if ($_POST['value']) {
            $user = $_POST['value'];
            $belong->save($id, $user);
        } else {
            $where = "group_id =" . $id;
            $belong->delete($where);
        }
    }

}
