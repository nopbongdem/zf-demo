<?php

class Nop_Controller_Action extends Zend_Controller_Action {

    public function init() {
        parent::init();
    }

    public function createTemplate($dirTemplate, $sectionConfig = 'template', $fileConfig = 'template.ini') {

        $headLink = new Zend_View_Helper_HeadLink();
        $containerHeadLink = $headLink->getContainer();
        $containerHeadLink->exchangeArray(array());

        $headMeta = new Zend_View_Helper_HeadMeta();
        $containerHeadMeta = $headMeta->getContainer();
        $containerHeadMeta->exchangeArray(array());

        $headScript = new Zend_View_Helper_HeadScript();
        $containerHeadScript = $headScript->getContainer();
        $containerHeadScript->exchangeArray(array());

        $filename = $dirTemplate . '/' . $fileConfig;
        $section = $sectionConfig;
        $config = new Zend_Config_Ini($filename, $section);
        $config = $config->toArray();

        $baseUrl = $this->_request->getBaseUrl();
        $dirTemplateUrl = $baseUrl . $config['url'];

        $this->view->headTitle($config['title']);

        if (count($config['metaHttp']) > 0) {
            foreach ($config['metaHttp'] as $key => $metaHttp) {
                $tmp = explode('|', $metaHttp);
                $this->view->headMeta()->appendHttpEquiv($tmp[0], $tmp[1]);
            }
        }

        if (count($config['metaName']) > 0) {
            foreach ($config['metaName'] as $key => $metaName) {
                $tmp = explode('|', $metaName);
                $this->view->headMeta()->appendName($tmp[0], $tmp[1]);
            }
        }

        $dirCss = $dirTemplateUrl . $config['dirCss'];
        if (count($config['fileCss']) > 0) {
            foreach ($config['fileCss'] as $key => $css) {
                $this->view->headLink()->appendStylesheet($dirCss . $css, 'screen');
            }
        }

        $dirJs = $dirTemplateUrl . $config['dirJs'];
        if (count($config['fileJs']) > 0) {
            foreach ($config['fileJs'] as $key => $js) {
                $this->view->headScript()->appendFile($dirJs . $js, 'text/javascript');
            }
        }
        $this->view->dirCss = $dirTemplateUrl . $config['dirCss'];
        $this->view->dirImg = $dirTemplateUrl . $config['dirImg'];
        $this->view->dirJs = $dirTemplateUrl . $config['dirJs'];

        $option = array('layout' => $config['layout'],
            'layoutPath' => $dirTemplate);
        Zend_Layout::startMvc($option);
    }

}