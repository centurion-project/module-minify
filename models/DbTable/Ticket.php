<?php

class Minify_Model_DbTable_Ticket extends Centurion_Db_Table_Abstract
{
    protected $_name = 'minify_ticket';
    
    protected $_manyDependentTables = array(
        'files'        =>  array(
            'refTableClass'     =>  'Minify_Model_DbTable_File',
            'intersectionTable' =>  'Minify_Model_DbTable_TicketFile',
            'columns'   =>  array(
                'local'     =>  'ticket_id',
                'foreign'   =>  'file_id'
            )
        )
    );
}
