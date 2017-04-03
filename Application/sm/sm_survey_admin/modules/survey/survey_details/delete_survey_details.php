<?php
//****************************************************************************************
//Generated by Cobalt, a rapid application development framework. http://cobalt.jvroig.com
//Cobalt developed by JV Roig (jvroig@jvroig.com)
//****************************************************************************************
require 'path.php';
init_cobalt('Delete survey details');

if(isset($_GET['survey_details_id']))
{
    $survey_details_id = urldecode($_GET['survey_details_id']);
    require_once 'form_data_survey_details.php';
}

if(xsrf_guard())
{
    init_var($_POST['btn_cancel']);
    init_var($_POST['btn_delete']);
    require 'components/query_string_standard.php';

    if($_POST['btn_cancel'])
    {
        log_action('Pressed cancel button');
        redirect("listview_survey_details.php?$query_string");
    }

    elseif($_POST['btn_delete'])
    {
        log_action('Pressed delete button');
        require_once 'subclasses/survey_details.php';
        $dbh_survey_details = new survey_details;

        $object_name = 'dbh_survey_details';
        require 'components/create_form_data.php';


        $dbh_survey_details->delete($arr_form_data);

        redirect("listview_survey_details.php?$query_string");
    }
}
require 'subclasses/survey_details_html.php';
$html = new survey_details_html;
$html->draw_header('Delete %%', $message, $message_type);
$html->draw_listview_referrer_info($filter_field_used, $filter_used, $page_from, $filter_sort_asc, $filter_sort_desc);

$html->draw_hidden('survey_details_id');

$html->detail_view = TRUE;
$html->draw_controls('delete');

$html->draw_footer();