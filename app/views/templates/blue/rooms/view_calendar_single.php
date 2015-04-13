<!-- Required Stylesheets -->
<link href="<?php echo css_url(); ?>/edit_listing.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo css_url(); ?>/calendar_single.css" media="screen" rel="stylesheet" type="text/css" />

<script type="text/javascript">
    function showmydiv()
    {
        var availability = document.getElementById('lwlb_availability').value;
        if (availability == "Available")
        {
            document.getElementById('lwlb_price').style.display = "block";
            document.getElementById('lwlb_notes').style.display = "none";
        }
        else
        {
            document.getElementById('lwlb_price').style.display = "none";
            document.getElementById('lwlb_notes').style.display = "block";
        }
    }

</script>
<script type="text/javascript">
    jQuery.noConflict();
</script>
<script type="text/javascript" src="<?php echo js_url(); ?>/prototype.js"></script>
<script type="text/javascript" src="<?php echo js_url(); ?>/scriptaculous.js"></script>

<script type="text/javascript">

    function get_square(rowIndex, colIndex) {
        return schedules[rowIndex][colIndex];
    }
    function gridColToDataCol(gridCol) {
        return (gridCol);
    }

    function gridRowToDataRow(gridRow) {
        return (gridRow);
    }

    function update_calendar_data(visible_row, hosting_id, json) {

        schedules[0] = json[0];
        render_grid(0, 34);
    }

    function is_address_line(row) {
        return false;
    }

    function lwlb_hide_special() {
        lwlb_hide('lwlb_calendar2');
        range_remove_all();
    }

    function range_add(i) {

        if (select_range_stop == null)
            return;
        if (i < select_range_start)
            return;
        if (i < select_range_stop)
            range_remove(i + 1);

        for (var tmp = select_range_stop; tmp <= i; tmp++) {
            if (!is_selectable(0, tmp))
                return;

            rollover('tile_' + tmp, 'tile', 'tile_selected');
        }

        select_range_stop = i;
    }

    function range_remove(i) {
        if (select_range_stop == null)
            return;
        if (i <= select_range_start)
            return;
        if (select_range_click_count >= 2)
            return;
        if (i > select_range_stop)
            return;

        for (; select_range_stop >= i; select_range_stop--) {
            rollover('tile_' + select_range_stop, 'tile_selected', 'tile');
        }

        select_range_stop = i;
    }


    function range_remove_all() {
        for (var i = select_range_start; i <= select_range_stop; i++) {
            rollover('tile_' + i, 'tile_selected', 'tile');
        }

        select_range_click_count = 0;
        select_range_start = null;
        select_range_stop = null;
    }

    function click_down(i) {
        select_range_click_count++;

        if (select_range_click_count > 2) {
            range_remove_all();
            return;
        }

        if (select_range_stop != null)
            return; // abort on second click

        if (!is_selectable(0, i)) {
            var matches = getAtomicBounds(0, i);
            select_range_start = matches[0];
            select_range_stop = matches[1];
            show_calendar();
            return;
        }

        select_range_start = i;
        select_range_stop = i;
        rollover('tile_' + i, 'tile', 'tile_selected');
    }

    function click_up(i) {
        if (select_range_click_count >= 2) {
            show_calendar();
        }
    }

    function show_calendar() {

        prepareLightbox(<?php echo $list_id; ?>, "<?php echo $list_title; ?>", 0, select_range_start, select_range_stop);

    }

</script>
<!-- End of style sheets inclusion -->
<?php
    $id = $this->uri->segment(3);
    $query = $this->db->get_where('list', array('id' => $id));
    $q = $query->result();
    $query2 = $this->db->get_where('amnities', array('id' => $id));
    $r = $query2->result();
?>
<div class="container_bg" id="View_Edit_List">
    <div id="View_Edit_Heading">
        <div class="heading_content clearfix">
            <div class="heading_content">
                <div class="edit_listing_photo">
                    <?php $url = getListImage($this->uri->segment(3)); ?>
                    <img alt="Host_pic" height="65" src="<?php echo $url; ?>" /> </div>
                <div class="listing_info">
                    <h3><?php echo anchor('rooms/' . $this->uri->segment(3), $q[0]->title, array('id' => "listing_title_banner")) ?></h3>
                    <?php echo anchor('rooms/' . $this->uri->segment(3), translate('View Listing'), array('class' => "clsLink2_Bg")) ?> <span id="availability-error-message"></span> </div>
                <div class="edit_view_all_list"> <?php echo anchor('hosting', translate('View All Listing'), array('class' => 'btn large blue')); ?> </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clearfix" id="View_Edit_Content">
        <div class="View_Edit_Nav">
            <div class="nav-container">
                <?php $this->load->view(THEME_FOLDER . '/includes/editList_header.php'); ?>
            </div>
        </div>
        <div class="View_Edit_Main_Content">
            <div id="notification-area"></div>
            <div id="dashboard-content">
                <div id="dashboard_v2" class="edit_room">
                    <div class="row">
                        <div class="col three-fourths content">
                            <div id="notification-area"></div>
                            <div id="dashboard-content">
                                <?php
                                    $first_day = mktime(0, 0, 0, $month, 1, $year);
                                    $days_in_month = cal_days_in_month(0, $month, $year);
                                    $day_of_week = date('N', $first_day);

                                    $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                                    $title = $months[$month - 1];

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
                                ?>
                                <script type="text/javascript">

                                    var global_grid = null;

<?php
    $columnInfo = '';
    $schedules = '';
    $firstDay = $prev_year . '-' . $prev_month . '-' . $day_prevmonth;
    //Previous Months Days
    while ($blank > 0)
    {
        $dayJ = date('D', strtotime($prev_year . '-' . $prev_month . '-' . $day_prevmonth));
        $month_day = date('M d', strtotime($prev_year . '-' . $prev_month . '-' . $day_prevmonth));
        $full_date = $prev_month . '/' . $day_prevmonth . '/' . $prev_year;

        $pre_schedules = '{cl: "av", sty: "both", isa: 1},';
        foreach ($result as $row)
        {
            if (get_gmt_time(strtotime($full_date)) == $row->booked_days)
            {
                if ($row->value != '')
                {
                    $value = ', reservation_value: ' . $row->value;
                }
                else
                {
                    $value = '';
                }
                if ($row->availability == 'Not Available')
                {
                    $c1 = 'bs';
                    $st = 2;
                }
                else
                {
                    $c1 = 'tp';
                    $st = 4;
                }
                $pre_schedules = '{cl: "' . $c1 . '", sty: "' . $row->style . '", isa: 0, id: ' . $row->id . ', notes: "' . $row->notes . '", st: ' . $st . ', sst: "' . $row->booked_using . '", gid: ' . $row->group_id . $value . '},';
            }
        }
        $schedules .= $pre_schedules;

        $columnInfo .= '"' . $dayJ . '\u003Cbr /\u003E' . $month_day . '",';

        $blank = $blank - 1;
        $day_count++;
        $day_prevmonth++;
    }

    //Current Months Days
    while ($day_num <= $days_in_month)
    {
        $dayJ = date('D', strtotime($year . '-' . $month . '-' . $day_num));
        $month_day = date('M d', strtotime($year . '-' . $month . '-' . $day_num));
        $full_date = $month . '/' . $day_num . '/' . $year;

        $pre_schedules = '{cl: "av", sty: "both", isa: 1},';
        foreach ($result as $row)
        {
            if (get_gmt_time(strtotime($full_date)) == $row->booked_days)
            {
                if ($row->value != '')
                {
                    $value = ', reservation_value: ' . $row->value;
                }
                else
                {
                    $value = '';
                }
                if ($row->availability == 'Not Available')
                {
                    $c1 = 'bs';
                    $st = 2;
                }
                else
                {
                    $c1 = 'tp';
                    $st = 4;
                }
                $pre_schedules = '{cl: "' . $c1 . '", sty: "' . $row->style . '", isa: 0, id: ' . $row->id . ', notes: "' . $row->notes . '", st: ' . $st . ', sst: "' . $row->booked_using . '", gid: ' . $row->group_id . $value . '},';
            }
        }
        $schedules .= $pre_schedules;

        $columnInfo .= '"' . $dayJ . '\u003Cbr /\u003E' . $month_day . '",';

        $day_num++;
        $day_count++;

        if ($day_count > 7)
        {
            $day_count = 1;
        }
    }

    //Next Months Days
    $day_nextmonth = 1;

    while ($day_count > 1 && $day_count <= 7)
    {
        $dayJ = date('D', strtotime($next_year . '-' . $next_month . '-' . $day_nextmonth));
        $month_day = date('M d', strtotime($next_year . '-' . $next_month . '-' . $day_nextmonth));
        $full_date = $next_month . '/' . $day_nextmonth . '/' . $next_year;

        $pre_schedules = '{cl: "av", sty: "both", isa: 1},';
        foreach ($result as $row)
        {
            if (get_gmt_time(strtotime($full_date)) == $row->booked_days)
            {
                if ($row->value != '')
                {
                    $value = ', reservation_value: ' . $row->value;
                }
                else
                {
                    $value = '';
                }
                if ($row->availability == 'Not Available')
                {
                    $c1 = 'bs';
                    $st = 2;
                }
                else
                {
                    $c1 = 'tp';
                    $st = 4;
                }
                $pre_schedules = '{cl: "' . $c1 . '", sty: "' . $row->style . '", isa: 0, id: ' . $row->id . ', notes: "' . $row->notes . '", st: ' . $st . ', sst: "' . $row->booked_using . '", gid: ' . $row->group_id . $value . '},';
            }
        }
        $schedules .= $pre_schedules;

        $columnInfo .= '"' . $dayJ . '\u003Cbr /\u003E' . $month_day . '",';

        $day_count++;
        $day_nextmonth++;
    }

    $lastDay = $next_year . '-' . $next_month . '-' . ($day_nextmonth - 1);
?>

                                    var columnInfo = [<?php echo substr($columnInfo, 0, -1); ?>];

                                    var hostings = [{"price":<?php echo get_currency_value1($list_id, $list_price); ?>, "name": "<?php echo $list_title; ?>", "available": 1, "row": 0, "id":<?php echo $list_id; ?>, "currency_symbol": "<?php echo get_currency_symbol($list_id); ?>", "currency": "<?php echo get_currency_code(); ?>", "lc_name": "<?php echo strtolower($list_title); ?>"}];

                                    var schedules = [[<?php echo substr($schedules, 0, -1); ?>]];


                                    var reservationHash = new Hash({});

                                    var g_start_date = date_parse_datestamp('<?php echo $firstDay; ?>');
                                    var g_stop_date = date_parse_datestamp('<?php echo $lastDay; ?>');
                                    var g_today_index = 19;

                                    var g_enable_change_dates = true;


                                    /* Mouse tracking */
                                    var g_mouse_x = 0;
                                    var g_mouse_y = 0;

                                    jQuery(document).ready(function () {
                                        jQuery(document).mousemove(function (e) {
                                            g_mouse_x = e.pageX;
                                            g_mouse_y = e.pageY;
                                        });
                                    });

                                    function centerLightbox() {
                                        var boxWidth = jQuery('#lwlb_calendar2').width();
                                        var boxHeight = jQuery('#lwlb_calendar2').height();
                                        $('lwlb_calendar2').setStyle({position: "absolute", left: (g_mouse_x - (boxWidth / 2)) + "px", top: (g_mouse_y - boxHeight - 25) + "px"});
                                    }

                                    /***** SELECTION *****/

                                    /* A span is selectable if it contains no atomic groups */
                                    function is_selectable(gridRow, gridCol) {
                                        if (is_address_line(gridRow))
                                            return false;

                                        var schedule = get_square(gridRow, gridCol);
                                        if (schedule.gid || schedule.confirmation) {
                                            return false;
                                        } else {
                                            return true;
                                        }
                                    }

                                    /* If atomic span / grouping, what are the bounds of the span? */
                                    function getAtomicBounds(gridRow, gridCol) {
                                        var minCol = gridCol;
                                        var maxCol = gridCol;

                                        var grouping_uid = get_square(gridRow, gridCol).gid;
                                        while (get_square(gridRow, maxCol + 1) && (get_square(gridRow, maxCol + 1).gid == grouping_uid)) {
                                            maxCol += 1;
                                        }
                                        while (get_square(gridRow, minCol - 1) && (get_square(gridRow, minCol - 1).gid == grouping_uid)) {
                                            minCol -= 1;
                                        }
                                        return [minCol, maxCol];
                                    }

                                    function getSpanBounds(gridRow, gridCol) {
                                        var minCol = gridCol;
                                        var maxCol = gridCol;

                                        if (get_square(gridRow, gridCol).sty == 'single')
                                            return [minCol, maxCol];
                                        while (get_square(gridRow, maxCol + 1) && (get_square(gridRow, maxCol + 1).sty != 'right')) {
                                            maxCol += 1;
                                        }
                                        return [minCol, maxCol + 1];
                                    }


                                    function getSquareText(square, gridRow, gridCol) {
                                        var dataRow = gridRowToDataRow(gridRow);
                                        var dataCol = gridColToDataCol(gridCol);

                                        var hosting = hostings[dataRow];

                                        if (("left" == square.sty) || ("single" == square.sty)) {
                                            switch (square.st) {
                                                case 0:
                                                    if (square.daily_price) {
                                                        return hosting.currency_symbol + square.daily_price;
                                                    } else {
                                                        return hosting.currency_symbol + hosting.price;
                                                    }
                                                case 2:
                                                    if (square.notes) {
                                                        return square.notes
                                                    } else {
                                                        return 'Not available';
                                                    }
                                                case 5:
                                                    if (square.notes) {
                                                        return square.notes
                                                    } else {
                                                        return 'Not available';
                                                    }
                                                case 3:
                                                    if (square.notes) {
                                                        return square.sst + ": " + square.notes
                                                    } else {
                                                        return square.sst + ': Not available';
                                                    }
                                                case 4:
                                                    return "Booked" + (square.notes ? ": " + square.notes : '');
                                                case 1:
                                                    var r = reservationHash.get(square.confirmation);
                                                    if (!r)
                                                        return '(error)';
                                                    if (r.is_accepted) {
                                                        return r.base_price + " Cogzidel, " + r.guest_name + (square.notes ? ": " + square.notes : '');
                                                    } else {
                                                        return r.base_price + " Cogzidel, " + r.guest_name + " (PENDING)" + (square.notes ? ": " + square.notes : '');
                                                    }
                                                default:
                                                    return hosting.currency_symbol + hostings[dataRow].price;
                                            }
                                        } else if ((dataCol == 0) && square.isa) {
                                            if (square.daily_price) {
                                                return hosting.currency_symbol + square.daily_price;
                                            } else {
                                                return hosting.currency_symbol + hosting.price;
                                            }
                                        } else {
                                            return "&nbsp;";
                                        }
                                    }


                                    function before_submit() {
                                        var lwprice = document.getElementById('seasonal_price').value;
                                        if ($('lwlb_availability').value == 'Available')
                                        {
                                            if (lwprice.length >= 5)
                                            {
                                                alert('Give the below 5 digits price');
                                                return false;
                                            }
                                            if (lwprice < 10)
                                            {
                                                //window.name = lwprice;
                                                alert('Must give the above 10 price');
                                                return false;
                                            }
                                        }

                                        if ($('lwlb_availability').value != 'Available' && $('lwlb_notes').value == 'Notes...')
                                        {
                                            alert('Give some notes.');
                                        }
                                        //window.name = '';
                                        $('lightbox_submit').disabled = true;
                                        $('lightbox_spinner').show();
                                    }

                                    function after_submit() {
                                        $('lightbox_submit').disabled = false;
                                        $('lightbox_spinner').hide();
                                        lwlb_hide_special();
                                    }


                                    function prepareLightbox(hosting_id, hosting_name, gridRow, gridMinCol, gridMaxCol) {
                                        var firstSquare = get_square(gridRow, gridMinCol);
                                        var lastSquare = get_square(gridRow, gridMaxCol);
                                        var startDate = new Date(g_start_date.getTime()); //date_parse_datestamp(firstSquare.dt);
                                        var stopDate = new Date(g_start_date.getTime()); //date_parse_datestamp(lastSquare.dt);

                                        var dataRow = gridRowToDataRow(gridRow);
                                        var hosting = hostings[dataRow];

                                        $$("span.currency_symbol").each(function (x) {
                                            x.innerHTML = hosting.currency_symbol;
                                        });
                                        $('lwlb_currency').value = hosting.currency;

                                        startDate.setDate(startDate.getDate() + gridColToDataCol(gridMinCol));
                                        stopDate.setDate(stopDate.getDate() + gridColToDataCol(gridMaxCol));

                                        /* Applies to all lightbox styles */

                                        $('lightbox_submit').disabled = false;
                                        $('lightbox_spinner').hide();

                                        $('lwlb_hosting_id').value = hosting_id;
                                        $('lwlb_visible_row').value = gridRow;

                                        if (firstSquare.gid) {
                                            $('lwlb_grouping_uid').value = firstSquare.gid;
                                        } else {
                                            $('lwlb_grouping_uid').value = "";
                                        }

                                        $('lwlb_starting_date').value = $('lwlb_starting_date_original').value = date_print_usa_date(startDate);
                                        $('lwlb_stopping_date').value = $('lwlb_stopping_date_original').value = date_print_usa_date(stopDate);
                                        $('lwlb_data_start_date').value = date_print_usa_date(g_start_date);
                                        $('lwlb_confirmation').value = firstSquare.confirmation ? firstSquare.confirmation : '';

                                        if (firstSquare.notes) {
                                            if (firstSquare.notes)
                                                $('lwlb_notes').value = firstSquare.notes;
                                        } else {
                                            $('lwlb_notes').value = "Notes...";
                                        }

                                        $('lwlb_reservation_section').hide();
                                        $('lwlb_normal_section').hide();

                                        // Reservation Case
                                        if (firstSquare.st == 1) {
                                            g_enable_change_dates = false;
                                            var r = reservationHash.get(firstSquare.confirmation);

                                            $('lwlb_availability').value = "Booked";
                                            $('lwlb_booking_service_other').value = "Cogzidel";

                                            // Setup the date span
                                            /*	alert(r.start_date);*/
                                            $('lwlb_date_span_start').innerHTML = date_print_simplified(date_parse_datestamp(r.start_date));
                                            $('lwlb_date_span_stop').innerHTML = date_print_simplified(date_parse_datestamp(r.end_date));
                                            $('lwlb_date_single').hide();
                                            $('lwlb_date_span').show();

                                            $('lwlb_reservation_section').show();
                                            $('lwlb_reservation_guest_pic').src = r.guest_pic;
                                            $('lwlb_reservation_hosting_name').innerHTML = hosting_name;

                                            if (r.is_accepted) {
                                                $('lwlb_reservation_code').innerHTML = r.confirmation;
                                            } else {
                                                $('lwlb_reservation_code').innerHTML = "pending";
                                            }
                                            $('lwlb_reservation_guest_name').innerHTML = r.guest_name;
                                            $('lwlb_reservation_dates').innerHTML = date_print_simplified(date_parse_datestamp(r.start_date)) + " - " + date_print_simplified(date_parse_datestamp(r.end_date));
                                            $('lwlb_reservation_base_price').innerHTML = r.base_price;
                                            $('lwlb_reservation_guest_email').innerHTML = r.guest_email;
                                            $('lwlb_reservation_guest_email').href = "mailto:" + r.guest_email;
                                            $('lwlb_reservation_guest_phone').innerHTML = ("" == r.guest_phone) ? "(unknown)" : r.guest_phone;

                                            if (r.is_accepted) {
                                                $('lwlb_reservation_contact').show();
                                                $('lwlb_reservation_itinerary').show();
                                                $('lwlb_reservation_accept').hide();
                                            } else {
                                                $('lwlb_reservation_contact').hide();
                                                $('lwlb_reservation_itinerary').hide();
                                                $('lwlb_reservation_accept').show();
                                            }

                                        } else {
                                            g_enable_change_dates = true;
                                            //if (minColIndex<g_today_index) return false; // disable click behavior if non-reservation

                                            $('lwlb_hosting_name').innerHTML = hosting_name;

                                            // Setup the date span
                                            if (gridMinCol == gridMaxCol) {
                                                $('lwlb_date_single').innerHTML = date_print_simplified(startDate);
                                                $('lwlb_date_single').show();
                                                $('lwlb_date_span').hide();
                                            } else {
                                                /*alert(startDate);*/
                                                $('lwlb_date_span_start').innerHTML = date_print_simplified(startDate);
                                                $('lwlb_date_span_stop').innerHTML = date_print_simplified(stopDate);
                                                $('lwlb_date_single').hide();
                                                $('lwlb_date_span').show();
                                            }

                                            $('lwlb_normal_section').show();

                                            //$('price').value = ""; //schedules[i].daily_price;
                                            if ((0 == firstSquare.st) || !firstSquare.st) {
                                                $('lwlb_availability').value = 'Available';
                                            } else if ((2 == firstSquare.st) || (5 == firstSquare.st)) {
                                                $('lwlb_availability').value = 'Not Available';
                                            } else {
                                                $('lwlb_availability').value = 'Booked';
                                            }
                                        }

                                        lwlb_show('lwlb_calendar2', {no_scroll: true});

                                        centerLightbox(); // specific to multi-calendar vs. singular calendar
                                    }


                                    /**** DATE HELPERS ****/

                                    function get_month_abbrev(month) {
                                        return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][month];
                                    }

                                    function date_parse_datestamp(datestamp) {
                                        // yyyy-mm-dd
                                        var parts = datestamp.split("-");
                                        return new Date(parts[0], parts[1] - 1, parts[2]);
                                        //return new Date(get_month_abbrev(parts[1]-1)+" "+parts[2]+", "+parts[0]+" 00:00:00 "); // parts[0],parts[1]-1,parts[2]
                                    }
                                    function date_parse_usa_date(datestamp) {
                                        // mm/dd/yyyy
                                        var parts = datestamp.split("/");
                                        return new Date(parts[2], parts[0] - 1, parts[1]);
                                    }

                                    function date_print_usa_date(dt) {
                                        // 2010-03-11: KEEP IT IN THE LOCAL TIMEZONE; OTHERWISE DATE OFFSET PROBLEMS!
                                        return (dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear();
                                    }

                                    function date_print_simplified(dt) {
                                        //return get_month_abbrev(dt.getUTCMonth()) + " " + dt.getUTCDate();
                                        // 2010-03-11: KEEP IT IN THE LOCAL TIMEZONE; OTHERWISE DATE OFFSET PROBLEMS!
                                        return get_month_abbrev(dt.getMonth()) + " " + dt.getDate();
                                    }

                                </script>
                                <div id="lwlb_overlay" style="opacity:0.2;"></div>
                                <script>

                                    function lwlb_force_stop_date_after_start() {
                                        var startDate = date_parse_usa_date($('lwlb_starting_date').value);
                                        var stopDate = date_parse_usa_date($('lwlb_stopping_date').value);

                                        if (startDate >= stopDate) {
                                            var newStopDate = new Date(startDate.getTime() + Date.one_day);
                                            $('lwlb_stopping_date').value = date_print_usa_date(newStopDate);
                                            $('lwlb_date_span_stop').innerHTML = date_print_simplified(newStopDate);
                                        }
                                    }

                                    function lwlb_start_date_calendar_popup() {
                                        if (!g_enable_change_dates)
                                            return false;

                                        new CalendarDateSelect($('lwlb_starting_date'), {
                                            popup_by: $('lwlb_date_span_start'),
                                            buttons: false,
                                            default_date_offset: 0,
                                            year_range: [new Date().getFullYear(), new Date().getFullYear() + 1],
                                            valid_date_check: function (dt) {
                                                return(dt >= calendar_helper_simple_today());
                                            }
                                        });
                                    }

                                    function lwlb_stop_date_calendar_popup() {
                                        if (!g_enable_change_dates)
                                            return false;

                                        var startDate = date_parse_usa_date($('lwlb_starting_date').value);
                                        var default_offset = Math.ceil((startDate - new Date()) / Date.one_day) + 1;

                                        new CalendarDateSelect($('lwlb_stopping_date'), {
                                            popup_by: $('lwlb_date_span_stop'),
                                            buttons: false,
                                            default_date_offset: default_offset,
                                            year_range: [new Date().getFullYear(), new Date().getFullYear() + 1],
                                            valid_date_check: function (dt) {
                                                return(dt > startDate);
                                            }
                                        });
                                    }

                                    function lwlb_booking_service_changed() {
                                        if ($('lwlb_booking_service').value == "Other") {
                                            $('lwlb_booking_service_other').show();
                                        } else {
                                            $('lwlb_booking_service_other').hide();
                                        }

                                    }

                                    function lwlb_availability_changed() {
                                        switch ($('lwlb_availability').value) {
                                            case 'Available':
                                                $('lwlb_row_per_night').show();
                                                $('lwlb_row_service').hide();
                                                $('lwlb_row_value').hide();
                                                $('lwlb_notes').hide();
                                                break;
                                            case 'Not Available':
                                                $('lwlb_row_per_night').hide();
                                                $('lwlb_row_service').hide();
                                                $('lwlb_row_value').hide();
                                                $('lwlb_notes').show();
                                                break;
                                            case 'Booked':
                                                $('lwlb_row_per_night').hide();
                                                $('lwlb_row_service').show();
                                                $('lwlb_row_value').show();
                                                $('lwlb_notes').show();
                                                break;
                                        }
                                    }
                                    function lwlbOpenProfile() {
                                        var confirmation = $('lwlb_confirmation').value;
                                        var record = reservationHash.get(confirmation);
                                        window.open('<?php echo base_url(); ?>users/show/' + record.guest_id);
                                    }

                                    function lwlbOpenMessage() {
                                        var confirmation = $('lwlb_confirmation').value;
                                        var record = reservationHash.get(confirmation);
                                        window.open('<?php echo base_url(); ?>messaging/qt_with/' + record.guest_id);
                                    }

                                    function lwlbOpenItinerary() {
                                        var confirmation = $('lwlb_confirmation').value;
                                        var record = reservationHash.get(confirmation);
                                        window.open('<?php echo base_url(); ?>reservation/print_confirmation/?code=' + confirmation);
                                    }

                                    function lwlbOpenAcceptDecline() {
                                        var confirmation = $('lwlb_confirmation').value;
                                        var record = reservationHash.get(confirmation);
                                        window.open('<?php echo base_url(); ?>reservation/approve?code=' + confirmation);
                                    }
                                </script>
                                <div id="lwlb_calendar2" class="lwlb_lightbox_calendar">
                                    <div class="container">
                                        <div class="inner">
                                            <form action="<?php echo site_url('calendar/modify_calendar') . '?month=' . $month . '&year=' . $year; ?>" method="post" onSubmit="before_submit();
                                                    ;
                                                    new Ajax.Request('<?php echo site_url('calendar/modify_calendar') . '?month=' . $month . '&year=' . $year; ?>', {asynchronous: true, evalScripts: true, parameters: Form.serialize(this), onSuccess: function (transport) {
                                                            location.reload(true);
                                                        }});
                                                    return false;">
                                                <input type="hidden" name="data_start_date" id="lwlb_data_start_date" value="" />
                                                <input type="hidden" name="confirmation" id="lwlb_confirmation" value="" />
                                                <input type="hidden" name="starting_date_original" id="lwlb_starting_date_original" value="" />
                                                <input type="hidden" name="stopping_date_original" id="lwlb_stopping_date_original" value="" />
                                                <input type="hidden" name="grouping_uid" id="lwlb_grouping_uid" value="" />
                                                <input type="hidden" name="currency" id="lwlb_currency" value="" />
                                                <input type="hidden" name="starting_date" id="lwlb_starting_date" value="" onChange="$('lwlb_date_span_start').innerHTML = date_print_simplified(date_parse_usa_date(this.value));
                                                        lwlb_force_stop_date_after_start();" />
                                                <input type="hidden" name="stopping_date" id="lwlb_stopping_date" value="" onChange="$('lwlb_date_span_stop').innerHTML = date_print_simplified(date_parse_usa_date(this.value));
                                                        lwlb_force_stop_date_after_start();" />
                                                <input type="hidden" name="hosting_id" id="lwlb_hosting_id" value="">
                                                <input type="hidden" name="visible_row" id="lwlb_visible_row" value="">
                                                <div id="lwlb_reservation_section" style="margin-bottom:10px;">
                                                    <div class="header bottom_line">
                                                        <div class="header_text" style="float:left;"> <span id="lwlb_reservation_guest_name">guest name</span> (<span id="lwlb_reservation_code">code</span>) </div>
                                                        <div class="close"><a href="#" onClick="lwlb_hide_special();
                                                                return false;"><img src="<?php echo css_url(); ?>/images/sin_cal_close.gif" /></a></div>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="bottom_line">
                                                        <div style="float:left;width:60px;"> <img id="lwlb_reservation_guest_pic" src="" style="width:50px;height:50px;" /> </div>
                                                        <div style="float:left;width:200px;">
                                                            <div><span class="label">Dates:</span> <span id="lwlb_reservation_dates">dates</span></div>
                                                            <div><span class="label">Place:</span> <span id="lwlb_reservation_hosting_name">dates</span></div>
                                                            <div><span class="label">Price:</span> <span id="lwlb_reservation_base_price">price</span></div>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div style=""> <span id="lwlb_reservation_contact"><a id="lwlb_reservation_contact_details_show_link" href="#" onClick="Element.hide('lwlb_reservation_contact_details_show_link');
                                                            Element.show('lwlb_reservation_contact_details');
                                                            Element.show('lwlb_reservation_contact_details_hide_link');
                                                            return(false);" >Contact</a><a id="lwlb_reservation_contact_details_hide_link" href="#" onClick="Element.hide('lwlb_reservation_contact_details_hide_link');
                                                                    Element.show('lwlb_reservation_contact_details_show_link');
                                                                    Element.hide('lwlb_reservation_contact_details');
                                                                    return(false);" style="display:none;">Hide Contact</a> |</span> <a href="#" onClick="lwlbOpenProfile();
                                                                            return false;">Profile</a> | <a href="#" onClick="lwlbOpenMessage();
                                                                                    return false;">Message History</a> <span id="lwlb_reservation_itinerary">| <a href="#" onClick="lwlbOpenItinerary();
                                                                                            return false;">Itinerary</a></span> <span id="lwlb_reservation_accept">| <a href="#" onClick="lwlbOpenAcceptDecline();
                                                                                                    return false;">Accept/Decline</a></span> </div>
                                                    <div id="lwlb_reservation_contact_details" style="display:none;border-top: 1px solid #CBCACA; padding-top:5px; margin-top:5px;"> <span class="label">Email:</span> <a id="lwlb_reservation_guest_email" href="#">guest email</a> <span class="label">Phone:</span> <span id="lwlb_reservation_guest_phone">guest phone</span> </div>
                                                </div>
                                                <div id="lwlb_normal_section">
                                                    <div class="header bottom_line">
                                                        <div id="lwlb_hosting_name" class="header_text" style="float:left; width:280px;">hosting name</div>
                                                        <div class="close"><a href="#" onClick="lwlb_hide_special();
                                                                return false;"><img src="<?php echo css_url(); ?>/images/sin_cal_close.gif" /></a></div>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div id="lwlb_date_single">single date</div>
                                                    <div id="lwlb_date_span"> <span id="lwlb_date_span_start" class="calendar_link" onClick="lwlb_start_date_calendar_popup();">checkin</span> - <span id="lwlb_date_span_stop" class="calendar_link" onClick="lwlb_stop_date_calendar_popup();">checkout</span> </div>

                                                    <div style="padding-bottom:5px;padding-top:5px;">
                                                        <table>
                                                            <tr>
                                                                <td style="width:80px;">Availability</td>
                                                                <td><select id="lwlb_availability" name="availability" value="" onChange="showmydiv()" >
                                                                        <option value="Available">Available</option>
                                                                        <option value="Booked">Booked</option>
                                                                        <option value="Not Available">Not Available</option>
                                                                    </select></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div id="lwlb_price">
                                                    <p><label style="padding-right: 57px;">Price</label><input type="text" name="seasonal_price" id="seasonal_price" value=""></p>
                                                </div>
                                                <textarea id="lwlb_notes" name="notes" style="width:100%;height:30px;display:none;" onClick="if (this.value == 'Notes...') {
                                                            this.value = '';
                                                        }
                                                        ;">Notes...</textarea>
                                                <div style="margin-top:5px;text-align:left;">

                                                    <button id="lightbox_submit" type="submit" class="btn blue gotomsg" name="commit"><span><span><?php echo translate("Submit"); ?></span></span></button>

                                                    <img src="<?php echo base_url(); ?>images/spinner.gif" id="lightbox_spinner" style="display:none;"/> or <a href="#" onClick="lwlb_hide_special();
                                                            return false;">Cancel</a> </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="Box editlist_Box">
                                        <div class="Box_Head1">
                                            <h2 class="step"><span class="edit_room_icon calendar"></span>Calendar</h2></div>
                                        <div id="calendar2" class="Box_Content">
                                            <div id="backdrop">
                                                <div class="clear"></div>
                                                <div>
                                                    <script>
                                                        var select_range_start = null;
                                                        var select_range_stop = null;
                                                        var select_range_click_count = 0;
                                                    </script>
                                                    <div class="full_bubble">
                                                        <div class="inner">
                                                            <?php
                                                                $first_day = mktime(0, 0, 0, $month, 1, $year);
                                                                $days_in_month = cal_days_in_month(0, $month, $year);
                                                                $day_of_week = date('N', $first_day);

                                                                $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                                                                $title = $months[$month - 1];

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
                                                            ?>
                                                            <div>
                                                                <!-- Table -->
                                                                <div class="calendar-header">
                                                                    <div class="prev-month"> <a href="<?php echo site_url('calendar/single/' . $list_id . '?month=' . $prev_month . '&year=' . $prev_year); ?>"> <img alt="Previous" height="34" src="<?php echo base_url(); ?>images/bttn_month_prev.png" width="35" /> </a> </div>
                                                                    <div class="next-month"> <a href="<?php echo site_url('calendar/single/' . $list_id . '?month=' . $next_month . '&year=' . $next_year); ?>"> <img alt="Next" height="34" src="<?php echo base_url(); ?>images/bttn_month_next.png" width="35" /> </a> </div>
                                                                    <div class="display-month"><?php echo "$title $year"; ?></div>

                                                                    <div class="clear"></div>
                                                                </div>
                                                                <div>
                                                                    <div>
                                                                        <div class="day_header">Sun</div>
                                                                        <div class="day_header">Mon</div>
                                                                        <div class="day_header">Tue</div>
                                                                        <div class="day_header">Wed</div>
                                                                        <div class="day_header">Thu</div>
                                                                        <div class="day_header">Fri</div>
                                                                        <div class="day_header">Sat</div>
                                                                        <div class="clear"></div>
                                                                    </div>
                                                                    <?php
                                                                        $k = 1;
                                                                        $i = 0;
                                                                        $j = 48;
                                                                        while ($blank > 0)
                                                                        {
                                                                            if ($k == 1)
                                                                                echo '<div>';
                                                                            ?>
        <?php if (strtotime($prev_year . '-' . $prev_month . '-' . $day_prevmonth) < time())
        {
            ?>
                                                                                <div class="tile disabled" id="tile_<?php echo $i; ?>">
                                                                                    <div class="tile_container">
                                                                                        <div class="day"><?php echo $day_prevmonth; ?></div>
                                                                                        <div class="line_reg" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"></span> </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                            else
                                                                            {
                                                                                //seasonal rate
                                                                                $date = $prev_month . '/' . $day_prevmonth . '/' . $prev_year;
                                                                                $price = getDailyPrice($list_id, get_gmt_time(strtotime($date)), $list_price);
                                                                                ?>
                                                                                <div class="tile" id="tile_<?php echo $i; ?>" onMouseDown="click_down(<?php echo $i; ?>);" onMouseUp="click_up(<?php echo $i; ?>);" onMouseOver="range_add(<?php echo $i; ?>);" onMouseOut="range_remove(<?php echo $i; ?>);">
                                                                                    <div class="tile_container">
                                                                                        <div class="day"><?php echo $day_prevmonth; ?></div>
                                                                                        <div class="line_reg" style="z-index:<?php echo $j; ?>;" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"><b><?php echo get_currency_symbol($list_id) . get_currency_value1($list_id, $price); ?></b></span> </div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                            <?php
                                                                            $blank = $blank - 1;
                                                                            $day_count++;
                                                                            $day_prevmonth++;
                                                                            $i++;
                                                                            $j--;
                                                                            if ($k == 7)
                                                                            {
                                                                                $k = 0;
                                                                                echo '<div class="clear"></div></div>';
                                                                            } $k++;
                                                                        }
                                                                    ?>
                                                                    <?php
                                                                        while ($day_num <= $days_in_month)
                                                                        {
                                                                            if ($k == 1)
                                                                                echo '<div>';
                                                                            ?>
        <?php
        if (strtotime($year . '-' . $month . '-' . $day_num) < time())
        {
            if ($i < date('d', time()) || $day_num != date('d', time()))
            {
                ?>
                                                                                    <div class="tile disabled" id="tile_<?php echo $i; ?>">
                                                                                        <div class="tile_container">
                                                                                            <div class="day"><?php echo $day_num; ?></div>
                                                                                            <div class="line_reg" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"></span> </div>
                                                                                        </div>
                                                                                    </div>
                <?php
            }
            else
            {
                //seasonal rate
                $date = $month . '/' . $day_num . '/' . $year;
                $price = getDailyPrice($list_id, get_gmt_time(strtotime($date)), $list_price);
                ?>
                                                                                    <div class="tile" id="tile_<?php echo $i; ?>" onMouseDown="click_down(<?php echo $i; ?>);" onMouseUp="click_up(<?php echo $i; ?>);" onMouseOver="range_add(<?php echo $i; ?>);" onMouseOut="range_remove(<?php echo $i; ?>);">
                                                                                        <div class="tile_container">
                                                                                            <div class="day"><?php echo $day_num; ?></div>
                                                                                            <div class="line_reg" style="z-index:<?php echo $j; ?>;" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"><b><?php echo get_currency_symbol($list_id) . get_currency_value1($list_id, $price); ?></b></span> </div>
                                                                                        </div>
                                                                                    </div>
                <?php
            }
        }
        else
        {
            //seasonal rate
            $date = $month . '/' . $day_num . '/' . $year;
            $price = getDailyPrice($list_id, get_gmt_time(strtotime($date)), $list_price);
            ?>
                                                                                <div class="tile" id="tile_<?php echo $i; ?>" onMouseDown="click_down(<?php echo $i; ?>);" onMouseUp="click_up(<?php echo $i; ?>);" onMouseOver="range_add(<?php echo $i; ?>);" onMouseOut="range_remove(<?php echo $i; ?>);">
                                                                                    <div class="tile_container">
                                                                                        <div class="day"><?php echo $day_num; ?></div>
                                                                                        <div class="line_reg" style="z-index:<?php echo $j; ?>;" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"><b><?php echo get_currency_symbol($list_id) . get_currency_value1($list_id, $price); ?></b></span> </div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                            <?php
                                                                            $day_num++;
                                                                            $day_count++;
                                                                            if ($day_count > 7)
                                                                            {
                                                                                echo "</tr><tr>";
                                                                                $day_count = 1;
                                                                            } $i++;
                                                                            $j--;
                                                                            if ($k == 7)
                                                                            {
                                                                                $k = 0;
                                                                                echo '<div class="clear"></div></div>';
                                                                            } $k++;
                                                                        }
                                                                    ?>
                                                                    <?php
                                                                        $day_nextmonth = 1;
                                                                        while ($day_count > 1 && $day_count <= 7)
                                                                        {
                                                                            if ($k == 1)
                                                                                echo '<div>';
                                                                            ?>
                                                                            <?php if (strtotime($next_year . '-' . $next_month . '-' . $day_nextmonth) < time())
                                                                            {
                                                                                ?>
                                                                                <div class="tile disabled" id="tile_<?php echo $i; ?>">
                                                                                    <div class="tile_container">
                                                                                        <div class="day"><?php echo $day_nextmonth; ?></div>
                                                                                        <div class="line_reg" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"></span> </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                            else
                                                                            {
                                                                                //seasonal rate
                                                                                $date = $next_month . '/' . $day_nextmonth . '/' . $next_year;
                                                                                $price = getDailyPrice($list_id, get_gmt_time(strtotime($date)), $list_price);
                                                                                ?>
                                                                                <div class="tile" id="tile_<?php echo $i; ?>" onMouseDown="click_down(<?php echo $i; ?>);" onMouseUp="click_up(<?php echo $i; ?>);" onMouseOver="range_add(<?php echo $i; ?>);" onMouseOut="range_remove(<?php echo $i; ?>);">
                                                                                    <div class="tile_container">
                                                                                        <div class="day"><?php echo $day_nextmonth; ?></div>
                                                                                        <div class="line_reg" style="z-index:<?php echo $j; ?>;" id="square_<?php echo $i; ?>"> <span class="startcap"></span> <span class="content"></span> <span class="endcap"><b><?php echo get_currency_symbol($list_id) . get_currency_value1($list_id, $price); ?></b></span> </div>
                                                                                    </div>
                                                                                </div>
        <?php } ?>
        <?php
        $day_count++;
        $day_nextmonth++;
        $i++;
        $j--;
        if ($k == 7)
        {
            $k = 0;
            echo '<div class="clear"></div></div>';
        } $k++;
    }
?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <script>



                                                        function render_grid(start_idx, stop_idx) {
                                                            var prev_square;

                                                            // ignore the first and last week of data (since it is padding)
                                                            for (var i = start_idx; i <= stop_idx; i++) {
                                                                var square = schedules[0][i];
                                                                var e = jQuery('#square_' + i);

                                                                if (e.length == 0 || square === undefined)
                                                                    continue;

                                                                var text = getSquareText(square, 0);

                                                                // append to the square text if we detect that it's blocked out because the host denied a reservation
                                                                if (prev_square != null && ((prev_square.st != square.st && square.st == 2 && square.gid != null) ||
                                                                        (prev_square.st == square.st && square.st == 2 && prev_square.gid == null && square.gid != null)))
                                                                    text += " (denied booking)";

                                                                var span = getSpanBounds(0, i);

                                                                var width = span[1] - span[0] + 1;

                                                                if (text.length > (width * 8)) {
                                                                    e.attr('title', text);
                                                                    text = text.substr(0, (width * 16) - 4) + "...";
                                                                }

                                                                e.find('span.content').html(text);
                                                                var baseClass = e.attr('class').split(' ')[0];
                                                                e.attr('class', baseClass + " " + square.cl + " " + square.sty);

                                                                prev_square = square;

                                                            }
                                                        }
                                                        render_grid(0, <?php if ($i == 35)
        echo '34';
    else
        echo '41';
?>);
                                                    </script>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            <!-- backdrop -->
                                        </div>
                                        <!-- calendar2 -->


                                    </div>
                                </div>
                                <div>
                                    <div class="Box editlist_Box">
                                        <div class="Box_Head1">
                                            <h2 class="step">
                                                <span class="edit_room_icon calendar"></span>
                                                Synchronize Calendars
                                            </h2>

                                        </div>
                                        <div id="synccal" class="Box_Content">

                                            <h3>Import and synchronize all your calendar</h3>

                                                            <?php
//date_default_timezone_set('Asia/Kolkata');
                                                                if (isset($_POST["icals"]))
                                                                {
                                                                    $icals = $_POST["icals"];
                                                                }
                                                            ?>

                                            <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
                                            <html>
                                                <head>
                                                    <title>Calender</title>
                                                    <link rel="stylesheet" type="text/css" href="codebase/style.css" />
                                                </head>
                                                <body>
                                                <center>
                                                    <div id="install_main">
                                                        <div id="install_content">
                                                            <?php
                                                                $problems = Array();


                                                                if (isset($_POST["next"]))
                                                                {

                                                                    if ((isset($_FILES["ical_file"])) && (file_exists($_FILES["ical_file"]["tmp_name"])))
                                                                    {
                                                                        $ical_content = file_get_contents($_FILES["ical_file"]["tmp_name"]);
                                                                    }
                                                                    else
                                                                    {
                                                                        if ((isset($_POST["ical_url"])) && ($_POST["ical_url"] != ''))
                                                                        {
                                                                            $ical_url = $_POST["ical_url"];

                                                                            if (@file_get_contents($_POST["ical_url"]) == true)
                                                                            {
                                                                                $ical_content = $_POST["ical_url"];
                                                                            }
                                                                            else
                                                                            {
                                                                                $problems[] = "Ical resource specified by url is not available.";
                                                                            }
                                                                        }
                                                                        else
                                                                        {
                                                                            $problems[] = "Resource file should be specified.";
                                                                        }
                                                                    }
                                                                    if (isset($_POST["ical_url"]))
                                                                    {
                                                                        $ical_url = $_POST["ical_url"];
                                                                    }
                                                                    if (isset($_POST["icals"]))
                                                                    {
                                                                        $icals = $_POST["icals"];
                                                                    }


                                                                    $db_name = $this->config->item('db');
                                                                    $db_table = "calendar";



                                                                    if (count($problems) == 0)
                                                                    {

                                                                        $data = array(
                                                                            'id' => NULL,
                                                                            'list_id' => $this->uri->segment(3),
                                                                            'url' => $this->input->post('ical_url'),
                                                                            'last_sync' => date("d M Y, g:i a")
                                                                        );
                                                                        $this->Common_model->insertData('ical_import', $data);


                                                                        $log = Array();

                                                                        $query1 = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '{$db_name}' AND table_name = '{$db_table}' LIMIT 1");



                                                                        /* 	if($query1->num_rows()==1)
                                                                          {
                                                                          if ($db_delete_old_data) {

                                                                          $condition = array("list_id" => $this->uri->segment(3));
                                                                          $this->Common_model->deleteTableData('calendar',$condition);
                                                                          }
                                                                          }
                                                                         */
                                                                        /* ! exporting event from source into hash */
                                                                        require_once("codebase/class.php");
                                                                        $exporter = new ICalExporter();
                                                                        $events = $exporter->toHash($ical_content);

                                                                        $success_num = 0;
                                                                        $error_num = 0;
                                                                        /* ! inserting events in database */

                                                                        $check_tb = $this->db->select('group_id')->where('list_id', $this->uri->segment(3))->order_by('id', 'desc')->limit(1)->get('calendar');

                                                                        if ($check_tb->num_rows() != 0)
                                                                        {
                                                                            $i1 = $check_tb->row()->group_id;
                                                                        }
                                                                        else
                                                                        {
                                                                            $i1 = 1;
                                                                        }

                                                                        for ($i = $i1; $i <= count($events); $i++)
                                                                        {
                                                                            $event = $events[$i];


                                                                            $days = (strtotime($event["end_date"]) - strtotime($event["start_date"])) / (60 * 60 * 24);
                                                                            $created = $event["start_date"];

                                                                            for ($j = 1; $j <= $days; $j++)
                                                                            {
                                                                                if ($days == 1)
                                                                                {
                                                                                    $direct = 'single';
                                                                                }
                                                                                else if ($days > 1)
                                                                                {

                                                                                    if ($j == 1)
                                                                                    {
                                                                                        $direct = 'left';
                                                                                    }
                                                                                    else if ($days == $j)
                                                                                    {
                                                                                        $direct = 'right';
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        $direct = 'both';
                                                                                    }
                                                                                }


                                                                                $startdate1 = $event["start_date"];

                                                                                $id = $this->uri->segment(3);

                                                                                $check_dates = $this->db->where('list_id', $id)->where('booked_days', strtotime($startdate1))->get('calendar');

                                                                                if ($check_dates->num_rows() != 0)
                                                                                {
                                                                                    
                                                                                }
                                                                                else
                                                                                {

                                                                                    $data = array(
                                                                                        'id' => NULL,
                                                                                        'list_id' => $id,
                                                                                        'group_id' => $i,
                                                                                        'availability' => "Booked",
                                                                                        'value' => 0,
                                                                                        'currency' => "EUR",
                                                                                        'notes' => "Not Available", //   $event["text"]
                                                                                        'style' => $direct,
                                                                                        'booked_using' => 0,
                                                                                        'booked_days' => strtotime($startdate1),
                                                                                        'created' => strtotime($created)
                                                                                    );

                                                                                    $this->Common_model->insertData('calendar', $data);
                                                                                }

                                                                                $abc = $event["start_date"];
                                                                                $newdate = strtotime('+1 day', strtotime($abc));
                                                                                $event["start_date"] = date("m/d/Y", $newdate);
                                                                            }

                                                                            $success_num++;
                                                                        }//for loop end

                                                                        $log[] = Array("text" => "{$success_num} Booking were inserted successfully", "type" => "success");
                                                                    }
                                                                }

                                                                if (isset($log))
                                                                {

                                                                    if (isset($_POST["icals"]))
                                                                    {
                                                                        $icals = $_POST["icals"];
                                                                    }
                                                                    /* ! output export result */
                                                                    ?>
                                                                        <?php
                                                                        for ($i = 0; $i < count($log); $i++)
                                                                        {
                                                                            ?>
                                                                        <div class="log_row">
                                                                            <div class="log_msg <?php echo $log[$i]["type"]; ?>"><?php
                                                                            if ($success_num != 0)
                                                                            {
                                                                                echo $log[$i]["text"];
                                                                                ?>
                                                                                    <div class="log_row">
                                                                                        <div class="log_msg"><div class="num"></div>Import is completed. 
                                                                                <?php echo anchor('calendar/single/' . $this->uri->segment(3), translate('Return to show the booked calender')); ?>
                                                                                        </div>
                                                                            <?php }// else echo '<span style="color:red">There is no data in given URL</span>'; ?>

                                                                                </div>														 
                                                                            </div>
                                                                            <?php
                                                                        }

                                                                        $urls = '';
                                                                    }
                                                                    else
                                                                    {

                                                                        $ical_import = $this->db->query("SELECT * FROM `ical_import` WHERE `list_id` = '" . $this->uri->segment(3) . "' order by id ASC");

                                                                        $results = $ical_import->result_array();

                                                                        foreach ($results as $ical_url)
                                                                        {

                                                                            $urls = $ical_url['url'];
                                                                            $last_sync = $ical_url['last_sync'];
                                                                        }

                                                                        if ($ical_import->num_rows() == 0)
                                                                        {
                                                                            $urls = '';
                                                                            $last_sync = '';
                                                                        }
                                                                    }
                                                                    /* ! outputing configuration form */
                                                                ?>
                                                                <div id="content_step_1">
                                                                    <form action="" method="post" enctype="multipart/form-data">
                                                                        <tr><td></td></tr>					

                                                                        <tr  style="height:10px; " >
                                                                            <td style="float: left; margin-top: -43px; margin-left: 60px; line-height:1">

                                                                            <td class="first_td" valign="top" style="float: left; font-size:14px; font-weight: bold; margin:6px 0 0 25px; font-family:Arial; color:#808080;"></td>
                                                                            <td style="float: left; margin-top: -43px; margin-left: 60px; line-height:1">
                                                                                <p>URL:&nbsp;<input class="input" name="ical_url" onBlur="if (this.value == '')
                                                                                            this.value = this.defaultValue" onFocus="if (this.value == this.defaultValue)
                                                                                                        this.value = ''" value="" type="text" style="width:400px; border-radius:2px; -o-border-radius:2px; -webkit-border-radius:2px; -moz-border-radius:2px; text-shadow:none;">

                                                                                    <input class="continue-button" type="submit" name="next" value="Import" id="bluebutt"> 
                                                                                <p style="color:#993300">Paste valid calendar link here, e.g. <?php echo site_url() ?>calendar/ical/...</p>
                                                                                </p>
                                                                            </td>
                                                                        </tr>

                                                                        </table>
                                                                    </form>

                                                                </div>
                                                    <?php ?>
                                                            </div>
                                                        </div>
                                                </center>
                                                </body>
                                            </html>

                                            <p style="margin-left: 27px; width: 682px;">
                                            <div id="list_url" style="padding-bottom:15px; margin-left:26px; width: 682px;"><span style="float:left; font-weight: bold; font-size:14px; margin-top:0px; font-family:Arial; color:#808080;">Import URL:</span>
                                                <span style=" text-decoration:underline; font-family:Arial; font-size:14px; font-weight:600;">
                                                      <?php
                                                          if ($urls != '')
                                                          {

                                                              if (strlen($urls) > 41)
                                                              {
                                                                  echo substr($urls, 0, 41) . "...";
                                                              }
                                                              else
                                                              {
                                                                  echo $urls;
                                                              }
                                                              ?></span>
                                                        <span style="font-size:12px; float:right; ">
        <?php echo "Last sync : $last_sync" . "  ago"; ?></span>
                                                        <br/>
                                                        <span style="font-size: 11.5px;
                                                              margin-left: 645px;
                                                              text-align: right;"><?php
        echo anchor('calendar/delete/' . $this->uri->segment(3), translate('Remove'));
    }
    else
    {
        echo "None";
    }
?></span>
                                            </div>
                                            </p>
                                            <br/>
                                            <p  style="padding-bottom:15px;  margin-left:26px; width: 100px;float:left;"><span style="float:left; font-weight: bold; font-size:14px; margin-top:0px; font-family:Arial; color:#808080; ">Export URL:</span> &nbsp;&nbsp;<div class="calender"><?php echo anchor('calendar/ical/' . $this->uri->segment(3), site_url() . 'calendar/ical/' . $this->uri->segment(3)) ?></div></p>

                                        </div>

                                    </div>
                                </div>	
                                <!-- export instructions -->
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<!-- edit_room -->
</div>