<?php
require 'components/get_listview_referrer.php';

require 'subclasses/branch.php';
$dbh_branch = new branch;
$dbh_branch->set_where("branch_id='" . quote_smart($branch_id) . "'");
if($result = $dbh_branch->make_query()->result)
{
    $data = $result->fetch_assoc();
    extract($data);

}

