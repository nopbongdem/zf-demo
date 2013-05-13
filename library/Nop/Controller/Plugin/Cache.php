<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Controller_Plugin_Cache extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $frontendOptions = array(
            'lifetime' => 120,
            'content_type_memorization' => true,
            'default_options' => array(
                'cache' => true,
                'cache_with_get_variables' => true,
                'cache_with_post_variables' => true,
                'cache_with_session_variables' => true,
                'cache_with_cookie_variables' => true,
            ),
            'regexps' => array(
                '^/.*' => array('cache' => true),
                '^/index/' => array('cache' => true),
            )
        );

        $date = date("m-d-Y", time());
        if (!is_dir(CACHE_PATH . "/core/" . $date)) {
            mkdir(CACHE_PATH . "/core/" . $date);
            chmod(CACHE_PATH . "/core/" . $date, 0775);
        }
        $backendOptions = array(
            'cache_dir' => CACHE_PATH . "/core/" . $date . "/"
        );

        $cache = Zend_Cache::factory('Page', 'File', $frontendOptions, $backendOptions);

        $cache->start();
    }

}

?>
