<?php
require_once 'documentation_class.php';
require_once 'survey_details_dd.php';
class survey_details_doc extends documentation
{
    function __construct()
    {
        $this->fields        = survey_details_dd::load_dictionary();
        $this->relations     = survey_details_dd::load_relationships();
        $this->subclasses    = survey_details_dd::load_subclass_info();
        $this->table_name    = survey_details_dd::$table_name;
        $this->readable_name = survey_details_dd::$readable_name;
        parent::__construct();
    }
}
