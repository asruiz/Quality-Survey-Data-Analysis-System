<?php

require_once 'path.php';
init_cobalt();
init_var($_POST['btn_submit']);
$show_report = FALSE;
if($_POST['btn_submit'])
{
	$month = $_POST['month'];
	$year = $_POST['year'];
	$date = $year.'-'.$month;
	$dbh = cobalt_load_class('survey_header');
	$result = $dbh->execute_query('SELECT DISTINCT(survey_number) as "survey_number", survey_header.survey_header_id FROM `survey_header` 
		LEFT JOIN `survey_details` on `survey_header`.`survey_header_id` = `survey_details`.`survey_header_id` 
		LEFT JOIN `question_header` ON `question_header`.`question_header_id` = `survey_details`.`question_header_id`
		LEFT JOIN question_details on `question_details`.question_header_id = `question_header`.`question_header_id` 
			where (`feedback` != "" AND `question_type` = "Comments and Suggestion") AND (guest_check_out BETWEEN "'.$date.'-01" AND "'.$date.'-31") order by guest_check_out, survey_header.survey_header_id DESC')->result;
	$arr_result_feedback = array();

	$counter = 0;
	while($row = $result->fetch_assoc())
	{
		$arr_result_feedback[$counter] = $row;
		$survey_header_id = $row["survey_header_id"];
		$dbh = cobalt_load_class('survey_details');
		$resulter = $dbh->execute_query('SELECT DISTINCT(survey_details.survey_details_id),feedback,question_details_description FROM `survey_details` 
		LEFT JOIN `question_header` ON `question_header`.`question_header_id` = `survey_details`.`question_header_id`
		LEFT JOIN question_details on `survey_details`.question_details_id = `question_details`.`question_details_id` 
			where (`feedback` != "" AND `question_type` = "Comments and Suggestion") AND survey_details.survey_header_id ='.$survey_header_id.'')->result;
		while($rower = $resulter->fetch_assoc())
		{
			$arr_result_feedback[$counter]['questions'][] = $rower;
		}
		$counter++;
	}	

	$show_report = TRUE;
}

$html = cobalt_load_class('user_html');

$html->draw_header('Reports');
$html->draw_container_div_start();
$html->draw_fieldset_header('Comments');
$html->draw_fieldset_body_start();
$options = array('items'=>array('January','February','March','April'),
				 'values'=>array('01','02','03','04'));
$html->draw_select_field($options, 'Month', $form_control_name='month', $draw_table_tags=TRUE, $extra='');

$arr_year = array();
for($a = 2017;$a < 2052;++$a)
{
	array_push($arr_year,$a);
}

$options = array('items' => $arr_year,
				 'values' =>$arr_year);
$html->draw_select_field($options, 'Year', $form_control_name='year', $draw_table_tags=TRUE, $extra='');
$html->draw_fieldset_body_end();
$html->draw_fieldset_footer_start();
$html->draw_submit_cancel();
$html->draw_fieldset_footer_end();
$html->draw_container_div_end();


if($show_report)
{
	$html->draw_container_div_start();
	$html->draw_fieldset_header('Comments');
	$html->draw_fieldset_body_start();

	echo '<table border="1px"><tr><td></td><td>Positive</td><td>Negative</td><td>Employee</td></tr>';

	$header = "";
	$print  = '<tr>';
	for($a = 0;$a<count($arr_result_feedback);++$a)
	{
			echo '<tr><td>'.$arr_result_feedback[$a]["survey_number"].'</td>';
				for($b = 0;$b<count($arr_result_feedback[$a]['questions']);++$b)
				{
					$feedback = $arr_result_feedback[$a]['questions'][$b]['feedback'];
					if($arr_result_feedback[$a]['questions'][$b]['question_details_description'] == 'What did you particularly enjoy about your stay?')
					{
						echo '<td>'.$feedback.'</td>';
					}
					elseif($arr_result_feedback[$a]['questions'][$b]['question_details_description'] == 'What problems did you encounter and what can we do to make your stay better?')
					{
						echo '<td>'.$feedback.'</td>';
					}
					elseif($arr_result_feedback[$a]['questions'][$b]['question_details_description'] == "If you've ever experienced service that goes beyond standards from any of our colleagues, please fill this out and encourage them.")
					{
						echo '<td>'.$feedback.'</td>';
					}
					else
					{
						echo '<td></td>';
					}

				}
			echo '</tr>';
	}
	echo $print;
	echo '</table>';
	$html->draw_fieldset_body_end();
	$html->draw_fieldset_footer_start();

	$html->draw_fieldset_footer_end();
	$html->draw_container_div_end();
}
?>