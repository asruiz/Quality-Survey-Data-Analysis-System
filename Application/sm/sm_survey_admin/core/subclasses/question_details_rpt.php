<?php
require_once 'question_details_dd.php';
class question_details_rpt extends reporter
{
    var $tables='';
    var $session_array_name = 'QUESTION_DETAILS_REPORT_CUSTOM';
    var $report_title = '%%: Custom Reporting Tool';
    var $html_subclass = 'question_details_html';
    var $data_subclass = 'question_details';
    var $result_page = 'reporter_result_question_details.php';
    var $cancel_page = 'listview_question_details.php';
    var $pdf_reporter_filename = 'reporter_pdfresult_question_details.php';

    function __construct()
    {
        $this->fields        = question_details_dd::load_dictionary();
        $this->relations     = question_details_dd::load_relationships();
        $this->subclasses    = question_details_dd::load_subclass_info();
        $this->table_name    = question_details_dd::$table_name;
        $this->tables        = question_details_dd::$table_name;
        $this->readable_name = question_details_dd::$readable_name;
        $this->get_report_fields();
    }
}
