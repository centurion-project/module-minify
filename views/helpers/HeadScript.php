<?php

class Minify_View_Helper_HeadScript extends Zend_View_Helper_HeadScript
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
            if (!empty($item->source)||!isset($item->attributes['src'])) {
                continue;
            }
            if (substr($item->attributes['src'], 0, 4) === 'http') {
                continue;
            }
            if (count($item->attributes) !== 1) {
                return;
            }
            
            $script[$index] = $item->attributes['src'];
            $mtime[$index] = filemtime(APPLICATION_PATH . '/../public/' . $item->attributes['src']);
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
                list($file,) = $fileTable->getOrCreate(array('file' => $script[$index]));
                $ticketFileTable->insert(array('ticket_id' => $ticket->id, 'file_id' => $file->id, 'order' => $index));
            }
        }
        
        $this->prependFile($this->view->url(array('key' => $key), 'minify_js', null, false, false));
        return parent::toString($indent);
    }
}
