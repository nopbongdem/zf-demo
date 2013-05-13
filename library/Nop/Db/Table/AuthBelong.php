<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Db_Table_AuthBelong extends Zend_Db_Table_Abstract {

    protected $_name = "auth_belong";

    public function getAuthBelogByGroup($id) {
        $select = $this->_db->select()
                ->from($this->_name)
                ->where("group_id =?", $id)
        ;
        $return = $this->_db->fetchAll($select);
        if ($return)
            return $return;
        return false;
    }

    public function getAuthBelogByUser($id) {
        $select = $this->_db->select()
                ->from($this->_name)
                ->where("user_id =?", $id)
                ->limit(1)
        ;
        $return = $this->_db->fetchRow($select);
        if ($return)
            return $return;
        return false;
    }

    public function savePermission($array) {
        $belong = $this->getAuthBelogByUser($array["user_id"]);
        if ($belong != null) {
            $where = "user_id =" . $array["user_id"];
            $data = array(
                'group_id' => $array['group_id'],
            );
            $this->update($data, $where);
        } else {
            $this->insert($array);
        }
    }

    public function saveData($id, $value) {
        $list = $this->getAuthBelogByGroup($id);
        $mark = array();
        if ($list) {
            foreach ($list as $key => $item) {
                if (in_array($item['user_id'], $value)) {
                    $mark[] = $item['user_id'];
                    unset($list[$key]);
                }
            }
            sort($list);
            foreach ($list as $items) {
                $where = 'group_id =' . $id . ' AND user_id =' . $items['user_id'];
                $this->delete($where);
            }

            foreach ($value as $ky => $val) {
                if (in_array($val, $mark)) {
                    unset($value[$ky]);
                }
            }
            sort($value);
            foreach ($value as $items) {
                $datas = array(
                    'group_id' => $id,
                    'user_id' => $items,
                );
                $this->insert($datas);
            }
        } else {
            foreach ($value as $items) {
                $data = array(
                    'group_id' => $id,
                    'user_id' => $items,
                );
                $this->insert($data);
            }
        }
    }

    public function save($id, $value) {
        $list = $this->getAuthBelogByUser($id);
        $mark = array();
        if ($list) {
            foreach ($list as $key => $item) {
                if (in_array($item['group_id'], $value)) {
                    $mark[] = $item['group_id'];
                    unset($list[$key]);
                }
            }
            sort($list);
            foreach ($list as $items) {
                $where = 'user_id =' . $id . ' AND group_id =' . $items['group_id'];
                $this->delete($where);
            }

            foreach ($value as $ky => $val) {
                if (in_array($val, $mark)) {
                    unset($value[$ky]);
                }
            }
            foreach ($value as $items) {
                $datas = array(
                    'user_id' => $id,
                    'group_id' => $items,
                );
                $this->insert($datas);
            }
        } else {
            foreach ($value as $items) {
                $data = array(
                    'user_id' => $id,
                    'group_id' => $items,
                );
                $this->insert($data);
            }
        }
    }

}

