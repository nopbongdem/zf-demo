<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Db_Table_AuthGroup extends Zend_Db_Table_Abstract {

    protected $_name = "auth_group";

    public function listGroups() {
        $select = $this->_db->select()
                ->from($this->_name)
                ->order('name ASC')
        ;
        return $this->_db->fetchAll($select);
    }

    public function getGroup($id) {
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

}

