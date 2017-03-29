<?php
require 'components/get_listview_referrer.php';

require 'subclasses/question_header.php';
$dbh_question_header = new question_header;
$dbh_question_header->set_where("question_header_id='" . quote_smart($question_header_id) . "'");
if($result = $dbh_question_header->make_query()->result)
{
    $data = $result->fetch_assoc();
    extract($data);

}

require_once 'subclasses/question_details.php';
$dbh_question_details = new question_details;
$dbh_question_details->set_fields('branch_id, question_details_description, is_active');
$dbh_question_details->set_where("question_header_id='" . quote_smart($question_header_id) . "'");
if($result = $dbh_question_details->make_query()->result)
{
    $num_question_details = $dbh_question_details->num_rows;
    for($a=0; $a<$num_question_details; $a++)
    {
        $data = $result->fetch_row();
        $cf_question_details_branch_id[$a] = $data[0];
        $cf_question_details_question_details_description[$a] = $data[1];
        $cf_question_details_is_active[$a] = $data[2];
    }
}

