<?php

    /**
     * DROPinn Calendar Controller Class
     *
     * helps to achieve common tasks related to the booking calendar.
     *
     * @package		DROPinn
     * @subpackage	Controllers
     * @category	Calendar
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Calendar extends CI_Controller
    {

        public function Calendar()
        {
            parent::__construct();

            $this->load->helper('url');
            $this->load->helper('form');

            $this->load->library('Form_validation');
            $this->load->library('email');


            $this->load->model('Users_model');
            $this->load->model('Email_model');
            $this->load->model('Trips_model');

            $this->facebook_lib->enable_debug(TRUE);
        }

        public function delete($param = '')
        {

            $condition = array("list_id" => $param);
            $this->Common_model->deleteTableData('ical_import', $condition);
            redirect('calendar/single/' . $param);
        }

        public function ical($param = '')
        {

            header("Content-Type: text/Calendar");
            header("Content-Disposition: inline; filename=$param.ics");

            echo "BEGIN:VCALENDAR\n";
            echo "PRODID;X-RICAL-TZSOURCE=TZINFO:-//dropinn Inc//Hosting Calendar 0.8.8//EN\n";
            echo "VERSION:2.0\n";
            echo "CALSCALE:GREGORIAN\n";

            $cals = $this->db->query("SELECT distinct(group_id) FROM `calendar` WHERE `list_id` = '" . $param . "'");
            $group = $cals->result_array();
            $count_id = $cals->num_rows();

            foreach ($group as $groups)
            {
                $check_style = $this->db->where('group_id', $groups['group_id'])->where('list_id', $param)->order_by('id', 'asc')->limit(1)->get('calendar');

                $check_count = $this->db->where('group_id', $groups['group_id'])->where('list_id', $param)->get('calendar');

                if ($check_count->num_rows() == 1)
                {
                    $style = 'single';
                    $this->db->where('id', $check_style->row()->id)->update('calendar', array('style' => $style));
                }

                $check_style = $this->db->where('group_id', $groups['group_id'])->where('list_id', $param)->order_by('id', 'asc')->limit(1)->get('calendar');

                if ($check_style->row()->style != 'left' && $check_style->row()->style != 'single')
                {
                    $this->db->where('id', $check_style->row()->id)->update('calendar', array('style' => 'left'));
                }

                $check_style = $this->db->where('group_id', $groups['group_id'])->where('list_id', $param)->order_by('id', 'desc')->limit(1)->get('calendar');

                $check_count = $this->db->where('group_id', $groups['group_id'])->where('list_id', $param)->get('calendar');

                if ($check_count->num_rows() == 1)
                {
                    $style = 'single';
                    $this->db->where('id', $check_style->row()->id)->update('calendar', array('style' => $style));
                }

                $check_style = $this->db->where('group_id', $groups['group_id'])->where('list_id', $param)->order_by('id', 'desc')->limit(1)->get('calendar');

                if ($check_style->row()->style != 'right' && $check_style->row()->style != 'single')
                {
                    $this->db->where('id', $check_style->row()->id)->update('calendar', array('style' => 'right'));
                }

                $cal = $this->db->query("SELECT  * FROM `calendar` WHERE `group_id` = '" . $groups['group_id'] . "' AND `list_id` = '" . $param . "' order by id ASC");

                $results = $cal->result_array();

                foreach ($results as $calendar)
                {

                    if ($calendar['notes'] != '')
                    {
                        $notes = $calendar['notes'];
                    }
                    else
                    {
                        $notes = $calendar['availability'];
                    }

                    $date = $calendar['created'];

                    if ($calendar['style'] == 'single')
                    {
                        $from_date = $calendar['booked_days'];

                        $to_date = $calendar['booked_days'] + 24 * 60 * 60;

                        $from_date2 = date("Ymd", $from_date);
                        $to_date2 = date("Ymd", $to_date);
                    }

                    if ($calendar['style'] == 'left')
                    {
                        $from_date = $calendar['booked_days'];

                        $from_date1 = date("Ymd", $from_date);
                    }

                    if ($calendar['style'] == 'right')
                    {
                        $to_date = $calendar['booked_days'] + 24 * 60 * 60;

                        $to_date1 = date("Ymd", $to_date);
                    }
                }

                if (isset($from_date2))
                {
                    echo "BEGIN:VEVENT\n";
                    echo "DTEND;VALUE=DATE:" . $to_date2 . "\n";
                    echo "DTSTART;VALUE=DATE:" . $from_date2 . "\n";
                    echo "DESCRIPTION:" . $notes . "\n";
                    echo "SUMMARY:" . $notes . "\n";
                    echo "STATUS:" . $calendar['availability'] . "\n";
                    echo "END:VEVENT\n";
                    unset($from_date2);
                    unset($to_date2);
                }
                else
                {
                    echo "BEGIN:VEVENT\n";
                    echo "DTEND;VALUE=DATE:" . $to_date1 . "\n";
                    echo "DTSTART;VALUE=DATE:" . $from_date1 . "\n";
                    echo "DESCRIPTION:" . $notes . "\n";
                    echo "SUMMARY:" . $notes . "\n";
                    echo "STATUS:" . $calendar['availability'] . "\n";
                    echo "END:VEVENT\n";
                }
            }

            echo "END:VCALENDAR\n";
        }

        public function single($param = '')
        {
            if ($param)
                if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
                {
                    $list_id = $param;
                    $day = date("d");
                    $month = $this->input->get('month', TRUE);
                    $year = $this->input->get('year', TRUE);

                    if (!empty($month) && !empty($year))
                    {
                        $month = $month;
                        $year = $year;
                    }
                    else
                    {
                        $month = date("m");
                        $year = date("Y");
                    }

                    if ($month > 12 || $month < 1)
                    {
                        $month = date("m");
                    }
                    else
                    {
                        $month = $month;
                    }

                    if (($year == ($year - 3)) || ($year == ($year + 3)))
                    {
                        redirect('calendar/single/' . $list_id . '?month=' . $month . '&year=' . date("Y"));
                    }
                    if ($this->Common_model->getTableData('list', array('id' => $param))->num_rows() == 0)
                    {
                        redirect('info/deny');
                    }
                    $row = $this->Common_model->getTableData('list', array('id' => $param))->row();
                    $data['list_title'] = $row->title;
                    $data['list_price'] = $row->price;

                    $conditions = array('list_id' => $list_id);
                    $data['result'] = $this->Trips_model->get_calendar($conditions)->result();

                    $data['list_id'] = $list_id;
                    $data['day'] = $day;
                    $data['month'] = $month;
                    $data['year'] = $year;
                    //Remove incorrect list from seasonal price
                    $query = $this->Common_model->getTableData('seasonalprice', array('list_id' => $list_id));
                    $res = $query->result_array();
                    foreach ($res as $seasonal)
                    {
                        $starttime = $seasonal['start_date'];
                        $gmtTime = $seasonal['end_date'];
                        if ($gmtTime < $starttime)
                        {
                            $list_id = $seasonal['list_id'];
                            $remove_query = "delete from seasonalprice where list_id='" . $list_id . "' and start_date='" . $seasonal['start_date'] . "' and end_date='" . $seasonal['end_date'] . "'";
                            $remove_exe = $this->db->query($remove_query);
                        }
                    }
                    $data['title'] = get_meta_details('Calendar', 'title');
                    $data["meta_keyword"] = get_meta_details('Calendar', 'meta_keyword');
                    $data["meta_description"] = get_meta_details('Calendar', 'meta_description');

                    $data['message_element'] = 'rooms/view_calendar_single';
                    $this->load->view('template', $data);
                }
                else
                {
                    $this->session->set_userdata('redirect_to', 'calendar/single/' . $param);
                    redirect('users/signin', 'refresh');
                }
        }

        public function modify_calendar()
        {
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {

                //Seasonal Price 

                $availability = $this->input->post('availability');
                $pricevalue = $this->input->post('seasonal_price');

                if ($availability == "Available" && $pricevalue != "" && $pricevalue >= 10 && strlen($pricevalue) < 5)
                {
                    $month = $this->input->get('month', TRUE);
                    $year = $this->input->get('year', TRUE);
                    $startdate = $this->input->post('starting_date');
                    $starttime = get_gmt_time(strtotime($startdate));
                    $enddate = $this->input->post('stopping_date');
                    $endtime = get_gmt_time(strtotime($enddate));
                    $pdata = array(
                        'list_id' => $this->input->post('hosting_id'),
                        'price' => $this->input->post('seasonal_price'),
                        'start_date' => $starttime,
                        'end_date' => $endtime
                    );

                    $update = 0;
                    $query = $this->Common_model->getTableData('seasonalprice', array('list_id' => $pdata['list_id']));
                    $res = $query->result_array();
                    $count = 1;
                    foreach ($res as $row)
                    {
                        $from = $row['start_date'];
                        $to = $row['end_date'];

                        if ($starttime == $from && $endtime == $to) //Case 1: start=from end=to 1update
                        {
                            $condition = array('list_id' => $pdata['list_id'], 'start_date' => $from, 'end_date' => $to);
                            $updatedata = array('price' => $pdata['price']);
                            $this->Common_model->updateTableData('seasonalprice', NULL, $condition, $updatedata);
                            $update = 1;
                        }
                        else if ($starttime == $from && $endtime == $from) //Case 2: strat=from end=from 1update & 1insert
                        {
                            //update	
                            $starttime_update = get_gmt_time(strtotime('+1 day', $starttime));
                            $condition = array('list_id' => $pdata['list_id'], 'start_date' => $from, 'end_date' => $to);
                            $updatedata = array('start_date' => $starttime_update);
                            $this->Common_model->updateTableData('seasonalprice', NULL, $condition, $updatedata);
                            //insert
                            $insertdata1 = array(
                                'list_id' => $this->input->post('hosting_id'),
                                'price' => $this->input->post('seasonal_price'),
                                'start_date' => $starttime,
                                'end_date' => $endtime
                            );
                            $this->db->insert('seasonalprice', $insertdata1);
                            $update = 1;
                        }
                        else if ($starttime == $to && $endtime == $to) //Case 3: start=to end=to 1update & 1insert
                        {
                            //update	
                            $endtime_update = get_gmt_time(strtotime('-1 day', $endtime));
                            $condition = array('list_id' => $pdata['list_id'], 'start_date' => $from, 'end_date' => $to);
                            $updatedata = array('end_date' => $endtime_update);
                            $this->Common_model->updateTableData('seasonalprice', NULL, $condition, $updatedata);
                            //insert
                            $insertdata1 = array(
                                'list_id' => $this->input->post('hosting_id'),
                                'price' => $this->input->post('seasonal_price'),
                                'start_date' => $starttime,
                                'end_date' => $endtime
                            );
                            $this->db->insert('seasonalprice', $insertdata1);
                            $update = 1;
                        }
                        else if (($starttime > $from && $starttime < $to) && ($endtime > $from && $endtime < $to)) //Case 4: start < ( from < to ) < end 2inserts & 1update
                        {
                            //update	
                            $endtime_update = get_gmt_time(strtotime('+1 day', $endtime));
                            $condition = array('list_id' => $pdata['list_id'], 'start_date' => $from, 'end_date' => $to);
                            $updatedata = array('start_date' => $endtime_update);
                            $this->Common_model->updateTableData('seasonalprice', NULL, $condition, $updatedata);
                            //insert 1
                            $starttime_update = get_gmt_time(strtotime('-1 day', $starttime));
                            $insertdata1 = array(
                                'list_id' => $this->input->post('hosting_id'),
                                'price' => $row['price'],
                                'start_date' => $row['start_date'],
                                'end_date' => $starttime_update
                            );
                            $this->db->insert('seasonalprice', $insertdata1);
                            //insert 2
                            $insertdata1 = array(
                                'list_id' => $this->input->post('hosting_id'),
                                'price' => $this->input->post('seasonal_price'),
                                'start_date' => $starttime,
                                'end_date' => $endtime
                            );
                            $this->db->insert('seasonalprice', $insertdata1);
                            $update = 1;
                        }
                        else if ((($starttime <= $from && $starttime < $to) && ($endtime > $from && $endtime < $to)) || (($starttime < $from && $starttime < $to) && ($endtime == $from))) //Case 5: ( start <= from ) < to < end 1insert & 1update
                        {
                            //update	
                            $endtime_update = get_gmt_time(strtotime('+1 day', $endtime));
                            $condition = array('list_id' => $pdata['list_id'], 'start_date' => $from, 'end_date' => $to);
                            $updatedata = array('start_date' => $endtime_update);
                            $this->Common_model->updateTableData('seasonalprice', NULL, $condition, $updatedata);
                            //insert 1
                            $insertdata1 = array(
                                'list_id' => $this->input->post('hosting_id'),
                                'price' => $this->input->post('seasonal_price'),
                                'start_date' => $starttime,
                                'end_date' => $endtime
                            );
                            $this->db->insert('seasonalprice', $insertdata1);
                            $update = 1;
                        }
                        else if ((($starttime > $from && $starttime < $to) && ($endtime > $from && $endtime >= $to)) || (($starttime == $to) && ($endtime > $from && $endtime > $to))) //Case 6: start < from  < ( to >= end ) 1insert & 1update
                        {
                            //update	
                            $starttime_update = get_gmt_time(strtotime('-1 day', $starttime));
                            $condition = array('list_id' => $pdata['list_id'], 'start_date' => $from, 'end_date' => $to);
                            $updatedata = array('end_date' => $starttime_update);
                            $this->Common_model->updateTableData('seasonalprice', NULL, $condition, $updatedata);
                            //insert 1
                            $insertdata1 = array(
                                'list_id' => $this->input->post('hosting_id'),
                                'price' => $this->input->post('seasonal_price'),
                                'start_date' => $starttime,
                                'end_date' => $endtime
                            );
                            $this->db->insert('seasonalprice', $insertdata1);
                            $update = 1;
                        }
                        else if ((($starttime < $from && $starttime < $to) && ($endtime > $from && $endtime > $to)) || (($starttime == $from) && ($endtime > $to & $startime < $to)) || ($starttime < $from && $starttime < $to) && ($endtime == $to)) //Case 7: start < from  < to < end  Delete & 1update
                        {
                            //Delete	
                            $condition = array('list_id' => $pdata['list_id'], 'start_date' => $from, 'end_date' => $to);
                            $this->Common_model->deleteTableData('seasonalprice', $condition);
                            //insert 1
                            if ($count == 1)
                            {
                                $insertdata1 = array(
                                    'list_id' => $this->input->post('hosting_id'),
                                    'price' => $this->input->post('seasonal_price'),
                                    'start_date' => $starttime,
                                    'end_date' => $endtime
                                );
                                $this->db->insert('seasonalprice', $insertdata1);
                            }
                            $update = 1;
                        }
                        $count++;
                    }
                    if ($update == 0)
                        $this->db->insert('seasonalprice', $pdata);
                }
                elseif ($availability == "Available" && $pricevalue < 10)
                {

                    exit;
                }
                elseif ($availability == "Available" && strlen($pricevalue) >= 5)
                {
                    exit;
                }
                elseif ($availability != "Available" && $this->input->post('notes') == 'Notes...')
                {
                    exit;
                }
                $day = date("d");
                $month = $this->input->get('month', TRUE);
                $year = $this->input->get('year', TRUE);

                $booked_from = $this->input->post('starting_date_original');
                $booked_to = $this->input->post('stopping_date_original');

                $list_id = $this->input->post('hosting_id');
                $grouping_uid = $this->input->post('grouping_uid');

                $insertData = array(
                    'list_id' => $this->input->post('hosting_id'),
                    'availability' => $this->input->post('availability'),
                    'value' => $this->input->post('value_native'),
                    'currency' => $this->input->post('currency'),
                    'notes' => $this->input->post('notes'),
                    'booked_using' => $this->input->post('booking_service')
                );

                if (!empty($grouping_uid))
                {
                    if ($insertData['availability'] == 'Available')
                    {
                        $conditions = array('group_id' => $grouping_uid);
                        $this->Trips_model->delete_calendar(NULL, $conditions);
                    }
                    else if ($insertData['availability'] == 'Booked')
                    {
                        $updateKey = array('group_id' => $grouping_uid);
                        $updateData = $insertData;
                        $this->Trips_model->update_calendar($updateKey, $updateData);
                    }
                    else
                    {
                        $updateKey = array('group_id' => $grouping_uid);
                        $updateData = $insertData;
                        $this->Trips_model->update_calendar($updateKey, $updateData);
                    }
                }
                else
                {
                    if ($insertData['availability'] == 'Booked' || $insertData['availability'] == 'Not Available')
                    {
                        $this->db->select_max('group_id');
                        $group_id = $this->db->get('calendar')->row()->group_id;

                        if (empty($group_id))
                            echo $countJ = 0;
                        else
                            $countJ = $group_id;

                        $insertData['group_id'] = $countJ + 1;

                        $days = getDaysInBetweenC($booked_from, $booked_to);

                        $count = count($days);
                        $i = 1;
                        foreach ($days as $val)
                        {
                            if ($count == 1)
                            {
                                $insertData['style'] = 'single';
                            }
                            else if ($count > 1)
                            {
                                if ($i == 1)
                                {
                                    $insertData['style'] = 'left';
                                }
                                else if ($count == $i)
                                {
                                    $insertData['notes'] = '';
                                    $insertData['style'] = 'right';
                                }
                                else
                                {
                                    $insertData['notes'] = '';
                                    $insertData['style'] = 'both';
                                }
                            }
                            $insertData['booked_days'] = get_gmt_time(strtotime($val));
                            $insertData['created'] = local_to_gmt();
                            $this->Trips_model->insert_calendar($insertData);
                            $i++;
                        }
                    }
                }

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

                $schedules = '';
                $firstDay = $prev_year . '-' . $prev_month . '-' . $day_prevmonth;

                $conditions = array('list_id' => $list_id);
                $result = $this->Trips_model->get_calendar($conditions)->result();

                //Previous Months Days
                while ($blank > 0)
                {
                    $full_date = $prev_month . '/' . $day_prevmonth . '/' . $prev_year;

                    $pre_schedules = '{cl: "av", sty: "both", isa: 1},';
                    foreach ($result as $row)
                    {
                        if (get_gmt_time(strtotime($full_date)) == $row->booked_days)
                        {
                            $pre_schedules = '{cl: "tp", sty: "' . $row->style . '", isa: 0, id: ' . $row->id . ', notes: "' . $row->notes . '", st: 4, sst: "' . $row->booked_using . '", gid: ' . $row->group_id . ', reservation_value: ' . $row->value . '},';
                        }
                    }
                    $schedules .= $pre_schedules;

                    $blank = $blank - 1;
                    $day_count++;
                    $day_prevmonth++;
                }

                //Current Months Days
                while ($day_num <= $days_in_month)
                {
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
                            $pre_schedules = '{cl: "' . $tp . '", sty: "' . $row->style . '", isa: 0, id: ' . $row->id . ', notes: "' . $row->notes . '", st: ' . $st . ', sst: "' . $row->booked_using . '", gid: ' . $row->group_id . $value . '},';
                        }
                    }
                    $schedules .= $pre_schedules;

                    $day_count++;
                    $day_nextmonth++;
                }

                echo 'update_calendar_data(0, ' . $list_id . ', [[' . substr($schedules, 0, -1) . ']]); after_submit();';
                exit;
                //redirect('calendar/single/'.$list_id.'?month='.$month.'&year='.$year);
            }
            else
            {
                redirect('users/signin');
            }
        }

    }

    /* End of file calendar.php */
    /* Location: ./app/controllers/calendar.php */
?>