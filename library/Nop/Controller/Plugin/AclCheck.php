<?php

class Nop_Controller_Plugin_AclCheck extends Zend_Controller_Plugin_Abstract {

    private $_acl = null;
    private $_auth = null;

    public function __construct(Zend_Acl $acl, Zend_Auth $auth) {
        $this->_acl = $acl;
        $this->_auth = $auth;
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $layoutPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . "/modules/" . $module . DIRECTORY_SEPARATOR . "/configs";

        $filename = $layoutPath . "/module.ini";
        $identity = $this->_auth->getStorage()->read();
        if (file_exists($filename)) {
            $configs = new Zend_Config_Ini($filename, "role");
            $config = $configs->toArray();
            if (isset($config['role']) && $config['role'] == "admin") {
                if ($identity) {
                    //$role = !empty($identity->role) ? $identity->role : 'admin';
                    $username = $identity->id;
                    $supper = $identity->is_super_admin;
                    $belong = new Nop_Db_Table_AuthBelong();
                    $list = $belong->getAuthBelogByUser($username);
                    if ($supper == 1) {
                        $role = "administrator";
                        Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($role);
                        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($this->_acl);
                        if ($module != "home" && $module != "error") {
                            if (!$this->_acl->isAllowed($role, $module . ":" . $controller, $action)) {
                                exit("Could not permission: " . $controller . DIRECTORY_SEPARATOR . $action);
                            }
                        }
                    } elseif (isset($list) && $list != null) {

                        $role = MD5($list['group_id']);
                        Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($role);
                        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($this->_acl);
                        if (!$this->_acl->isAllowed($role, $module . ":" . $controller, $action)) {
                            throw new Nop_Controller_Exception("Bạn không có quyền truy cập tới action này");                            
                        }
                    }
                } else {
                    $role = "guest";
                    if (!$this->_acl->isAllowed($role, $module . ":" . $controller, $action)) {
                        throw new Nop_Controller_Exception("Bạn không có quyền truy cập tới action này" );                        
                    }
                }
            }
        }
    }

}

