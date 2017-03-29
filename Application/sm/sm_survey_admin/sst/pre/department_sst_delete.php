<?php
require 'subclasses/department_sst.php';
$sst = new department_sst;
$sst->auto_test('delete');
$sst_script = $sst->script;