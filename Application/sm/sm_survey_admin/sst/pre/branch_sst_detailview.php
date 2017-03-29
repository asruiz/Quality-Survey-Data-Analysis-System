<?php
require 'subclasses/branch_sst.php';
$sst = new branch_sst;
$sst->auto_test('detail_view');
$sst_script = $sst->script;