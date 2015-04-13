<?php
    //Get the night price
    $condition = array('id' => $list_id);
    $list_price = $this->Common_model->getTableData('price', $condition)->row()->night;

    $first_day = mktime(0, 0, 0, $month, 1, $year);
    $days_in_month = cal_days_in_month(0, $month, $year);
    $day_of_week = date('N', $first_day);

    $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $title = $months[date('n', mktime(0, 0, 0, $month, $day, $year)) - 1];

    if ($day_of_week == 7)
    {
        $blank = 0;
    }
    else
    {
        $blank = $day_of_week;
    }

    if (($month - 1) == 0)
    {
        $prevmonth = 1;
        $prevyear = ($year - 1);
    }
    else
    {
        $prevmonth = ($month - 1);
        $prevyear = $year;
    }
    $day_prevmonth = cal_days_in_month(0, $prevmonth, $prevyear) - ($blank - 1);

    if ($month == 01)
    {
        $prev_month = 12;
        $prev_year = $year - 1;
    }
    else
    {
        $prev_month = $month - 1;
        $prev_year = $year;
    }

    if ($month == 12)
    {
        $next_month = 01;
        $next_year = $year + 1;
    }
    else
    {
        $next_month = $month + 1;
        $next_year = $year;
    }

    $day_num = 1;
    $day_count = 1;
    $datenow = time();
    $monthnow = date('n', $datenow);
    $yearnow = date('Y', $datenow);
    $daynow = date('j', $datenow);

    $firstDay = $prev_year . '-' . $prev_month . '-' . $day_prevmonth;
?>

<table cellspacing="0" id="calendar_table">
    <tbody>
        <tr>
            <th>Sun</th>
            <th>Mon</th>
            <th>Tue</th>
            <th>Wed</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Sat</th>
        </tr>


        <?php
            $k = 1;
            $i = 0;
            $j = 37;
            while ($blank > 0)
            {
                if ($k == 1)
                    echo '<tr>';
                //seasonal rate previous month
                $date = $prev_month . '/' . $day_prevmonth . '/' . $prev_year;
                $price = getDailyPrice($list_id, get_gmt_time(strtotime($date)), $list_price);
                ?>

                <?php if (strtotime($prev_year . '-' . $prev_month . '-' . $day_prevmonth) < time())
                {
                    ?>

                <td id="day_<?php echo $i; ?>" class="weekend in_the_past">
                    <span class="dom"><?php echo $day_prevmonth; ?></span>
                    <br><br><span class="endcap"><b><?php echo get_currency_symbol($list_id) . " " . get_currency_value1($list_id, $price); ?></b></span>
                </td>		

                <?php
            }
            else
            {

                $full_date = $prev_month . '/' . $day_prevmonth . '/' . $prev_year;

                $pre_schedules = '<td id="day_' . $i . '" class="realday available">
																									<span class="dom">' . $day_prevmonth . '</span>
																									<br><br><span class="endcap"><b>' . get_currency_symbol($list_id) . '&nbsp' . get_currency_value1($list_id, $price) . '</b></span>
																				    	</td>';

                foreach ($result as $row)
                {
                    if (get_gmt_time(strtotime($full_date)) == $row->booked_days)
                    {
                        $pre_schedules = '<td id="day_' . $i . '" class="realday unavailable">
																													<span class="dom">' . $day_prevmonth . '</span><br><br><span class="endcap"><b>' . get_currency_symbol($list_id) . '&nbsp' . get_currency_value1($list_id, $price) . '</b></span>
																				        	</td>';
                    }
                }
                echo $pre_schedules;
            }
            $blank = $blank - 1;
            $day_count++;
            $day_prevmonth++;
            $i++;
            $j++;
            if ($k == 7)
            {
                $k = 0;
                echo '</tr>';
            } $k++;
        }
    ?>


    <?php
        while ($day_num <= $days_in_month)
        {
            if ($k == 1)
                echo '<tr>';
            //seasonal rate Present month
            $date = $month . '/' . $day_num . '/' . $year;
            $price = getDailyPrice($list_id, get_gmt_time(strtotime($date)), $list_price);
            ?>

            <?php
            if (strtotime($year . '-' . $month . '-' . $day_num) < time())
            {
                if ($i < date('d', time()) || $day_num != date('d', time()))
                {
                    ?>

                    <td id="day_<?php echo $i; ?>" class="weekend in_the_past">
                        <span class="dom"><?php echo $day_num; ?></span>
                        <br><br><span class="endcap"><b><?php echo get_currency_symbol($list_id) . " " . get_currency_value1($list_id, $price); ?></b></span>
                    </td>		

                    <?php
                }
                else
                {

                    $full_date = $month . '/' . $day_num . '/' . $year;

                    $pre_schedules = '<td id="day_' . $i . '" class="realday available">
																									<span class="dom">' . $day_num . '</span><br><br><span class="endcap"><b>' . get_currency_symbol($list_id) . '&nbsp' . get_currency_value1($list_id, $price) . '</b></span>
																				    	</td>';

                    foreach ($result as $row)
                    {
                        if (get_gmt_time(strtotime($full_date)) == $row->booked_days)
                        {
                            $pre_schedules = '<td id="day_' . $i . '" class="realday unavailable">
																													<span class="dom">' . $day_num . '</span><br><br><span class="endcap"><b>' . get_currency_symbol($list_id) . '&nbsp' . get_currency_value1($list_id, $price) . '</b></span>

																				        	</td>';
                        }
                    }
                    echo $pre_schedules;
                }
            }
            else
            {

                $full_date = $month . '/' . $day_num . '/' . $year;

                $pre_schedules = '<td id="day_' . $i . '" class="realday available">
																									<span class="dom">' . $day_num . '</span><br><br><span class="endcap"><b>' . get_currency_symbol($list_id) . '&nbsp' . get_currency_value1($list_id, $price) . '</b></span>
																				    	</td>';

                foreach ($result as $row)
                {
                    if (get_gmt_time(strtotime($full_date)) == $row->booked_days)
                    {
                        $pre_schedules = '<td id="day_' . $i . '" class="realday unavailable">
																													<span class="dom">' . $day_num . '</span><br><br><span class="endcap"><b>' . get_currency_symbol($list_id) . '&nbsp' . get_currency_value1($list_id, $price) . '</b></span>
																				        	</td>';
                    }
                }
                echo $pre_schedules;
            }
            $day_num++;
            $day_count++;
            if ($day_count > 7)
            {
                $day_count = 1;
            } $i++;
            $j++;
            if ($k == 7)
            {
                $k = 0;
                echo '</tr>';
            } $k++;
        }
    ?>


    <?php
        $day_nextmonth = 1;
        while ($day_count > 1 && $day_count <= 7)
        {
            if ($k == 1)
                echo '<tr>';
            //seasonal rate Next month
            $date = $next_month . '/' . $day_nextmonth . '/' . $next_year;
            $price = getDailyPrice($list_id, get_gmt_time(strtotime($date)), $list_price);
            ?>

            <?php if (strtotime($next_year . '-' . $next_month . '-' . $day_nextmonth) < time())
            {
                ?>

                <td id="day_<?php echo $i; ?>" class="weekend in_the_past">
                    <span class="dom"><?php echo $day_nextmonth; ?></span>
                    <br><br><span class="endcap"><b><?php echo get_currency_symbol($list_id) . " " . get_currency_value1($list_id, $price); ?></b></span>
                </td>		

                <?php
            }
            else
            {

                $full_date = $next_month . '/' . $day_nextmonth . '/' . $next_year;

                $pre_schedules = '<td id="day_' . $i . '" class="realday available">
																									<span class="dom">' . $day_nextmonth . '</span><br><br><span class="endcap"><b>' . get_currency_symbol($list_id) . '&nbsp' . get_currency_value1($list_id, $price) . '</b></span>
																				    	</td>';

                foreach ($result as $row)
                {
                    if (get_gmt_time(strtotime($full_date)) == $row->booked_days)
                    {
                        $pre_schedules = '<td id="day_' . $i . '" class="realday unavailable">
																													<span class="dom">' . $day_nextmonth . '</span><br><br><span class="endcap"><b>' . get_currency_symbol($list_id) . '&nbsp' . get_currency_value1($list_id, $price) . '</b></span>
																				        	</td>';
                    }
                }
                echo $pre_schedules;
            }
            $day_count++;
            $day_nextmonth++;
            $i++;
            $j++;
            if ($k == 7)
            {
                $k = 0;
                echo '</tr>';
            } $k++;
        }
    ?>
</tbody></table>
