<?php
require 'subclasses/question_header_sst.php';
$sst = new question_header_sst;
$sst->auto_test();
$sst_script = $sst->script;