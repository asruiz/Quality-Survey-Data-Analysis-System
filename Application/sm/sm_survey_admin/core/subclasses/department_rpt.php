<?php
require_once 'department_dd.php';
class department_rpt extends reporter
{
    var $tables='';
    var $session_array_name = 'DEPARTMENT_REPORT_CUSTOM';
    var $report_title = '%%: Custom Reporting Tool';
    var $html_subclass = 'department_html';
    var $data_subclass = 'department';
    var $result_page = 'reporter_result_department.php';
    var $cancel_page = 'listview_department.php';
    var $pdf_reporter_filename = 'reporter_pdfresult_department.php';

    function __construct()
    {
        $this->fields        = department_dd::load_dictionary();
        $this->relations     = department_dd::load_relationships();
        $this->subclasses    = department_dd::load_subclass_info();
        $this->table_name    = department_dd::$table_name;
        $this->tables        = department_dd::$table_name;
        $this->readable_name = department_dd::$readable_name;
        $this->get_report_fields();
    }
}
