<?php
//****************************************************************************************
//Generated by Cobalt, a rapid application development framework. http://cobalt.jvroig.com
//Cobalt developed by JV Roig (jvroig@jvroig.com)
//****************************************************************************************
require 'path.php';
init_cobalt('Add question header');

require 'components/get_listview_referrer.php';

$anchor_tag = '';

if(xsrf_guard())
{
    init_var($_POST['btn_cancel']);
    init_var($_POST['btn_submit']);
    init_var($_POST['btn_mf_question_details']);
    require 'components/query_string_standard.php';
    require 'subclasses/question_header.php';
    $dbh_question_header = new question_header;

    $object_name = 'dbh_question_header';
    require 'components/create_form_data.php';
    extract($arr_form_data);

    if($_POST['btn_cancel'])
    {
        log_action('Pressed cancel button');
        redirect("listview_question_header.php?$query_string");
    }

    if($_POST['btn_mf_question_details'])
    {
        $anchor_tag = "Question_Details";
    }

    if($_POST['btn_submit'])
    {
        log_action('Pressed submit button');

        $message .= $dbh_question_header->sanitize($arr_form_data)->lst_error;
        extract($arr_form_data);

        if($dbh_question_header->check_uniqueness($arr_form_data)->is_unique)
        {
            //Good, no duplicate in database
        }
        else
        {
            $message = "Record already exists with the same primary identifiers!";
        }

        if($message=="")
        {
            $dbh_question_header->add($arr_form_data);
            $question_header_id = $dbh_question_header->auto_id;
            require_once 'subclasses/question_details.php';
            $dbh_question_header = new question_details;
            for($a=0; $a<$question_details_count;$a++)
            {
                
                $param = array(
                               'question_header_id'=>$question_header_id,
                               'branch_id'=>$cf_question_details_branch_id[$a],
                               'question_details_description'=>$cf_question_details_question_details_description[$a],
                               'is_active'=>$cf_question_details_is_active[$a]
                              );
                $dbh_question_header->add($param);
            }


            redirect("listview_question_header.php?$query_string");
        }
    }
}
require 'subclasses/question_header_html.php';
$html = new question_header_html;
$html->draw_header('Add %%', $message, $message_type);
$html->draw_listview_referrer_info($filter_field_used, $filter_used, $page_from, $filter_sort_asc, $filter_sort_desc);
$html->draw_controls('add');

$html->draw_footer();