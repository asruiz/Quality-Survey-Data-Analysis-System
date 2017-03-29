<?php
//****************************************************************************************
//Generated by Cobalt, a rapid application development framework. http://cobalt.jvroig.com
//Cobalt developed by JV Roig (jvroig@jvroig.com)
//****************************************************************************************
require 'path.php';
init_cobalt('Edit branch');

if(isset($_GET['branch_id']))
{
    $branch_id = urldecode($_GET['branch_id']);
    require 'form_data_branch.php';

}

$anchor_tag = '';

if(xsrf_guard())
{
    init_var($_POST['btn_cancel']);
    init_var($_POST['btn_submit']);
    require 'components/query_string_standard.php';
    require 'subclasses/branch.php';
    $dbh_branch = new branch;

    $object_name = 'dbh_branch';
    require 'components/create_form_data.php';

    extract($arr_form_data);

    if($_POST['btn_cancel'])
    {
        log_action('Pressed cancel button');
        redirect("listview_branch.php?$query_string");
    }

    if($_POST['btn_submit'])
    {
        log_action('Pressed submit button');

        $message .= $dbh_branch->sanitize($arr_form_data)->lst_error;
        extract($arr_form_data);

        if($dbh_branch->check_uniqueness_for_editing($arr_form_data)->is_unique)
        {
            //Good, no duplicate in database
        }
        else
        {
            $message = "Record already exists with the same primary identifiers!";
        }

        if($message=="")
        {

            $dbh_branch->edit($arr_form_data);

            redirect("listview_branch.php?$query_string");
        }
    }
}
require 'subclasses/branch_html.php';
$html = new branch_html;
$html->draw_header('Edit %%', $message, $message_type);
$html->draw_listview_referrer_info($filter_field_used, $filter_used, $page_from, $filter_sort_asc, $filter_sort_desc);
$html->draw_hidden('branch_id');

$html->draw_controls('edit');

$html->draw_footer();