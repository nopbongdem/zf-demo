<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_AdminNavigationController extends Zend_Controller_Action {

//Mang tham so nhan duoc o moi Action
    protected $_arrParam;
    //Duong dan cua Controller
    protected $_currentController;
    //Duong dan cua Action chinh
    protected $_actionMain;
    //Thong so phan trang
    protected $_paginator = array(
        'itemCountPerPage' => 5,
        'pageRange' => 3,
    );
    protected $_namespace;

    public function init() {
        $this->view->headTitle("Admin Navigation", true);

        //Mang tham so nhan duoc o moi Action
        $this->_arrParam = $this->_request->getParams();

        //Duong dan cua Controller
        $this->_currentController = '/' . $this->_arrParam['module'] . '/' . $this->_arrParam['controller'];

        //Duong dan cua Action chinh		
        $this->_actionMain = '/' . $this->_arrParam['module'] . '/' . $this->_arrParam['controller'] . '/index';


        $this->_paginator['currentPage'] = $this->_request->getParam('page', 1);
        $this->_arrParam['paginator'] = $this->_paginator;

        //Luu cac du lieu filter vao SESSION
        //Dat ten SESSION
        $this->_namespace = $this->_arrParam['module'] . '-' . $this->_arrParam['controller'];
        $ssFilter = new Zend_Session_Namespace($this->_namespace);
        //$ssFilter->unsetAll();
        if (empty($ssFilter->col)) {
            $ssFilter->keywords = '';
            $ssFilter->col = 'g.id';
            $ssFilter->order = 'DESC';
        }
        $this->_arrParam['ssFilter']['keywords'] = $ssFilter->keywords;
        $this->_arrParam['ssFilter']['col'] = $ssFilter->col;
        $this->_arrParam['ssFilter']['order'] = $ssFilter->order;

        //Truyen ra view
        $this->view->arrParam = $this->_arrParam;
        $this->view->currentController = $this->_currentController;
        $this->view->actionMain = $this->_actionMain;


        parent::init();
    }

    public function indexAction() {
        //$baseUrl = $this->_request->getBaseUrl();
    }

    public function createAction() {
        $navi = new Nop_Db_Table_Navigation();
        $form = new Nop_Form_Model_Navigation();
        $authPer = new Nop_Db_Table_AuthPermission();
        $form->submit->setLabel("Save");
        $this->view->form = $form;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($form->isValid($data)) {
                unset($data['submit']);
                if ($data['action'] == null) {
                    $data['action'] = "index";
                }
                $data['status'] = 1;
                if ($navi->insert($data)) {
                    $value = array(
                        'module' => $data['module'],
                        'controller' => $data['controller'],
                        'action' => $data['action'],
                        'description' => $data['note'],
                    );

                    $authPer->save($value);
                    $this->_redirect($this->_currentController);
                }
            }
        }
    }

    public function editAction() {
        $id = $this->_arrParam['id'];
        $navi = new Nop_Db_Table_Navigation();
        $authPer = new Nop_Db_Table_AuthPermission();
        $values = $navi->getNavigation($id);
        $form = new Nop_Form_Model_Navigation();
        $form->populate($values);
        $form->submit->setLabel("Save");
        $this->view->form = $form;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($form->isValid($data)) {
                unset($data['submit']);
                $where = "id =" . $id;
                $value = array(
                    'module' => $data['module'],
                    'controller' => $data['controller'],
                    'action' => $data['action'],
                    'description' => $data['note'],
                );

                $authPer->save($value);
                if ($navi->update($data, $where)) {
                    $this->view->update = "Cập nhật thành công";
                }
            }
        }
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $navigation = new Nop_Db_Table_Navigation();
        // Get the JSON string
        $jsonstring = $_GET['jsonstring'];

// Decode it into an array
        $jsonDecoded = json_decode($jsonstring, true, 64);

        $readbleArray = $this->parseJsonArray($jsonDecoded);

        foreach ($readbleArray as $key => $value) {

            // $value should always be an array, but we do a check
            if (is_array($value)) {
                $where = "id =" . $value['id'];
                $update = array(
                    'rang' => $key,
                    'parent_id' => $value['parentID'],
                );
                $result[] = $navigation->update($update, $where);
                //$result = mysql_query("UPDATE menu_items SET rang='" . $key . "', parent_id = '" . $value['parentID'] . "' WHERE id='" . $value['id'] . "'") or die(mysql_error());
            }
        }
        $check = 1;
        foreach ($result as $item) {
            if ($item != 0) {
                $check = 2;
                break;
            }
        }
        if ($check == 2) {
            echo "Câp nhật thành công " . date("y-m-d H:i:s") . "!";
        }
    }

    public function parseJsonArray($jsonArray, $parentID = 0) {
        $return = array();
        foreach ($jsonArray as $subArray) {
            $returnSubSubArray = array();
            if (isset($subArray['children'])) {
                $returnSubSubArray = $this->parseJsonArray($subArray['children'], $subArray['id']);
            }
            $return[] = array('id' => $subArray['id'], 'parentID' => $parentID);
            $return = array_merge($return, $returnSubSubArray);
        }

        return $return;
    }

    public function activeAction() {
        $this->_helper->layout->disableLayout();
        $id = $this->_arrParam['id'];
        $navigation = new Nop_Db_Table_Navigation();
        $nav = $navigation->getNavigation($id);
        if ($nav) {
            $status = array();
            if ($nav['status'] == 1) {
                $data = array(
                    "status" => 0
                );
                $navigation->update($data, 'id =' . $id);
                $status['active'] = 'off';
                echo $this->_helper->json($status);
            } elseif ($nav['status'] == 0) {
                $data = array(
                    "status" => 1,
                );
                $navigation->update($data, 'id =' . $id);
                $status['active'] = 'on';
                echo $this->_helper->json($status);
            }
        }
    }

    public function delAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $id = $_POST['data'];
        $navigation = new Nop_Db_Table_Navigation();
        $navigation->deleteNavigation($id);
    }

}
