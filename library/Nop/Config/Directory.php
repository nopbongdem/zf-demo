<?php

class Nop_Config_Directory {

    protected static $_environment = null;

    public static function loadConfig($path, $environment) {
        self::$_environment = $environment;

        if (is_string($path) && is_dir($path)) {
            $config = array();
            require_once 'Nop/Iterator/Directory.php';
            $iterator = new Nop_Iterator_Directory($path);
            foreach ($iterator as $file) {
                $suffix = strtolower(pathinfo($file->getPathName(), PATHINFO_EXTENSION));

                switch ($suffix) {
                    case 'ini':
                    case 'xml':
                    case 'php':
                    case 'inc':
                        $result = self::_loadConfig($file->getPathName());
                        $config = array_merge_recursive($config, $result);
                }
            }
            return $config;
        }

        throw new Exception('Path must be a directory', 500);
    }

    /**
     * @see Zend_Application->_loadConfig();
     */
    protected static function _loadConfig($file) {
        $environment = self::$_environment;
        $suffix = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        switch ($suffix) {
            case 'ini':
                $config = new Zend_Config_Ini($file, $environment);
                break;

            case 'xml':
                $config = new Zend_Config_Xml($file, $environment);
                break;

            case 'php':
            case 'inc':
                $config = include $file;
                if (!is_array($config)) {
                    throw new Zend_Application_Exception('Invalid configuration file provided; PHP file does not return array value');
                }
                return $config;
                break;

            default:
                throw new Zend_Application_Exception('Invalid configuration file provided; unknown config type');
        }

        return $config->toArray();
    }

}