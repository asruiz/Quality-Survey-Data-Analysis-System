<?php
require 'subclasses/question_details_sst.php';
$sst = new question_details_sst;
$sst->auto_test('detail_view');
$sst_script = $sst->script;