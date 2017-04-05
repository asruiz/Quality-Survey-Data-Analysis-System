<?php

require_once 'path.php';
init_cobalt();
$show_report = FALSE;
require_once 'subclasses/question_header.php';
$dbh = new question_header;

$arr_questions = array();
$arr_validate_questions = array();
$result = $dbh->execute_query("SELECT *, question_header.question_header_id as 'header_id' FROM question_header LEFT JOIN question_details on question_header.question_header_id = question_details.question_header_id WHERE question_type ='Scorecard'")->result;
$counter = 0;
while($row = $result->fetch_assoc())
{
	$arr_validate_questions = array();
	if(is_null($row['question_details_id']))
	{
		$arr_questions['question_id'][$counter] = $row['header_id'];
		$arr_questions['question'][$counter]   = $row['question_header_description'];
		$arr_questions['is_header'][$counter]   = 'Yes';
	}
	else
	{
		$arr_questions['question_id'][$counter] = $row['question_details_id'];
		$arr_questions['question'][$counter]    = $row['question_details_description'];
		$arr_questions['is_header'][$counter]   = 'No';
	}
	// debug($row);
	++$counter;
}


if($_POST['btn_submit'] AND $_POST['questions'] != 0)
{
	$index = array_search($_POST['questions'],$arr_questions['question_id']);
	// debug($index);
	if($arr_questions['is_header'][$index] == 'Yes')
	{
		$query = "SELECT * FROM survey_details WHERE question_header_id =".$_POST['questions']."";	
	}
	else
	{
		$query = "SELECT * FROM survey_details WHERE question_details_id =".$_POST['questions']."";	

	}

	$dbh = cobalt_load_class('survey_header');
	$result = $dbh->execute_query($query)->result;
	// debug($dbh->query);
	$arr_result = array();
	$excellent_counter = 0;
	$very_good_counter = 0;
	$good_counter      = 0;
	$fair              = 0;
	$poor              = 0;
	while($row = $result->fetch_assoc())
	{
		// debug($row);
		if($row['points'] == 5)
		{
			++$excellent_counter;
		}
		elseif($row['points'] == 4)
		{
			++$very_good_counter;
		}
		elseif($row['points'] == 3)
		{
			++$good_counter;
		}
		elseif($row['points'] == 2)
		{
			++$fair;
		}
		elseif($row['points'] == 1)
		{
			++$poor;
		}
	}

	// debug($excellent_counter);
	$show_report = TRUE;
}

// debug($arr_questions);
$html = cobalt_load_class('user_html');

$html->draw_header('Graph Reports');

$html->draw_container_div_start();
$html->draw_fieldset_header('Comments');
$html->draw_fieldset_body_start();
// $query = 'SELECT * FROM ';
// $html->draw_select_field_from_query($query, $list_value, $list_items, $cobalt_field_label, $form_control_name='', $detail_view=FALSE, $draw_table_tags=TRUE, $list_separators='', $extra='')

// $arr_year = array();
// for($a = 2017;$a < 2052;++$a)
// {
// 	array_push($arr_year,$a);
// }
// // debug($arr_year);
// // $options = array
$options = array('items' => $arr_questions['question'],
				 'values' =>$arr_questions['question_id']);

$html->draw_select_field($options, 'Questions', $form_control_name='questions', $draw_table_tags=TRUE, $extra='');
$html->draw_fieldset_body_end();
$html->draw_fieldset_footer_start();
$html->draw_submit_cancel();
$html->draw_fieldset_footer_end();
$html->draw_container_div_end();

if($show_report)
{
	$html->draw_container_div_start();

	// require_once 'subclasses/chart_data.php';
	echo '<fieldset class="container">';                    // Do not remove this line...

    // START - NOT COBALT DEFAULT: This will render the graph section of the dashboard... 
    echo '<fieldset class="top"><img src="images/cobalt/dashboard.png">Dashboard</fieldset>';
    echo '<fieldset class="middle">';
    echo '<div class="container_icons_CC">';
    echo '<table width="100%">
            <tr>
                <td width="60%">
                    <div id="chart_container" align="center" style="width:100%;">
                        <canvas id="chart_canvas1"></canvas>
                    </div>
                </td>
                <td width="40%">
                    <div id="chart_container" align="center" style="width:100%;">
                        <canvas id="chart_canvas2"></canvas>
                    </div>
                </td>
            </tr>
          </table>';
    echo '</div>';
    echo '</fieldset>';
    echo '</fieldset>';

	$html->draw_container_div_end();
}
