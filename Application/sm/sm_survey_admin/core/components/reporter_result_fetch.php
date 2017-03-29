<?php
$num_rows = 0;
$arr_data = array(); //will contain row-dump result set
$arr_results = array(); //will contain the default col-dump result set, which should be transformed to row-dump
foreach($arr_result_fields as $field_name)
{
    if(isset($obj_custom_report->dump[$field_name]))
    {
        $arr_results[$field_name] = $obj_custom_report->dump[$field_name];
        if($num_rows == 0)
        {
            $num_rows = count($obj_custom_report->dump[$field_name]);
        }
    }
}

$arr_data['num_rows'] = $num_rows;

$b = 0;
foreach($arr_result_fields as $field_name)
{
    for($a=0; $a<$num_rows; ++$a)
    {
        $arr_data[$a][$b] = $arr_results[$field_name][$a];
    }
    ++$b;
}


$b = 0;
$arr_data['total'] = array();
foreach($arr_result_fields as $index=>$field_name)
{
    if($arr_show_sum[$index])
    {
        for($a=0; $a<$num_rows; ++$a)
        {
            init_var($arr_data['total'][$b]);
            $arr_data['total'][$b] += $arr_results[$field_name][$a];
        }
    }
    else
    {
        $arr_data['total'][$b] = '';
    }
    ++$b;
}
