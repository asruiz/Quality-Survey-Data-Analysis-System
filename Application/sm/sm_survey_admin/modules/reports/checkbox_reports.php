<?php

require_once 'path.php';
init_cobalt();
init_var($_POST['year']);
init_var($_POST['month']);
init_var($_POST['btn_submit']);
init_var($_POST['questions']);

$show_report = FALSE;
require_once 'subclasses/question_header.php';
$dbh = new question_header;


$questions = $_POST['questions'];
$year = $_POST['year'];
$month = $_POST['month'];
$arr_questions = array();
$arr_validate_questions = array();
$result = $dbh->execute_query("SELECT *, question_header.question_header_id as 'header_id' FROM question_header WHERE question_type ='Checkbox'")->result;
$counter = 0;
while($row = $result->fetch_assoc())
{
	$arr_validate_questions = array();
	$data = explode('. ',$row['question_header_description']);
	$arr_questions['question_id'][$counter] = $row['header_id'];
	$arr_questions['question'][$counter]   = $data[1];	
	++$counter;
}


if($_POST['btn_submit'] AND $_POST['questions'] != 0)
{
	$date = $_POST['year'].'-'.$_POST['month'];
	$prev_date = ($_POST['year']-1).'-'.$_POST['month'];
	$index = array_search($_POST['questions'],$arr_questions['question_id']);


	$query = 'SELECT *,count(question_details_id) as "counter" FROM survey_details LEFT JOIN survey_header ON survey_details.survey_header_id = survey_header.survey_header_id WHERE question_header_id ='.$_POST["questions"].' AND (guest_check_out BETWEEN "'.$date.'-01" AND "'.$date.'-31") group by question_details_id';	

	$prev_query = 'SELECT *,count(question_details_id) as "counter" FROM survey_details LEFT JOIN survey_header ON survey_details.survey_header_id = survey_header.survey_header_id WHERE question_header_id ='.$_POST["questions"].' AND (guest_check_out BETWEEN "'.$prev_date.'-01" AND "'.$prev_date.'-31") group by question_details_id';	
	

	$dbh = cobalt_load_class('survey_header');
	$result = $dbh->execute_query($query)->result;

	//present data
	$arr_result = array();

	while($row = $result->fetch_assoc())
	{
		$arr_result[] = $row; 
	}


	$dbh = cobalt_load_class('survey_header');
	$result = $dbh->execute_query($prev_query)->result;

	//previous data
	$arr_result_prev = array();
	while($row = $result->fetch_assoc())
	{
		$arr_result_prev[] = $row;
	}

	if(count($arr_result) > 0 && count($arr_result_prev) > 0)
	{
		$show_report = TRUE;
	}
	else
	{
		$message = "No record found.";
	}
}
// debug($arr_result_prev);
//..........................



$html = cobalt_load_class('user_html');

$html->draw_header('Checkbox Graph Reports',$message);

$html->draw_container_div_start();
$html->draw_fieldset_header('Filter By');
$html->draw_fieldset_body_start();

$options = array('items' => $arr_questions['question'],
				 'values' =>$arr_questions['question_id']);
echo '<table>';
$html->draw_select_field($options, 'Questions', $form_control_name='questions', $draw_table_tags=TRUE, $extra='');
$options = array('items'=>array('January','February','March','April','May','June','July','August','September','November','December'),
				 'values'=>array('01','02','03','04','05','06','07','08','09','10','11','12'));
$html->draw_select_field($options, 'Month', $form_control_name='month', $draw_table_tags=TRUE, $extra='');

$arr_year = array();
for($a = 2017;$a < 2052;++$a)
{
	array_push($arr_year,$a);
}

$options = array('items' => $arr_year,
				 'values' =>$arr_year);
$html->draw_select_field($options, 'Year', $form_control_name='year', $draw_table_tags=TRUE, $extra='');
echo '</table>';
$html->draw_fieldset_body_end();
$html->draw_fieldset_footer_start();
$html->draw_submit_cancel();
$html->draw_fieldset_footer_end();
$html->draw_container_div_end();

// for($a = 0; $a <count($arr_result_prev); ++$a)
//  	{
//  		echo "['".$arr_result_prev[$a]['feedback']."',".intval($arr_result_prev[$a]['counter'])."],";
//  	}
if($show_report && count($arr_result) > 0 && count($arr_result_prev) > 0)
{
	$title = 'Comparison: Month of '.date('F',$_POST['month']) .' <br> Year: '. $_POST["year"] .' vs.'. ' '.($_POST["year"]-1) .'';
	$html->draw_container_div_start();
	$html->draw_fieldset_header($title);
	$html->draw_fieldset_body_start();
?>
<head>
    <!--Load the AJAX API-->
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
    	var oldData = google.visualization.arrayToDataTable([
    		['Name', 'Checkbox'],
    		<?php
    		for($a = 0; $a <count($arr_result_prev); ++$a)
    		{
    			echo "['".$arr_result_prev[$a]['feedback']."',".intval($arr_result_prev[$a]['counter'])."],";
    		}
    		?>
    		]);

    	var newData = google.visualization.arrayToDataTable([
    		['Name', 'Checkbox'],
    		<?php
    		for($a = 0; $a <count($arr_result); ++$a)
    		{
    			echo "['".$arr_result[$a]['feedback']."',".intval($arr_result[$a]['counter'])."],";
    		}
    		?>
    		]);

    var colChartDiff = new google.visualization.PieChart(document.getElementById('colchart_diff'));

    var options = { legend: { position: 'top' } };

    var diffData = colChartDiff.computeDiff(oldData, newData);
    colChartDiff.draw(diffData, options);
  }

</script>

<span id='colchart_diff' style='width: 600px; height: 600px; display: inline-block'></span>

<?php
	$html->draw_container_div_end();
	echo '<br/>';
}
