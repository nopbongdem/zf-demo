<?php

class Nop_Db_Table_GroupsPermission extends Zend_Db_Table_Abstract {

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

    public function saveData($id, $value) {
        $list = $this->getPermisByGroup($id);
        $mark = array();
        if ($list) {
            foreach ($list as $key => $item) {
                if (in_array($item['permission_id'], $value)) {
                    $mark[] = $item['permission_id'];
                    unset($list[$key]);
                }
            }
            sort($list);
            foreach ($list as $items) {
                $where = 'group_id =' . $id . ' AND permission_id =' . $items['permission_id'];
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
                    'permission_id' => $items,
                );
                $this->insert($datas);
            }
        } else {
            foreach ($value as $items) {
                $data = array(
                    'group_id' => $id,
                    'permission_id' => $items,
                );
                $this->insert($data);
            }
        }
    }

}

?>