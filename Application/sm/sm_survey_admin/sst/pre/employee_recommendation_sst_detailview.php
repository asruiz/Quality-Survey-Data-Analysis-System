<?php
require 'subclasses/employee_recommendation_sst.php';
$sst = new employee_recommendation_sst;
$sst->auto_test('detail_view');
$sst_script = $sst->script;