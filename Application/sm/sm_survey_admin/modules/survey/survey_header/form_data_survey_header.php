<?php
require 'components/get_listview_referrer.php';

require 'subclasses/survey_header.php';
$dbh_survey_header = new survey_header;
$dbh_survey_header->set_where("survey_header_id='" . quote_smart($survey_header_id) . "'");
if($result = $dbh_survey_header->make_query()->result)
{
    $data = $result->fetch_assoc();
    extract($data);

    $data = explode('-',$date_submitted);
    if(count($data) == 3)
    {
        $date_submitted_year = $data[0];
        $date_submitted_month = $data[1];
        $date_submitted_day = $data[2];
    }
    $data = explode('-',$guest_check_in);
    if(count($data) == 3)
    {
        $guest_check_in_year = $data[0];
        $guest_check_in_month = $data[1];
        $guest_check_in_day = $data[2];
    }
    $data = explode('-',$guest_check_out);
    if(count($data) == 3)
    {
        $guest_check_out_year = $data[0];
        $guest_check_out_month = $data[1];
        $guest_check_out_day = $data[2];
    }
}

require_once 'subclasses/survey_details.php';
$dbh_survey_details = new survey_details;
$dbh_survey_details->set_fields('question_header_id, question_details_id, points, feedback');
$dbh_survey_details->set_where("survey_header_id='" . quote_smart($survey_header_id) . "'");
if($result = $dbh_survey_details->make_query()->result)
{
    $num_survey_details = $dbh_survey_details->num_rows;
    for($a=0; $a<$num_survey_details; $a++)
    {
        $data = $result->fetch_row();
        $cf_survey_details_question_header_id[$a] = $data[0];
        $cf_survey_details_question_details_id[$a] = $data[1];
        $cf_survey_details_points[$a] = $data[2];
        $cf_survey_details_feedback[$a] = $data[3];
    }
}

