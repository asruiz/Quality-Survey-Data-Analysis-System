<?php
//****************************************************************************************
//Generated by Cobalt, a rapid application development framework. http://cobalt.jvroig.com
//Cobalt developed by JV Roig (jvroig@jvroig.com)
//****************************************************************************************
require 'path.php';
init_cobalt('Edit survey header');

if(isset($_GET['survey_header_id']))
{
    $survey_header_id = urldecode($_GET['survey_header_id']);
    require 'form_data_survey_header.php';

}

$anchor_tag = '';

if(xsrf_guard())
{
    init_var($_POST['btn_cancel']);
    init_var($_POST['btn_submit']);
    init_var($_POST['btn_mf_survey_details']);
    require 'components/query_string_standard.php';
    require 'subclasses/survey_header.php';
    $dbh_survey_header = new survey_header;

    $object_name = 'dbh_survey_header';
    require 'components/create_form_data.php';

    extract($arr_form_data);

    if($_POST['btn_cancel'])
    {
        log_action('Pressed cancel button');
        redirect("listview_survey_header.php?$query_string");
    }

    if($_POST['btn_mf_survey_details'])
    {
        $anchor_tag = "Survey_Details";
    }

    if($_POST['btn_submit'])
    {
        log_action('Pressed submit button');

        $message .= $dbh_survey_header->sanitize($arr_form_data)->lst_error;
        extract($arr_form_data);

        if($dbh_survey_header->check_uniqueness_for_editing($arr_form_data)->is_unique)
        {
            //Good, no duplicate in database
        }
        else
        {
            $message = "Record already exists with the same primary identifiers!";
        }

        if($message=="")
        {
            require_once 'subclasses/survey_details.php';
            $dbh_survey_details = new survey_details;
            $dbh_survey_details->delete_many($arr_form_data);

            for($a=0; $a<$survey_details_count;$a++)
            {
                
                $param = array(
                               'survey_header_id'=>$survey_header_id,
                               'question_header_id'=>$cf_survey_details_question_header_id[$a],
                               'question_details_id'=>$cf_survey_details_question_details_id[$a],
                               'points'=>$cf_survey_details_points[$a],
                               'feedback'=>$cf_survey_details_feedback[$a]
                              );
                $dbh_survey_details->add($param);
            }


            $dbh_survey_header->edit($arr_form_data);

            redirect("listview_survey_header.php?$query_string");
        }
    }
}
require 'subclasses/survey_header_html.php';
$html = new survey_header_html;
$html->draw_header('Edit %%', $message, $message_type);
$html->draw_listview_referrer_info($filter_field_used, $filter_used, $page_from, $filter_sort_asc, $filter_sort_desc);
$html->draw_hidden('survey_header_id');

$html->draw_controls('edit');

$html->draw_footer();