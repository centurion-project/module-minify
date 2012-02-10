<?php
class Minify_Bootstrap extends Centurion_Application_Module_Bootstrap
{
    protected function _initHelper()
    {
        $this->bootstrap('frontController');
        $this->getApplication()->bootstrap('view');
        
        $frontController = $this->getResource('frontController');
        
        if ($this->getOption('html') == '1') {
            $minifyHmtl = new Minify_Plugin_Minify();
            $frontController->registerPlugin($minifyHmtl);
        }
        
        if ($this->getOption('js') == '1') {
            $view = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer')->view;
            $temp = $this->getResourceLoader()->getResourceType('viewhelper');
            $view->addHelperPath($temp['path'], $temp['namespace'] . '_');
            
        }
    }
}