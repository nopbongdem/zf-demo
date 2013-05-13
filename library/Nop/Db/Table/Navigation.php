<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Db_Table_Navigation extends Zend_Db_Table_Abstract {

    protected $_name = "auth_navigation";

    public function listMenu() {
        $select = $this->_db->select()
                ->from($this->_name)
                ->order('id ASC');
        return $this->_db->fetchAll($select);
    }

    public function listNavigation() {
        $select = $this->_db->select()
                ->from($this->_name)
                ->group("controller")
                ->order('controller ASC');
        return $this->_db->fetchAll($select);
    }

    public function getNavigation($id) {
        $select = $this->_db->select()
                ->from($this->_name)
                ->where("id = ?", $id)
                ->limit(1)
        ;
        return $this->_db->fetchRow($select);
    }

    public function showMenu($parentID, $url) {
        $name = $url;
        $select = $this->_db->select()
                ->from($this->_name)
                ->where("parent_id = ?", $parentID)
                ->order("rang ASC")
        ;
        $result = $this->_db->fetchAll($select);

        if (count($result) > 0) {

            echo "\n";
            echo "<ol class='dd-list'>\n";
            foreach ($result as $row) {
                echo "\n";

                echo "<li id='menu{$row['id']}' class='dd-item' data-id='{$row['id']}'>\n";
                if ($row['status'] == 1) {
                    echo "<p class='right' onclick='status({$row['id']});'><i id='img-status{$row['id']}' class='front-on'></i></p>";
                } elseif ($row['status'] == 0) {
                    echo "<p class='right' onclick='status({$row['id']});'><i id='img-status{$row['id']}' class='front-off'></i></p>";
                }
                echo "<p class='right' id='delete' onclick='check_del({$row['id']});'><i class='icon-trash'></i></p>";
                echo "<a class='right' id='delete' href='{$name}'/edit/id/'{$row['id']}'><i class='icon-pencil'></i></a>";
                echo "<div class='dd-handle'>{$row['id']}: {$row['name']}";

                echo "</div>\n";

                $this->showMenu($row['id'], $name);

                echo "</li>\n";
            }
            echo "</ol>\n";
        }
    }

    public function showMenus($parentID) {
        $select = $this->_db->select()
                ->from($this->_name)
                ->where("parent_id = ?", $parentID)
                ->order("rang ASC")
        ;
        $result = $this->_db->fetchAll($select);
        return $result;
    }

    public function deleteNavigation($parentID) {
        $w = "id =" . $parentID;
        $this->delete($w);

        $select = $this->_db->select()
                ->from($this->_name)
                ->where("parent_id = ?", $parentID)
                ->order("rang ASC")
        ;
        $result = $this->_db->fetchAll($select);
        if (count($result) > 0) {
            foreach ($result as $row) {
                $where = "id =" . $row['id'];
                $this->delete($where);
                $this->deleteNavigation($row['id']);
            }
        }
    }

    public function getByParentId($parentID) {
        $select = $this->_db->select()
                ->from($this->_name)
                ->where("parent_id = ?", $parentID)
                ->order("rang ASC")
        ;
        return $this->_db->fetchAll($select);
    }

}
