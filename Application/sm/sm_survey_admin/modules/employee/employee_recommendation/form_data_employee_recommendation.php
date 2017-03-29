<?php
require 'components/get_listview_referrer.php';

require 'subclasses/employee_recommendation.php';
$dbh_employee_recommendation = new employee_recommendation;
$dbh_employee_recommendation->set_where("employee_recommendation_id='" . quote_smart($employee_recommendation_id) . "'");
if($result = $dbh_employee_recommendation->make_query()->result)
{
    $data = $result->fetch_assoc();
    extract($data);

}

