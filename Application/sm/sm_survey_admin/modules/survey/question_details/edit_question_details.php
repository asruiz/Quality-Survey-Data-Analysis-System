<?php
//****************************************************************************************
//Generated by Cobalt, a rapid application development framework. http://cobalt.jvroig.com
//Cobalt developed by JV Roig (jvroig@jvroig.com)
//****************************************************************************************
require 'path.php';
init_cobalt('Edit question details');

if(isset($_GET['question_details_id']))
{
    $question_details_id = urldecode($_GET['question_details_id']);
    require 'form_data_question_details.php';

}

$anchor_tag = '';

if(xsrf_guard())
{
    init_var($_POST['btn_cancel']);
    init_var($_POST['btn_submit']);
    require 'components/query_string_standard.php';
    require 'subclasses/question_details.php';
    $dbh_question_details = new question_details;

    $object_name = 'dbh_question_details';
    require 'components/create_form_data.php';

    extract($arr_form_data);

    if($_POST['btn_cancel'])
    {
        log_action('Pressed cancel button');
        redirect("listview_question_details.php?$query_string");
    }

    if($_POST['btn_submit'])
    {
        log_action('Pressed submit button');

        $message .= $dbh_question_details->sanitize($arr_form_data)->lst_error;
        extract($arr_form_data);

        if($dbh_question_details->check_uniqueness_for_editing($arr_form_data)->is_unique)
        {
            //Good, no duplicate in database
        }
        else
        {
            $message = "Record already exists with the same primary identifiers!";
        }

        if($message=="")
        {

            $dbh_question_details->edit($arr_form_data);

            redirect("listview_question_details.php?$query_string");
        }
    }
}
require 'subclasses/question_details_html.php';
$html = new question_details_html;
$html->draw_header('Edit %%', $message, $message_type);
$html->draw_listview_referrer_info($filter_field_used, $filter_used, $page_from, $filter_sort_asc, $filter_sort_desc);
$html->draw_hidden('question_details_id');

$html->draw_controls('edit');

$html->draw_footer();