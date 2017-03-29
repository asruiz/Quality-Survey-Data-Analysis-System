<?php
require_once 'question_details_dd.php';
class question_details_html extends html
{
    function __construct()
    {
        $this->fields        = question_details_dd::load_dictionary();
        $this->relations     = question_details_dd::load_relationships();
        $this->subclasses    = question_details_dd::load_subclass_info();
        $this->table_name    = question_details_dd::$table_name;
        $this->readable_name = question_details_dd::$readable_name;
    }
}
