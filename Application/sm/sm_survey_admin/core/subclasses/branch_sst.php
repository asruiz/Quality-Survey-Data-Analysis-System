<?php
require_once 'sst_class.php';
require_once 'branch_dd.php';
class branch_sst extends sst
{
    function __construct()
    {
        $this->fields        = branch_dd::load_dictionary();
        $this->relations     = branch_dd::load_relationships();
        $this->subclasses    = branch_dd::load_subclass_info();
        $this->table_name    = branch_dd::$table_name;
        $this->readable_name = branch_dd::$readable_name;
        parent::__construct();
    }
}
