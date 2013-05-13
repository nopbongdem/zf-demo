<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Navigation_Menu extends Zend_Db_Table_Abstract {
    
    public function menu_showNested($parentID) {
        

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
