<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Application extends Zend_Application {

    public function __construct($environment, $options = null) {
        $this->_environment = (string) $environment;
        require_once 'Nop/Config/Directory.php';
        require_once 'Zend/Loader/Autoloader.php';
        $this->_autoloader = Zend_Loader_Autoloader::getInstance();

        if (is_string($options) && is_dir($options)) {
            $config = Nop_Config_Directory::loadConfig($options, $environment);
            $this->setOptions($config);
        } else {
            if (null !== $options) {
                if (is_string($options)) {
                    $options = $this->_loadConfig($options);
                } elseif ($options instanceof Zend_Config) {
                    $options = $options->toArray();
                } elseif (!is_array($options)) {
                    throw new Zend_Application_Exception('Invalid options provided; must be location of config file, a config object, or an array');
                }

                $this->setOptions($options);
            }
        }
    }

    /**
     * Set options for Centurion_Config_Manager
     *
     * @param array $options Options
     * @return Centurion_Application
     */
    public function setOptions(array $options) {
        parent::setOptions($options);
        Nop_Config_Manager::add($options);

        return $this;
    }

}
