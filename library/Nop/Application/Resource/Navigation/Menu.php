<?php

class Nop_Application_Resource_Navigation_Menu extends Zend_Db_Table_Abstract {

    protected $_name = "menu_items";

    public function showMenu($parent) {
        $sql = $this->_db->select()
                ->from($this->_name)
                ->where("parent_id =?", $parent)
                ->order("rang DESC")
        ;
        $return = $this->_db->fetchAll($sql);
        if (count($return) > 0) {
            echo "\n";
            echo "<ol class='dd-list'>\n";
            foreach ($return as $item) {
                echo "\n";

                echo "<li class='dd-item' data-id='{$item['id']}'>\n";
                echo "<div class='dd-handle'>{$item['id']}: {$item['name']}</div>\n";

                showMenu($item['id']);

                echo "</li>\n";
            }
            echo "</ol>\n";
        }
    }

    public function build_Navigation($parentID) {
        $sql = $this->_db->select()
                ->from($this->_name)
                ->where("parent_id =?", $parentID)
                ->order("rang DESC")
        ;
        $return = $this->_db->fetchAll($sql);
        if (count($return) > 0) {
            echo "\n";
            echo "<ol class='dd-list'>\n";
            foreach ($return as $item) {
                echo "\n";

                echo "<li class='dd-item' data-id='{$item['id']}'>\n";
                echo "<div class='dd-handle'>{$item['id']}: {$item['name']}</div>\n";

                showMenu($item['id']);

                echo "</li>\n";
            }
            echo "</ol>\n";
        }
    }

    public function menu_showNested($parentID) {
        global $connection;

        $sql = "SELECT * FROM menu_items WHERE parents ='$parentID' ORDER BY rang";
        $result = mysql_query($sql, $connection);
        $numRows = mysql_num_rows($result);

        if ($numRows > 0) {
            echo "\n";
            echo "<ol class='dd-list'>\n";
            while ($row = mysql_fetch_array($result)) {
                echo "\n";

                echo "<li class='dd-item' data-id='{$row['id']}'>\n";
                echo "<div class='dd-handle'>{$row['id']}: {$row['name']}</div>\n";

                // Run this function again (it would stop running when the mysql_num_result is 0
                menu_showNested($row['id']);

                echo "</li>\n";
            }
            echo "</ol>\n";
        }
    }

}

