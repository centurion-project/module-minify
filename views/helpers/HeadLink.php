<?php

class Minify_View_Helper_HeadLink extends Zend_View_Helper_HeadLink
{
    public function toString($indent = null)
    {
        $script = array();
        $mtime = array();
        $rows = array();
        
        $md5Str = '';
        
        $fileTable = Centurion_Db::getSingleton('minify/file');
        $ticketTable = Centurion_Db::getSingleton('minify/ticket');
        $ticketFileTable = Centurion_Db::getSingleton('minify/ticketFile');
        
        foreach ($this->getContainer() as $index => $item) {
            if (!isset($item->type) || !($item->type === 'text/css' && $item->href[0] === '/')) {
                continue;
            }
            
            if (substr($item->href, 0, 4) === 'http') {
                continue;
            }
            
            $script[$index] = $item->href;
            $mtime[$index] = filemtime(APPLICATION_PATH . '/../public/' . $item->href);
        }
        
        $md5Str = implode('-', $script);
        $md5Str .= implode('-', $mtime);
        $md5Str = 'Centurion'.$md5Str.Centurion_Config_Manager::get('minify.salt');
        
        $key = Centurion_Inflector::md5UrlEncode($md5Str);
        
        $urlVar = array();
        $container = $this->getContainer();
        
        list($ticket, $created) = $ticketTable->getOrCreate(array('key' => $key));
        
        foreach ($script as $index => $val) {
            unset($container[$index]);
            if ($created) {
                list($file,) = $fileTable->getOrCreate(array('file' => $val));
                $ticketFileTable->insert(array('ticket_id' => $ticket->id, 'file_id' => $file->id, 'order' => $index));
            }
        }
        $options = Centurion_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        if ($options['minify']['minify_css'] == 1) {
            $this->prependStylesheet($this->view->url(array('key' => $key), 'minify_css', null, false, false));
        }
        elseif ($options['minify']['concat_css'] == 1) {
            $this->prependStylesheet($this->view->url(array('key' => $key), 'concat_css', null, false, false));
        }
        return parent::toString($indent);
    }
}