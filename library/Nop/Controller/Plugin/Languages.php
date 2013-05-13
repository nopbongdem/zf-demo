<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Controller_Plugin_Languages extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $config = Zend_Controller_Front::getInstance()
                ->getParam('bootstrap');
        //->getOptions();

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $id = md5($ip);

        $frontendParam = array(
            "lifetime" => null, //thời gian tồn tại của cache, giá trị null nghĩa là thời gian tồn tại vô hạn
            " automatic_serialization" => "true" //cho phép tự động serialize với các kiểu dữ liệu phức tạp
        );
        $backendParam = array(
            "cache_dir" => CACHE_PATH . "language/"    //chỉ định vị trí thư mục cache
        );
        $cache = Zend_Cache::factory("Core ", "File ", $frontendParam, $backendParam);

        //$langCache = $config->getResource('cachemanager')->getCache('language');


        $config->bootstrap('view');
        $view = $config->getResource('view');
//        $bootstrap = $this->getApplication();
//        $layout = $bootstrap->getResource('layout');
//        $view = $layout->getView();

        $translate = new Zend_Translate('gettext', APPLICATION_PATH . '/languages', null, array('scan' => Zend_Translate::LOCALE_FILENAME));

        $session = new Zend_Session_Namespace('lang');
        $locale = new Zend_Locale();
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $user = $auth->getStorage()->read()->username;
            $iduser = md5($user);
            if ($cache->load($iduser)) {
                $requestedLanguage = $cache->load($iduser);
                $locale->setLocale($requestedLanguage);
            } elseif (isset($session->language)) {
                $requestedLanguage = $session->language;
                $locale->setLocale($requestedLanguage);
            } else {
                $locale->setLocale(Zend_Locale::BROWSER);
                $requestedLanguage = key($locale->getBrowser());
            }
        } else {
            if ($cache->load($id)) {
                $requestedLanguage = $cache->load($id);
                $locale->setLocale($requestedLanguage);
            } elseif (isset($session->language)) {
                $requestedLanguage = $session->language;
                $locale->setLocale($requestedLanguage);
            } else {
                $locale->setLocale(Zend_Locale::BROWSER);
                $requestedLanguage = key($locale->getBrowser());
            }
        }


        if (in_array($requestedLanguage, $translate->getList())) {
            $language = $requestedLanguage;
        } else {
            $language = 'vi_VN';
        }

        Zend_Registry::set('locale', $locale);
        $translate->setLocale($language);
        $view->translate = $translate;
        //print_r($translate);
        Zend_Registry::set('translate', $translate);
    }

}