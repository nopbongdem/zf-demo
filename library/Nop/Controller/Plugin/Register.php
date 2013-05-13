<?php

class Nop_Controller_Plugin_Register extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        $module = $request->getModuleName();
        if ($module == "admin") {
            $layoutPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . "/modules/" . $module . DIRECTORY_SEPARATOR . "/configs";
            $filename = $layoutPath . "/module.ini";
            if (file_exists($filename)) {
                $configs = new Zend_Config_Ini($filename, "controller");
                $config = $configs->toArray();
                if (isset($config['controller_name'])) {
                    $controller = $config['controller_name'];
                    $control = $request->getControllerName();
                    if (!in_array($control, $controller)) {
                        throw new Nop_Controller_Exception('(' . $control . ') chưa được khai báo trong configs/module.ini');
                    }
                } else {
                    throw new Nop_Controller_Exception('Không tìm thấy session controller_name trong configs/module.ini');
                }
            } else {
                throw new Nop_Controller_Exception('Không tìm thấy file module.ini trong ' . $layoutPath);
            }
        }
    }

}