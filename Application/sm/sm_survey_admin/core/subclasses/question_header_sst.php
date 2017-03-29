<?php
require_once 'sst_class.php';
require_once 'question_header_dd.php';
class question_header_sst extends sst
{
    function __construct()
    {
        $this->fields        = question_header_dd::load_dictionary();
        $this->relations     = question_header_dd::load_relationships();
        $this->subclasses    = question_header_dd::load_subclass_info();
        $this->table_name    = question_header_dd::$table_name;
        $this->readable_name = question_header_dd::$readable_name;
        parent::__construct();
    }
}
