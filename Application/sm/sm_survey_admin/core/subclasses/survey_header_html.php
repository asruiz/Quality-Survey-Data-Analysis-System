<?php
require_once 'survey_header_dd.php';
class survey_header_html extends html
{
    function __construct()
    {
        $this->fields        = survey_header_dd::load_dictionary();
        $this->relations     = survey_header_dd::load_relationships();
        $this->subclasses    = survey_header_dd::load_subclass_info();
        $this->table_name    = survey_header_dd::$table_name;
        $this->readable_name = survey_header_dd::$readable_name;
    }
}
