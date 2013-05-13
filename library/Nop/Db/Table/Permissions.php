<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Db_Table_Permissions extends Zend_Db_Table_Abstract {

    protected $_name = "auth_group_permission";

    public function getPermisByGroup($id) {
        $select = $this->_db->select()
                ->from($this->_name)
                ->where("group_id =?", $id)
        ;
        $return = $this->_db->fetchAll($select);
        if ($return)
            return $return;
        return false;
    }

    public function listPermisByGroup() {
        $select = $this->_db->select()
                ->from($this->_name)

        ;
        $return = $this->_db->fetchAll($select);
        if ($return)
            return $return;
        return false;
    }

}