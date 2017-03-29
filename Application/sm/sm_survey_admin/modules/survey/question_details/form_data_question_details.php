<?php
require 'components/get_listview_referrer.php';

require 'subclasses/question_details.php';
$dbh_question_details = new question_details;
$dbh_question_details->set_where("question_details_id='" . quote_smart($question_details_id) . "'");
if($result = $dbh_question_details->make_query()->result)
{
    $data = $result->fetch_assoc();
    extract($data);

}

