<?php

class Minify_Plugin_Minify extends Zend_Controller_Plugin_Abstract
{
    //TODO: move this in it own plugin helper
    public function isAdminLayout()
    {
        return ($this->getRequest()->getParam('_layout') === 'admin'); 
    }
    
    public function dispatchLoopShutdown()
    {
        if ($this->isAdminLayout())
            return true;
            
        if ($this->_response->isRedirect() || $this->_response->getHttpResponseCode() !== 200) {
            return true;
        }
        
        foreach ($this->_response->getHeaders() as $header) {
            if (isset($header['name']) && $header['name'] === 'Content-Type' && $header['value'] !== 'text/html') {
                return;
            }
        }
        
        $this->_initIncludepath();
        //$this->_minifyCssHeader();
        $this->_minifyHtml();
    }

    /**
     * This should be
     */
    protected function _initIncludepath()
    {
        Zend_Loader_Autoloader::getInstance()
            ->registerNamespace('Minify_');
            
        set_include_path(implode(PATH_SEPARATOR, array(
            realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'models'),
            get_include_path()
        )));
        
        include_once 'JSMin.php';
    }
    
    protected function _minifyHtml()
    {
        foreach ($this->_response->getBody(true) as $bodyName => $bodyContent) {
            $html = Minify_Model_HTML::minify($bodyContent, array(
                    'cssMinifier' => 'Minify_Model_CSS::minify',
                    'jsMinifier' => 'Minify_Model_JSMin::minify',
                )
            );
            
            $this->_response->setBody($html, $bodyName);
        }
    }
}