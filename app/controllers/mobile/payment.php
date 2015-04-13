<?php

    /**
     * DROPinn Payment Controller Class
     *
     * helps to achieve common tasks related to the site for mobile app like android and iphone.
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Payment
     * @author		Cogzidel Product Team
     * @version		Version 1.0
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Payment extends CI_Controller
    {

        public function Payment()
        {
            parent::__construct();

            $this->load->helper('url');
            $this->load->library('Paypal_Lib');
            $this->load->library('Twoco_Lib');
            $this->load->library('email');
            $this->load->library('DX_Auth');
            $this->load->library('Paypal_Lib');

            $this->load->model('Users_model');
            $this->load->model('Gallery');
            $this->load->model('Contacts_model');
            $this->load->model('Trips_model');
            $this->load->model('Referrals_model');
            $this->load->model('Email_model');
            $this->load->model('Message_model');
        }

        public function index()
        {
            
        }

        function pay()
        {
            if ((!$this->input->get('list_id')) || (!$this->input->get('status')))
            {
                echo '[{"status":"Required All Fields"}]';
            }
            else
            {

                $list_id = $this->input->get('list_id');
                $list['list_id'] = $list_id;
                $token = $_GET["token"];
                $playerid = $_GET["PayerID"];
                $status = $this->input->get('status');
                //$httpParsedResponseAr = $this->PPHttpPost('GetExpressCheckoutDetails', $padata, $api_user, $api_pwd, $api_key, $PayPalMode);
// print_r($httpParsedResponseAr['ACK']);exit;
                if ($status == 'success')
                //if($_REQUEST['payment_status'] == 'Completed')
                {

                    //print_r($_REQUEST['ItemName']);exit;
                    //echo "<script> alert(''success');</script>";
                    $custom = $this->session->userdata('custom');
                    //print_r($custom);
                    //exit;
                    $data = array();
                    $list = array();
                    $data = explode('@', $custom);

                    $contact_key = $data[9];

                    $list['list_id'] = $data[0];
                    $list['userby'] = $data[1];

                    $query1 = $this->Common_model->getTableData('list', array('id' => $list['list_id']));
                    $buyer_id = $query1->row()->user_id;

                    $list['userto'] = $buyer_id;
                    $list['checkin'] = $data[2];
                    $list['checkout'] = $data[3];
                    $list['no_quest'] = $data[4];

                    $amt = explode('%', $httpParsedResponseAr['AMT']);

                    $list['price'] = $amt[0];
                    $currency = $httpParsedResponseAr['CURRENCYCODE'];

                    $list['payment_id'] = 2;
                    $list['credit_type'] = 1;
                    $list['transaction_id'] = 0;

                    $is_travelCretids = $data[5];
                    $user_travel_cretids = $data[6];

                    $list['topay'] = get_currency_value2($currency, $query1->row()->currency, $data[7]);
                    $list['currency'] = $query1->row()->currency;
                    $list['admin_commission'] = $data[8];

                    //Entering into it



                    $list['status'] = 3;
                    $this->db->select_max('group_id');
                    $group_id = $this->db->get('calendar')->row()->group_id;

                    if (empty($group_id))
                        echo $countJ = 0;
                    else
                        $countJ = $group_id;

                    $insertData['list_id'] = $list['list_id'];
                    $insertData['group_id'] = $countJ + 1;
                    $insertData['availability'] = 'Booked';
                    $insertData['booked_using'] = 'Other';

                    $checkin = date('m/d/Y', $list['checkin']);
                    $checkout = date('m/d/Y', $list['checkout']);

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

                    if ($list['price'] > 75)
                    {
                        $user_id = $list['userby'];
                        $details = $this->Referrals_model->get_details_by_Iid($user_id);
                        $row = $details->row();
                        $count = $details->num_rows();
                        if ($count > 0)
                        {
                            $details1 = $this->Referrals_model->get_details_refamount($row->invite_from);
                            if ($details1->num_rows() == 0)
                            {
                                $insertData = array();
                                $insertData['user_id'] = $row->invite_from;
                                $insertData['count_trip'] = 1;
                                $insertData['amount'] = 25;
                                $this->Referrals_model->insertReferralsAmount($insertData);
                            }
                            else
                            {
                                $count_trip = $details1->row()->count_trip;
                                $amount = $details1->row()->amount;
                                $updateKey = array('id' => $row->id);
                                $updateData = array();
                                $updateData['count_trip'] = $count_trip + 1;
                                $updateData['amount'] = $amount + 25;
                                $this->Referrals_model->updateReferralsAmount($updateKey, $updateData);
                            }
                        }
                    }

                    $q = $query1->result();
                    $row_list = $query1->row();
                    $iUser_id = $q[0]->user_id;
                    $details2 = $this->Referrals_model->get_details_by_Iid($iUser_id);
                    $row = $details2->row();
                    $count = $details2->num_rows();
                    if ($count > 0)
                    {
                        $details3 = $this->Referrals_model->get_details_refamount($row->invite_from);
                        if ($details3->num_rows() == 0)
                        {
                            $insertData = array();
                            $insertData['user_id'] = $row->invite_from;
                            $insertData['count_book'] = 1;
                            $insertData['amount'] = 75;
                            $this->Referrals_model->insertReferralsAmount($insertData);
                        }
                        else
                        {
                            $count_book = $details3->row()->count_book;
                            $amount = $details3->row()->amount;
                            $updateKey = array('id' => $row->id);
                            $updateData = array();
                            $updateData['count_trip'] = $count_book + 1;
                            $updateData['amount'] = $amount + 75;
                            $this->Referrals_model->updateReferralsAmount($updateKey, $updateData);
                        }
                    }

                    $admin_email = $this->dx_auth->get_site_sadmin();
                    $admin_name = $this->dx_auth->get_site_title();

                    $query3 = $this->Common_model->getTableData('users', array('id' => $list['userby']));
                    $rows = $query3->row();

                    $username = $rows->username;
                    $user_id = $rows->id;
                    $email_id = $rows->email;

                    $query4 = $this->Users_model->get_user_by_id($buyer_id);
                    $buyer_name = $query4->row()->username;
                    $buyer_email = $query4->row()->email;

                    //Check md5('No Travel Cretids') || md5('Yes Travel Cretids')
                    if ($is_travelCretids == '7c4f08a53f4454ea2a9fdd94ad0c2eeb')
                    {
                        $query5 = $this->Referrals_model->get_details_refamount($user_id);
                        $amount = $query5->row()->amount;

                        $updateKey = array('user_id ' => $user_id);
                        $updateData = array();
                        $updateData['amount'] = $amount - $user_travel_cretids;
                        $this->Referrals_model->updateReferralsAmount($updateKey, $updateData);

                        $list['credit_type'] = 2;
                        $list['ref_amount'] = $user_travel_cretids;


                        $row = $query4->row();

                        //sent mail to administrator
                        $email_name = 'tc_book_to_admin';
                        $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => date('d-m-Y', $list['checkin']), "{checkout}" => date('d-m-Y', $list['checkout']), "{market_price}" => $user_travel_cretids + $list['price'], "{payed_amount}" => $list['price'], "{travel_credits}" => $user_travel_cretids, "{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
                        //Send Mail
                        $this->Email_model->sendMail($admin_email, $email_id, ucfirst($username), $email_name, $splVars);


                        //sent mail to buyer
                        $email_name = 'tc_book_to_host';
                        $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => date('d-m-Y', $list['checkin']), "{checkout}" => date('d-m-Y', $list['checkout']), "{market_price}" => $list['price']);
                        //Send Mail
                        if ($buyer_email != '0')
                        {
                            $this->Email_model->sendMail($buyer_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);
                        }
                    }

                    $list['book_date'] = local_to_gmt();

                    //Actual insertion into the database
                    $this->Common_model->insertData('reservation', $list);
                    $reservation_id = $this->db->insert_id();

                    //Send Message Notification
                    $insertData = array(
                        'list_id' => $list['list_id'],
                        'reservation_id' => $reservation_id,
                        'userby' => $list['userby'],
                        'userto' => $list['userto'],
                        'message' => 'You have a new reservation request from ' . ucfirst($username),
                        'created' => local_to_gmt(),
                        'message_type' => 1
                    );
                    $this->Message_model->sentMessage($insertData, ucfirst($buyer_name), ucfirst($username), $row_list->title, $reservation_id);
                    $message_id = $this->db->insert_id();

                    $actionurl = site_url('trips/request/' . $reservation_id);

                    //Reservation Notification To Host
                    $email_name = 'host_reservation_notification';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => date('d-m-Y', $list['checkin']), "{checkout}" => date('d-m-Y', $list['checkout']), "{market_price}" => $list['price'], "{action_url}" => $actionurl);
                    //Send Mail
                    //
			if ($buyer_email != '0')
                    {
                        $this->Email_model->sendMail($buyer_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);
                    }
                    //Reservation Notification To Traveller
                    $email_name = 'traveller_reservation_notification';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username));
                    //Send Mail
                    $this->Email_model->sendMail($email_id, $admin_email, ucfirst($admin_name), $email_name, $splVars);

                    //Reservation Notification To Administrator
                    $email_name = 'admin_reservation_notification';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => date('d-m-Y', $list['checkin']), "{checkout}" => date('d-m-Y', $list['checkout']), "{market_price}" => $user_travel_cretids + $list['price'], "{payed_amount}" => $list['price'], "{travel_credits}" => $user_travel_cretids, "{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
                    //Send Mail
                    $this->Email_model->sendMail($admin_email, $email_id, ucfirst($username), $email_name, $splVars);

                    //	if($is_block == 'on')
                    //	{
                    $this->db->select_max('group_id');
                    $group_id = $this->db->get('calendar')->row()->group_id;

                    if (empty($group_id))
                        echo $countJ = 0;
                    else
                        $countJ = $group_id;

                    $insertData1['list_id'] = $list['list_id'];
                    //$insertData['reservation_id'] = $reservation_id;
                    $insertData1['group_id'] = $countJ + 1;
                    $insertData1['availability'] = 'Not Available';
                    $insertData1['booked_using'] = 'Other';

                    $checkin = date('m/d/Y', $list['checkin']);
                    $checkout = date('m/d/Y', $list['checkout']);
                    $days = getDaysInBetween($checkin, $checkout);

                    $count = count($days);
                    $i = 1;
                    foreach ($days as $val)
                    {
                        if ($count == 1)
                        {
                            $insertData1['style'] = 'single';
                        }
                        else if ($count > 1)
                        {
                            if ($i == 1)
                            {
                                $insertData1['style'] = 'left';
                            }
                            else if ($count == $i)
                            {
                                $insertData1['notes'] = '';
                                $insertData1['style'] = 'right';
                            }
                            else
                            {
                                $insertData1['notes'] = '';
                                $insertData1['style'] = 'both';
                            }
                        }
                        $insertData1['booked_days'] = $val;
                        $this->Trips_model->insert_calendar($insertData1);
                        $i++;
                    }
                }


                $referral_amount = $this->db->where('id', $this->dx_auth->get_user_id())->get('users')->row()->referral_amount;
                if ($referral_amount > 100)
                {
                    $this->db->set('referral_amount', $referral_amount - 100)->where('id', $this->dx_auth->get_user_id())->update('users');
                }
                else
                {
                    $this->db->set('referral_amount', 0)->where('id', $this->dx_auth->get_user_id())->update('users');
                }

                echo '[{"status":"SUCCESS updated"}]';
            }
        }

        function form()
        {

            $id = $this->input->get('id');
            $checkin = $this->input->get('checkin');
            $checkout = $this->input->get('checkout');
            $data['guests'] = $this->input->get('guest');


            $param = $id;
            $data['checkin'] = $checkin;
            $data['checkout'] = $checkout;

            $ckin = explode('/', $checkin);
            $ckout = explode('/', $checkout);
            $pay = $this->Common_model->getTableData('paywhom', array('id' => 1));
            $paywhom = $pay->result();
            $paywhom = $paywhom[0]->whom;
            $id = $param;

            if ($ckin[0] == "mm")
            {
                //$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Sorry! Access denied.'));
                redirect('rooms/' . $id, "refresh");
            }
            if ($ckout[0] == "mm")
            {
                //	$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Sorry! Access denied.'));
                redirect('rooms/' . $id, "refresh");
            }


            $xprice = $this->Common_model->getTableData('price', array('id' => $param))->row();

            /* if($this->input->get())
              {
              $price = $this->input->get('subtotal');
              }
              else { */
            $price = $xprice->night;
            //}
            $placeid = $xprice->id;

            $guests = $xprice->guests;

            if (isset($xprice->cleaning))
                $cleaning = $xprice->cleaning;
            else
                $cleaning = 0;

            if (isset($xprice->week))
                $Wprice = $xprice->week;
            else
                $Wprice = 0;

            if (isset($xprice->month))
                $Mprice = $xprice->month;
            else
                $Mprice = 0;


            if ($paywhom)
            {
                $query = $this->Common_model->getTableData('list', array('id' => $id))->row();
                $email = $query->email;
            }
            else
            {
                $query = $this->Common_model->getTableData('users', array('role_id' => 2))->row();
                $email = $query->email;
            }

            $query = $this->Common_model->getTableData('list', array('id' => $id));
            $list = $query->row();
            $data['address'] = $list->address;
            $data['room_type'] = $list->room_type;
            $data['total_guests'] = $list->capacity;
            $data['tit'] = $list->title;
            $data['manual'] = $list->manual;


            $diff = strtotime($ckout[2] . '-' . $ckout[0] . '-' . $ckout[1]) - strtotime($ckin[2] . '-' . $ckin[0] . '-' . $ckin[1]);
            $days = ceil($diff / (3600 * 24));

            /* $amt = $price * $days * $data['guests']; */
            if ($data['guests'] > $guests)
            {
                $diff_days = $data['guests'] - $guests;
                $amt = ($price * $days) + ($days * $xprice->addguests * $diff_days);
            }
            else
            {
                $amt = $price * $days;
            }


            //Entering it into data variables
            $data['id'] = $id;
            $data['price'] = $xprice->night;
            $data['days'] = $days;
            $data['full_cretids'] = 'off';

            $data['commission'] = 0;

            if ($days >= 7 && $days < 30)
            {
                if (!empty($Wprice))
                {
                    $finalAmount = $Wprice;
                    $differNights = $days - 7;
                    $perDay = $Wprice / 7;
                    $per_night = $price = round($perDay, 2);
                    if ($differNights > 0)
                    {
                        $addAmount = $differNights * $per_night;
                        $finalAmount = $Wprice + $addAmount;
                    }
                    $amt = $finalAmount;
                }
            }


            if ($days >= 30)
            {
                if (!empty($Mprice))
                {
                    $finalAmount = $Mprice;
                    $differNights = $days - 30;
                    $perDay = $Mprice / 30;
                    $per_night = $price = round($perDay, 2);
                    if ($differNights > 0)
                    {
                        $addAmount = $differNights * $per_night;
                        $finalAmount = $Mprice + $addAmount;
                    }
                    $amt = $finalAmount;
                }
            }

            //Update the daily price
            $data['price'] = $xprice->night;

            //Cleaning fee
            if ($cleaning != 0)
            {
                $amt = $amt + $cleaning;
            }
            else
            {
                $amt = $amt;
            }
            $session_coupon = $this->session->userdata("coupon");
            if ($this->input->get('contact'))
            {
                $amt = $contact_result->price;
                $this->session->set_userdata("total_price_'" . $id . "'_'" . $this->dx_auth->get_user_id() . "'", $amt);
            }
            else
            {
                //$amt=$this->session->userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'");
            }

            //Coupon Starts
            if ($this->input->post('apply_coupon'))
            {
                $is_coupon = 0;
                //Get All coupons
                $query = $this->Common_model->get_coupon();
                $row = $query->result_array();

                $list_id = $this->input->post('hosting_id');
                $coupon_code = $this->input->post('coupon_code');
                $user_id = $this->dx_auth->get_user_id();

                if ($coupon_code != "")
                {
                    $is_list_already = $this->Common_model->getTableData('coupon_users', array('list_id' => $list_id, 'user_id' => $user_id));
                    $is_coupon_already = $this->Common_model->getTableData('coupon_users', array('used_coupon_code' => $coupon_code, 'user_id' => $user_id));
                    //Check the list is already access with the coupon by the host or not
                    /* if($is_list_already->num_rows() != 0)
                      {
                      $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Sorry! You cannot use coupons for this list'));
                      redirect('rooms/'.$list_id, "refresh");
                      }
                      //Check the host already used the coupon or not
                      else */ if ($is_coupon_already->num_rows() != 0)
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Sorry! Your coupon is invalid')));
                        redirect('rooms/' . $list_id, "refresh");
                    }
                    else
                    {
                        //Coupon Discount calculation	
                        foreach ($row as $code)
                        {
                            if ($coupon_code == $code['couponcode'])
                            {
                                //Currecy coversion
                                $is_coupon = 1;
                                $current_currency = get_currency_code();
                                $coupon_currency = $code['currency'];
                                if ($current_currency == $coupon_currency)
                                    $Coupon_amt = $code['coupon_price'];
                                else
                                    $Coupon_amt = get_currency_value_coupon($code['coupon_price'], $coupon_currency);
                            }
                        }
                        if ($is_coupon == 1)
                        {
                            if ($Coupon_amt >= get_currency_value1($list_id, $amt))
                            {
                                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Sorry! There is equal money or more money in your coupon to book this list.')));
                                redirect('rooms/' . $list_id, "refresh");
                            }
                            else
                            {
                                //Get the result amount & store the coupon informations		
                                $amt = $amt - $Coupon_amt;
                                $insertData = array(
                                    'list_id' => $list_id,
                                    'used_coupon_code' => $coupon_code,
                                    'user_id' => $user_id,
                                    'status' => 0
                                );
                                $this->Common_model->inserTableData('coupon_users', $insertData);
                                $this->db->where('couponcode', $coupon_code)->update('coupon', array('status' => 1));
                                $this->session->set_userdata("total_price_'" . $list_id . "'_'" . $user_id . "'", $amt);
                            }
                        }
                        else
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Sorry! Your coupon does not match.')));
                            redirect('rooms/' . $list_id, "refresh");
                        }
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Sorry! Your coupon does not match.')));
                    redirect('rooms/' . $list_id, "refresh");
                }
            }
            //Coupon Ends



            $data['subtotal'] = $amt;

            //if($this->session->userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'") == "")
            //{ echo 'total';exit;
            //redirect('rooms/'.$param, "refresh");
            //	$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Please! Try Again'));
            //}
            //check admin premium condition and apply so for
            $query = $this->Common_model->getTableData('paymode', array('id' => 2));
            $row = $query->row();
            if ($row->is_premium == 1)
            {
                if ($row->is_fixed == 1)
                {
                    $fix = $row->fixed_amount;
                    $amt = $amt + $fix;
                    $data['commission'] = $fix;
                }
                else
                {
                    $per = $row->percentage_amount;
                    $camt = floatval(($amt * $per) / 100);
                    $amt = $amt + $camt;
                    $data['commission'] = $camt;
                }
            }
            else
            {
                $amt = $amt;
            }

            // Coupon Code Starts
            //print_r($amt);exit;
            if ($amt > 110)
            {
                $da = array();
                $this->db->select('*');
                $this->db->where('id', $this->dx_auth->get_user_id());
                $this->db->from('users');
                $value = $this->db->get()->result();
                foreach ($value as $val)
                {
                    $da = $val->referral_amount;
                }

                if ($da != 0)
                {
                    $data['amt'] = $amt;
                    $data['referral_amount'] = $da;
                }
                else
                {
                    $data['amt'] = $amt;
                }
            }
            else
            {
                $data['amt'] = $amt;
            }

            if ($amt < 0)
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Sorry! Your payment should be greater than 0.')));
                redirect('rooms/' . $id, "refresh");
            }

            $dat['result'] = $this->Common_model->getTableData('payments')->result();

            $array_items = array(
                'list_id' => '',
                'Lcheckin' => '',
                'Lcheckout' => '',
                'number_of_guests' => '',
                'formCheckout' => ''
            );
            $this->session->unset_userdata($array_items);

            //$id = $list_id;
            $checkin_time = get_gmt_time(strtotime($checkin));
            $checkout_time = get_gmt_time(strtotime($checkout));
            $travel_dates = array();
            $seasonal_prices = array();
            $total_nights = 1;
            $total_price = 0;
            $is_seasonal = 0;
            $i = $checkin_time;
            while ($i < $checkout_time)
            {
                $checkin_date = date('m/d/Y', $i);
                $checkin_date = explode('/', $checkin_date);
                $travel_dates[$total_nights] = $checkin_date[1] . $checkin_date[0] . $checkin_date[2];
                $i = get_gmt_time(strtotime('+1 day', $i));
                $total_nights++;
            }
            for ($i = 1; $i < $total_nights; $i++)
            {
                $seasonal_prices[$travel_dates[$i]] = "";
            }
            //Store seasonal price of a list in an array
            $seasonal_query = $this->Common_model->getTableData('seasonalprice', array('list_id' => $id));
            $seasonal_result = $seasonal_query->result_array();
            if ($seasonal_query->num_rows() > 0)
            {
                foreach ($seasonal_result as $time)
                {

                    //Get Seasonal price
                    $seasonalprice_query = $this->Common_model->getTableData('seasonalprice', array('list_id' => $id, 'start_date' => $time['start_date'], 'end_date' => $time['end_date']));
                    $seasonalprice = $seasonalprice_query->row()->price;
                    //Days between start date and end date -> seasonal price	
                    $start_time = $time['start_date'];
                    $end_time = $time['end_date'];
                    $i = $start_time;
                    while ($i <= $end_time)
                    {
                        $start_date = date('m/d/Y', $i);
                        $s_date = explode('/', $start_date);
                        $s_date = $s_date[1] . $s_date[0] . $s_date[2];
                        $seasonal_prices[$s_date] = $seasonalprice;
                        $i = get_gmt_time(strtotime('+1 day', $i));
                    }
                }
                //Total Price
                for ($i = 1; $i < $total_nights; $i++)
                {
                    if ($seasonal_prices[$travel_dates[$i]] == "")
                    {
                        $xprice = $this->Common_model->getTableData('price', array('id' => $id))->row();
                        $total_price = $total_price + $xprice->night;
                    }
                    else
                    {
                        $total_price = $total_price + $seasonal_prices[$travel_dates[$i]];
                        $is_seasonal = 1;
                    }
                }
                //Additional Guests
                if ($data['guests'] > $guests)
                {
                    $days = $total_nights - 1;
                    $diff_guests = $data['guests'] - $guests;
                    $total_price = $total_price + ($days * $xprice->addguests * $diff_guests);
                }
                //Cleaning
                if ($cleaning != 0)
                {
                    $total_price = $total_price + $cleaning;
                }
                //Admin Commission
                //$data['commission'] = 0;			
            }
            if ($is_seasonal == 1)
            {
                //Total days
                $days = $total_nights;
                //Final price	
                $data['subtotal'] = $total_price;
                $data['avg_price'] = $total_price / ($days - 1);
                //echo $data['avg_price'];exit;
                $amt = $data['subtotal'];

                $query = $this->Common_model->getTableData('paymode', array('id' => 2));
                $row = $query->row();
                if ($row->is_premium == 1)
                {
                    if ($row->is_fixed == 1)
                    {
                        $fix = $row->fixed_amount;
                        $amt = $amt + $fix;
                        $data['commission'] = $fix;
                    }
                    else
                    {
                        $per = $row->percentage_amount;
                        $camt = floatval(($amt * $per) / 100);
                        $amt = $amt + $camt;
                        $data['commission'] = $camt;
                    }
                }
                else
                {
                    $amt = $amt;
                }
                $data['amt'] = $amt;
                $this->session->set_userdata('topay', $amt);
            }
            $data['img'] = getListImage($id);

            $data['env'] = 'mobile';

            $data['countries'] = $this->Common_model->getCountries()->result();
            $data['title'] = get_meta_details('Confirm_your_booking', 'title');
            $data["meta_keyword"] = 'mobile';
            $data["meta_description"] = get_meta_details('Confirm_your_booking', 'meta_description');

            $data['message_element'] = "payments/view_booking";
            $this->load->view('template', $data);
        }

        public function paypal()
        {
            $id = $this->input->get('list_id');
            $checkin = $this->input->get('checkin');
            $checkout = $this->input->get('checkout');
            $data['guests'] = $this->input->get('guests');

            //check the list_id is in db
            $this->db->where('status !=', 0);
            $this->db->where('user_id !=', 0);
            $this->db->where('address !=', '0');
            $this->db->where('id', $id);
            $query = $this->db->get('list');
            if ($query->num_rows() == 0)
            {
                echo '[{"available":false,"reason_message":"The host id is not available"}]';
                exit;
            }

            $ckin = explode('/', $checkin);
            $ckout = explode('/', $checkout);

            $x = $this->db->get_where('price', array('id' => $id));
            $x1 = $x->result();

            $per_night = $x1[0]->night;

            $guests = $x1[0]->guests;

            if (isset($x1[0]->cleaning))
                $cleaning = $x1[0]->cleaning;
            else
                $cleaning = 0;

            if (isset($x1[0]->night))
                $price = $x1[0]->night;
            else
                $price = 0;

            if (isset($x1[0]->week))
                $Wprice = $x1[0]->week;
            else
                $Wprice = 0;

            if (isset($x1[0]->month))
                $Mprice = $x1[0]->month;
            else
                $Mprice = 0;

            //check admin premium condition and apply so for
            $query = $this->db->get_where('paymode', array('id' => 2));
            $row = $query->row();


            if (($ckin[0] == "mm" && $ckout[0] == "mm") or ( $ckin[0] == "" && $ckout[0] == ""))
            {
                $days = 0;

                $data['price'] = $price;

                if ($Wprice == 0)
                {
                    $data['Wprice'] = $price * 7;
                }
                else
                {
                    $data['Wprice'] = $Wprice;
                }
                if ($Mprice == 0)
                {
                    $data['Mprice'] = $price * 30;
                }
                else
                {
                    $data['Mprice'] = $Mprice;
                }

                $data['commission'] = 0;

                if ($row->is_premium == 1)
                {
                    if ($row->is_fixed == 1)
                    {
                        $fix = $row->fixed_amount;
                        $amt = $price + $fix;
                        $data['commission'] = $fix;
                        $Fprice = $amt;
                    }
                    else
                    {
                        $per = $row->percentage_amount;
                        $camt = floatval(($price * $per) / 100);
                        $amt = $price + $camt;
                        $data['commission'] = $camt;
                        $Fprice = $amt;
                    }

                    if ($Wprice == 0)
                    {
                        $data['Wprice'] = $price * 7;
                    }
                    else
                    {
                        $data['Wprice'] = $Wprice;
                    }
                    if ($Mprice == 0)
                    {
                        $data['Mprice'] = $price * 30;
                    }
                    else
                    {
                        $data['Mprice'] = $Mprice;
                    }
                }
            }
            else
            {
                $diff = strtotime($ckout[2] . '-' . $ckout[0] . '-' . $ckout[1]) - strtotime($ckin[2] . '-' . $ckin[0] . '-' . $ckin[1]);
                $days = ceil($diff / (3600 * 24));

                if ($data['guests'] > $guests)
                {
                    $diff_days = $data['guests'] - $guests;
                    $price = ($price * $days) + ($days * $x1[0]->addguests * $diff_days);
                }
                else
                {
                    $price = $price * $days;
                }

                if ($cleaning != 0)
                {
                    $price = $price + $cleaning;
                }

                //Entering it into data variables
                $data['price'] = $price;

                if ($Wprice == 0)
                {
                    $data['Wprice'] = $price * 7;
                }
                else
                {
                    $data['Wprice'] = $Wprice;
                }
                if ($Mprice == 0)
                {
                    $data['Mprice'] = $price * 30;
                }
                else
                {
                    $data['Mprice'] = $Mprice;
                }
                $data['commission'] = 0;

                if ($row->is_premium == 1)
                {
                    if ($row->is_fixed == 1)
                    {
                        $fix = $row->fixed_amount;
                        $amt = $price + $fix;
                        $data['commission'] = $fix;
                        $Fprice = $amt;
                    }
                    else
                    {
                        $per = $row->percentage_amount;
                        $camt = floatval(($price * $per) / 100);
                        $amt = $price + $camt;
                        $data['commission'] = $camt;
                        $Fprice = $amt;
                    }

                    if ($Wprice == 0)
                    {
                        $data['Wprice'] = $price * 7;
                    }
                    else
                    {
                        $data['Wprice'] = $Wprice;
                    }
                    if ($Mprice == 0)
                    {
                        $data['Mprice'] = $price * 30;
                    }
                    else
                    {
                        $data['Mprice'] = $Mprice;
                    }
                }
            }


            $query = $this->db->query("SELECT id,list_id FROM `calendar` WHERE `list_id` = '" . $id . "' AND (`booked_days` = '" . $checkin . "' OR `booked_days` = '" . $checkout . "') GROUP BY `list_id`");
            $rows = $query->num_rows();
            //echo $this->db->last_query();exit;

            if ($rows > 0)
            {
                echo '[{"available":false,"total_price":' . $data['price'] . ',"reason_message":"Those dates are not available"}]';
            }
            else
            {
                $is_live = $this->db->get_where('payments', array('id' => 2))->row()->is_live;

                if ($is_live == 1)
                    $paypal_url = '1';
                else
                    $paypal_url = '2';

                $paypal_id = $this->Common_model->getTableData('payment_details', array('code' => 'PAYPAL_ID'))->row()->value;

                echo '[{"available":true,"is_live":"' . $paypal_url . '","paypal_id":"' . $paypal_id . '","service_fee":"$' . $data['commission'] . '","cleaning_fee":"$' . $cleaning . '","reason_message":"","price_per_night":"$' . $per_night . '","nights":' . $days . ',"total_price":"$' . ($data['price'] + $data['commission']) . '"}]';
            }
        }

        public function paypalipn()
        {

            //mail('rameshr@cogzidel.com','Checkiny by me',$_REQUEST['payment_status'].'Coming'.$_REQUEST['mc_gross'].'Coming'.$_REQUEST['custom']);
            if ($this->input->get('status') == 'Completed')
            {
                $list = array();

                $list['list_id'] = $this->input->get('list_id');
                $list['userby'] = $this->input->get('userby');

                $query1 = $this->db->get_where('list', array('id' => $list['list_id']));
                $buyer_id = $query1->row()->user_id;

                $list['userto'] = $buyer_id;
                $list['checkin'] = $this->input->get('checkin');
                $list['checkout'] = $this->input->get('checkout');
                $list['no_quest'] = $this->input->get('guest');
                $list['price'] = $this->input->get('amount');
                $list['credit_type'] = 1;

                $is_travelCretids = NULL;
                $user_travel_cretids = NULL;
                //mail('rameshr@cogzidel.com','Test-done',$list['from'].'Coming3'.$list['to'].'vvv'.$data[2].'bbb'.$data[3]);

                $list['status'] = 1;

                if ($list['price'] > 75)
                {
                    $user_id = $list['userby'];
                    $details = $this->Referrals_model->get_details_by_Iid($user_id);
                    $row = $details->row();
                    $count = $details->num_rows();
                    if ($count > 0)
                    {
                        $details1 = $this->Referrals_model->get_details_refamount($row->invite_from);
                        if ($details1->num_rows() == 0)
                        {
                            $insertData = array();
                            $insertData['user_id'] = $row->invite_from;
                            $insertData['count_trip'] = 1;
                            $insertData['amount'] = 25;
                            $this->Referrals_model->insertReferralsAmount($insertData);
                        }
                        else
                        {
                            $count_trip = $details1->row()->count_trip;
                            $amount = $details1->row()->amount;
                            $updateKey = array('id' => $row->id);
                            $updateData = array();
                            $updateData['count_trip'] = $count_trip + 1;
                            $updateData['amount'] = $amount + 25;
                            $this->Referrals_model->updateReferralsAmount($updateKey, $updateData);
                        }
                    }
                }

                $q = $query1->result();
                $row_list = $query1->row();
                $iUser_id = $q[0]->user_id;
                $details2 = $this->Referrals_model->get_details_by_Iid($iUser_id);
                $row = $details2->row();
                $count = $details2->num_rows();
                if ($count > 0)
                {
                    $details3 = $this->Referrals_model->get_details_refamount($row->invite_from);
                    if ($details3->num_rows() == 0)
                    {
                        $insertData = array();
                        $insertData['user_id'] = $row->invite_from;
                        $insertData['count_book'] = 1;
                        $insertData['amount'] = 75;
                        $this->Referrals_model->insertReferralsAmount($insertData);
                    }
                    else
                    {
                        $count_book = $details3->row()->count_book;
                        $amount = $details3->row()->amount;
                        $updateKey = array('id' => $row->id);
                        $updateData = array();
                        $updateData['count_trip'] = $count_book + 1;
                        $updateData['amount'] = $amount + 75;
                        $this->Referrals_model->updateReferralsAmount($updateKey, $updateData);
                    }
                }

                $admin_email = $this->dx_auth->get_site_sadmin();
                $admin_name = $this->dx_auth->get_site_title();

                $query3 = $this->db->get_where('users', array('id' => $list['userby']));
                $rows = $query3->row();

                $username = $rows->username;
                $user_id = $rows->id;
                $email_id = $rows->email;

                $query4 = $this->users->get_user_by_id($buyer_id);
                $buyer_name = $query4->row()->username;
                $buyer_email = $query4->row()->email;

                //Check md5('No Travel Cretids') || md5('Yes Travel Cretids')
                if ($is_travelCretids == '7c4f08a53f4454ea2a9fdd94ad0c2eeb')
                {
                    $query5 = $this->Referrals_model->get_details_refamount($user_id);
                    $amount = $query5->row()->amount;

                    $updateKey = array('user_id ' => $user_id);
                    $updateData = array();
                    $updateData['amount'] = $amount - $user_travel_cretids;
                    $this->Referrals_model->updateReferralsAmount($updateKey, $updateData);

                    $list['credit_type'] = 2;
                    $list['ref_amount'] = $user_travel_cretids;


                    $row = $query4->row();

                    //sent mail to administrator
                    $email_name = 'tc_book_to_admin';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $list['checkin'], "{checkout}" => $list['checkout'], "{market_price}" => $user_travel_cretids + $list['price'], "{payed_amount}" => $list['price'], "{travel_credits}" => $user_travel_cretids, "{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
                    //Send Mail
                    $this->Email_model->sendMail($admin_email, $email_id, ucfirst($username), $email_name, $splVars);


                    //sent mail to buyer
                    $email_name = 'tc_book_to_host';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $list['checkin'], "{checkout}" => $list['checkout'], "{market_price}" => $list['price']);
                    //Send Mail
                    $this->Email_model->sendMail($buyer_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);
                }

                //	$list['book_date']           = date('d-m-Y H:i:s');
                //Actual insertion into the database
                $this->db->insert('reservation', $list);
                $reservation_id = $this->db->insert_id();

                //Send Message Notification
                $insertData = array(
                    'list_id' => $list['list_id'],
                    'reservation_id' => $reservation_id,
                    'userby' => $list['userby'],
                    'userto' => $list['userto'],
                    'message' => 'You have a new reservation request from ' . ucfirst($username),
                    'created' => date('m/d/Y g:i A'),
                    'message_type' => 1
                );
                $this->Message_model->sentMessage($insertData, ucfirst($buyer_name), ucfirst($username), $row_list->title, $reservation_id);
                $message_id = $this->db->insert_id();

                $actionurl = site_url('trips/request/' . $reservation_id);

                //Reservation Notification To Host
                $email_name = 'host_reservation_notification';
                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $list['checkin'], "{checkout}" => $list['checkout'], "{market_price}" => $list['price'], "{action_url}" => $actionurl);
                //Send Mail
                $this->Email_model->sendMail($buyer_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);

                //Reservation Notification To Traveller
                $email_name = 'traveller_reservation_notification';
                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username));
                //Send Mail
                $this->Email_model->sendMail($email_id, $admin_email, ucfirst($admin_name), $email_name, $splVars);

                //Reservation Notification To Administrator
                $email_name = 'admin_reservation_notification';
                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row_list->title, "{book_date}" => date('m/d/Y'), "{book_time}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $list['checkin'], "{checkout}" => $list['checkout'], "{market_price}" => $user_travel_cretids + $list['price'], "{payed_amount}" => $list['price'], "{travel_credits}" => $user_travel_cretids, "{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
                //Send Mail
                $this->Email_model->sendMail($admin_email, $email_id, ucfirst($username), $email_name, $splVars);

                echo '[{"reason_message":"Payment completed successfully."}]';
                exit;
            }
        }

    }

?>