<?php
require 'subclasses/survey_header_sst.php';
$sst = new survey_header_sst;
$sst->auto_test('delete');
$sst_script = $sst->script;