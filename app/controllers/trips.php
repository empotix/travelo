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

    class Trips extends CI_Controller
    {

        public function Trips()
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



            $api_user = $this->Common_model->getTableData('payment_details', array('code' => 'CC_USER'))->row()->value;
            $api_pwd = $this->Common_model->getTableData('payment_details', array('code' => 'CC_PASSWORD'))->row()->value;
            $api_key = $this->Common_model->getTableData('payment_details', array('code' => 'CC_SIGNATURE'))->row()->value;

            $paymode = $this->Common_model->getTableData('payments', array('payment_name' => 'Paypal'))->row()->is_live;

            if ($paymode == 0)
            {
                $paymode = TRUE;
            }
            else
            {
                $paymode = FALSE;
            }
            $paypal_details = array(
// you can get this from your Paypal account, or from your
// test accounts in Sandbox
                'API_username' => $api_user,
                'API_signature' => $api_key,
                'API_password' => $api_pwd,
// Paypal_ec defaults sandbox status to true
// Change to false if you want to go live and
// update the API credentials above
                'sandbox_status' => $paymode,
            );
            $this->load->library('paypal_ec', $paypal_details);

            $this->facebook_lib->enable_debug(TRUE);
        }

        public function request($param = '')
        {
            if ((!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()))
            {
                $this->session->set_userdata('redirect_to', 'trips/request/' . $param);
                redirect('users/signin', 'refresh');
            }
            if (isset($param))
            {
                $reservation_id = $param;

                $conditions = array('reservation.id' => $reservation_id, 'reservation.userto' => $this->dx_auth->get_user_id());
                $result = $this->Trips_model->get_reservation($conditions);

                if ($result->num_rows() == 0)
                {
                    redirect('info');
                }

                $data['result'] = $result->row();
                $list_id = $data['result']->list_id;
                $no_quest = $data['no_quest'] = $data['result']->no_quest;

                $x = $this->Common_model->getTableData('price', array('id' => $list_id));


                $diff = $data['result']->checkout - $data['result']->checkin;
                $data['nights'] = $days = ceil($diff / (3600 * 24));
                $amt = $data['subtotal'] = $result->row()->topay;

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

                $data['commission'] = 0;
                //check admin premium condition and apply so for
                $query = $this->Common_model->getTableData('paymode', array('id' => 2));
                $row = $query->row();
                /* if($row->is_premium == 1)
                  {
                  if($row->is_fixed == 1)
                  {
                  $fix           = $row->fixed_amount;
                  $amt           = $amt - $fix;
                  $data['commission'] = $amt ;
                  echo $amt;exit;
                  }
                  else
                  {
                  $per           = $row->percentage_amount;
                  $camt          = floatval(($amt * $per) / 100);
                  $amt           = $amt - $camt;
                  $data['commission']  = $camt ;
                  //echo $camt;exit;
                  }
                  }
                  else
                  {
                  $amt                      = $amt;
                  } */

                $data['total_payout'] = $amt;
                $data['subtotal'] = $amt;
                //$data['per_night'] = $price = $amt/$days;

                $data['policy'] = $this->db->where('id', $list_id)->get('list')->row()->cancellation_policy;

                if ($data['policy'] == '')
                {
                    $data['policy'] == 'No policy';
                }

                $data['title'] = get_meta_details('Reservation_Request', 'title');
                $data["meta_keyword"] = get_meta_details('Reservation_Request', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Reservation_Request', 'meta_description');

                $data['message_element'] = 'trips/request';
                $this->load->view('template', $data);
            }
            else
            {
                redirect('info');
            }
        }

        // Ajax Page
        public function accept()
        {
            $this->session->set_userdata('reservation_id', $this->input->post('reservation_id'));

            $this->session->set_userdata('is_block', $this->input->post('is_block'));
            $this->session->set_userdata('comment', $this->input->post('comment'));

            $this->session->set_userdata('checkin', $this->input->post('checkin'));
            $this->session->set_userdata('checkout', $this->input->post('checkout'));

            echo "trips/accept_pay";
            exit;
        }

        public function accept_pay($id)
        {
            $data['id'] = $id;

            $query = $this->Common_model->getTableData('paymode', array('id' => 3));
            $row = $query->row();

            $topay = $this->db->where('id', $id)->get('reservation')->row()->topay;

            if ($row->is_premium == 1)
            {
                if ($row->is_fixed == 1)
                {
                    $fix = $row->fixed_amount;
                    $amt = $fix;
                    $data['commission'] = $fix;
                }
                else
                {
                    $per = $row->percentage_amount;
                    $camt = floatval(($topay * $per) / 100);
                    $data['commission'] = $camt;
                }
            }
            else
            {
                redirect('trips/accept_without_pay');
            }

            $data['amt'] = $data['commission'];
            $data['full_cretids'] = 'off';

            $data['result'] = $this->Common_model->getTableData('payments')->result();

            $data['title'] = get_meta_details('Payment_Option', 'title');
            $data["meta_keyword"] = get_meta_details('Payment_Option', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Payment_Option', 'meta_description');

            $data['message_element'] = "payments/view_acceptPay";
            $this->load->view('template', $data);
        }

        public function payment($param)
        {

            $reservation_id = $param;

            $row = $this->Common_model->getTableData('payment_details', array('code' => 'PAYPAL_ID'))->row();
            $paymode = $this->db->where('payment_name', 'Paypal')->get('payments')->row()->is_live;

            $query = $this->Common_model->getTableData('paymode', array('id' => 3));
            $row = $query->row();

            $topay = $this->db->where('id', $reservation_id)->get('reservation')->row()->topay;

            if ($row->is_premium == 1)
            {
                if ($row->is_fixed == 1)
                {
                    $fix = $row->fixed_amount;
                    $amt = $fix;
                    $data['commission'] = $fix;
                }
                else
                {
                    $per = $row->percentage_amount;
                    $camt = floatval(($topay * $per) / 100);
                    $data['commission'] = $camt;
                }
            }

            $to_buy = array(
                'desc' => 'Purchase from ACME Store',
                'currency' => get_currency_code(),
                'type' => 'sale',
                'return_URL' => site_url('trips/trips_success/'),
// see below have a function for this -- function back()
// whatever you use, make sure the URL is live and can process
// the next steps
                'cancel_URL' => site_url('trips/trips_cancel'), // this goes to this controllers index()
                'shipping_amount' => 0,
                'get_shipping' => false);
// I am just iterating through $this->product from defined
// above. In a live case, you could be iterating through
// the content of your shopping cart.
//foreach($this->product as $p) {
            $temp_product = array(
                'name' => $this->dx_auth->get_site_title() . ' Transaction',
                'number' => $reservation_id,
                'quantity' => 1, // simple example -- fixed to 1
                'amount' => get_currency_value($data['commission']));

// add product to main $to_buy array
            $to_buy['products'][] = $temp_product;
//}
// enquire Paypal API for token
            $set_ec_return = $this->paypal_ec->set_ec($to_buy);

            if (isset($set_ec_return['ec_status']) && ($set_ec_return['ec_status'] === true))
            {
// redirect to Paypal
                $this->paypal_ec->redirect_to_paypal($set_ec_return['TOKEN']);
// You could detect your visitor's browser and redirect to Paypal's mobile checkout
// if they are on a mobile device. Just add a true as the last parameter. It defaults
// to false
// $this->paypal_ec->redirect_to_paypal( $set_ec_return['TOKEN'], true);
            }
            else
            {
                $this->_error($set_ec_return);
            }
        }

        public function trips_success()
        {
            $reservation_id = $this->session->userdata('reservation_id');

            $is_block = $this->session->userdata('is_block');

            $comment = $this->session->userdata('comment');

            $checkin = $this->session->userdata('checkin');

            $checkout = $this->session->userdata('checkout');

            $token = $_GET['token'];
            $payer_id = $_GET['PayerID'];
// GetExpressCheckoutDetails
            $get_ec_return = $this->paypal_ec->get_ec($token);

            if (isset($get_ec_return['ec_status']) && ($get_ec_return['ec_status'] === true))
            {

// at this point, you have all of the data for the transaction.
// you may want to save the data for future action. what's left to
// do is to collect the money -- you do that by call DoExpressCheckoutPayment
// via $this->paypal_ec->do_ec();
//
// I suggest to save all of the details of the transaction. You get all that
// in $get_ec_return array
                $ec_details = array(
                    'token' => $token,
                    'payer_id' => $payer_id,
                    'currency' => get_currency_code(),
                    'amount' => $get_ec_return['PAYMENTREQUEST_0_AMT'],
                    'IPN_URL' => site_url('payments/ipn'),
// in case you want to log the IPN, and you
// may have to in case of Pending transaction
                    'type' => 'sale');

// DoExpressCheckoutPayment
                $do_ec_return = $this->paypal_ec->do_ec($ec_details);

                if (isset($do_ec_return['ec_status']) && ($do_ec_return['ec_status'] === true))
                {
// at this point, you have collected payment from your customer
// you may want to process the order now.

                    /* echo "<h1>Thank you. We will process your order now.</h1>";
                      echo "<pre>";
                      echo "\nGetExpressCheckoutDetails Data\n" . print_r($get_ec_return, true);
                      echo "\n\nDoExpressCheckoutPayment Data\n" . print_r($do_ec_return, true);
                      echo "</pre>";exit; */

                    if (isset($do_ec_return['L_SHORTMESSAGE0']) && ($do_ec_return['L_SHORTMESSAGE0'] === 'Duplicate Request'))
                    {
                        redirect('home');
                    }

                    $admin_email = $this->dx_auth->get_site_sadmin();
                    $admin_name = $this->dx_auth->get_site_title();

                    $conditions = array('reservation.id' => $reservation_id);
                    $row = $this->Trips_model->get_reservation($conditions)->row();

                    /* $query1     						 = $this->Users_model->get_user_by_id($row->userby);
                      $traveler_name 				= $query1->row()->username;
                      $traveler_email 			= $query1->row()->email; */

                    $query1 = $this->db->where('id', $row->userby)->get('users');
                    $traveler_name = $query1->row()->username;
                    $traveler_email = $query1->row()->email;

                    //$query2     						 = $this->Users_model->get_user_by_id($row->userto);
                    $query2 = $this->db->where('id', $row->userto)->get('users');
                    $host_name = $query2->row()->username;
                    $host_email = $query2->row()->email;

                    $list_title = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;

                    $traveler = $this->Common_model->getTableData('profiles', array('id' => $row->userby))->row();
                    $host = $this->Common_model->getTableData('profiles', array('id' => $row->userto))->row();

                    //Traveller Info
                    if (!empty($traveler))
                    {
                        $FnameT = $traveler->Fname;
                        $LnameT = $traveler->Lname;
                        $liveT = $traveler->live;
                        $phnumT = $traveler->phnum;
                    }
                    else
                    {
                        $FnameT = '';
                        $LnameT = '';
                        $liveT = '';
                        $phnumT = '';
                    }

                    //Host Info
                    if (!empty($host))
                    {
                        $FnameH = $host->Fname;
                        $LnameH = $host->Lname;
                        $liveH = $host->live;
                        $phnumH = $host->phnum;
                    }
                    else
                    {
                        $FnameH = '';
                        $LnameH = '';
                        $liveH = '';
                        $phnumH = '';
                    }

                    //for calendar
                    if ($is_block == 'on')
                    {
                        $this->db->select_max('group_id');
                        $group_id = $this->db->get('calendar')->row()->group_id;

                        if (empty($group_id))
                            echo $countJ = 0;
                        else
                            $countJ = $group_id;

                        $insertData['list_id'] = $row->list_id;
                        $insertData['group_id'] = $countJ + 1;
                        $insertData['availability'] = 'Booked';
                        $insertData['booked_using'] = 'Other';

                        $checkin = date('m/d/Y', $checkin);
                        $checkout = date('m/d/Y', $checkout);
                        $days = getDaysInBetween($checkin, $checkout);

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
                            $insertData['booked_days'] = $val;
                            $this->Trips_model->insert_calendar($insertData);
                            $i++;
                        }
                    }
                    $list_data = $this->db->where('id', $row->list_id)->get('list')->row();
                    //Send Message Notification To Traveler
                    $insertData = array(
                        'list_id' => $row->list_id,
                        'reservation_id' => $reservation_id,
                        'userby' => $row->userto,
                        'userto' => $row->userby,
                        'message' => "Congratulation, Your reservation request is granted by $host_name for $list_title.",
                        'created' => local_to_gmt(),
                        'message_type' => 3
                    );
                    $this->Message_model->sentMessage($insertData, 1);

                    $referral_code_check1 = $this->db->where('id', $row->userto)->get('users');

                    if ($referral_code_check1->row()->list_referral_code)
                    {
                        $own_referral_code = $referral_code_check1->row()->list_referral_code;
                        $referral_code_check2 = $this->db->where('referral_code', $own_referral_code)->get('users');
                        if ($referral_code_check2->num_rows() != 0)
                        {
                            $insertData = array(
                                'list_id' => $row->list_id,
                                'reservation_id' => $reservation_id,
                                'userby' => $row->userto,
                                'userto' => $referral_code_check2->row()->id,
                                'message' => "Congratulation, You have earned $75 by $host_name.",
                                'created' => local_to_gmt(),
                                'message_type' => 9
                            );
                            $this->Message_model->sentMessage($insertData, 1);

                            $this->db->set('list_referral_code', '')->where('id', $referral_code_check1->row()->id)->update('users');
                            // $this->db->set('cancel_list_referral_code',$own_referral_code)->where('id',$referral_code_check1->row()->id)->update('users');

                            $amt_check = $this->db->where('id', $referral_code_check2->row()->id)->get('users');
                            if ($amt_check->row()->referral_amount)
                            {
                                $amt = 75 + $amt_check->row()->referral_amount;
                            }
                            else
                            {
                                $amt = 75;
                            }

                            $this->db->set('referral_amount', $amt)->where('id', $referral_code_check2->row()->id)->update('users');


                            $email_name = 'referral_credit';
                            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{friend_name}" => $host_name, "{user_name}" => $referral_code_check2->row()->username, '{amount}' => '$75');
                            $this->Email_model->sendMail($referral_code_check2->row()->email, $admin_email, ucfirst($admin_name), $email_name, $splVars);
                        }
                    }

                    $referral_code_check3 = $this->db->where('id', $row->userby)->get('users');

                    if ($referral_code_check3->row()->trips_referral_code)
                    {
                        $own_referral_code = $referral_code_check3->row()->trips_referral_code;
                        // echo $own_referral_code;
                        // exit;
                        $referral_code_check4 = $this->db->where('referral_code', $own_referral_code)->get('users');
                        if ($referral_code_check4->num_rows() != 0)
                        {
                            $insertData = array(
                                'list_id' => $row->list_id,
                                'reservation_id' => $reservation_id,
                                'userby' => $row->userby,
                                'userto' => $referral_code_check4->row()->id,
                                'message' => "Congratulation, You have earned $25 by " . $referral_code_check3->row()->username,
                                'created' => local_to_gmt(),
                                'message_type' => 9
                            );
                            $this->Message_model->sentMessage($insertData, 1);

                            $this->db->set('trips_referral_code', '')->where('id', $referral_code_check3->row()->id)->update('users');
                            // $this->db->set('cancel_trips_referral_code',$own_referral_code)->where('id',$referral_code_check3->row()->id)->update('users');

                            $amt_check1 = $this->db->where('id', $referral_code_check4->row()->id)->get('users');
                            if ($amt_check1->row()->referral_amount)
                            {
                                $amt1 = 25 + $amt_check1->row()->referral_amount;
                            }
                            else
                            {
                                $amt1 = 25;
                            }

                            $this->db->set('referral_amount', $amt1)->where('id', $referral_code_check4->row()->id)->update('users');

                            $email_name = 'referral_credit';
                            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{friend_name}" => $referral_code_check3->row()->username, "{username}" => $referral_code_check4->row()->username, "{amount}" => '$25');
                            $this->Email_model->sendMail($referral_code_check4->row()->email, $admin_email, ucfirst($admin_name), $email_name, $splVars);
                        }
                    }

                    $updateKey = array('id' => $reservation_id);
                    $updateData = array();
                    $updateData['status '] = 3;
                    $updateData['is_payed'] = 0;
                    $this->Trips_model->update_reservation($updateKey, $updateData);

                    if ($list_data->optional_address == '')
                    {
                        $optional_address = ' -';
                    }
                    else
                    {
                        $optional_address = $list_data->optional_address;
                    }
                    if ($list_data->state == '')
                    {
                        $state = ' -';
                    }
                    else
                    {
                        $state = $list_data->state;
                    }

                    //Send Mail To Traveller
                    $email_name = 'traveler_reservation_granted';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_name}" => $list_title, "{host_name}" => ucfirst($host_name), "{Fname}" => $FnameH, "{Lname}" => $LnameH, "{livein}" => $liveH, "{phnum}" => $phnumH, "{comment}" => $comment,
                        "{street_address}" => $list_data->street_address, "{optional_address}" => $optional_address, "{city}" => $list_data->city, "{state}" => $state, "{country}" => $list_data->country, "{zipcode}" => $list_data->zip_code);
                    $this->Email_model->sendMail($traveler_email, $host_email, ucfirst($admin_name), $email_name, $splVars);

                    //Send Mail To Host
                    $email_name = 'host_reservation_granted';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($host_name), "{Fname}" => $FnameT, "{Lname}" => $LnameT, "{livein}" => $liveT, "{phnum}" => $phnumT, "{comment}" => $comment);
                    $this->Email_model->sendMail($host_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);

                    //Send Mail To Administrator
                    $email_name = 'admin_reservation_granted';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($host_name));
                    $this->Email_model->sendMail($admin_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);

                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Your reservation request accept process successfully completed.')));
                    redirect('trips/request/' . $reservation_id);
                }
                else
                {
                    $this->_error($do_ec_return);
                }
            }
            else
            {
                $this->_error($get_ec_return);
            }
        }

        public function accept_without_pay()
        {
            $reservation_id = $this->session->userdata('reservation_id');

            $is_block = $this->session->userdata('is_block');

            $comment = $this->session->userdata('comment');

            $checkin = $this->session->userdata('checkin');

            $checkout = $this->session->userdata('checkout');

            $admin_email = $this->dx_auth->get_site_sadmin();
            $admin_name = $this->dx_auth->get_site_title();

            $conditions = array('reservation.id' => $reservation_id);
            $row = $this->Trips_model->get_reservation($conditions)->row();

            /* $query1     						 = $this->Users_model->get_user_by_id($row->userby);
              $traveler_name 				= $query1->row()->username;
              $traveler_email 			= $query1->row()->email; */

            $query1 = $this->db->where('id', $row->userby)->get('users');
            $traveler_name = $query1->row()->username;
            $traveler_email = $query1->row()->email;

            //$query2     						 = $this->Users_model->get_user_by_id($row->userto);
            $query2 = $this->db->where('id', $row->userto)->get('users');
            $host_name = $query2->row()->username;
            $host_email = $query2->row()->email;

            $list_title = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;

            $traveler = $this->Common_model->getTableData('profiles', array('id' => $row->userby))->row();
            $host = $this->Common_model->getTableData('profiles', array('id' => $row->userto))->row();

            //Traveller Info
            if (!empty($traveler))
            {
                $FnameT = $traveler->Fname;
                $LnameT = $traveler->Lname;
                $liveT = $traveler->live;
                $phnumT = $traveler->phnum;
            }
            else
            {
                $FnameT = '';
                $LnameT = '';
                $liveT = '';
                $phnumT = '';
            }

            //Host Info
            if (!empty($host))
            {
                $FnameH = $host->Fname;
                $LnameH = $host->Lname;
                $liveH = $host->live;
                $phnumH = $host->phnum;
            }
            else
            {
                $FnameH = '';
                $LnameH = '';
                $liveH = '';
                $phnumH = '';
            }

            //for calendar
            if ($is_block == 'on')
            {
                $this->db->select_max('group_id');
                $group_id = $this->db->get('calendar')->row()->group_id;

                if (empty($group_id))
                    echo $countJ = 0;
                else
                    $countJ = $group_id;

                $insertData['list_id'] = $row->list_id;
                $insertData['group_id'] = $countJ + 1;
                $insertData['availability'] = 'Booked';
                $insertData['booked_using'] = 'Other';

                $checkin = date('m/d/Y', $checkin);
                $checkout = date('m/d/Y', $checkout);
                $days = getDaysInBetween($checkin, $checkout);

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
                    $insertData['booked_days'] = $val;
                    $this->Trips_model->insert_calendar($insertData);
                    $i++;
                }
            }
            $list_data = $this->db->where('id', $row->list_id)->get('list')->row();
            //Send Message Notification To Traveler
            $insertData = array(
                'list_id' => $row->list_id,
                'reservation_id' => $reservation_id,
                'userby' => $row->userto,
                'userto' => $row->userby,
                'message' => "Congratulation, Your reservation request is granted by $host_name for $list_title.",
                'created' => local_to_gmt(),
                'message_type' => 3
            );
            $this->Message_model->sentMessage($insertData, 1);

            $referral_code_check1 = $this->db->where('id', $row->userto)->get('users');

            if ($referral_code_check1->row()->list_referral_code)
            {
                $own_referral_code = $referral_code_check1->row()->list_referral_code;
                $referral_code_check2 = $this->db->where('referral_code', $own_referral_code)->get('users');
                if ($referral_code_check2->num_rows() != 0)
                {
                    $insertData = array(
                        'list_id' => $row->list_id,
                        'reservation_id' => $reservation_id,
                        'userby' => $row->userto,
                        'userto' => $referral_code_check2->row()->id,
                        'message' => "Congratulation, You have earned $75 by $host_name.",
                        'created' => local_to_gmt(),
                        'message_type' => 9
                    );
                    $this->Message_model->sentMessage($insertData, 1);

                    $this->db->set('list_referral_code', '')->where('id', $referral_code_check1->row()->id)->update('users');
                    // $this->db->set('cancel_list_referral_code',$own_referral_code)->where('id',$referral_code_check1->row()->id)->update('users');

                    $amt_check = $this->db->where('id', $referral_code_check2->row()->id)->get('users');
                    if ($amt_check->row()->referral_amount)
                    {
                        $amt = 75 + $amt_check->row()->referral_amount;
                    }
                    else
                    {
                        $amt = 75;
                    }

                    $this->db->set('referral_amount', $amt)->where('id', $referral_code_check2->row()->id)->update('users');


                    $email_name = 'referral_credit';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{friend_name}" => $host_name, "{user_name}" => $referral_code_check2->row()->username, '{amount}' => '$75');
                    $this->Email_model->sendMail($referral_code_check2->row()->email, $admin_email, ucfirst($admin_name), $email_name, $splVars);
                }
            }

            $referral_code_check3 = $this->db->where('id', $row->userby)->get('users');

            if ($referral_code_check3->row()->trips_referral_code)
            {
                $own_referral_code = $referral_code_check3->row()->trips_referral_code;
                // echo $own_referral_code;
                // exit;
                $referral_code_check4 = $this->db->where('referral_code', $own_referral_code)->get('users');
                if ($referral_code_check4->num_rows() != 0)
                {
                    $insertData = array(
                        'list_id' => $row->list_id,
                        'reservation_id' => $reservation_id,
                        'userby' => $row->userby,
                        'userto' => $referral_code_check4->row()->id,
                        'message' => "Congratulation, You have earned $25 by " . $referral_code_check3->row()->username,
                        'created' => local_to_gmt(),
                        'message_type' => 9
                    );
                    $this->Message_model->sentMessage($insertData, 1);

                    $this->db->set('trips_referral_code', '')->where('id', $referral_code_check3->row()->id)->update('users');
                    // $this->db->set('cancel_trips_referral_code',$own_referral_code)->where('id',$referral_code_check3->row()->id)->update('users');

                    $amt_check1 = $this->db->where('id', $referral_code_check4->row()->id)->get('users');
                    if ($amt_check1->row()->referral_amount)
                    {
                        $amt1 = 25 + $amt_check1->row()->referral_amount;
                    }
                    else
                    {
                        $amt1 = 25;
                    }

                    $this->db->set('referral_amount', $amt1)->where('id', $referral_code_check4->row()->id)->update('users');

                    $email_name = 'referral_credit';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{friend_name}" => $referral_code_check3->row()->username, "{username}" => $referral_code_check4->row()->username, "{amount}" => '$25');
                    $this->Email_model->sendMail($referral_code_check4->row()->email, $admin_email, ucfirst($admin_name), $email_name, $splVars);
                }
            }

            $updateKey = array('id' => $reservation_id);
            $updateData = array();
            $updateData['status '] = 3;
            $updateData['is_payed'] = 0;
            $this->Trips_model->update_reservation($updateKey, $updateData);

            if ($list_data->optional_address == '')
            {
                $optional_address = ' -';
            }
            else
            {
                $optional_address = $list_data->optional_address;
            }
            if ($list_data->state == '')
            {
                $state = ' -';
            }
            else
            {
                $state = $list_data->state;
            }

            //Send Mail To Traveller
            $email_name = 'traveler_reservation_granted';
            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_name}" => $list_title, "{host_name}" => ucfirst($host_name), "{Fname}" => $FnameH, "{Lname}" => $LnameH, "{livein}" => $liveH, "{phnum}" => $phnumH, "{comment}" => $comment,
                "{street_address}" => $list_data->street_address, "{optional_address}" => $optional_address, "{city}" => $list_data->city, "{state}" => $state, "{country}" => $list_data->country, "{zipcode}" => $list_data->zip_code);
            $this->Email_model->sendMail($traveler_email, $host_email, ucfirst($admin_name), $email_name, $splVars);

            //Send Mail To Host
            $email_name = 'host_reservation_granted';
            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($host_name), "{Fname}" => $FnameT, "{Lname}" => $LnameT, "{livein}" => $liveT, "{phnum}" => $phnumT, "{comment}" => $comment);
            $this->Email_model->sendMail($host_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);

            //Send Mail To Administrator
            $email_name = 'admin_reservation_granted';
            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($host_name));
            $this->Email_model->sendMail($admin_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);

            redirect('trips/request/' . $reservation_id);
        }

        public function decline()
        {
            $reservation_id = $this->input->post('reservation_id');
            $is_block = $this->input->post('is_block');
            $comment = $this->input->post('comment');

            $checkin = $this->input->post('checkin');
            $checkout = $this->input->post('checkout');

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

            //for calendar
            //if($is_block == 'on')
            //{
            $this->db->select_max('group_id');
            $group_id = $this->db->get('calendar')->row()->group_id;

            if (empty($group_id))
                echo $countJ = 0;
            else
                $countJ = $group_id;

            $insertData['list_id'] = $row->list_id;
            $insertData['group_id'] = $countJ + 1;
            $insertData['availability'] = 'Available';
            $insertData['booked_using'] = 'Other';

            $checkin = date('m/d/Y', $checkin);
            $checkout = date('m/d/Y', $checkout);
            $days = getDaysInBetween($checkin, $checkout);

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

                $insertData['booked_days'] = $val;
                $this->Trips_model->insert_calendar($insertData);
                $i++;
            }
            $this->db->where('list_id', $row->list_id)->where('availability', 'Available')->delete('calendar');
            $query = $this->db->get('calendar');
            $row1 = $query->last_row();
            if ($row1->availability == 'Not Available')
            {
                $this->db->where('group_id', $row1->group_id)->delete('calendar');
            }
            //}
            //Send Message Notification To Traveller
            $insertData = array(
                'list_id' => $row->list_id,
                'reservation_id' => $reservation_id,
                'userby' => $row->userto,
                'userto' => $row->userby,
                'message' => "Sorry, Your reservation request is declined by $host_name for $list_title.",
                'created' => local_to_gmt(),
                'message_type' => 3
            );
            $this->Message_model->sentMessage($insertData, 1);
            $message_id = $this->db->insert_id();


            $updateKey = array('id' => $reservation_id);
            $updateData = array();
            $updateData['status '] = 4;
            $this->Trips_model->update_reservation($updateKey, $updateData);

            //Send Mail To Traveller
            $email_name = 'traveler_reservation_declined';
            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($host_name), "{comment}" => $comment);
            $this->Email_model->sendMail($traveler_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);

            //Send Mail To Host
            $email_name = 'host_reservation_declined';
            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($host_name), "{comment}" => $comment);
            $this->Email_model->sendMail($host_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);

            //Send Mail To Administrator
            $email_name = 'admin_reservation_declined';
            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title, "{host_name}" => ucfirst($host_name));
            $this->Email_model->sendMail($admin_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);
        }

        public function expire()
        {
            $admin_email = $this->dx_auth->get_site_sadmin();
            $admin_name = $this->dx_auth->get_site_title();

            $reservation_id = $this->input->post('reservation_id');

            $conditions = array('reservation.id' => $reservation_id);
            $row = $this->Trips_model->get_reservation($conditions)->row();

            $query1 = $this->users->get_user_by_id($row->userby);
            $traveler_name = $query1->row()->username;
            $traveler_email = $query1->row()->email;

            $query2 = $this->users->get_user_by_id($row->userto);
            $host_name = $query2->row()->username;
            $host_email = $query2->row()->email;

            $list_title = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;


            //Send Message Notification
            $insertData = array(
                'list_id' => $row->list_id,
                'reservation_id' => $reservation_id,
                'userby' => $row->userto,
                'userto' => $row->userby,
                'message' => "Sorry, Your reservation request is expired by $host_name for $list_title.",
                'created' => local_to_gmt(),
                'message_type ' => 2
            );


            $this->Message_model->sentMessage($insertData);
            $message_id = $this->db->insert_id();

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

        public function conversation($param = '')
        {
            $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');
            if ($param == 0)
            {
                redirect('info');
            }
            if ($param == '')
            {
                redirect('info');
            }
            $check = $this->db->where('conversation_id', $param)->get('messages');
            if ($check->num_rows() == 0)
            {
                redirect('info');
            }
            if ($this->input->post())
            {
                $this->form_validation->set_rules('comment', 'Message', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {

                    if ($this->input->post('reservation_id') != 0)
                    {
                        $insertData = array(
                            'list_id' => $this->input->post('list_id'),
                            'reservation_id' => $this->input->post('reservation_id'),
                            'userby' => $this->input->post('userby'),
                            'userto' => $this->input->post('userto'),
                            'message' => $this->input->post('comment'),
                            'created' => local_to_gmt(),
                            'message_type ' => 3
                        );
                    }
                    else
                    {
                        $insertData = array(
                            'list_id' => $this->input->post('list_id'),
                            'contact_id' => $this->input->post('contact_id'),
                            'userby' => $this->input->post('userby'),
                            'userto' => $this->input->post('userto'),
                            'message' => $this->input->post('comment'),
                            'created' => local_to_gmt(),
                            'message_type ' => 3
                        );
                    }
                    $this->Message_model->sentMessage($insertData, 1);
                    redirect('trips/conversation/' . $param);
                }
            }



            $data['conversation_id'] = $param;
            $conditions = array("messages.conversation_id" => $param, "messages.userby" => $this->dx_auth->get_user_id());
            $or_where = array("messages.userto" => $this->dx_auth->get_user_id());

            $query = $this->Message_model->get_messages($conditions, $or_where);

            if ($query->num_rows() == 0)
            {
                redirect('info');
            }

            $condition = array("messages.conversation_id" => $param);
            $orderby = array('id', "DESC");
            $result = $data['messages'] = $this->Message_model->get_messages($condition, NULL, $orderby);
            $row = $result->row();


            if ($row->userby == $this->dx_auth->get_user_id())
            {
                $coversation_userID = $row->userto;
            }
            else
            {
                $coversation_userID = $row->userby;
            }
            $contact_id = $this->db->where('conversation_id', $param)->order_by("id", "desc")->limit(1)->get('messages')->row()->contact_id;
            $data['list_id'] = $row->list_id;
            $data['reservation_id'] = $row->reservation_id;
            $data['contact_id'] = $contact_id;

            $data['conv_userData'] = get_user_by_id($coversation_userID);

            $data['title'] = get_meta_details('Conversations', 'title');
            $data["meta_keyword"] = get_meta_details('Conversations', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Conversations', 'meta_description');


            $data['message_element'] = 'trips/view_conversation';
            $this->load->view('template', $data);
        }

        public function send_message($param = '')
        {
            if ($param == '')
            {
                redirect('info');
            }

            $userby = $this->dx_auth->get_user_id();
            $userto = $param;
            $query = $this->db->query("SELECT MAX(`conversation_id`) as conversation_id FROM `messages` WHERE (`userby` = '" . $userby . "' AND `userto` ='" . $userto . "') OR (`userby` = '" . $userto . "' AND `userto` ='" . $userby . "')");
            $row = $query->row();

            $conversation_id = $row->conversation_id;
            redirect('trips/conversation/' . $conversation_id);
        }

        public function review_by_host($param = '')
        {
            if ($this->input->post())
            {
                $reservation_id = $this->input->post('reservation_id');
                $review = $this->input->post('review');
                $feedback = $this->input->post('feedback');
                $cleanliness = $this->input->post('cleanliness');
                $communication = $this->input->post('communication');
                $house_rules = $this->input->post('house_rules');

                $updateKey = array('id' => $reservation_id);
                $updateData = array();
                $updateData['status '] = 9;
                $this->Trips_model->update_reservation($updateKey, $updateData);

                $conditions = array('reservation.id' => $reservation_id);
                $row = $this->Trips_model->get_reservation($conditions)->row();

                $username = ucfirst($this->dx_auth->get_username());

                $insertData = array(
                    'list_id' => $row->list_id,
                    'reservation_id' => $reservation_id,
                    'userby' => $row->userto,
                    'userto' => $row->userby,
                    'message' => "$username wants the review from you.",
                    'created' => local_to_gmt(),
                    'message_type ' => 5
                );

                $this->Message_model->sentMessage($insertData);


                $insertDataR = array(
                    'userby' => $row->userto,
                    'userto' => $row->userby,
                    'list_id' => $row->list_id,
                    'reservation_id' => $reservation_id,
                    'review' => $review,
                    'feedback' => $feedback,
                    'cleanliness' => $cleanliness,
                    'communication' => $communication,
                    'house_rules' => $house_rules,
                    'created' => local_to_gmt()
                );

                $this->Trips_model->insertReview($insertDataR);

                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Your review saved successfully.')));
                redirect('hosting/my_reservation');
            }
            else
            {
                if (isset($param))
                {
                    $reservation_id = $param;

                    $conditions = array('reservation.id' => $reservation_id, 'reservation.userto' => $this->dx_auth->get_user_id());
                    $result = $this->Trips_model->get_reservation($conditions);

                    if ($result->num_rows() == 0)
                    {
                        //redirect('info');
                    }

                    if ($result->row()->status != 8)
                    {
                        redirect('trips/host_review/' . $reservation_id);
                    }

                    $data['reservation_id'] = $param;

                    $data['title'] = get_meta_details('Review', 'title');
                    $data["meta_keyword"] = get_meta_details('Review', 'meta_keyword');
                    $data["meta_description"] = get_meta_details('Review', 'meta_description');

                    $data['message_element'] = 'trips/view_review_host';
                    $this->load->view('template', $data);
                }
                else
                {
                    redirect('info');
                }
            }
        }

        public function review_by_traveller($param = '')
        {
            if ($this->input->post())
            {
                $reservation_id = $this->input->post('reservation_id');
                $review = $this->input->post('review');
                $feedback = $this->input->post('feedback');
                $cleanliness = $this->input->post('cleanliness');
                $communication = $this->input->post('communication');
                $accuracy = $this->input->post('accuracy');
                $checkin = $this->input->post('checkin');
                $location = $this->input->post('location');
                $value = $this->input->post('value');

                $updateKey = array('id' => $reservation_id);
                $updateData = array();
                $updateData['status '] = 10;
                $this->Trips_model->update_reservation($updateKey, $updateData);

                $conditions = array('reservation.id' => $reservation_id);
                $row = $this->Trips_model->get_reservation($conditions)->row();

                $username = ucfirst($this->dx_auth->get_username());

                $insertData = array(
                    'list_id' => $row->list_id,
                    'reservation_id' => $reservation_id,
                    'userby' => $row->userto,
                    'userto' => $row->userby,
                    'message' => "$username gives the reviews for you.",
                    'created' => local_to_gmt(),
                    'message_type ' => 4
                );

                //$this->Message_model->sentMessage($insertData);


                $insertDataR = array(
                    'userby' => $row->userby,
                    'userto' => $row->userto,
                    'list_id' => $row->list_id,
                    'reservation_id' => $reservation_id,
                    'review' => $review,
                    'feedback' => $feedback,
                    'cleanliness' => $cleanliness,
                    'communication' => $communication,
                    'accuracy' => $accuracy,
                    'checkin' => $checkin,
                    'location' => $location,
                    'value' => $value,
                    'created' => local_to_gmt()
                );

                $this->Trips_model->insertReview($insertDataR);

                //Update The Review Cout
                $no_review = get_list_by_id($row->list_id)->review;
                $update_r = $no_review + 1;
                $data = array('review' => $update_r);

                $this->db->where('id', $row->list_id);
                $this->db->update('list', $data);

                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Your review saved successfully.')));
                redirect('hosting/my_reservation');
            }
            else
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

                    if ($result->row()->status != 9)
                    {
                        redirect('trips/traveler_review/' . $reservation_id);
                    }

                    $data['reservation_id'] = $param;

                    $data['title'] = get_meta_details('Review', 'title');
                    $data["meta_keyword"] = get_meta_details('Review', 'meta_keyword');
                    $data["meta_description"] = get_meta_details('Review', 'meta_description');

                    $data['message_element'] = 'trips/view_review_traveller';
                    $this->load->view('template', $data);
                }
                else
                {
                    redirect('info');
                }
            }
        }

        public function host_review($param = '')
        {
            if (isset($param))
            {
                $reservation_id = $param;

                $conditions = array('reservation.id' => $reservation_id, 'reservation.userto' => $this->dx_auth->get_user_id());
                $result = $this->Trips_model->get_reservation($conditions);

                if ($result->num_rows() == 0)
                {
                    //redirect('info');
                }

                $conditions = array('reservation_id' => $reservation_id, 'userby' => $this->dx_auth->get_user_id());
                $result = $this->Trips_model->get_review($conditions);
                $data['result'] = $result->row();

                $data['title'] = get_meta_details('View_Your_Review', 'title');
                $data["meta_keyword"] = get_meta_details('View_Your_Review', 'meta_keyword');
                $data["meta_description"] = get_meta_details('View_Your_Review', 'meta_description');

                $data['message_element'] = 'trips/view_host_review';
                $this->load->view('template', $data);
            }
            else
            {
                redirect('info');
            }
        }

        public function traveler_review($param = '')
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

                $conditions = array('reservation_id' => $reservation_id, 'userby' => $this->dx_auth->get_user_id());
                $result = $this->Trips_model->get_review($conditions);
                $data['result'] = $result->row();

                $data['title'] = get_meta_details('View_your_review', 'title');
                $data["meta_keyword"] = get_meta_details('View_your_review', 'meta_keyword');
                $data["meta_description"] = get_meta_details('View_your_review', 'meta_description');

                $data['message_element'] = 'trips/view_traveler_review';
                $this->load->view('template', $data);
            }
            else
            {
                redirect('info');
            }
        }

    }

    /* End of file trips.php */
    /* Location: ./app/controllers/trips.php */
?>
