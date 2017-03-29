<?php
//Expects $reporter object to exist
//Expects $chosen_report

$d = new data_abstraction;
$d->set_table('cobalt_reporter');
$d->set_where("module_name=? AND report_name=?");
$reporter_mod_name = $reporter->session_array_name;
$bind_params = array('ss', $reporter_mod_name, $chosen_report);
$d->stmt_prepare($bind_params);
$d->stmt_fetch('single');
$arr_report_details = $d->dump;
$d = null;

$show_field   = $_SESSION[$reporter->session_array_name]['show_field']   = unserialize($arr_report_details['show_field']);
$operator     = $_SESSION[$reporter->session_array_name]['operator']     = unserialize($arr_report_details['operator']);
$text_field   = $_SESSION[$reporter->session_array_name]['text_field']   = unserialize($arr_report_details['text_field']);
$sum_field    = $_SESSION[$reporter->session_array_name]['sum_field']    = unserialize($arr_report_details['sum_field']);
$count_field  = $_SESSION[$reporter->session_array_name]['count_field']  = unserialize($arr_report_details['count_field']);
$group_field1 = $_SESSION[$reporter->session_array_name]['group_field1'] = unserialize($arr_report_details['group_field1']);
$group_field2 = $_SESSION[$reporter->session_array_name]['group_field2'] = unserialize($arr_report_details['group_field2']);
$group_field3 = $_SESSION[$reporter->session_array_name]['group_field3'] = unserialize($arr_report_details['group_field3']);

$token = generate_token();
$_SESSION[$reporter->session_array_name]['token'] = $token;
$token = rawurlencode($token);
