<?php
require_once 'branch_dd.php';
class branch_rpt extends reporter
{
    var $tables='';
    var $session_array_name = 'BRANCH_REPORT_CUSTOM';
    var $report_title = '%%: Custom Reporting Tool';
    var $html_subclass = 'branch_html';
    var $data_subclass = 'branch';
    var $result_page = 'reporter_result_branch.php';
    var $cancel_page = 'listview_branch.php';
    var $pdf_reporter_filename = 'reporter_pdfresult_branch.php';

    function __construct()
    {
        $this->fields        = branch_dd::load_dictionary();
        $this->relations     = branch_dd::load_relationships();
        $this->subclasses    = branch_dd::load_subclass_info();
        $this->table_name    = branch_dd::$table_name;
        $this->tables        = branch_dd::$table_name;
        $this->readable_name = branch_dd::$readable_name;
        $this->get_report_fields();
    }
}
