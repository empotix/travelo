<?php

    /**
     * DROPinn Trips Controller Class
     *
     * Helps to control the trips functionality
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Trips
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com
     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Cron extends CI_Controller
    {

        public function Cron()
        {
            parent::__construct();

            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->helper('cookie');

            $this->load->library('Form_validation');

            $this->load->model('Users_model');
            $this->load->model('Email_model');
            $this->load->model('Message_model');
            $this->load->model('Trips_model');

            $this->facebook_lib->enable_debug(TRUE);
        }

        public function expire()
        {


            $sql = "select *from reservation";
            $query = $this->db->query($sql);
            $res = $query->result_array();
            $date = date("F j, Y, g:i a");
            $date = get_gmt_time(strtotime($date));

            foreach ($res as $reservation)
            {
                $timestamp = $reservation['book_date'];
                $book_date = date("F j, Y, g:i a", $timestamp);
                $book_date = strtotime($book_date);
                $gmtTime = get_gmt_time(strtotime('+1 day', $timestamp));

                if ($gmtTime <= $date && $reservation['status'] == 1)
                {
                    $reservation_id = $reservation['id'];
                    $admin_email = $this->dx_auth->get_site_sadmin();
                    $admin_name = $this->dx_auth->get_site_title();
                    $conditions = array('reservation.id' => $reservation_id);
                    $row = $this->Trips_model->get_reservation($conditions)->row();
                    $query1 = $this->Users_model->get_user_by_id($row->userby);
                    $traveler_name = $query1->row()->username;
                    $traveler_email = $query1->row()->email;

                    $query2 = $this->Users_model->get_user_by_id($row->userto);
                    $host_name = $query2->row()->username;
                    $host_email = $query2->row()->email;

                    $list_title = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;

                    $updateKey = array('id' => $reservation_id);
                    $updateData = array();
                    $updateData['status '] = 2;
                    $this->Trips_model->update_reservation($updateKey, $updateData);

                    //Send Mail To Traveller
                    $email_name = 'traveler_reservation_expire';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($host_name));
                    $this->Email_model->sendMail($traveler_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);

                    //Send Mail To Host
                    $email_name = 'host_reservation_expire';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($host_name));
                    $this->Email_model->sendMail($host_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);

                    //Send Mail To Administrator
                    $email_name = 'admin_reservation_expire';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($host_name));
                    $this->Email_model->sendMail($admin_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);
                }
            }
        }

        public function unlink_thumb()
        {
            foreach (glob('/opt/lampp/htdocs/dropinn-1.6.6/files/cache/*.*') as $file)
                if (is_file($file))
                    @unlink($file);
        }

        public function calendar_sync()
        {
            require_once("app/views/templates/blue/rooms/codebase/class.php");

            $exporter = new ICalExporter();

            $ical_urls = $this->db->get('ical_import');

            if ($ical_urls->num_rows() != 0)
            {
                foreach ($ical_urls->result() as $row)
                {

                    $ical_content = file_get_contents($row->url);

                    $events = $exporter->toHash($ical_content);
                    $success_num = 0;
                    $error_num = 0;

                    $id = $row->list_id;

                    /* ! inserting events in database */

                    $check_tb = $this->db->select('group_id')->where('list_id', $id)->order_by('id', 'desc')->limit(1)->get('calendar');
                    //$query = $this->db->last_query();
                    //echo $query;exit;
                    //print_r($check_tb->num_rows());exit;
                    if ($check_tb->num_rows() != 0)
                    {
                        $i1 = $check_tb->row()->group_id;
                    }
                    else
                    {
                        $i1 = 1;
                    }


                    for ($i = 1; $i <= count($events); $i++)
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

                            $check_dates = $this->db->where('list_id', $id)->where('booked_days', strtotime($startdate1))->get('calendar');

                            if ($check_dates->num_rows() != 0)
                            {
                                $conflict = $i;
                            }
                            else
                            {

                                $data = array(
                                    'id' => NULL,
                                    'list_id' => $id,
                                    'group_id' => $i + $i1,
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

                            //	if(isset($conflict))
                            //	{
                            //		$this->db->where('list_id',$id)->where('group_id',$conflict)->delete('calendar');
                            //	}

                            $abc = $event["start_date"];
                            $newdate = strtotime('+1 day', strtotime($abc));
                            $event["start_date"] = date("m/d/Y", $newdate);
                        }

                        $success_num++;
                    }//for loop end
                }
            }

            echo '<h2>Cron Successfully Runned.</h2>';
        }

    }
    