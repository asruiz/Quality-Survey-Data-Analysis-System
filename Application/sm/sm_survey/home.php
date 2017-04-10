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

if($_POST['btn_submit'])
{
    $dbh = cobalt_load_class('survey_header');

    $radio_counter = 0;
    $radio_question_counter = 0;
    for ($a = 0; $a < count($arr_result); ++$a) {
        if (isset($_POST['ratings'][$a])) {
            if (!is_array($_POST['ratings'])) {
                ++$radio_counter;
            }
            else {
                $radio_counter += count($_POST['ratings'][$a]);
            }
        }
        if (!isset($arr_result[$a]['questions']) && $arr_result[$a]['is_active'] == 'Yes') {
            ++$radio_question_counter;
        }

        else {
            $radio_question_counter += count($arr_result[$a]['questions']);
        }
    }

    $dbh_pre_reg_request_header =  cobalt_load_class('survey_header');
    $dbh_pre_reg_request_header->execute_query("SELECT COUNT(survey_header_id) AS req_id FROM survey_header");

    $result = $dbh_pre_reg_request_header->result;
    $row = $result->fetch_assoc();

    extract($row);


    if($row == '')
    {
        $counter = 1;
    }
    else
    {
        $counter = ++$req_id;
    }
    $req_tag = 'SN';
    $req_count = str_pad($counter, '5','0', STR_PAD_LEFT);
    $code = "{$req_tag}".date('Y')."-{$req_count}";

    if ($radio_counter == $radio_question_counter) {
        $param = array(
            'branch_id' => '',
            'survey_number' => $code,
            'room_number' => $_POST['room_number'],
            'date_submitted' => date('Y-m-d'),
            'guest_first_name' => $_POST['guest_first_name'],
            'guest_last_name' => $_POST['guest_last_name'],
            'guest_age' => '',
            'guest_address' => '',
            'guest_check_in' => '',
            'guest_check_out' => $_POST['guest_check_out'],
            'include_in_mailing_list'=> 'Yes'
            );
        $dbh->add($param);
        $survey_header_id = $dbh->auto_id;
        $dbh = cobalt_load_class('survey_details');

        /* Ratings Submit */
        for($a = 0;$a <count($_POST['ratings']);++$a)
        {
            for($b = 0;$b<count($_POST['ratings'][$a]);++$b)
            {
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
        redirect('end.php');
    }

    else {
        $message = 'Please complete fields with radio button';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Taal Vista | Survey</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/stylish-portfolio.css" rel="stylesheet">
    <link href="assets/css/taal-vista.css" rel="stylesheet">
    <link href="assets/css/input-text.css" rel="stylesheet">
    <style type="text/css">
        body {
            background-color: gold; 
            background: url(assets/img/taal-vista.jpg); 
            background-size: 100% 100%; 
            background-attachment: fixed; 
            background-repeat: no-repeat;
        }

        .logbox {
            margin: 0 auto;
            margin-top: 1%;
            margin-bottom: 5%;
            overflow: hidden;
            background-color: #fff;
            border: 4px solid #B48A00;
            border-radius: 25px;
            width: 800px;
            height: auto;
            -webkit-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.25);
            -moz-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.25);
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.25);
        }
        .hr-line {
            color: #fff;
            box-shadow: 2px 2px 5px rgba(180,138,0,0.4);
            border: 1px solid #B48A00;
            width:80%;
        }
        
        .guest-info-container {
            padding-left: 10%; 
            margin-top: -4%;
        } 

        #overlay 
        {
            position:fixed;
            top:1px;
            left:1px;
            height:100%;
            width:100%;
            visibility:hidden;
            background-color:rgba(255,255,255,.4);
        }

        .modal_content
        {
         height:300px;

     }
    </style>
</head>

<body>
    <div class="logbox">
        <img src="assets/img/logo.png">
            <div align="center">
                <h3>Customer Survey</h3>
                <hr class="hr-line">
            </div>
        <?php
        require_once '../sm_survey_admin/core/subclasses/user_html.php';
        $html = new user_html;

        if($message != "")
        {
            $html->draw_header();
            $html->display_error($message);
        }
        ?>

        <form method="POST" action="home.php">
            <!-- Table for Guest Information -->
            <div id = "overlay">
                <div class="logbox">
                    <div align="center">
                       <br/><img src = "assets/img/warning.png" style="width: 100px; height: 100px;"><br/>

                       <h2>Are you sure?</h2>

                       <input type="submit" class="btn btn-lg" style="background-color: #B48A00; color: white;" name="btn_submit"  value="Yes">
                       <span class="btn btn-lg" style="background-color: #B48A00; color: white;" onclick="hide_modal()">No</span>
                   </div>
                   <br/>
               </div>
           </div>
           <br>
           <div class ="guest-info-container">
            <table>
                <tbody>
                    <tr>
                        <?php echo '<td><input class="input form-inline" type="text" name="guest_first_name" placeholder="First Name"></td>';?>
                        <td> &nbsp; &nbsp; </td>
                        <?php echo '<td><input class="input form-inline" type="text" name="guest_last_name" placeholder="Last Name"></td>';?>
                    </tr>

                    <tr>
                        <?php echo '<td><input class="input form-inline" type="text" name="room_number" placeholder="Room Number"></td>';?>
                        <td> &nbsp; &nbsp; </td>
                        <td><input class="input form-inline" placeholder="Check-out Date" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" <?php echo 'name="guest_check_out"'?>></td>
                    </tr>

                </tbody>
            </table>
        </div>

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
                        echo '<td align="center"><input type="radio" class="radio" name="ratings['.$a.']['.$b.']" value="5"';
                        if(isset($_POST['ratings'][$a][$b]) && $_POST['ratings'][$a][$b] == '5')
                        { 
                            echo 'checked="checked"';
                        }
                        echo '></td>';

                        echo '<td><input type="radio" name="ratings['.$a.']['.$b.']" value="4"';
                        if(isset($_POST['ratings'][$a][$b]) && $_POST['ratings'][$a][$b] == '4')
                        { 
                            echo 'checked="checked"';
                        }
                        echo '></td>';

                        echo '<td><input type="radio" name="ratings['.$a.']['.$b.']" value="3"';
                        if(isset($_POST['ratings'][$a][$b]) && $_POST['ratings'][$a][$b] == '3')
                        { 
                            echo 'checked="checked"';
                        }
                        echo '></td>';

                        echo '<td><input type="radio" name="ratings['.$a.']['.$b.']" value="2"';
                        if(isset($_POST['ratings'][$a][$b]) && $_POST['ratings'][$a][$b] == '2')
                        { 
                            echo 'checked="checked"';
                        }
                        echo '></td>';

                        echo '<td><input type="radio" name="ratings['.$a.']['.$b.']" value="1"';
                        if(isset($_POST['ratings'][$a][$b]) && $_POST['ratings'][$a][$b] == '1')
                        { 
                            echo 'checked="checked"';
                        }
                        echo '></td>';
                        echo '</tr>';

                    } 
                }

                else
                {
                 echo '<td align="center"><input type="radio" name="ratings['.$a.']" value="5"';
                 if(isset($_POST['ratings'][$a]) && $_POST['ratings'][$a] == '5')
                 { 
                    echo 'checked="checked"';
                }
                echo '></td>';

                echo '<td><input type="radio" name="ratings['.$a.']" value="4"';
                if(isset($_POST['ratings'][$a]) && $_POST['ratings'][$a] == '4')
                { 
                    echo 'checked="checked"';
                }
                echo '></td>';

                echo '<td><input type="radio" name="ratings['.$a.']" value="3"';
                if(isset($_POST['ratings'][$a]) && $_POST['ratings'][$a]== '3')
                { 
                    echo 'checked="checked"';
                }
                echo '></td>';

                echo '<td><input type="radio" name="ratings['.$a.']" value="2"';
                if(isset($_POST['ratings'][$a]) && $_POST['ratings'][$a] == '2')
                { 
                    echo 'checked="checked"';
                }
                echo '></td>';

                echo '<td><input type="radio" name="ratings['.$a.']" value="1"';
                if(isset($_POST['ratings'][$a]) && $_POST['ratings'][$a] == '1')
                { 
                    echo 'checked="checked"';
                }
                echo '></td>';
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
                        if(isset($_POST['checkbox'][$c][$d]) && $_POST['checkbox'][$c][$d] == $arr_result_checkbox[$c]['questions'][$d]['question_details_description'])
                        {
                            $checked = 'checked = "checked"';
                        }
                        else
                        {
                            $checked = "";
                        }

                        echo '<td></td><td></td><td></td><td></td><td></td><tr>';
                        echo '<td> <input type="checkbox" name="checkbox['.$c.']['.$d.']" 
                        value="'.$arr_result_checkbox[$c]['questions'][$d]['question_details_description'].'" 
                        '.$checked.'>
                        &nbsp;'.$arr_result_checkbox[$c]['questions'][$d]['question_details_description'].'</td>';
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
                    <td style="font-weight: bold"><?php echo $arr_result_feedback[$e]['question_header_description']?></td>
                    <?php
                    if(isset($arr_result_feedback[$e]['questions']))
                    {
                        for($f = 0;$f < count($arr_result_feedback[$e]['questions']);++$f)
                        {
                            echo '<tr>';
                            if(isset($_POST['feedback'][$f]))
                            {
                                $textarea_value = $_POST['feedback'][$f]; 
                            }
                            else
                            {
                                $textarea_value = "";
                            }
                            if($f == 2) {    
                                echo '<td>&nbsp;'. $arr_result_feedback[$e]['questions'][$f]['question_details_description'].'<br/><textarea rows = "5" cols = "50" name="feedback['.$f.']" placeholder="Name of Staff" class="input-textarea">'.$textarea_value.'</textarea></td>';
                            }

                            else {
                                echo '<td>&nbsp;'. $arr_result_feedback[$e]['questions'][$f]['question_details_description'].'<br/><textarea rows = "5" cols = "50" name="feedback['.$f.']" class="input-textarea   ">'.$textarea_value.'</textarea></td>';
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
    </form>
    <div align="center">
        <button class="btn btn-lg" style="background-color: #B48A00; color: white;" onclick="show_modal()">Submit</button>
        <br><br>
    </div>
</div>

</body>

<script>
    function show_modal()
    {
        document.getElementById("overlay").style.visibility = "visible";
    }

    function hide_modal()
    {
        document.getElementById("overlay").style.visibility = "hidden";
    }
</script>
</html>