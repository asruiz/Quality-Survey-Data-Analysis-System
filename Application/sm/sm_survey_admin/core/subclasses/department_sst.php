<?php
require_once 'sst_class.php';
require_once 'department_dd.php';
class department_sst extends sst
{
    function __construct()
    {
        $this->fields        = department_dd::load_dictionary();
        $this->relations     = department_dd::load_relationships();
        $this->subclasses    = department_dd::load_subclass_info();
        $this->table_name    = department_dd::$table_name;
        $this->readable_name = department_dd::$readable_name;
        parent::__construct();
    }
}
