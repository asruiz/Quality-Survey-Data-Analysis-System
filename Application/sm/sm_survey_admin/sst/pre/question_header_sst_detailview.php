<?php
require 'subclasses/question_header_sst.php';
$sst = new question_header_sst;
$sst->auto_test('detail_view');
$sst_script = $sst->script;