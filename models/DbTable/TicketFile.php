<?php

class Minify_Model_DbTable_TicketFile extends Centurion_Db_Table_Abstract
{
    protected $_name = 'minify_ticket_file';
    
    protected $_meta = array('verboseName'   => 'ticketFile',
                             'verbosePlural' => 'ticketFiles');
    
    protected $_referenceMap = array(
        'file' => array(
                'columns' => 'file_id',
                'refColumns' => 'id',
                'refTableClass' => 'Minify_Model_DbTable_File',
                'onDelete'      => self::CASCADE,
                'onUpdate'      => self::CASCADE
        ),
        'ticket' => array(
                'columns' => 'ticket_id',
                'refColumns' => 'id',
                'refTableClass' => 'Minify_Model_DbTable_Ticket',
                'onDelete'      => self::CASCADE,
                'onUpdate'      => self::CASCADE
        )
    );
}
