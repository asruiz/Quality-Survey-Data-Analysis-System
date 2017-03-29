<?php
require 'components/get_listview_referrer.php';

require 'subclasses/survey_details.php';
$dbh_survey_details = new survey_details;
$dbh_survey_details->set_where("survey_details_id='" . quote_smart($survey_details_id) . "'");
if($result = $dbh_survey_details->make_query()->result)
{
    $data = $result->fetch_assoc();
    extract($data);

}

