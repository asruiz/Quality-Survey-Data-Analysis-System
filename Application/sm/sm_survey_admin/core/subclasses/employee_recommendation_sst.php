<?php
require_once 'sst_class.php';
require_once 'employee_recommendation_dd.php';
class employee_recommendation_sst extends sst
{
    function __construct()
    {
        $this->fields        = employee_recommendation_dd::load_dictionary();
        $this->relations     = employee_recommendation_dd::load_relationships();
        $this->subclasses    = employee_recommendation_dd::load_subclass_info();
        $this->table_name    = employee_recommendation_dd::$table_name;
        $this->readable_name = employee_recommendation_dd::$readable_name;
        parent::__construct();
    }
}
