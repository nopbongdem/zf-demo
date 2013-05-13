<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_LanguageController extends Zend_Controller_Action {

    protected $_cache;

    public function init() {
        $this->_helper->viewRenderer->setNoRender();
        $frontendParam = array(
            "lifetime" => null, //thời gian tồn tại của cache, giá trị null nghĩa là thời gian tồn tại vô hạn
            " automatic_serialization" => "true" //cho phép tự động serialize với các kiểu dữ liệu phức tạp
        );
        $backendParam = array(
            "cache_dir" => CACHE_PATH . "language/"    //chỉ định vị trí thư mục cache
        );
        $this->_cache = Zend_Cache::factory("Core ", "File ", $frontendParam, $backendParam);
    }

    public function viAction() {
        $locale = new Zend_Locale();
        $locale->setLocale("vi_VN");
        Zend_Registry::set("local", $locale);
        $session = new Zend_Session_Namespace('lang');
        $session->language = "vi_VN";
        $auth = Zend_Auth::getInstance();

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }


        $id = md5($ip);
        if ($auth->hasIdentity()) {
            $user = $auth->getStorage()->read()->username;
            $iduser = MD5($user);
            if (!$this->_cache->load($iduser)) {
                $lang = "vi_VN";
                $this->_cache->save($lang, $iduser);
            } else {
                $this->_cache->remove($iduser);
                $lang = "vi_VN";
                $this->_cache->save($lang, $iduser);
            }
        } else {
            if (!$this->_cache->load($id)) {
                $lang = "vi_VN";
                $this->_cache->save($lang, $id);
            } else {
                $this->_cache->remove($iduser);
                $lang = "vi_VN";
                $this->_cache->save($lang, $iduser);
            }
        }
    }

    public function enAction() {
        $locale = new Zend_Locale();
        $locale->setLocale("en_US");
        Zend_Registry::set("local", $locale);
        $session = new Zend_Session_Namespace('lang');
        $session->language = "en_US";

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $id = md5($ip);
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $user = $auth->getStorage()->read()->username;
            $iduser = MD5($user);
            if (!$this->_cache->load($iduser)) {
                $lang = "en_US";
                $this->_cache->save($lang, $iduser);
            } else {
                $this->_cache->remove($iduser);
                $lang = "en_US";
                $this->_cache->save($lang, $iduser);
            }
        } else {
            if (!$this->_cache->load($id)) {
                $lang = "en_US";
                $this->_cache->save($lang, $id);
            } else {
                $this->_cache->remove($iduser);
                $lang = "en_US";
                $this->_cache->save($lang, $iduser);
            }
        }
    }

}
