<?php

class Minify_MinifyController extends Centurion_Controller_Action
{
    public function init()
    {
         $this->_helper->cache(array('minify-js'), array(), 'js');
         $this->_helper->cache(array('minify-css'), array(), 'css');
    }
    public function initHelper()
    {
        $this->getHelper('layout')->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender(true);
    }
    
    public function minifyCssAction()
    {
        $this->minify('css');
    }
    
    public function minifyJsAction()
    {
        $this->minify('js');
    }
    
    protected function minify($type = 'js')
    {
        $this->initHelper();
        
        $this->_getParam('key');
        
        $ticket = Centurion_Db::getSingleton('minify/ticket')->findOneByKey($this->_getParam('key'));
        
        if ($ticket === null) {
            throw new Centurion_Controller_Action_Exception('Invalid key', 404);
        }

        if ($type === 'js') {
            $contentType = 'application/javascript';
        } else {
            $contentType = 'text/css';
        }
        
        $rowset = Centurion_Db::getSingleton('minify/ticketFile')->select(true)->where('ticket_id=?', $ticket->id)->order('order asc')->fetchAll();
        
        foreach ($rowset as $ticketFile) {
            $file = $ticketFile->file;
            $cache = new Centurion_Cache_Frontend_File(array(
                                            'master_files' => APPLICATION_PATH . '/../public/' . $file->file,
            ));
            $cache->setBackend($this->_getCache('core')->getBackend());
            
            $id = md5($file->file);

            if (!($minified = $cache->load($id))) {
                if ($type == 'js') {
                    $minified = Minify_Model_JSMin::minify(file_get_contents(APPLICATION_PATH . '/../public/' . $file->file));
                } else {
                    $path = $file->file;
                    $tab = explode('/', $path);
                    array_pop($tab);
                    
                    $minified = Minify_Model_CSS::minify(file_get_contents(APPLICATION_PATH . '/../public/' . $file->file), array('prependRelativePath' => implode('/', $tab) . '/'));
                }
                $cache->save($minified, $id);
            }
            $this->_response->appendBody($minified . PHP_EOL);
        }
        $this->_response->setHeader('Content-Type', $contentType);
    }
}