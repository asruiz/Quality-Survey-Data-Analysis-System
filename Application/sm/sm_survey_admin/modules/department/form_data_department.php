<?php
require 'components/get_listview_referrer.php';

require 'subclasses/department.php';
$dbh_department = new department;
$dbh_department->set_where("department_id='" . quote_smart($department_id) . "'");
if($result = $dbh_department->make_query()->result)
{
    $data = $result->fetch_assoc();
    extract($data);

}

