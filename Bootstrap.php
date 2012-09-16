<?php
class Minify_Bootstrap extends Centurion_Application_Module_Bootstrap
{
    protected function _initHelper()
    {
        $this->bootstrap('frontController');
        $this->getApplication()->bootstrap('view');
        
        $frontController = $this->getResource('frontController');
        
//        if ($this->getOption('minify_html') == '1') {
            $minifyHmtl = new Minify_Plugin_Minify(
                    $this->getOption('minify_html'),
                    $this->getOption('concat_css'),
                    $this->getOption('concat_js'),
                    $this->getOption('minify_css'),
                    $this->getOption('minify_js')
            );
            $frontController->registerPlugin($minifyHmtl);
//        }
        
        if ( (($this->getOption('minify_js') == '1') || ($this->getOption('concat_js') == '1')) ) {
            $view = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer')->view;
            $temp = $this->getResourceLoader()->getResourceType('viewhelper');
            $view->addHelperPath($temp['path'], $temp['namespace'] . '_');
            
        }
    }
}