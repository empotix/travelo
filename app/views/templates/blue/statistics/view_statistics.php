<link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<?php
    $this->load->view(THEME_FOLDER . '/includes/dash_header');
    $room_id = $this->uri->segment(3);
?>

<div id="dashboard_container">
    <div id="View_Edit_Profile" class="Box">
        <div class="Box_Head">
            <h2><?php echo translate("Statistics"); ?> </h2></div>


        <style type="text/css">
            body { margin: 0; }
            /* Graph 1 */
            #testgraph .graph { 
                height: 170px; /* Needs to be divisible by Number of Graph lines, IE & Chrome will round down decimals in CSS. FF will use the decimals. */
                width: 550px; /* Needs to be divisible by number of Bars, if you want them to be centered nicely */ 
            }

            .graphtable td { vertical-align: top; font-family: Arial, Helvetica, sans-serif; }
            .graphtable label { display: block; width: 30px; height: 25px; font-size: 13px; padding-left: 0px;float:left; }
            .graphtable label span { display: block; width: 55px; float: right; height: 15px; width: 15px;  border: 1px solid #ccc; text-indent: -20px;  }
            .line { font-size: 10px; }
            .graph { margin-left: 20px; border: 1px solid #666; }
            .graph .line { border-bottom: 1px solid #ccc; margin-top: -1px; }
            .graph .fix  { border-bottom: none;}

            .graph .line span { position: absolute; display: block; margin-left: -40px; width: 35px; text-align: right; margin-top: -5px; }
            .bar { position: absolute; margin-bottom: 0; }
            .graph_Head{
                padding:20px 0 20px 38px;
                color:#1D9EDC;
            }
            .values.month label{
                padding:0 14px 0 2px;
            }
            #yeargraph {
                padding:0 0 50px 0;
            }
            .values.year  label {
                padding:0 170px 0 90px;
            }
        </style>

        <style type="text/css">
            body { margin: 0; }
            /* Graph 1 */
            #yeargraph .graph { 
                height: 170px; /* Needs to be divisible by Number of Graph lines, IE & Chrome will round down decimals in CSS. FF will use the decimals. */
                width: 550px; /* Needs to be divisible by number of Bars, if you want them to be centered nicely */ 
            }

            /*.graphtable td { vertical-align: top; font-family: Arial, Helvetica, sans-serif; }
            .graphtable label { display: block; width: 145px; height: 25px; font-size: 13px; padding-left: 35px; }
            .graphtable label span { display: block; width: 55px; float: right; height: 15px; width: 15px;  border: 1px solid #ccc; text-indent: -20px;  }
            .line { font-size: 10px; }
            .graph { margin-left: 35px; border: 1px solid #666; }
            .graph .line { border-bottom: 1px solid #ccc; margin-top: -1px; }
            .graph .fix  { border-bottom: none;}
            
            .graph .line span { position: absolute; display: block; margin-left: -40px; width: 35px; text-align: right; margin-top: -5px; }
            .bar { position: absolute; margin-bottom: 0; }*/

        </style>

        <style type="text/css">
            body { margin: 0; }
            /* Graph 1 */
            #dategraph .graph { 
                height: 170px; /* Needs to be divisible by Number of Graph lines, IE & Chrome will round down decimals in CSS. FF will use the decimals. */
                width: 930px; /* Needs to be divisible by number of Bars, if you want them to be centered nicely */ 
            }

            /*.graphtable td { vertical-align: top; font-family: Arial, Helvetica, sans-serif; }
            .graphtable label { display: block; width: 145px; height: 25px; font-size: 13px; padding-left: 35px; }
            .graphtable label span { display: block; width: 55px; float: right; height: 15px; width: 15px;  border: 1px solid #ccc; text-indent: -20px;  }
            .line { font-size: 10px; }
            .graph { margin-left: 35px; border: 1px solid #666; }
            .graph .line { border-bottom: 1px solid #ccc; margin-top: -1px; }
            .graph .fix  { border-bottom: none;}

            .graph .line span { position: absolute; display: block; margin-left: -40px; width: 35px; text-align: right; margin-top: -5px; }
            .bar { position: absolute; margin-bottom: 0; }

        </style>
        <script type="text/javascript">

            /**
             * Initiates Graph Functions
             **/
            function graphit($graph_id, $lines, $bar_margins, $bar_speed, $animate) {

                $v = new Object(); // create graph object
                $v.graphid = $graph_id; // id of graph container, example "graph1" or "myGraph"
                $v.values = new Array(); // array of values
                $v.heights = new Array(); // array of bar heights
                $v.colors = new Array(); // colors for bars
                $v.lines = $lines; // number of lines - keep this 10 unless you want to write a bunch more code
                $v.bm = $bar_margins; // margins between the bars
                $v.mx = 0; // highest number, or rounded up number
                $v.gw = $('#' + $v.graphid + ' .graph').width(); // graph width	
                $v.gh = $('#' + $v.graphid + ' .graph').height(); // graph height
                $v.speed = $bar_speed; // speed for bar animation in milliseconds
                $v.animate = $animate; // determines if animation on bars are run, set to FALSE if multiple charts
                getValues(); // load the values & colors for bars into $v object	
                graphLines(); // makes the lines for the chart
                graphBars(); // make the bars
                if ($v.animate)
                    animateBars(0); // animate and show the bars
            }

            function graphit_date($graph_id, $lines, $bar_margins, $bar_speed, $animate) {

                $v = new Object(); // create graph object
                $v.graphid = $graph_id; // id of graph container, example "graph1" or "myGraph"
                $v.values = new Array(); // array of values
                $v.heights = new Array(); // array of bar heights
                $v.colors = new Array(); // colors for bars
                $v.lines = $lines; // number of lines - keep this 10 unless you want to write a bunch more code
                $v.bm = $bar_margins; // margins between the bars
                $v.mx = 0; // highest number, or rounded up number
                $v.gw = $('#' + $v.graphid + ' .graph').width(); // graph width	
                $v.gh = $('#' + $v.graphid + ' .graph').height(); // graph height
                $v.speed = $bar_speed; // speed for bar animation in milliseconds
                $v.animate = $animate; // determines if animation on bars are run, set to FALSE if multiple charts
                getValues(); // load the values & colors for bars into $v object	
                graphLines(); // makes the lines for the chart
                graphBars_date(); // make the bars
                if ($v.animate)
                    animateBars(0); // animate and show the bars
            }

            function graphBars_date() {
                $xbars = $v.values.length; // number of bars	
                $barW = ($v.gw - ($xbars * ($v.bm))) / $xbars;
                $mL = ($('#' + $v.graphid + ' .line span').width()) + ($v.bm / 2) - 25;
                $html = "";
                for ($i = 1; $i <= $xbars; $i++) {
                    $v.heights.push(($v.gh / $v.mx) * $v.values[$i - 1]);
                    $ht = ($v.animate == true) ? 0 : $v.heights[$i - 1];
                    $html += "<div class='bar' id='" + $v.graphid + "_bar_" + ($i - 1) + "' style='height: " + $ht + "px; margin-top: -" + ($ht + 1) + "px; ";
                    $html += "background-color: " + $v.colors[$i - 1] + "; margin-left: " + $mL + "px'>&nbsp;</div>";
                    $mL = $mL + $barW + $v.bm;
                }
                $($html).insertAfter('#' + $v.graphid + ' .graph');
                //$('#'+$v.graphid+' .bar').css("width", $barW + "px");			
            }


            /**
             * Makes the HTML for the lines on the chart, and places them into the page.
             **/
            function graphLines() {
                $r = ($v.mx < 100) ? 10 : 100; // determine to round up to 10 or 100
                $v.mx = roundUp($v.mx, $r); // round up to get the max number for lines on chart
                $d = $v.mx / $v.lines; // determines the increment for the chart line numbers	

                // Loop through and create the html for the divs that will make up the lines & numbers
                $html = "";
                $i = $v.mx;
                if ($i > 0 && $d > 0) {
                    while ($i >= 0) {
                        $html += graphLinesHelper($i, $v.mx);
                        $i = $i - $d;
                    }
                }
                $('#' + $v.graphid + ' .graph').html($html); // Put the lines into the html		
                $margin = $v.gh / $v.lines; // Determine the margin size for line spacing
                $('#' + $v.graphid + ' .line').css("margin-bottom", $margin + "px");	// Add the margins to the lines			
            }

            /**
             * Creates the html for the graph lines and numbers
             **/
            function graphLinesHelper($num, $maxNum) {
                $fix = ($i == $maxNum || $i == 0) ? "fix " : ""; // adds class .fix, which removes the "border" for top and bottom lines
                return "<div class='" + $fix + "line'><span>" + $num + "</span></div>";
            }

            /**
             * A Simple Round Up Function
             **/
            function roundUp($n, $r) {
                return (($n % $r) > 0) ? $n - ($n % $r) + $r : $n;
            }

            /**
             * Gets the values & colors from the HTML <labels> and saves them into $v ohject
             **/
            function getValues() {
                $lbls = $('#' + $v.graphid + ' .values span'); // assigns the span DOM object to be looped through
                // loop through
                for ($i = 0; $i <= $lbls.length - 1; $i++) {
                    $vals = parseFloat($lbls.eq($i).text());
                    $v.colors.push($lbls.eq($i).css('background-color'));
                    $v.mx = ($vals > $v.mx) ? $vals : $v.mx;
                    $v.values.push($vals);
                }
            }

            /**
             * Creates the HTML for the Bars, adds colors, widths, and margins for proper spacing. 
             * Then Puts it on the page.
             **/
            function graphBars() {
                $xbars = $v.values.length; // number of bars	
                $barW = ($v.gw - ($xbars * ($v.bm))) / $xbars;
                $mL = ($('#' + $v.graphid + ' .line span').width()) + ($v.bm / 2) - 22;
                $html = "";
                for ($i = 1; $i <= $xbars; $i++) {
                    $v.heights.push(($v.gh / $v.mx) * $v.values[$i - 1]);
                    $ht = ($v.animate == true) ? 0 : $v.heights[$i - 1];
                    $html += "<div class='bar' id='" + $v.graphid + "_bar_" + ($i - 1) + "' style='height: " + $ht + "px; margin-top: -" + ($ht + 1) + "px; ";
                    $html += "background-color: " + $v.colors[$i - 1] + "; margin-left: " + $mL + "px'>&nbsp;</div>";
                    $mL = $mL + $barW + $v.bm;
                }
                $($html).insertAfter('#' + $v.graphid + ' .graph');
                $('#' + $v.graphid + ' .bar').css("width", $barW + "px");
            }

            /**
             * Animates the Bars to the correct heights.
             **/
            function animateBars($i) {
                if ($i == $v.values.length) {
                    return;
                }
                $('#' + $v.graphid + '_bar_' + $i).animate({
                    marginTop: "-" + ($v.heights[$i] + 1) + "px",
                    height: ($v.heights[$i]) + "px"
                }, $v.speed, "swing", function () {
                    animateBars($i + 1);
                });
            }

        </script>


        <script type="text/javascript">
            $(document).ready(function () {
                $graph_id = 'testgraph'; // id of graph container	
                $lines = 10; // number of lines - keep this 10 unless you want to write a bunch more code
                $bar_margins = 30; // margins between the bars
                $bar_speed = 500; // speed for bar animation in milliseconds		
                $animate = false; // set to false if multiple charts on one page
                graphit($graph_id, $lines, $bar_margins, $bar_speed, $animate);
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                $graph_id = 'yeargraph'; // id of graph container	
                $lines = 10; // number of lines - keep this 10 unless you want to write a bunch more code
                $bar_margins = 30; // margins between the bars
                $bar_speed = 500; // speed for bar animation in milliseconds		
                $animate = false; // set to false if multiple charts on one page
                graphit($graph_id, $lines, $bar_margins, $bar_speed, $animate);
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                $graph_id = 'dategraph'; // id of graph container	
                $lines = 10; // number of lines - keep this 10 unless you want to write a bunch more code
                $bar_margins = 30; // margins between the bars
                $bar_speed = 500; // speed for bar animation in milliseconds		
                $animate = false; // set to false if multiple charts on one page
                graphit_date($graph_id, $lines, $bar_margins, $bar_speed, $animate);
            });
        </script>




        <div class="graph_Head">
            <h3><?php echo translate("Days Travellers Visits (Last 31 Days)"); ?> </h3></div>

        <table id="dategraph" class="graphtable">
            <tr>
            <span style="margin-left: 39px;"> 
                <?php
                    if (empty($results))
                    {
                        echo translate("No Statistics Found");
                    }
                    else
                    {
                        ?>
                    </span>
                    <td>
                        <div class="gCont">

                            <div class="graph">                    
                            </div>   
                        </div>
                    </td>
                    </tr>
                    <tr style="position: absolute; left: 20px;">
                        <td class="values">
                            <?php
                            $currentmonth = date('m', time());

                            $currentyear = date('Y', time());
                            $currentdate = date('d', time());
                            $premonth = $currentmonth - 1;
                            $preyear = $currentyear - 1;
                            if ($premonth == 0)
                            {
                                $premonth = 12;
                            }
                            $days_in_month = cal_days_in_month(0, $premonth, $currentyear);

                            $count = 31 - $currentdate;
                            $start = $days_in_month - $count;


                            if ($count != 0)
                            {

                                if ($start < 0)
                                {
                                    ///
                                    if (($days_in_month == 28) && ($currentdate == 1))
                                    {
                                        ?>
                                        <label> Jan 30 <span style="background-color: #5CBBF9;display:none;">
                                                <?php
                                                $predate = $this->db->query('select date, SUM(page_view) AS total_predate from statistics where list_id=' . $room_id . ' AND date= 30 AND  month="January" AND year=' . $currentyear . '');
                                                $pre = $predate->result();

                                                foreach ($pre as $row1)
                                                {
                                                    if (empty($row1->date))
                                                    {
                                                        $predate1 = 0;
                                                    }
                                                    else
                                                    {
                                                        $predate1 = $row1->total_predate;
                                                    }
                                                }
                                                echo $predate1;
                                                ?>
                                            </span></label>				
                                        <label> Jan 31 <span style="background-color: #5CBBF9;display:none;">

                                                <?php
                                                $predate_inc12 = $this->db->query('select date, SUM(page_view) AS total_predate from statistics where list_id=' . $room_id . ' AND date= 31 AND  month="January" AND year=' . $currentyear . '');
                                                $pre_inc12 = $predate_inc12->result();

                                                foreach ($pre_inc12 as $row12)
                                                {
                                                    if (empty($row12->date))
                                                    {
                                                        $predate12 = 0;
                                                    }
                                                    else
                                                    {
                                                        $predate12 = $row12->total_predate;
                                                    }
                                                }
                                                echo $predate12;
                                                ?>

                                            </span></label>
                                        <?php
                                    }
                                    elseif (($days_in_month == 29) && ($currentdate == 1))
                                    {
                                        ?>
                                        <label> Jan 31 <span style="background-color: #5CBBF9;display:none;">

                                                <?php
                                                $predate_inc = $this->db->query('select date, SUM(page_view) AS total_predate from statistics where list_id=' . $room_id . ' AND date= 31 AND  month="January" AND year=' . $currentyear . '');
                                                $pre_inc = $predate_inc->result();

                                                foreach ($pre_inc as $row2)
                                                {
                                                    if (empty($row2->date))
                                                    {
                                                        $predate2 = 0;
                                                    }
                                                    else
                                                    {
                                                        $predate2 = $row2->total_predate;
                                                    }
                                                }
                                                echo $predate2;
                                                ?>

                                            </span></label>

                                        <?php
                                    }
                                    elseif (($days_in_month == 28) && ($currentdate == 2))
                                    {
                                        ?>


                                        <label> Jan 31 <span style="background-color: #5CBBF9;display:none;">

                                                <?php
                                                $precount = $this->db->query('select date, SUM(page_view) AS total_predate from statistics where list_id=' . $room_id . ' AND date= 31 AND  month="January" AND year=' . $currentyear . '');
                                                $value = $precount->result();

                                                foreach ($value as $row3)
                                                {
                                                    if (empty($row3->date))
                                                    {
                                                        $predate3 = 0;
                                                    }
                                                    else
                                                    {
                                                        $predate3 = $row3->total_predate;
                                                    }
                                                }
                                                echo $predate3;
                                                ?>

                                            </span></label>

                                        <?php
                                    }

                                    //

                                    for ($i = 1; $i <= $days_in_month; $i++)
                                    {

                                        $months_date = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');


                                        if ($currentmonth == 01)
                                        {

                                            $title_pre_date_full = $months[date('n', mktime(0, 0, 0, 0, 0, $preyear))];

                                            $title_pre_date = substr($title_pre_date_full, 0, 3);
                                            $date = $i;
                                            $month = "12";
                                            $year = $preyear;
                                        }
                                        else
                                        {

                                            $title_pre_date_full = $months_date[date('n', mktime(0, 0, 0, $premonth, $i, $currentyear)) - 1];
                                            $title_pre_date = substr($title_pre_date_full, 0, 3);
                                            //if($i<10){
                                            //$date = "0".$i;
                                            //$month = $premonth ;
                                            //$year = $currentyear;
                                            //}else{
                                            $date = $i;
                                            $month = $premonth;
                                            $year = $currentyear;

                                            //}	
                                        }
                                        ?>
                                        <label> <?php echo $title_pre_date; ?> <?php echo $i; ?><span style="background-color: #5CBBF9;display:none;"><?php
                                                $query_predate = $this->db->query('select date, SUM(page_view) AS total_predate from statistics where list_id=' . $room_id . ' AND date=' . $date . ' AND  month=' . "'$title_pre_date_full'" . ' AND year=' . $year . '');
                                                $inc_pre = $query_predate->result();

                                                foreach ($inc_pre as $row)
                                                {
                                                    if (empty($row->date))
                                                    {
                                                        $inc_predate = 0;
                                                    }
                                                    else
                                                    {
                                                        $inc_predate = $row->total_predate;
                                                    }
                                                }
                                                echo $inc_predate;
                                                ?></span></label>

                                        <?php
                                    }


                                    ///
                                }
                                else
                                {


                                    ///next 

                                    for ($i = ($start + 1); $i <= $days_in_month; $i++)
                                    {

                                        $months_date = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');


                                        if ($currentmonth == 01)
                                        {

                                            $title_pre_date_full = $months_date[date('n', mktime(0, 0, 0, 0, 0, $preyear))];

                                            $title_pre_date = substr($title_pre_date_full, 0, 3);
                                            //if($i<10)</tr>
                                            //{
                                            //$date = "0".$i;
                                            //$month = "12" ;
                                            //$year = $preyear;
                                            // }else{
                                            $date = $i;
                                            $month = "12";
                                            $year = $preyear;

                                            //}
                                        }
                                        else
                                        {

                                            $title_pre_date_full = $months_date[date('n', mktime(0, 0, 0, $premonth, $i, $currentyear)) - 1];
                                            $title_pre_date = substr($title_pre_date_full, 0, 3);
                                            //if($i<10){
                                            //$date = "0".$i;
                                            //$month = $premonth ;
                                            //$year = $currentyear;
                                            //}else{
                                            $date = $i;
                                            $month = $premonth;
                                            $year = $currentyear;

                                            //}	
                                        }
                                        ?>
                                        <label> <?php echo $title_pre_date; ?> <?php echo $i; ?><span style="background-color: #5CBBF9;display:none;"><?php
                                                $query_predate = $this->db->query('select date, SUM(page_view) AS total_predate from statistics where list_id=' . $room_id . ' AND date=' . $date . ' AND  month=' . "'$title_pre_date_full'" . ' AND year=' . $year . '');
                                                $inc_pre = $query_predate->result();

                                                foreach ($inc_pre as $row)
                                                {
                                                    if (empty($row->date))
                                                    {
                                                        $inc_predate = 0;
                                                    }
                                                    else
                                                    {
                                                        $inc_predate = $row->total_predate;
                                                    }
                                                }
                                                echo $inc_predate;
                                                ?></span></label>

                                        <?php
                                    }
                                }
                            }

//
                            for ($j = 1; $j <= $currentdate; $j++)
                            {


                                $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                                $title_cur_full = $months[date('n', mktime(0, 0, 0, $currentmonth, $j, $currentyear)) - 1];
                                $title_cur = substr($title_cur_full, 0, 3);
                                //if($j<10){
                                //$datecount =  "0".$j;
                                //$monthcount =  $currentmonth;
                                //$yearcount =  $currentyear;
                                //}else{
                                $datecount = $j;
                                $monthcount = $currentmonth;
                                $yearcount = $currentyear;

                                //}
                                $query_date = $this->db->query('select date, SUM(page_view) AS total_date from statistics where list_id=' . $room_id . ' AND  date=' . $datecount . ' AND month=' . "'$title_cur_full'" . ' AND year=' . $yearcount . '');
                                $inc_res = $query_date->result();

                                foreach ($inc_res as $row)
                                {
                                    if (empty($row->date))
                                    {
                                        $inc_date = 0;
                                    }
                                    else
                                    {
                                        $inc_date = $row->total_date;
                                    }
                                }
                                ?>

                                <label> <?php echo $title_cur; ?> <?php echo $j; ?><span style="background-color: #5CBBF9;display:none;"><?php echo $inc_date; ?></span></label>

        <?php }
        ?>



                        </td>
    <?php } ?>
            </tr>
        </table>








        <table id="testgraph" class="graphtable">
            <div class="graph_Head">
                <h3 style="padding: 30px 0px 0px;"><?php echo translate("Months Travellers Visits (Last 12 Months)"); ?> </h3></div>
            <tr>
            <span style="margin-left: 39px;"> 
<?php
    if (empty($results))
    {
        echo translate("No Statistics Found");
    }
    else
    {
        ?>
                    </span>
                    <td>
                        <div class="gCont">
                            <div class="graph">                    
                            </div>   
                        </div>
                    </td>
                    </tr>
                    <tr style="position: absolute; left: 20px;">
                        <td class="values month">
        <?php
        $currentmonth = date('m', time());
        $currentyear = date('Y', time());
        $currentday = date('d', time());
        $preyear = $currentyear - 1;
        $count = 12 - $currentmonth;
        $v = $currentmonth + 1;
        if ($count != 0)
        {


            for ($i = $v; $i <= 12; $i++)
            {
                $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                $title_pre_full = $months[date('n', mktime(0, 0, 0, $i, 0, $preyear))];
                $title_pre = substr($title_pre_full, 0, 3);
                ?>
                                    <label> <?php echo $title_pre; ?><span style="background-color: #5CBBF9;display:none;"><?php
                                    $query = $this->db->query('select list_id,month,year,SUM(page_view) AS total_month  from statistics where list_id=' . $room_id . ' AND month=' . "'$title_pre_full'" . ' AND year=' . $preyear . '');
                                    $inc = $query->result();
                                    foreach ($inc as $row)
                                    {
                                        if (empty($row->month))
                                        {
                                            $inc_pre = 0;
                                        }
                                        else
                                        {
                                            $inc_pre = $row->total_month;
                                        }
                                    }





                                    echo $inc_pre;
                                    ?></span></label>

                                            <?php
                                        }
                                    }
                                    for ($j = 01; $j <= $currentmonth; $j++)
                                    {
                                        $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                                        $title_cur_full = $months[date('n', mktime(0, 0, 0, $j, $currentday, $currentyear)) - 1];
                                        $title_cur = substr($title_cur_full, 0, 3);
                                        $query_month = $this->db->query('select month,year,SUM(page_view) AS total  from statistics where list_id=' . $room_id . ' AND month=' . "'$title_cur_full'" . ' AND year=' . $currentyear . '');
                                        $inc_value = $query_month->result();


                                        foreach ($inc_value as $row1)
                                        {
                                            if (empty($row1->month))
                                            {
                                                $inc_month = 0;
                                            }
                                            else
                                            {
                                                $inc_month = $row1->total;
                                            }
                                        }
                                        ?>

                                <label> <?php echo $title_cur; ?><span style="background-color: #5CBBF9; display:none;"><?php echo $inc_month; ?></span></label>

        <?php }
        ?>
                        </td>
                        <?php } ?>
            </tr>
        </table>


        <div style="clear:both"></div>
        <table id="yeargraph" class="graphtable">
            <div class="graph_Head">
                <h3 style="padding: 15px 0px 0px;"><?php echo translate("Years Travellers Visits (Last Years)"); ?> </h3></div>

            <tr>
            <span style="margin-left: 39px;"> 
<?php
    if (empty($results))
    {
        echo translate("No Statistics Found");
    }
    else
    {
        ?>
                    </span>
                    <td>
                        <div class="gCont">
                            <div class="graph">                    
                            </div>   
                        </div>
                    </td>
                    </tr>
                    <tr style="position: absolute; left: 18px;">
                        <td class="values year value_width">

        <?php
        $today_year = date('Y');
        $past_year = date('Y') - 12;
        $c = array();
        $year = array();
        foreach ($results as $row)
        {

            $res[] = $row->year;

            //$year = array_unique($res);
        }
        $year[0] = $past_year;
        for ($i = 1; $i <= 12; $i++)
        {
            $year[$i] = $past_year + $i;
        }

        foreach ($year as $j)
        {
            if ($j <= $today_year && $j >= $past_year)
            {
                ?>
                                    <label> <?php echo $j; ?><span style="background-color: #5CBBF9; display: none;">

                                    <?php
                                    $today_year = date('Y');
                                    $query_year = $this->db->query('select year,SUM(page_view) AS total_year  from statistics where list_id=' . $room_id . ' AND  year=' . $j . '');
                                    $fetch = $query_year->result();

                                    foreach ($fetch as $row)
                                    {

                                        if (empty($row->year))
                                        {
                                            $inc_year = 0;
                                        }
                                        else
                                        {
                                            $inc_year = $row->total_year;
                                        }
                                    }
                                    echo $inc_year;
                                    ?>


                                        </span></label>

                <?php
            }
        }
        ?>

                        </td>
                        <?php } ?>
            </tr>
        </table>
        <div style="clear:both"></div>

    </div>
</div>


