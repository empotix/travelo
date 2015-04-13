<?php

    /**
     * DROPinn Travelling Controller Class
     *
     * helps to achieve common tasks related to the site like flash message formats,pagination variables.
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Travelling
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Travelling extends CI_Controller
    {

        public function Travelling()
        {
            parent::__construct();

            $this->load->helper('url');
            $this->load->helper('cookie');
            $this->load->helper('form');

            $this->load->library('form_validation');

            $this->load->model('Users_model');
            $this->load->model('Trips_model');
            $this->load->model('Email_model');
            $this->load->model('Message_model');
            $this->facebook_lib->enable_debug(TRUE);
        }

        //Current Trips
        /* public function current_trip()
          {
          //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()))
          if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
          {
          $cur_user_id              = $this->dx_auth->get_user_id();
          $conditions               = array("reservation.userby" => $cur_user_id, "reservation.status" => 7);
          $data['result']           = $this->Trips_model->get_reservation_trips($conditions);

          $data['title']            = get_meta_details('Your_Current_Trips','title');
          $data["meta_keyword"]     = get_meta_details('Your_Current_Trips','meta_keyword');
          $data["meta_description"] = get_meta_details('Your_Current_Trips','meta_description');

          $data['message_element']  = "travelling/view_current_trips";
          $this->load->view('template',$data);
          }
          else
          {
          redirect('users/signin');
          }
          }
         */

        //Upcomming Trips
        public function your_trips()
        {
            //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $cur_user_id = $this->dx_auth->get_user_id();
                $conditions = array("reservation.userby" => $cur_user_id);
                $conditions_in = array(1, 3, 6, 7);
                $data['result'] = $this->Trips_model->get_reservation_trips($conditions, $conditions_in);
                $data['title'] = get_meta_details('Your_trips', 'title');
                $data["meta_keyword"] = get_meta_details('Your_trips', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Your_trips', 'meta_description');


                $data['message_element'] = "travelling/view_upcomming_trips";
                $this->load->view('template', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        //Previous Trips
        public function previous_trips()
        {
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $cur_user_id = $this->dx_auth->get_user_id();
                $conditions = array("reservation.userby" => $cur_user_id, "reservation.status >=" => 8);
                $data['result'] = $this->Trips_model->get_reservation_trips($conditions);

                $data['title'] = get_meta_details('Your_Previous_Trips_Trips', 'title');
                $data["meta_keyword"] = get_meta_details('Your_Previous_Trips_Trips', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Your_Previous_Trips_Trips', 'meta_description');

                $data['message_element'] = "travelling/view_previous_trips";
                $this->load->view('template', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        public function host_details($param = '')
        {
            $data['title'] = get_meta_details('Host_Details', 'title');
            $data["meta_keyword"] = get_meta_details('Host_Details', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Host_Details', 'meta_description');

            $data['message_element'] = "travelling/view_host_details";
            $this->load->view('template', $data);
        }

        public function billing($param = '')
        {
            if (isset($param))
            {
                $reservation_id = $param;

                $conditions = array('reservation.id' => $reservation_id, 'reservation.userby' => $this->dx_auth->get_user_id());
                $result = $this->Trips_model->get_reservation($conditions);

                if ($result->num_rows() == 0)
                {
                    redirect('info');
                }

                $data['result'] = $result->row();
                $list_id = $data['result']->list_id;
                $no_quest = $data['no_quest'] = $data['result']->no_quest;

                $x = $this->Common_model->getTableData('price', array('id' => $list_id));
                $data['per_night'] = $price = $x->row()->night;

                $diff = $data['result']->checkout - $data['result']->checkin;
                $data['nights'] = $days = ceil($diff / (3600 * 24));
                $amt = $data['subtotal'] = $result->row()->topay;
                $data['commission'] = 0;
                $data['policy'] = $this->db->where('id', $list_id)->get('list')->row()->cancellation_policy;
                //check admin premium condition and apply so for
                $query = $this->Common_model->getTableData('paymode', array('id' => 3));
                $row = $query->row();

                $data['cleaning'] = $result->row()->cleaning;
                $data['security'] = $result->row()->security;

                $guests = $result->row()->no_quest;

                if ($guests > $result->row()->guest_count)
                {
                    $diff_days = $guests - $result->row()->guest_count;
                    $data['extra_guest_price'] = $result->row()->extra_guest_price * $diff_days;
                    $data['per_night'] = $result->row()->topay - $result->row()->cleaning - $result->row()->security - $data['extra_guest_price'];
                }
                else
                {
                    $data['per_night'] = $result->row()->topay - $result->row()->cleaning - $result->row()->security;
                }
                /*
                  //*if($row->is_premium == 1)
                  {
                  if($row->is_fixed == 1)
                  {
                  $fix           = $row->fixed_amount;
                  $amt           = $amt - $fix;
                  $data['commission'] = $amt ;
                  }
                  else
                  {
                  $per           = $row->percentage_amount;
                  $camt          = floatval(($amt * $per) / 100);
                  $amt           = $amt - $camt;
                  $data['commission']  = $camt ;
                  }
                  }
                  else
                  { */
                $amt = $amt;
                //	}

                $data['total_payout'] = $amt;

                $data['title'] = get_meta_details('Reservation_Request', 'title');
                $data["meta_keyword"] = get_meta_details('Reservation_Request', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Reservation_Request', 'meta_description');


                $data['message_element'] = 'trips/request_traveller';
                $this->load->view('template', $data);
            }
            else
            {
                redirect('info');
            }
        }

        // Ajax page
        public function cancel_travel($params1 = '', $params2 = '')
        {
            if ($this->input->post('reservation_id'))
            {
                $reservation_id = $this->input->post('reservation_id');

                $admin_email = $this->dx_auth->get_site_sadmin();
                $admin_name = $this->dx_auth->get_site_title();

                $conditions = array('reservation.id' => $reservation_id);
                $row = $this->Trips_model->get_reservation($conditions)->row();

                $before_status = $row->status;

                if ($this->Users_model->get_user_by_id($row->userby))
                {
                    $query1 = $this->Users_model->get_user_by_id($row->userby);
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Your List was deleted.')));
                    redirect('travelling/your_trips');
                }

                $traveler_name = $query1->row()->username;
                $traveler_email = $query1->row()->email;

                $query2 = $this->Users_model->get_user_by_id($row->userto);
                $host_name = $query2->row()->username;
                $host_email = $query2->row()->email;

                $list_title = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;

                $updateKey = array('id' => $reservation_id);
                $updateData = array();

                $check_user = $this->db->where('user_id', $this->dx_auth->get_user_id())->where('id', $row->list_id)->get('list');

                if ($check_user->num_rows() == 1)
                {
                    $updateData['status '] = 5;

                    $insertData = array(
                        'list_id' => $row->list_id,
                        'reservation_id' => $reservation_id,
                        'userby' => $row->userto,
                        'userto' => $row->userby,
                        'message' => "Sorry, Your reservation is cancelled by $host_name for $list_title.",
                        'created' => local_to_gmt(),
                        'message_type' => 3
                    );
                    $this->Message_model->sentMessage($insertData, 1);
                }
                else
                {
                    $updateData['status '] = 6;

                    $insertData = array(
                        'list_id' => $row->list_id,
                        'reservation_id' => $reservation_id,
                        'userby' => $row->userby,
                        'userto' => $row->userto,
                        'message' => "Sorry, Your list reservation is cancelled by $traveler_name for $list_title.",
                        'created' => local_to_gmt(),
                        'message_type' => 3
                    );
                    $this->Message_model->sentMessage($insertData, 1);
                }
                $this->Trips_model->update_reservation($updateKey, $updateData);

                /* 	$cancel_trip_referral_query = $this->db->select('cancel_trips_referral_code')->where('id',$row->userby)->get('users');
                  if($cancel_trip_referral_query->num_rows()!=0)
                  {
                  if($cancel_trip_referral_query->row()->cancel_trips_referral_code != '')
                  {
                  $this->db->set('trips_referral_code',$cancel_trip_referral_query->row()->cancel_trips_referral_code)->where('id',$row->userby)->update('users');
                  $this->db->set('cancel_trips_referral_code','')->where('id',$row->userby)->update('users');
                  $referral_by = $this->db->where('referral_code',$cancel_trip_referral_query->row()->cancel_trips_referral_code)->get('users');
                  if($referral_by->num_rows()!=0)
                  {
                  $referral_amount = $referral_by->row()->referral_amount-25;
                  $this->db->set('referral_amount',$referral_amount)->where('referral_code',$cancel_trip_referral_query->row()->cancel_trips_referral_code)->update('users');
                  }
                  }
                  }

                  $cancel_list_referral_query = $this->db->select('cancel_list_referral_code')->where('id',$row->userto)->get('users');
                  if($cancel_list_referral_query->num_rows()!=0)
                  {
                  if($cancel_list_referral_query->row()->cancel_list_referral_code != '')
                  {
                  $this->db->set('list_referral_code',$cancel_list_referral_query->row()->cancel_list_referral_code)->where('id',$row->userto)->update('users');
                  $this->db->set('cancel_list_referral_code','')->where('id',$row->userto)->update('users');
                  $referral_by = $this->db->where('referral_code',$cancel_list_referral_query->row()->cancel_list_referral_code)->get('users');
                  if($referral_by->num_rows()!=0)
                  {
                  $referral_amount = $referral_by->row()->referral_amount-75;
                  $this->db->set('referral_amount',$referral_amount)->where('referral_code',$cancel_list_referral_query->row()->cancel_list_referral_code)->update('users');
                  }
                  }
                  }
                 */

                $comment = $this->input->post('comment');

                $this->db->where('list_id', $row->list_id)->where('booked_days >=', $row->checkin)->where('booked_days <=', $row->checkout)->delete('calendar');

                if ($row->status == 3)
                {
                    $status = 'Confirmed';
                }
                else if ($row->status == 7)
                {
                    $status = 'After Checkin';
                }
                else
                {
                    $status = 'Pending';
                }

                if ($updateData['status '] == 5)
                {

                    //Send Mail To Host
                    $email_name = 'host_reservation_cancel';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{status}" => $status, "{comment}" => $comment, "{traveler_name}" => ucfirst($host_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($traveler_name));
                    $this->Email_model->sendMail($host_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);

                    //Send Mail To Traveller
                    $email_name = 'traveler_reservation_cancel';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{user_type}" => 'Host', "{status}" => $status, "{comment}" => $comment, "{traveler_name}" => ucfirst($host_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($traveler_name));
                    $this->Email_model->sendMail($traveler_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);
                }
                else
                {

                    //Send Mail To Host
                    $email_name = 'host_reservation_cancel';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{status}" => $status, "{comment}" => $comment, "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($host_name));
                    $this->Email_model->sendMail($traveler_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);

                    //Send Mail To Traveller
                    $email_name = 'traveler_reservation_cancel';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{user_type}" => 'Guest', "{status}" => $status, "{comment}" => $comment, "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($host_name));
                    $this->Email_model->sendMail($host_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);
                }

//Send Mail To Administrator
                $email_name = 'admin_reservation_cancel';
                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{status}" => $status, "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($host_name));
                $this->Email_model->sendMail($admin_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('You are successfully cancelled the trip.')));

                if ($this->uri->segment(3) == '')
                {
                    redirect('travelling/your_trips');
                }
            }
            else
            {
                $conditions = array('reservation.id' => $params1);
                $row = $this->Trips_model->get_reservation($conditions)->row();

                if ($this->Trips_model->get_reservation($conditions)->num_rows() == 0)
                {
                    redirect('info');
                }


                $checkin = $row->checkin;
                $checkout = $row->checkout;

                $diff1 = $checkout - $checkin;
                $days1 = ceil($diff1 / (3600 * 24));

                $diff2 = local_to_gmt() - $checkin;
                $days2 = ceil($diff2 / (3600 * 24));

                $data['nights'] = $days1;
                $data['non_nights'] = $days2;

                $data['reservation_id'] = $params1;
                $data['list_id'] = $params2;
                $this->load->view(THEME_FOLDER . '/travelling/view_cancel_travel', $data);
            }
        }

        //Ajax Page
        public function checkin($param = '')
        {
            if ($this->input->post())
            {
                $reservation_id = $this->input->post('reservation_id');
                $updateKey = array('id' => $reservation_id);
                $updateData = array();
                $updateData['status '] = 7;
                if (!$this->Trips_model->update_reservation($updateKey, $updateData))
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('You are successfully checked in. Enjoy the trip.')));
                else
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Your List was deleted.')));
                redirect('travelling/your_trips');
            }

            $data['reservation_id'] = $param;
            $this->load->view(THEME_FOLDER . '/travelling/view_checkin', $data);
        }

        // Ajax Page
        public function checkout($param = '')
        {
            if ($this->input->post())
            {
                $reservation_id = $this->input->post('reservation_id');

                $updateKey = array('id' => $reservation_id);
                $updateData = array();
                $updateData['status '] = 8;
                $this->Trips_model->update_reservation($updateKey, $updateData);

                $conditions = array('reservation.id' => $reservation_id);
                $row = $this->Trips_model->get_reservation($conditions)->row();

                $username = ucfirst($this->dx_auth->get_username());

                if ($row->list_id)
                {



                    $insertData = array(
                        'list_id' => $row->list_id,
                        'reservation_id' => $reservation_id,
                        'userby' => $row->userby,
                        'userto' => $row->userto,
                        'message' => "$username wants the review from you.",
                        'created' => date('m/d/Y g:i A'),
                        'message_type ' => 4
                    );

                    $this->Message_model->sentMessage($insertData);
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('You are successfully checked out.')));
                    redirect('travelling/previous_trips');
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Your List was deleted.')));
                    redirect('travelling/previous_trips');
                }
            }

            $data['reservation_id'] = $param;
            $this->load->view(THEME_FOLDER . '/travelling/view_checkout', $data);
        }

    }

    /* End of file travelling.php */
    /* Location: ./app/controllers/travelling.php */
?>
