<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Db_Table_AuthPermission extends Zend_Db_Table_Abstract {

    protected $_name = "auth_permission";

    public function totalPermission() {
        $select = $this->_db->select()
                ->from($this->_name, 'count(id) as total')
        ;
        $rowSet = $this->_db->fetchOne($select);
        return $rowSet;
    }

    public function getControllerByModule($module) {
        $select = $this->_db->select()
                ->from($this->_name)
                ->where("module =?", $module)
                ->group('controller')
        ;
        $rowSet = $this->_db->fetchAll($select);
        if ($rowSet)
            return $rowSet;
        return FALSE;
    }

    public function getList() {
        $select = $this->_db->select()
                ->from($this->_name)
                ->order('module ASC')
                ->order("controller ASC")
                ->order("action ASC")
        ;
        $rowSet = $this->_db->fetchAll($select);
        if ($rowSet)
            return $rowSet;
        return FALSE;
    }

    public function listPermission($arrParam) {

        $paginator = $arrParam['paginator'];
        if ($paginator['itemCountPerPage'] > 0) {
            $page = $paginator['currentPage'];
            $rowCount = $paginator['itemCountPerPage'];
        }
        $select = $this->_db->select()
                ->from($this->_name)
                ->order('module ASC')
                ->order("controller ASC")
                ->order("action ASC")
                ->limitPage($page, $rowCount)
        ;
        $rowSet = $this->_db->fetchAll($select);
        if ($rowSet)
            return $rowSet;
        return FALSE;
    }

    public function getPermis($id) {
        $select = $this->_db->select()
                ->from($this->_name)
                ->where("id =?", $id)
                ->limit(1)
        ;
        $return = $this->_db->fetchRow($select);
        if ($return)
            return $return;
        return false;
    }

    public function save($array) {
        $select = $this->_db->select()
                ->from($this->_name)
                ->where("module =?", $array['module'])
                ->where("controller =?", $array['controller'])
                ->where("action =?", $array['action'])
                ->limit(1)
        ;
        $return = $this->_db->fetchRow($select);
        if ($return != null) {
            $select->where("description =?", $array['description']);
            $check = $this->_db->fetchRow($select);
            if ($check == null) {
                $where = "id =" . $return['id'];
                $this->update($array, $where);
            }
        } else {
            $this->insert($array);
        }
    }

}

