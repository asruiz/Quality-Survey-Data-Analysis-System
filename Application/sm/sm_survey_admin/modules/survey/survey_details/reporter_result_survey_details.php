<?php
//****************************************************************************************
//Generated by Cobalt, a rapid application development framework. http://cobalt.jvroig.com
//Cobalt developed by JV Roig (jvroig@jvroig.com)
//****************************************************************************************
require 'path.php';
init_cobalt('View survey details');

require 'reporter_class.php';
$reporter = cobalt_load_class('survey_details_rpt');
require 'components/reporter_result_query_constructor.php';
require 'components/reporter_result_body.php';