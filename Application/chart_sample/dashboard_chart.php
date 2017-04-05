<?php
require_once 'path.php';
init_cobalt();

extract($_GET);

require_once 'subclasses/Loan.php';
$dbh_Loan = new Loan;

if ($q == 'latest')
{
    if ($result = $dbh_Loan->select_loan_latest_transactions()->dump)
    {
        $colors = array('#F7464A','#46BFBD','#FDB45C','#949FB1','#FD9FB1','#F7BFBD','#94463E');
        $dataset = array();
        for ($index = 0; $index < count($result['datetime']); $index++)
        {
            $loan_count = (!is_null($result['loan_count'][$index]) ? $result['loan_count'][$index] : 0);
            $overdue_count = (!is_null($result['overdue_count'][$index]) ? $result['overdue_count'][$index] : 0);
            $extended_count = (!is_null($result['extended_count'][$index]) ? $result['extended_count'][$index] : 0);
            $returned_count = (!is_null($result['returned_count'][$index]) ? $result['returned_count'][$index] : 0);

            $data_obj = new stdClass();
            $data_obj->data = array($loan_count, $overdue_count, $extended_count, $returned_count);
            $data_obj->backgroundColor = $colors[$index];
            $data_obj->label = date('D, m/d', strtotime($result['datetime'][$index]));

            $dataset[] = $data_obj; 
        }
    }    

    echo json_encode($dataset);    
}
elseif ($q == 'summary')
{
    if ($result = $dbh_Loan->select_loan_summary()->dump)
    {
        extract($result);
        $result_data = $lent_count[0] . "|" . $extended_count[0] . "|" . $returned_count[0] . "|" . $overdue_count[0];
    }

    echo $result_data;    
}
elseif ($q == 'latest_member')
{
    if (isset($member_id))
    {
        if ($result = $dbh_Loan->select_loan_latest_transactions_per_member($member_id)->dump)
        {
            $colors = array('#F7464A','#46BFBD','#FDB45C','#949FB1','#FD9FB1','#F7BFBD','#94463E');
            $dataset = array();
            for ($index = 0; $index < count($result['datetime']); $index++)
            {
                $loan_count = (!is_null($result['loan_count'][$index]) ? $result['loan_count'][$index] : 0);
                $overdue_count = (!is_null($result['overdue_count'][$index]) ? $result['overdue_count'][$index] : 0);
                $extended_count = (!is_null($result['extended_count'][$index]) ? $result['extended_count'][$index] : 0);
                $returned_count = (!is_null($result['returned_count'][$index]) ? $result['returned_count'][$index] : 0);

                $data_obj = new stdClass();
                $data_obj->data = array($loan_count, $overdue_count, $extended_count, $returned_count);
                $data_obj->backgroundColor = $colors[$index];
                $data_obj->label = date('D, m/d', strtotime($result['datetime'][$index]));

                $dataset[] = $data_obj; 
            }
        }    

        echo json_encode($dataset);
    }
}
elseif ($q == 'summary_member')
{
    if ($result = $dbh_Loan->select_loan_summary()->dump)
    {
        extract($result);
        $result_data = $lent_count[0] . "|" . $extended_count[0] . "|" . $returned_count[0] . "|" . $overdue_count[0];
    }

    echo $result_data;    
}
