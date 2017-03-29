<?php
require_once 'survey_details_dd.php';
class survey_details_rpt extends reporter
{
    var $tables='';
    var $session_array_name = 'SURVEY_DETAILS_REPORT_CUSTOM';
    var $report_title = '%%: Custom Reporting Tool';
    var $html_subclass = 'survey_details_html';
    var $data_subclass = 'survey_details';
    var $result_page = 'reporter_result_survey_details.php';
    var $cancel_page = 'listview_survey_details.php';
    var $pdf_reporter_filename = 'reporter_pdfresult_survey_details.php';

    function __construct()
    {
        $this->fields        = survey_details_dd::load_dictionary();
        $this->relations     = survey_details_dd::load_relationships();
        $this->subclasses    = survey_details_dd::load_subclass_info();
        $this->table_name    = survey_details_dd::$table_name;
        $this->tables        = survey_details_dd::$table_name;
        $this->readable_name = survey_details_dd::$readable_name;
        $this->get_report_fields();
    }
}
