<?php

class Minify_Plugin_Minify extends Zend_Controller_Plugin_Abstract
{
    protected $_minifyHtml = false;
    protected $_minifyJs = false;
    protected $_minifyCss = true;
    protected $_concatJs = true;
    protected $_concatCss = true;
    
    public function __construct($minifyHtml, $concatCss = true, $concatJs = true, $minifyCss = true, $minifyJs = false) {
        $this->_minifyHtml = $minifyHtml;
        $this->_minifyCss = $minifyCss;
        $this->_minifyJs = $minifyJs;
        $this->_concatCss = $concatCss;
        $this->_concatJs = $concatJs;
    }
    
    //TODO: move this in it own plugin helper
    public function isAdminLayout()
    {
        return ($this->getRequest()->getParam('_layout') === 'admin'); 
    }
    
    public function dispatchLoopShutdown()
    {
        if (!$this->_minifyHtml) {
            return;
        }
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
            if ($this->_minifyCss) {
                $cssMinifier = 'Minify_Model_CSS::minify';
            }
            elseif ($this->_concatCss) {
                $cssMinifier = 'Minify_Model_CSS::concat';
            }
            if ($this->_minifyJs) {
                $jsMinifier = 'Minify_Model_JSMin::minify';
            }
            elseif ($this->_concatJs) {
                $jsMinifier = 'Minify_Model_JSMin::concat';
            }
            
            $html = Minify_Model_HTML::minify($bodyContent, array(
                    'cssMinifier' => $cssMinifier,
                    'jsMinifier' => $jsMinifier,
                    'minifyHtml' => $this->_minifyHtml,
                    'minifyJs' => $this->_minifyJs,
                    'minifyCss' => $this->_minifyCss,
                    'concatJs' => $this->_concatJs,
                    'concatCss' => $this->_concatCss,
                )
            );
            
            $this->_response->setBody($html, $bodyName);
        }
    }
}