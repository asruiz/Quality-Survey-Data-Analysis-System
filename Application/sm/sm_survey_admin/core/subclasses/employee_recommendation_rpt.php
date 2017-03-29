<?php
require_once 'employee_recommendation_dd.php';
class employee_recommendation_rpt extends reporter
{
    var $tables='';
    var $session_array_name = 'EMPLOYEE_RECOMMENDATION_REPORT_CUSTOM';
    var $report_title = '%%: Custom Reporting Tool';
    var $html_subclass = 'employee_recommendation_html';
    var $data_subclass = 'employee_recommendation';
    var $result_page = 'reporter_result_employee_recommendation.php';
    var $cancel_page = 'listview_employee_recommendation.php';
    var $pdf_reporter_filename = 'reporter_pdfresult_employee_recommendation.php';

    function __construct()
    {
        $this->fields        = employee_recommendation_dd::load_dictionary();
        $this->relations     = employee_recommendation_dd::load_relationships();
        $this->subclasses    = employee_recommendation_dd::load_subclass_info();
        $this->table_name    = employee_recommendation_dd::$table_name;
        $this->tables        = employee_recommendation_dd::$table_name;
        $this->readable_name = employee_recommendation_dd::$readable_name;
        $this->get_report_fields();
    }
}
