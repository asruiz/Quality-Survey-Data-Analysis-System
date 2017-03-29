<?php
require_once 'survey_header_dd.php';
class survey_header_rpt extends reporter
{
    var $tables='';
    var $session_array_name = 'SURVEY_HEADER_REPORT_CUSTOM';
    var $report_title = '%%: Custom Reporting Tool';
    var $html_subclass = 'survey_header_html';
    var $data_subclass = 'survey_header';
    var $result_page = 'reporter_result_survey_header.php';
    var $cancel_page = 'listview_survey_header.php';
    var $pdf_reporter_filename = 'reporter_pdfresult_survey_header.php';

    function __construct()
    {
        $this->fields        = survey_header_dd::load_dictionary();
        $this->relations     = survey_header_dd::load_relationships();
        $this->subclasses    = survey_header_dd::load_subclass_info();
        $this->table_name    = survey_header_dd::$table_name;
        $this->tables        = survey_header_dd::$table_name;
        $this->readable_name = survey_header_dd::$readable_name;
        $this->get_report_fields();
    }
}
