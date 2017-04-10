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
$result = $dbh->execute_query("SELECT *, question_header.question_header_id as 'header_id' FROM question_header LEFT JOIN question_details on question_header.question_header_id = question_details.question_header_id WHERE question_type ='Scorecard'")->result;
$counter = 0;
while($row = $result->fetch_assoc())
{
	if(is_null($row['question_details_id']))
	{
		$data = explode('. ',$row['question_header_description']);
		$arr_questions['question_id'][$counter] = $row['header_id'];
		$arr_questions['question'][$counter]   = $data[1];
		$arr_questions['is_header'][$counter]   = 'Yes';
	}
	else
	{
		$arr_questions['question_id'][$counter] = $row['question_details_id'];
		$arr_questions['question'][$counter]    = $row['question_details_description'];
		$arr_questions['is_header'][$counter]   = 'No';
	}
	++$counter;
}


if($_POST['btn_submit'] AND $_POST['questions'] != 0)
{
	if($_POST['year'] .'-'.$_POST['month'] == date('Y-m'))
	{
		$date_from = $_POST['year'].'-'.$_POST['month'].'-1';
		$prev_date_from = ($_POST['year']-1).'-'.$_POST['month'].'-1';
		$date_to = $_POST['year'].'-'.$_POST['month'].'-'.date('d');
		$prev_date_to = ($_POST['year']-1).'-'.$_POST['month'].'-'.date('d');	
	}
	else
	{
		$date_from = $_POST['year'].'-'.$_POST['month'].'-1';
		$prev_date_from = ($_POST['year']-1).'-'.$_POST['month'].'-1';
		$date_to = $_POST['year'].'-'.$_POST['month'].'-31';
		$prev_date_to = ($_POST['year']-1).'-'.$_POST['month'].'-31';
	
	}
	$index = array_search($_POST['questions'],$arr_questions['question_id']);

	if($arr_questions['is_header'][$index] == 'Yes')
	{

		$query = 'SELECT * FROM survey_details LEFT JOIN survey_header ON survey_details.survey_header_id = survey_header.survey_header_id WHERE question_header_id ='.$_POST["questions"].' AND (guest_check_out BETWEEN "'.$date_from.'" AND "'.$date_to.'") ';	

		$prev_query = 'SELECT * FROM survey_details LEFT JOIN survey_header ON survey_details.survey_header_id = survey_header.survey_header_id WHERE question_header_id ='.$_POST["questions"].' AND (guest_check_out BETWEEN "'.$prev_date_from.'" AND "'.$prev_date_to.'") ';	
	}
	else
	{
		$query = 'SELECT * FROM survey_details LEFT JOIN survey_header ON survey_details.survey_header_id = survey_header.survey_header_id WHERE question_details_id ='.$_POST["questions"].' AND (guest_check_out BETWEEN "'.$date_from.'" AND "'.$date_to.'") ';	

		$prev_query = 'SELECT * FROM survey_details LEFT JOIN survey_header ON survey_details.survey_header_id = survey_header.survey_header_id WHERE question_details_id ='.$_POST["questions"].' AND (guest_check_out BETWEEN "'.$prev_date_from.'" AND "'.$prev_date_to.'") ';	
	}

	$dbh = cobalt_load_class('survey_header');
	$result = $dbh->execute_query($query)->result;
	// debug($dbh->query);
	//present data
	$arr_result = array();
	$excellent_counter = 0;
	$very_good_counter = 0;
	$good_counter      = 0;
	$fair              = 0;
	$poor              = 0;

	// //previous data
	// $excellent_counter_p = 0;
	// $very_good_counter_p = 0;
	// $good_counter_p      = 0;
	// $fair_p              = 0;
	// $poor_p              = 0;
	while($row = $result->fetch_assoc())
	{
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


	$dbh = cobalt_load_class('survey_header');
	$result = $dbh->execute_query($prev_query)->result;
	// debug($dbh->query);
	//previous data
	$excellent_counter_p = 0;
	$very_good_counter_p = 0;
	$good_counter_p      = 0;
	$fair_p              = 0;
	$poor_p              = 0;
	while($row = $result->fetch_assoc())
	{
		if($row['points'] == 5)
		{
			++$excellent_counter_p;
		}
		elseif($row['points'] == 4)
		{
			++$very_good_counter_p;
		}
		elseif($row['points'] == 3)
		{
			++$good_counter_p;
		}
		elseif($row['points'] == 2)
		{
			++$fair_p;
		}
		elseif($row['points'] == 1)
		{
			++$poor_p;
		}
	}
	$show_report = TRUE;
}

$html = cobalt_load_class('user_html');

$html->draw_header('Ratings Graph Reports');

$html->draw_container_div_start();
$html->draw_fieldset_header('Comments');
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

if($show_report)
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
      ['Name', 'Rating'],
      ['Excellent', <?php echo $excellent_counter_p;?>]

    ]);

    var newData = google.visualization.arrayToDataTable([
      ['Name', 'Rating'],
      ['Excellent', <?php echo $excellent_counter;?>],
    ]);

    var colChartDiff = new google.visualization.ColumnChart(document.getElementById('colchart_diff'));

    var options = { legend: { position: 'top' } };

    var diffData = colChartDiff.computeDiff(oldData, newData);
    colChartDiff.draw(diffData, options);
  }
</script>

<span id='colchart_diff' style='width: 450px; height: 250px; display: inline-block'></span>

<?php
	$html->draw_container_div_end();
	echo '<br/>';
}
