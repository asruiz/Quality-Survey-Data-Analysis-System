<?php
require 'components/get_listview_referrer.php';

require 'subclasses/employee.php';
$dbh_employee = new employee;
$dbh_employee->set_where("employee_id='" . quote_smart($employee_id) . "'");
if($result = $dbh_employee->make_query()->result)
{
    $data = $result->fetch_assoc();
    extract($data);

}

