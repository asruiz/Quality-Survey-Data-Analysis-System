<?php
require 'subclasses/survey_details_sst.php';
$sst = new survey_details_sst;
$sst->auto_test('delete');
$sst_script = $sst->script;