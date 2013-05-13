<?php

class Admin_GroupPermissionController extends Zend_Controller_Action {

    protected $_arrParam;
    protected $_currentController;
    protected $_actionMain;
    protected $_paginator = array(
        'itemCountPerPage' => 5,
        'pageRange' => 3,
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
        $this->view->headTitle("Groups Permission", true);
        $this->view->add = "Thêm mới";
        $group = new Nop_Db_Table_AuthGroup();
        $this->view->list = $group->listGroups();
    }

    public function createAction() {
        $this->view->headTitle("New Groups Permission", true);
        $this->view->back = "Back Index";
        $group = new Nop_Db_Table_AuthGroup();
        $list = $group->listGroups();
        $value = array();
        foreach ($list as $item) {
            $value[$item['id']] = $item['name'];
        }
        $form = new Nop_Form_Model_AuthGroup(null, $value);
        $form->submit->setLabel("Save");
        $this->view->form = $form;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($form->isValid($data)) {
                unset($data['submit']);
                if ($group->insert($data)) {
                    $this->_redirect($this->_currentController);
                }
            }
        }
    }

    public function getAction() {
        $this->view->headTitle("Phân quyền ", true);
        $id = $this->_arrParam['id'];
        $user = new Nop_Db_Table_User();
        //$belog = new Nop_Db_Table_AuthBelong();
        $listuser = $user->listUser();
        $per = new Nop_Db_Table_AuthPermission();
        $group = new Nop_Db_Table_AuthGroup();
        $igroup = $group->getGroup($id);
        $list = $per->getList();
        foreach ($list as $key => $item) {
            if ($item['description'] == null) {
                $des = $item['module'] . "_" . $item['controller'] . "_" . $item['action'];
                //unset($list[$key]['description']);
                $list[$key]['description'] = $des;
            }
        }
        $this->view->data = $list;
        $this->view->user = $listuser;
        $this->view->group = $igroup;
    }

    public function addAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $gPermis = new Nop_Db_Table_GroupsPermission();
        $belong = new Nop_Db_Table_AuthBelong();
        $id = $_POST['id'];

        if ($_POST['value']) {
            $data = $_POST['value'];
            foreach ($data as $vl) {
                if ($vl != null) {
                    $arr[] = $vl;
                }
            }

            $gPermis->saveData($id, $arr);
        } else {
            $where = "group_id =" . $id;
            $gPermis->delete($where);
        }
        if ($_POST['user']) {
            $user = $_POST['user'];
            $belong->saveData($id, $user);
        } else {
            $where = "group_id =" . $id;
            $belong->delete($where);
        }
    }

}