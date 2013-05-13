<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Permission_Acl extends Zend_Acl {

    public function __construct() {
        $group = new Nop_Db_Table_AuthGroup();
        $per = new Nop_Db_Table_AuthPermission();
        $list = $group->listGroups();
        $this->addRole(new Zend_Acl_Role("guest"));
        $this->addRole(new Zend_Acl_Role("administrator"));
        foreach ($list as $it) {
            $this->addRole(new Zend_Acl_Role(MD5($it['id'])), "guest");
        }
        $dir = $file = APPLICATION_PATH . "/modules";
        if (is_dir($dir)) {
            $op = scandir($dir);
            unset($op[0]);
            unset($op[1]);
            sort($op);
            foreach ($op as $item) {
                $file = APPLICATION_PATH . "/modules/" . $item . "/configs/module.ini";
                if (file_exists($file)) {
                    $conf = new Zend_Config_Ini($file, "controller");
                    $con = $conf->toArray();
                    $configs = new Zend_Config_Ini($file, "role");
                    $config = $configs->toArray();

                    if (isset($config['role']) && $config['role'] == "admin") {
                        $listPer = $per->getControllerByModule($item);
                        foreach ($listPer as $val) {
                            $arrPer[] = $val['controller'];
                        }
                        foreach ($arrPer as $key => $check) {
                            if (in_array($check, $con['controller_name'])) {
                                unset($arrPer[$key]);
                            }
                        }
                        sort($arrPer);
                        if ($arrPer != null) {
                            foreach ($arrPer as $value) {
                                $this->addResource(new Zend_Acl_Resource($item . ":" . $value));
                            }
                        }
                        foreach ($con['controller_name'] as $items) {
                            $this->addResource(new Zend_Acl_Resource($item . ":" . $items));
                        }
                    }
                } else {
                    throw new Nop_Controller_Exception("Không tìm thấy file " . $file);
                }
            }
        } else {
            throw new Nop_Controller_Exception("Không tìm thấy thư mục " . $dir);
        }

        $groupPer = new Nop_Db_Table_Permissions();
        $listPermission = $groupPer->listPermisByGroup();
        $authPermis = new Nop_Db_Table_AuthPermission();
        foreach ($listPermission as $val) {
            $ifPermis = $authPermis->getPermis($val['permission_id']);
            $action = !empty($ifPermis['action']) ? $ifPermis['action'] : 'index';
            $this->allow(MD5($val['group_id']), $ifPermis['module'] . ":" . $ifPermis['controller'], $action);
        }

        $this->allow("administrator", null, null);
        $this->allow("guest", "admin:login", null);
        $this->allow("guest", "admin:index", null);
        //$this->allow("guest", "default:error", null);
        //$this->allow("guest", "error:error", null);
    }

}

