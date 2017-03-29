<?php

define('FULLPATH_BASE', "C:/xampp/htdocs/sm/sm_survey_admin/");
require '../sm_survey_admin/core/cobalt_core.php';
init_cobalt();
init_var($_POST['btn_submit']);

$message = "";

/* Scorecard */
$dbh = cobalt_load_class('question_header');
$result = $dbh->execute_query('SELECT * FROM question_header where question_type = "Scorecard"')->result;

$arr_result = array();
$counter = 0;
while($row = $result->fetch_assoc())
{
    $arr_result[$counter] = $row;

    $header = $row['question_header_id'];
    $dbh_question_details = cobalt_load_class('question_details');
    $result1 = $dbh_question_details->execute_query("SELECT * FROM question_details where question_header_id = $header AND is_active = 'Yes'")->result;
    while($row1 = $result1->fetch_assoc())
    {
        $arr_result[$counter]['questions'][] = $row1;
    } 
    ++$counter;
}
/* End-Scorecard */

/* Checkbox */
$dbh = cobalt_load_class('question_header');
$result_checkbox = $dbh->execute_query('SELECT * FROM question_header where question_type = "Checkbox"')->result;
// debug($dbh->query);
$arr_result_checkbox = array();
$counter_checkbox = 0;
while($row_checkbox = $result_checkbox->fetch_assoc())
{
    $arr_result_checkbox[$counter_checkbox] = $row_checkbox;

    $header = $row_checkbox['question_header_id'];
    $dbh_question_details = cobalt_load_class('question_details');
    $result1_checkbox = $dbh_question_details->execute_query("SELECT * FROM question_details where question_header_id = $header  AND is_active = 'Yes'")->result;
    while($row1_checkbox = $result1_checkbox->fetch_assoc())
    {
        $arr_result_checkbox[$counter_checkbox]['questions'][] = $row1_checkbox;
    } 
    ++$counter_checkbox;
}
/* End-Checkbox */

/* Comments and Suggestion */
$dbh = cobalt_load_class('question_header');
$result_feedback = $dbh->execute_query('SELECT * FROM question_header where question_type = "Comments and Suggestion"')->result;
// debug($dbh->query);

$arr_result_feedback = array();
$counter_feedback = 0;
while($row_feedback = $result_feedback->fetch_assoc())
{
    $arr_result_feedback[$counter_feedback] = $row_feedback;

    $header = $row_feedback['question_header_id'];
    $dbh_question_details = cobalt_load_class('question_details');
    $result1_feedback = $dbh_question_details->execute_query("SELECT * FROM question_details where question_header_id = $header  AND is_active = 'Yes'")->result;
    while($row1_feedback = $result1_feedback->fetch_assoc())
    {
        $arr_result_feedback[$counter_feedback]['questions'][] = $row1_feedback;
    } 
    ++$counter_feedback;
}
/* End-Comments and Suggestion */

// debug($arr_result_checkbox);
// $question_header = $dbh->dump['question_header_description'];
// debug($dbh->dump);
// $html = cobalt_load_class('survey_header_html');
// debug($arr_result[1]['questions']);

if($_POST['btn_submit'])
{
    $dbh = cobalt_load_class('survey_header');

    $radio_counter = 0;
    $radio_question_counter = 0;
    for ($a = 0; $a < count($arr_result); ++$a) {
        // array_values($_POST['ratings']);
        // debug($_POST['ratings'][$a]);
        if (isset($_POST['ratings'][$a])) {
            // debug(is_array($_POST['ratings']));
            if (!is_array($_POST['ratings'])) {
                ++$radio_counter;
            }
            else {
                // debug($a);
                $radio_counter += count($_POST['ratings'][$a]);
            }
        }
        // debug($_POST['ratings']);
        if (!isset($arr_result[$a]['questions']) && $arr_result[$a]['is_active'] == 'Yes') {
            ++$radio_question_counter;
        }

        else {
            $radio_question_counter += count($arr_result[$a]['questions']);
        }
    }

    // debug($radio_counter);
    // debug($radio_question_counter);
    // debug($_POST['ratings']);
    // debug($arr_result);
    if ($radio_counter == $radio_question_counter) {
        $param = array(
            'branch_id' => '',
            'survey_number' => 'Sample12313',
            'room_number' => $_POST['room_number'],
            'date_submitted' => date('Y-m-d'),
            // 'guest_first_name' => $_POST['guest_first_name'],
            // 'guest_last_name' => $_POST['guest_last_name'],
            'guest_name' => $_POST['guest_name'],
            'guest_age' => '',
            'guest_address' => '',
            'guest_check_in' => '',
            'guest_check_out' => $_POST['guest_check_out'],
            'include_in_mailing_list'=> 'Yes'
            );
        $dbh->add($param);
        $survey_header_id = $dbh->auto_id;
    // debug($survey_header_id);
    // debug($arr_result);

        $dbh = cobalt_load_class('survey_details');

        /* Ratings Submit */
        for($a = 0;$a <count($_POST['ratings']);++$a)
        {
            for($b = 0;$b<count($_POST['ratings'][$a]);++$b)
            {
            // debug(count($_POST['ratings'][$a]));
                if(isset($arr_result[$a]['questions'][$b]['question_details_id']))
                {
                    $question_details_id = $arr_result[$a]['questions'][$b]['question_details_id'];
                }
                else
                {
                    $question_details_id = NULL;
                }

                $parameters = array(
                 'survey_header_id'=>$survey_header_id,
                 'question_header_id'=>$arr_result[$a]['question_header_id'],
                 'question_details_id'=>$question_details_id,
                 'points'=>$_POST['ratings'][$a][$b],
                 'feedback'=>''
                 );
                $dbh->add($parameters); 
            }
        }
        /* End-Ratings Submit*/

        /* Checkbox Submit*/
        for($a = 0;$a <count($_POST['checkbox']);++$a)
        {
            if(isset($_POST['checkbox'][$a]))
            {
            // brpt();
            // $arr_keys = array_keys($_POST['checkbox'][$a]); 
            // debug($arr_keys);
                for($j = 0;$j < count($arr_result_checkbox[$a]['questions']);++$j)
                {
                    if(isset($_POST['checkbox'][$a][$j]))
                    {
                    //do nothing
                    }
                    else
                    {
                        $_POST['checkbox'][$a][$j] = "";
                    }
                }  
            // array_values($_POST['checkbox'][$a]);
            // debug($_POST['checkbox']);
                for($b = 0;$b<count($_POST['checkbox'][$a]);++$b)
                {
                    if($_POST['checkbox'][$a][$b] != "")
                    {
                        $parameters = array(
                           'survey_header_id'=>$survey_header_id,
                           'question_header_id'=>$arr_result_checkbox[$a]['question_header_id'],
                           'question_details_id'=>$arr_result_checkbox[$a]['questions'][$b]['question_details_id'],
                           'points'=>'',
                           'feedback'=>$_POST['checkbox'][$a][$b]
                           );
                        $dbh->add($parameters); 
                    }


                }
            }
        }
        /* End-Checkbox Submit*/

        /* Comments and Suggestion*/
    // for($a = 0;$a <count($arr_result_feedback[$a]['questions']);++$a)
    // {
    //     debug(count($_POST['ratings'][$a]));
    //     if(isset($_POST['feedback'][$a]))
    //     {
            // $counter = count($arr_result_feedback);
            // debug($counter);
        for($b = 0;$b<count($_POST['feedback']);++$b)
        {
            if($_POST['feedback'][$b] != "")
            {
                $parameters = array(
                 'survey_header_id'=>$survey_header_id,
                 'question_header_id'=>$arr_result_feedback[0]['question_header_id'],
                 'question_details_id'=>$arr_result_feedback[0]['questions'][$b]['question_details_id'],
                 'points'=>'',
                 'feedback'=>$_POST['feedback'][$b]
                 );
                $dbh->add($parameters); 
            }

        }
    }

    else {
        $message = 'Please complete fields with radio button';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Taal Vista Survey Form</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/stylish-portfolio.css" rel="stylesheet">
    <link href="assets/css/taal-vista.css" rel="stylesheet">
</head>

<body style="background-color: gold; background: url(assets/img/taal-vista.jpg); background-size: 100% 100%; background-attachment: fixed; background-repeat: no-repeat;">

<div class="logbox">
<img src="assets/img/logo.png">

<form method="POST" action="home.php">
    <!-- Table for Guest Information -->
    <table>
        <tbody>
            <tr>
                <?php echo '<td>Name: <input class="form-control" type="text" name="guest_name"></td>';?>
            </tr>

            <tr>
                <?php echo '<td>Room Number: <input class="form-control" type="text" name="room_number"></td>';?>
            </tr>

            <tr>
                <?php echo '<td>Check-out date: <input class="form-control" type="date" name="guest_check_out"></td>';?>
            </tr>

        </tbody>
    </table>


    <!-- Table for Scorecard -->
    <table class="table table-borderless" align="center">
        <thead>
            <tr>
              <th style="width:50%;">  </th>
              <th>Excellent</th>
              <th>Very<br/> Good</th>
              <th>Good</th>
              <th>Fair</th>
              <th>Poor</th>
          </tr>
      </thead>

        <tbody>
            <?php
            for($a = 0;$a < count($arr_result);++$a)
            {
             ?>
            <tr>
                <td style="font-weight: bold;"><?php echo $arr_result[$a]['question_header_description']?></td>

                <?php
                if(isset($arr_result[$a]['questions']))
                {
                    for($b = 0;$b < count($arr_result[$a]['questions']);++$b)
                    {
                        echo '<td></td><td></td><td></td><td></td><td></td><tr>';
                        echo '<td style="font-style: italic;">'. $arr_result[$a]['questions'][$b]['question_details_description'].'</td>';
                        echo '<td align="center"><input type="radio" name="ratings['.$a.']['.$b.']" value="5"></td>';
                        echo '<td><input type="radio" name="ratings['.$a.']['.$b.']" value="4"></td>';
                        echo '<td><input type="radio" name="ratings['.$a.']['.$b.']" value="3"></td>';
                        echo '<td><input type="radio" name="ratings['.$a.']['.$b.']" value="2"></td>';
                        echo '<td><input type="radio" name="ratings['.$a.']['.$b.']" value="1"></td>';
                        echo '</tr>';

                    } 
                }

                else
                {
                    echo '<td align="center"><input type="radio" name="ratings['.$a.']" value="5"></td>';
                    echo '<td><input type="radio" name="ratings['.$a.']" value="4"></td>';
                    echo '<td><input type="radio" name="ratings['.$a.']" value="3"></td>';
                    echo '<td><input type="radio" name="ratings['.$a.']" value="2"></td>';
                    echo '<td><input type="radio" name="ratings['.$a.']" value="1"></td>';
                    echo '</tr>';
                }
                ?>
                <?php        
            }        
            ?>
        </tbody>
    </table> 

    <!-- Table for Checkbox -->
    <table class="table table-borderless" align="center">
        <tbody>
        <?php
        for($c = 0;$c < count($arr_result_checkbox);++$c)
        {
            ?>
            <tr>
                <td style="font-weight: bold;"><?php echo $arr_result_checkbox[$c]['question_header_description']?></td>
                
                <?php
                if(isset($arr_result_checkbox[$c]['questions']))
                {

                        // debug($arr_result);
                    for($d = 0;$d < count($arr_result_checkbox[$c]['questions']);++$d)
                    {
                        echo '<td></td><td></td><td></td><td></td><td></td><tr>';
                        echo '<td> <input type="checkbox" name="checkbox['.$c.']['.$d.']" value="'.$arr_result_checkbox[$c]['questions'][$d]['question_details_description'].'">&nbsp;'. $arr_result_checkbox[$c]['questions'][$d]['question_details_description'].'</td>';
                            // echo '<td style="font-style: italic;"></td>';
                        echo '</tr>';

                    } 
                }
                ?>
                <?php        
            }        
            ?>         
        </tbody>
    </table>    

    <!-- Table for Comments and Suggestions -->
    <table class="table table-borderless" align="center">
        <tbody>
            <?php
            for($e = 0;$e < count($arr_result_feedback);++$e)
            {
                ?>
                <tr>
                    <td style="font-weight: bold;"><?php echo $arr_result_feedback[$e]['question_header_description']?></td>
                    
                    <?php
                    if(isset($arr_result_feedback[$e]['questions']))
                    {
                        for($f = 0;$f < count($arr_result_feedback[$e]['questions']);++$f)
                        {
                            echo '<tr>';

                            if($f == 2) {    
                                echo '<td>&nbsp;'. $arr_result_feedback[$e]['questions'][$f]['question_details_description'].'<br/><textarea rows = "5" cols = "50" name="feedback['.$f.']" placeholder="Name of Staff"></textarea></td>';
                            }

                            else {
                                echo '<td>&nbsp;'. $arr_result_feedback[$e]['questions'][$f]['question_details_description'].'<br/><textarea rows = "5" cols = "50" name="feedback['.$f.']"></textarea></td>';
                            }
                            
                            echo '</tr>';
                        } 
                    }
                ?>
                <?php        
            }        
            ?>         
        </tbody>
    </table>  

    <div align="center">
        <input type="submit" class="btn btn-lg" name = "btn_submit" style="background-color: #B48A00; color: white;">
        <!-- <a href="home.php" class="btn-lg" style="background-color: #B48A00; color: white;">Take Survey</a> -->
    </div>

</form>
</div>

</body>
</html>