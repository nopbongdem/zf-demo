<?php

class Nop_Application_Resource_Navigation {

    public function __construct() {

        $navigation = new Nop_Db_Table_Navigation();

        $result = $navigation->listMenu();

        $re = new Nop_System_Recursive($result);
        $pages = $re->buildRecursive(0);
//        print_r($pages);
//        exit;
//        $resource = new Zend_Application_Resource_Navigation(array(
//            'pages' => $pages,
//        ));
        //$resource->setBootstrap($this);
        return $pages;
        /*
          $language = $request->getParam('lang', '');
          if ($language !== 'en' && $language !== 'vi') {
          $request->setParam('lang', 'vi');
          }
          $language = $request->getParam('lang');
          if ($language == 'vi') {
          $local = 'vi_VN';
          }else
          $local = 'en_US';
          $zl = new Zend_Locale();
          $zl->setLocale($local);
          Zend_Registry::set('Zend_Locale', $zl);

          $translate = new Zend_Translate('csv', APPLICATION_PATH . '/languages/' . $language . '.csv', $language);
          Zend_Registry::set('Zend_Translate', $translate);
         */
    }

}

