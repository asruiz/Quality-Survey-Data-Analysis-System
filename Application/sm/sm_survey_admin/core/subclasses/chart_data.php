<?php
require_once 'path.php';
init_cobalt();

// var_dump();
// $year = $_GET['year'];
// $month = $_GET['month'];

// echo 'hello';
$sql = "SELECT 'question_header_id' FROM question_header ";

// ....
$dataset = array();
$data_obj = new stdClass();
$data_obj->data = array(4, 4, 7, 10, 4);
$data_obj->backgroundColor = '#F7464A';
$data_obj->label = 'Question 1';
$dataset[] = $data_obj;


// $data_obj = new stdClass();
// $data_obj->data = array(3, 5, 15, 7, 9);
// $data_obj->backgroundColor = '#46BFBD';
// $data_obj->label = 'Question 2';
// $dataset[] = $data_obj;

// $data_obj = new stdClass();
// $data_obj->data = array(7, 4, 12, 9, 5);
// $data_obj->backgroundColor = '#FDB45C';
// $data_obj->label = 'Question 3';
// $dataset[] = $data_obj;

echo json_encode($dataset);