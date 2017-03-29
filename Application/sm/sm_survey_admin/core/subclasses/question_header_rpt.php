<?php
require_once 'question_header_dd.php';
class question_header_rpt extends reporter
{
    var $tables='';
    var $session_array_name = 'QUESTION_HEADER_REPORT_CUSTOM';
    var $report_title = '%%: Custom Reporting Tool';
    var $html_subclass = 'question_header_html';
    var $data_subclass = 'question_header';
    var $result_page = 'reporter_result_question_header.php';
    var $cancel_page = 'listview_question_header.php';
    var $pdf_reporter_filename = 'reporter_pdfresult_question_header.php';

    function __construct()
    {
        $this->fields        = question_header_dd::load_dictionary();
        $this->relations     = question_header_dd::load_relationships();
        $this->subclasses    = question_header_dd::load_subclass_info();
        $this->table_name    = question_header_dd::$table_name;
        $this->tables        = question_header_dd::$table_name;
        $this->readable_name = question_header_dd::$readable_name;
        $this->get_report_fields();
    }
}
