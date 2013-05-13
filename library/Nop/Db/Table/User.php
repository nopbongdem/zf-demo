<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Db_Table_User extends Zend_Db_Table_Abstract {

    protected $_name = "auth_user";

    public function listUser() {
        $select = $this->_db->select()
                ->from($this->_name)
                ->order('username ASC');
        return $this->_db->fetchAll($select);
    }

    public function getUser($id) {
        $select = $this->_db->select()
                ->from($this->_name)
                ->where('id =?', $id);
        return $this->_db->fetchRow($select);
    }

    public function checkUser($username) {
        $select = $this->_db->select()
                ->from($this->_name)
                ->where("username =?", $username)
                ->where("is_active =?", 1)
        ;
        $result = $this->_db->fetchRow($select);
        if ($result)
            return $result;
        return FALSE;
    }

    public function getByUsername($value) {
        $select = $this->_db->select()
                ->from($this->_name)
                ->where('username =?', $value);
        return $this->_db->fetchRow($select);
    }

}