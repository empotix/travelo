<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    if (!function_exists('get_est_time'))
    {

        function get_est_time()
        {
            $now = time();

            $gmt = local_to_gmt($now);

            $timezone = 'UM5';

            $daylight_saving = FALSE;

            $tt = gmt_to_local($gmt, $timezone, $daylight_saving);

            return $tt;
        }

    }


    /*
     * Get the GMT time
     */

    if (!function_exists('get_gmt_time'))
    {

        function get_gmt_time($time = '')
        {

            if ($time == '')
                $time = time();

            $gmt = local_to_gmt($time);

            return $gmt;
        }

    }


    if (!function_exists('get_datetime'))
    {

        function get_datetime($timestamp)
        {

            $CI = & get_instance();

            $userTimezone = $CI->dx_auth->get_timezone();

            if (isset($userTimezone) and $userTimezone)
                $time_zone = $userTimezone != '' ? $userTimezone : 'UTC';
            else
                $time_zone = 'UTC';

            $timestamp = get_user_time($timestamp, $time_zone);

            if (date('d/M/Y') == date('d/M/Y', $timestamp))
                $date = 'Today at' . " " . date('H:i', $timestamp);
            else
                $date = date('M d, Y', $timestamp);

            return $date;
        }

    }


    if (!function_exists('get_usertimestamp'))
    {

        function get_usertimestamp($timestamp)
        {

            $CI = & get_instance();

            //$userTimezone = $CI->dx_auth->get_timezone();

            $userTimezone = $CI->db->where('id', $CI->dx_auth->get_user_id())->get('users');
            if ($userTimezone->num_rows() != 0)
            {
                $userTimezone = $userTimezone->row()->timezone;
            }

            if (isset($userTimezone) and $userTimezone)
                $time_zone = $userTimezone != '' ? $userTimezone : 'UTC';
            else
                $time_zone = 'UTC';

            $timestamp = get_user_time($timestamp, $time_zone);

            return $timestamp;
        }

    }



    if (!function_exists('get_datetime_with_zone'))
    {

        function get_datetime_with_zone($timestamp)
        {
            $date = array();

            $CI = & get_instance();

            if ($CI->loggedInUser)
            {
                // get user zone
                $str_name = $CI->loggedInUser->timezone;
                $qry_zones = "SELECT * FROM time_zones where name = '$str_name'";
                $zones = $CI->db->query($qry_zones);
                $zones = $zones->row();
                //echo $zones->format;

                if (is_object($zones))
                    $time_zone = $zones->format != '' ? $zones->format : 'UM5';
                else
                    $time_zone = 'UM5';
            }
            else
            {
                $time_zone = 'UM5';
            }

            $timestamp = get_user_time($timestamp, $time_zone);

            if (date('d/M/Y') == date('d/M/Y', $timestamp))
                $date[] = $CI->lang->line('Today at') . " " . date('H:i', $timestamp);
            else
                $date[] = date('M d, Y H:i T', $timestamp);

            $timez = $time_zone;

            $number = timezones($timez);

            if ($number > 0)
            {
                $intva = intval($number);

                $intva = sprintf("%02d", $intva);

                $dec = $number - intval($number);

                if ($dec != '0')
                {
                    if ($dec == '0.5')
                    {
                        $timezformat = "UTC+" . $intva . ":30";
                    }

                    if ($dec == '0.75')
                    {
                        $timezformat = "UTC+" . $intva . ":45";
                    }
                }
                else
                {
                    if ($number == '0')
                    {
                        $timezformat = "UTC";
                    }

                    if ($dec == '0' and $number != '0')
                    {
                        $timezformat = "UTC+" . $intva;
                    }
                }
            }
            else
            {
                $intva = intval($number);

                $intva = sprintf("%02d", $intva);

                $dec = $number - intval($number);

                $dec = abs($dec);

                if ($dec != '0')
                {

                    if ($dec == '0.5')
                    {
                        $intval = abs($intva);

                        $intval = sprintf("%02d", $intval);

                        $timezformat = "UTC-" . $intval . ":30";
                    }

                    if ($dec == '0.75')
                    {
                        $intval = abs($intva);

                        $intval = sprintf("%02d", $intval);

                        $timezformat = "UTC-" . $intval . ":45";
                    }
                }
                else
                {
                    if ($number == '0')
                    {
                        $timezformat = "UTC";
                    }

                    if ($dec == '0' and $number != '0')
                    {
                        $intval = abs($intva);

                        $intval = sprintf("%02d", $intval);

                        $timezformat = "UTC-" . $intval;
                    }
                }
            }


            $timezoneinfo = getTimezoneinfo($timezformat);

            //$date['timezone']=$timezoneinfo['abr'];
            //$zones

            $date['timezone'] = $zones->abbr;

            $date['timezonename'] = $timezoneinfo['name'];

            return $date;
        }

    }



    if (!function_exists('get_user_time'))
    {

        function get_user_time($gmt_time = '', $time_zone = '', $day_light = FALSE)
        {

            return gmt_to_local($gmt_time, $time_zone, $day_light);
        }

    }


    if (!function_exists('get_user_times'))
    {

        function get_user_times($gmt_time = '', $time_zone = '', $day_light = FALSE)
        {

            return date('M d, Y', gmt_to_local($gmt_time, $time_zone, $day_light));
        }

    }


    if (!function_exists('current_date'))
    {

        function current_date($timestamp)
        {

            $CI = & get_instance();

            if ($CI->loggedInUser)
                $time_zone = $CI->loggedInUser->timezone != '' ? $CI->loggedInUser->timezone : 'UM5';
            else
                $time_zone = 'UM5';

            $timestamp = get_user_time($timestamp, $time_zone);

            if (date('d/M/Y') == date('d/M/Y', $timestamp))
                $date = date('M d, Y H:i', $timestamp);
            else
                $date = date('M d, Y H:i', $timestamp);

            return $date;
        }

    }



    if (!function_exists('current_date_without_hours'))
    {

        function current_date_without_hours($timestamp)
        {

            $CI = & get_instance();

            if ($CI->loggedInUser)
                $time_zone = $CI->loggedInUser->timezone != '' ? $CI->loggedInUser->timezone : 'UM5';
            else
                $time_zone = 'UM5';

            $timestamp = get_user_time($timestamp, $time_zone);

            if (date('d/M/Y') == date('d/M/Y', $timestamp))
                $date = date('M d, Y', $timestamp);
            else
                $date = date('M d, Y', $timestamp);

            return $date;
        }

    }


    if (!function_exists('show_date'))
    {

        function show_date($timestamp)
        {

            $CI = & get_instance();

            if ($CI->loggedInUser)
                $time_zone = $CI->loggedInUser->timezone != '' ? $CI->loggedInUser->timezone : 'UM5';
            else
                $time_zone = 'UM5';



            $timestamp = get_user_time($timestamp, $time_zone);

            if (date('d/M/Y') == date('d/M/Y', $timestamp))
                $date = date('M d, Y H:i', $timestamp);
            else
                $date = date('M d, Y H:i', $timestamp);

            return $date;
        }

    }

    // Set timezone
    date_default_timezone_set("UTC");

    // Time format is UNIX timestamp or
    // PHP strtotime compatible strings

    function dateDiff($time1, $time2, $precision = 6)
    {
        // If not numeric then convert texts to unix timestamps
        if (!is_int($time1))
        {
            $time1 = strtotime($time1);
        }

        if (!is_int($time2))
        {
            $time2 = strtotime($time2);
        }

        // If time1 is bigger than time2
        // Then swap time1 and time2
        if ($time1 > $time2)
        {
            $ttime = $time1;
            $time1 = $time2;
            $time2 = $ttime;
        }


        // Set up intervals and diffs arrays
        $intervals = array('year', 'month', 'day', 'hour', 'minute', 'second');
        $diffs = array();

        // Loop thru all intervals
        foreach ($intervals as $interval)
        {
            // Set default diff to 0
            $diffs[$interval] = 0;

            // Create temp time from time1 and interval
            $ttime = strtotime("+1 " . $interval, $time1);

            // Loop until temp time is smaller than time2
            while ($time2 >= $ttime)
            {
                $time1 = $ttime;

                $diffs[$interval] ++;

                // Create new temp time from time1 and interval
                $ttime = strtotime("+1 " . $interval, $time1);
            }
        }

        $count = 0;
        $times = array();

        // Loop thru all diffs
        foreach ($diffs as $interval => $value)
        {
            // Break if we have needed precission
            if ($count >= $precision)
            {
                break;
            }

            // Add value and interval 
            // if value is bigger than 0
            if ($value > 0)
            {
                // Add s if value is not 1
                if ($value != 1)
                {
                    $interval .= "s";
                }

                // Add value and interval to times array
                $times[] = $value . " " . $interval;
                $count++;
            }
        }

        // Return string with times
        return implode(", ", $times);
    }

    if (!function_exists('timezone_name'))
    {

        function timezone_name($default = 'UTC', $class = "", $name = 'timezones')
        {

            $CI = & get_instance();

            $CI->lang->load('date');

            if ($default == 'GMT')
                $default = 'UTC';

            $menu = '';

            foreach (timezones() as $key => $val)
            {
                if ($default == $key)
                    $menu = $CI->lang->line($key);
            }

            return $menu;
        }

    }


// for future work
    if (!function_exists('timezone_menus'))
    {

        function timezone_menus($default = 'UTC', $class = "", $name = 'timezones')
        {
            $CI = & get_instance();
            $CI->lang->load('date');

            // get zones
            $qry_zones = "SELECT * FROM time_zones";
            $zones = $CI->db->query($qry_zones);
            //pr($zones->result()); exit;

            if ($default == 'GMT')
                $default = 'UTC';

            $menu = '<select name="' . $name . '"';

            if ($class != '')
            {
                $menu .= ' class="' . $class . '"';
            }

            $menu .= ">\n";

            foreach ($zones->result() as $zone)
            {
                $selected = ($default == $zone->name) ? " selected='selected'" : '';
                $menu .= "<option value='{$zone->name}'{$selected}>" . '(' . $zone->utf_offset . ') ' . $zone->name . "</option>\n";
            }

            $menu .= "</select>";

            return $menu;
        }

    }

    /* End of file MY_date_helper.php */

    /* Location: ./app/helpers/MY_date_helper.php */
?>