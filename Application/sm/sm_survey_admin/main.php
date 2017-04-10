<?php
require_once 'path.php';
init_cobalt('ALLOW_ALL',FALSE);

$html = new html;
// $html->custom_header = 'custom_header.php';
$html->draw_header('Welcome to your Control Center', $message, $message_type, FALSE);

if(ENABLE_SIDEBAR)
{
    echo '
    <script>
    if (top.location == location)
    {
        window.location.replace("start.php");
    }
    </script>
    ';
}

if(DEBUG_MODE)
{
    $html->display_error('System is running in DEBUG MODE. Please contact the system administrator ASAP.');
}


$menu_links = array();
$data_con = new data_abstraction;
$data_con->set_fields('a.link_id, a.descriptive_title, a.target, a.description, c.passport_group, a.icon as link_icon, c.icon as `group_icon`');
$data_con->set_table('user_links a, user_passport b, user_passport_groups c');
$data_con->set_where("a.link_id=b.link_id AND b.username='" . quote_smart($_SESSION['user']) . "' AND a.passport_group_id=c.passport_group_id AND a.show_in_tasklist='Yes' AND a.status='On'");
$data_con->set_order('c.priority DESC, c.passport_group, a.priority DESC, a.descriptive_title');
if($result = $data_con->make_query()->result)
{
    while($data = $result->fetch_assoc())
    {
        extract($data);
        $menu_links[$passport_group]['title'][]       = $descriptive_title;
        $menu_links[$passport_group]['target'][]      = $target;
        $menu_links[$passport_group]['link_id'][]     = $link_id;
        $menu_links[$passport_group]['description'][] = $description;
        $menu_links[$passport_group]['link_icon'][]   = $link_icon;
        $menu_links[$passport_group]['group_icon'][]  = $group_icon;
    }
    $result->close();
}
else die("Fatal error: cannot retrieve modules");
unset($data_con);

if(isset($_SESSION['control_center_columns']) && $_SESSION['control_center_columns'] > 0)
{
    $columns_per_row = $_SESSION['control_center_columns'];
}
elseif(defined('CONTROL_CENTER_COLUMNS'))
{
    $columns_per_row = CONTROL_CENTER_COLUMNS;
}
else
{
    $columns_per_row = 3; //just an arbitrary default value based on historical Cobalt setting
}

$cntr_limit = $columns_per_row - 1; //subtraction needed due to 0-based counter
$column_width = (100 / $columns_per_row);
$current_group='';
$cntr=0;
if(is_array($menu_links))
{
    $target_frame='';
    if(ENABLE_SIDEBAR)
    {
        $target_frame = 'target="content_frame"';
    }

    $dbh = cobalt_load_class('question_header');
    $result = $dbh->execute_query("SELECT *, question_header.question_header_id as 'header_id' FROM question_header LEFT JOIN question_details on question_header.question_header_id = question_details.question_header_id WHERE question_type ='Scorecard'")->result;
    $counter = 0;
    $arr_month = array('01','02','03','04','05','06','07','08','09','10','11','12');
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
            $data = explode('. ',$row['question_header_description']);
            // debug($data);
            $arr_questions['header'][$counter] = $data[1];
        }
        ++$counter;
    }
    // debug($arr_questions);
    echo '<fieldset class="container">';                  

    // Passport group per question

    for($a = 0;$a< count($arr_questions['question']);++$a)
    {
        if($arr_questions['question'][$a] == 'Overall')
        {
            $header = $arr_questions['question'][$a].': '.$arr_questions['header'][$a];
        }
        else
        {
            $header =$arr_questions['question'][$a];
        }

        $rating_string = "";
        $prev_rating_string = "";
        $total_current = 0;
        $total_prev = 0;
        $total_survey_counter = 0;
        $total_survey_counter_p = 0;
        for($b = 0;$b<count($arr_month);++$b)
        {

            if(date('Y').'-'.$arr_month[$b] == date('Y-m'))
            {
                $date_from = date('Y').'-'.$arr_month[$b].'-1';
                $prev_date_from = date('Y').'-'.$arr_month[$b].'-1';
                $date_to = date('Y').'-'.$arr_month[$b].'-'.date('d');
                $prev_date_to = (date('Y')-1).'-'.$arr_month[$b].'-'.date('d');   
            }
            else
            {
                $date_from = date('Y').'-'.$arr_month[$b].'-1';
                $prev_date_from = (date('Y')-1).'-'.$arr_month[$b].'-1';
                $date_to = date('Y').'-'.$arr_month[$b].'-31';
                $prev_date_to = (date('Y')-1).'-'.$arr_month[$b].'-31';

            }


            if($arr_questions['is_header'][$a] == 'Yes')
            {

                $query = 'SELECT * FROM survey_details LEFT JOIN survey_header ON survey_details.survey_header_id = survey_header.survey_header_id WHERE question_header_id ='.$arr_questions['question_id'][$a].' AND (guest_check_out BETWEEN "'.$date_from.'" AND "'.$date_to.'") ';   

                $prev_query = 'SELECT * FROM survey_details LEFT JOIN survey_header ON survey_details.survey_header_id = survey_header.survey_header_id WHERE question_header_id ='.$arr_questions['question_id'][$a].' AND (guest_check_out BETWEEN "'.$prev_date_from.'" AND "'.$prev_date_to.'") ';    
            }
            else
            {
                $query = 'SELECT * FROM survey_details LEFT JOIN survey_header ON survey_details.survey_header_id = survey_header.survey_header_id WHERE question_details_id ='.$arr_questions['question_id'][$a].' AND (guest_check_out BETWEEN "'.$date_from.'" AND "'.$date_to.'") ';  

                $prev_query = 'SELECT * FROM survey_details LEFT JOIN survey_header ON survey_details.survey_header_id = survey_header.survey_header_id WHERE question_details_id ='.$arr_questions['question_id'][$a].' AND (guest_check_out BETWEEN "'.$prev_date_from.'" AND "'.$prev_date_to.'") ';   
            }

            $dbh = cobalt_load_class('survey_header');
            $result = $dbh->execute_query($query)->result;

            // debug($dbh->query);
            $excellent_counter = 0;
            $survey_counter = 0;
            while($row = $result->fetch_assoc())
            {
                if($row['points'] == 5)
                {
                    ++$excellent_counter;
                }
                ++$survey_counter;
            }

            $dbh = cobalt_load_class('survey_header');
            $result = $dbh->execute_query($prev_query)->result;

            $excellent_counter_p = 0;
            $survey_counter_p = 0;
            while($row = $result->fetch_assoc())
            {
                if($row['points'] == 5)
                {
                    ++$excellent_counter_p;
                }
                ++$survey_counter_p;
            }
            if($excellent_counter != 0)
            {
                $percentage_value = ($excellent_counter/$survey_counter)*100;
            }   
            else
            {
                $percentage_value = 0;
            }

            if($excellent_counter_p != 0)
            {
                $percentage_value_p = ($excellent_counter_p/$survey_counter_p)*100;

            }
            else
            {
                 $percentage_value_p = 0;
            }
            $rating_string .= "['".date('M',strtotime(date('Y').'-'.$arr_month[$b]))."',".$percentage_value."],";
            $prev_rating_string .= "['".date('M',strtotime((date('Y')-1).'-'.$arr_month[$b]))."',".$percentage_value_p."],";
            $total_current += $excellent_counter;
            $total_prev += $excellent_counter_p;
            $total_survey_counter += $survey_counter;
            $total_survey_counter_p += $survey_counter_p;
        }

        if($total_current != 0)
        {
            $ytd_percentage = ($total_current/$total_survey_counter)*100;
        }
        else
        {
            $ytd_percentage = 0;
        }

        if($total_prev != 0)
        {
            $ytd_percentage_p = ($total_prev/$total_survey_counter_p)*100;
        }
        else
        {
            $ytd_percentage_p = 0;
        }

        $rating_string .= "['YTD',".$ytd_percentage."]";
        $prev_rating_string .= "['YTD',".$ytd_percentage_p."]";

        echo '<fieldset class="top">'.$header.'</fieldset>';
            echo '<fieldset class="middle">';
                echo '<div class="container_icons_CC">';
 
?>
            <!--Load the AJAX API-->
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
                google.charts.load('current', {packages:['corechart']});
                google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    var oldData = google.visualization.arrayToDataTable([
                      ['Name', '<?php echo date('Y') ?>'],
                      <?php echo $rating_string;?>

                      ]);

                    var newData = google.visualization.arrayToDataTable([
                      ['Name', '<?php echo date('Y') ?>'],
                      <?php echo $prev_rating_string;?>
                      ]);

                    var colChartDiff = new google.visualization.ColumnChart(document.getElementById('colchart_diff<?php echo $a?>'));

                    var options = { legend: { position: 'top' } };

                    var diffData = colChartDiff.computeDiff(oldData, newData);
                    colChartDiff.draw(diffData, options);
                }
            </script>

            <span id='colchart_diff<?php echo $a?>' style='width: 100%; height: 250px; display: inline-block'></span>

<?php                 
                echo '</div>';
            echo '</fieldset>';
       
    }
         echo '</fieldset>';
    echo '</fieldset>';
    // END - NOT COBALT DEFAULT: This will render the graph section of the dashboard...


    echo '<fieldset class="container">';
    foreach($menu_links as $group => $link_info)
    {
        if($current_group=='')
        {
            $current_group = $group;
            menuGroupWindowHeader($group, $link_info['group_icon'][0]);
        }

        $num_links = count($link_info['title']);
        for($a=0; $a<$num_links; ++$a)
        {
            if($current_group!= $group)
            {
                echo '</tr></table></div>';
                $cntr=0;
                menuGroupWindowFooter();
                menuGroupWindowHeader($group, $link_info['group_icon'][$a]);
                $current_group = $group;
            }

            if($cntr==0)
            {
                echo '<div class="container_icons_CC">';
                echo '<table width = "100%">';
                echo '<tr>';
            }
            elseif($cntr > $cntr_limit)
            {
                echo '</tr></table>';
                echo '</div><div class="container_icons_CC">';
                echo '<table width = "100%">';
                echo '<tr>';
                $cntr = 0;
            }
            ++$cntr;
            echo '<td width="' . $column_width . '%" valign="top">
                    <a href="/' . BASE_DIRECTORY . '/' . $link_info['target'][$a] . '" $target_frame class="linkCC">
                        <img src="images/' . $_SESSION['icon_set'] . '/' . $link_info['link_icon'][$a] . '"><br>' . $link_info['title'][$a] . '
                    </a>
                  </td>';

        }

        //Just to be sure we have three columns before closing the table
        for($z = $cntr; $z<=$cntr_limit; ++$z)
        {
            echo '<td width="'. $column_width . '%"> &nbsp; </td>';
        }
    }
    echo '</tr></table></div>';
    echo '</fieldset>';
}
else
{
    $html->display_error("You have no Control Center privileges in your account. Please contact your system administrator.");
}

menuGroupWindowFooter();

function menuGroupWindowHeader($group, $icon)
{
    echo '<fieldset class="top">';
    echo "<img src='images/" . $_SESSION['icon_set'] . "/$icon'> $group";
    echo '</fieldset>';
    echo '<fieldset class="middle">';
}

function menuGroupWindowFooter()
{
    echo '</fieldset>';
}
$html->draw_footer();
