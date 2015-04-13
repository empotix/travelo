<?php

    /**
     * DROPinn Payments Controller Class
     *
     * Helps to control payment functionality
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Profiles
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com
     */
    class Payments extends CI_Controller
    {

        function Payments()
        {
            parent::__construct();

            $this->load->helper('url');

            $this->load->library('Twoco_Lib');
            $this->load->library('email');
            $this->load->helper('form');
            $this->load->model('Users_model');
            $this->load->model('Referrals_model');
            $this->load->model('Email_model');
            $this->load->model('Message_model');
            $this->load->model('Contacts_model');
            $this->load->model('Trips_model');
            $trackingId = '4568246565';
            $this->facebook_lib->enable_debug(TRUE);

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
        }

        function index($param = '')
        {
            $this->session->set_userdata('cnumber_error', '');
            $this->session->set_userdata('cname_error', '');
            $this->session->set_userdata('ctype_error', '');
            $this->session->set_userdata('expire_error', '');

            if ($param == '')
            {
                redirect('info/deny');
            }

            $result = $this->Common_model->getTableData('list', array('id' => $param, 'is_enable' => 1));
            if ($result->num_rows() == 0)
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate("This List Hidden by Host.")));
                redirect('rooms/' . $param);
            }
            $check = $this->db->where('id', $param)->where('user_id', $this->dx_auth->get_user_id())->get('list');
            if ($check->num_rows() != 0)
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate("Host can't book their list.")));
                redirect('rooms/' . $param);
            }

            if ((!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()))
            {
                if ($this->input->get())
                {
                    //contact me	
                    $contact = $this->input->get('contact');
                    if ($this->input->get('contact'))
                        $redirect_to = 'payments/index/' . $param . '?contact=' . $contact;
                    else
                        $redirect_to = 'payments/index/' . $param;

                    $newdata = array(
                        'list_id' => $param,
                        'Lcheckin' => $this->input->get('checkin'),
                        'Lcheckout' => $this->input->get('checkout'),
                        'number_of_guests' => $this->input->get('guest'),
                        'redirect_to' => $redirect_to,
                        'formCheckout' => TRUE
                    );
                    $this->session->set_userdata($newdata);

                    redirect('users/signin', 'refresh');
                }
                else
                {
                    $contact = $this->input->get('contact');
                    if ($this->input->get('contact'))
                        $redirect_to = 'payments/index/' . $param . '?contact=' . $contact;
                    else
                        $redirect_to = 'payments/index/' . $param;

                    $newdata = array(
                        'list_id' => $param,
                        'Lcheckin' => $this->input->post('checkin'),
                        'Lcheckout' => $this->input->post('checkout'),
                        'number_of_guests' => $this->input->post('number_of_guests'),
                        'redirect_to' => $redirect_to,
                        'formCheckout' => TRUE
                    );
                    $this->session->set_userdata($newdata);

                    redirect('users/signin', 'refresh');
                }
            }

            /* Include Get option */

            if ($this->input->post('checkin') || $this->session->userdata('Lcheckin') || $this->input->get('checkin'))
            {
                if ($this->input->post('SignUp') != NULL)
                {
                    //echo 'got it';
                    //$this->guest_signup();


                    if ($this->input->post() || $this->input->get())
                    {
                        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
                        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
                        $this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean|callback__check_user_name');
                        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|xss_clean|callback__check_user_email');
                        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[5]|max_length[16]|xss_clean|matches[confirmpassword]');
                        $this->form_validation->set_rules('confirmpassword', 'Confirm Password', 'required|trim|min_length[5]|max_length[16]|xss_clean');

                        if ($this->form_validation->run())
                        {
                            //Get the post values
                            $first_name = $this->input->post('first_name');
                            $last_name = $this->input->post('last_name');
                            $username = $this->input->post('username');
                            $email = $this->input->post('email');
                            $password = $this->input->post('password');
                            $confirmpassword = $this->input->post('confirmpassword');
                            $newsletter = $this->input->post('news_letter');

                            $data = $this->dx_auth->register($username, $password, $email);

                            $this->dx_auth->login($username, $password, 'TRUE');

                            //To check user come by reference
                            if ($this->session->userdata('ref_id'))
                                $ref_id = $this->session->userdata('ref_id');
                            else
                                $ref_id = "";

                            if (!empty($ref_id))
                            {
                                $details = $this->Referrals_model->get_user_by_refId($ref_id);
                                $invite_from = $details->row()->id;

                                $insertData = array();
                                $insertData['invite_from'] = $invite_from;
                                $insertData['invite_to'] = $this->dx_auth->get_user_id();
                                $insertData['join_date'] = local_to_gmt();

                                $this->Referrals_model->insertReferrals($insertData);

                                $this->session->unset_userdata('ref_id');
                            }

                            $notification = array();
                            $notification['user_id'] = $this->dx_auth->get_user_id();
                            $notification['new_review '] = 1;
                            $notification['leave_review'] = 1;
                            $this->Common_model->insertData('user_notification', $notification);

                            //Need to add this data to user profile too 
                            $add['Fname'] = $first_name;
                            $add['Lname'] = $last_name;
                            $add['id'] = $this->dx_auth->get_user_id();
                            $add['email'] = $email;
                            $this->Common_model->insertData('profiles', $add);
                            //End of adding it
                            $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Registered successfully.')));
                        }
                    }
                }
                else if ($this->input->post('SignIn') != NULL)
                {

                    if ($this->input->post() || $this->input->get())
                    {
                        if (!$this->dx_auth->is_logged_in())
                        {
                            // Set form validation rules
                            $this->form_validation->set_rules('username1', 'Username or Email', 'required|trim|xss_clean');
                            $this->form_validation->set_rules('password1', 'password', 'required|trim|xss_clean');
                            //	$this->form_validation->set_rules('remember', 'Remember me', 'integer');

                            if ($this->form_validation->run())
                            {
                                $username = $this->input->post("username1");
                                $password = $this->input->post("password1");

                                if ($this->dx_auth->login($username, $password, $this->form_validation->set_value('TRUE')))
                                {
                                    // Redirect to homepage
                                    $newdata = array(
                                        'user' => $this->dx_auth->get_user_id(),
                                        'username' => $this->dx_auth->get_username(),
                                        'logged_in' => TRUE
                                    );
                                    $this->session->set_userdata($newdata);
                                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Logged in successfully.')));
                                }
                            }
                        }
                    }
                }
                $this->form($param);
            }
            else
            {
                redirect('rooms/' . $param, "refresh");
            }
        }

        function contact()
        {

            if ((!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()))
            {

                $data['status'] = "error";
                //Store the values in session to redirect this page after login
                $newdata = array(
                    'Lid' => $this->input->post('id'),
                    'Lcheckin' => $this->input->post('checkin'),
                    'Lcheckout' => $this->input->post('checkout'),
                    'number_of_guests' => $this->input->post('guests'),
                    'Lmessage' => $this->input->post('message'),
                    'redirect_to' => 'rooms/' . $this->input->post('id'),
                    'formCheckout' => TRUE
                );
                $this->session->set_userdata($newdata);
            }
            else
            {
                $check = $this->db->where('id', $this->input->post('id'))->where('user_id', $this->dx_auth->get_user_id())->get('list');

                if ($check->num_rows() != 0)
                {
                    $data['status'] = "your_list";
                }
                else
                {

                    $status = 1;
                    if ($this->session->userdata('formCheckout'))
                    {
                        $id = $this->session->userdata('Lid');
                        $checkin = $this->session->userdata('Lcheckin');
                        $checkout = $this->session->userdata('Lcheckout');
                        $data['guests'] = $this->session->userdata('number_of_guests');
                        $message = $this->session->userdata('Lmessage');
                    }
                    else
                    {
                        $id = $this->input->post('id');
                        $checkin = $this->input->post('checkin');
                        $checkout = $this->input->post('checkout');
                        $data['guests'] = $this->input->post('guests');
                        $message = $this->input->post('message');
                    }
                    //Check the rooms availability
                    $checkin_time = $checkin;
                    $checkin_time = get_gmt_time(strtotime($checkin_time));
                    $checkout_time = $checkout;
                    $checkout_time = get_gmt_time(strtotime($checkout_time));
                    $sql = "select checkin,checkout from contacts where list_id='" . $id . "' and status!=1";
                    $query = $this->db->query($sql);
                    $res = $query->result_array();
                    if ($query->num_rows() > 0)
                    {
                        foreach ($res as $time)
                        {
                            $start_date = $time['checkin'];
                            $end_date = $time['checkout'];
                            $start = get_gmt_time(strtotime($start_date));
                            $end = get_gmt_time(strtotime($end_date));
                            if (($checkin_time >= $start && $checkin_time <= $end) || ($checkout_time >= $start && $checkout_time <= $end))
                            {
                                $status = 0;
                            }
                        }
                    }
                    $daysexist = $this->db->query("SELECT id,list_id,booked_days FROM `calendar` WHERE `list_id` = '" . $id . "' AND (`booked_days` >= '" . get_gmt_time(strtotime($checkin)) . "' AND `booked_days` <= '" . get_gmt_time(strtotime($checkout)) . "') GROUP BY `id`");
                    //echo $data['status'] = $this->db->last_query();exit;
                    $rowsexist = $daysexist->num_rows();
                    // echo $data['status'] = $daysexist->num_rows();exit;
                    if ($rowsexist > 0)
                    {
                        $status = 0;
                    }
                    else
                    {
                        $status = 1;
                    }

                    $capacity = $this->db->where('id', $id)->get('list')->row()->capacity;
                    $capacity = $capacity + 1;

                    if ($status == 0)
                    {
                        $data['status'] = "not_available";
                    }
                    else if ($data['guests'] > $capacity)
                    {
                        $data['status'] = "not_available";
                    }
                    else
                    {
                        $data['status'] = "success";
                        $list['list_id'] = $id;
                        $list['checkin'] = $checkin;
                        $list['checkout'] = $checkout;
                        $list['no_quest'] = $data['guests'];
                        $list['currency'] = get_currency_code();

                        //calculate price for the checkin and checkout dates
                        $ckin = explode('/', $checkin);
                        $ckout = explode('/', $checkout);

                        $xprice = $this->Common_model->getTableData('price', array('id' => $id))->row();

                        $guests = $xprice->guests;
                        $per_night = $xprice->night;

                        if (isset($xprice->cleaning))
                            $cleaning = $xprice->cleaning;
                        else
                            $cleaning = 0;

                        if (isset($xprice->security))
                            $security = $xprice->security;
                        else
                            $security = 0;

                        if (isset($xprice->night))
                            $price = $xprice->night;
                        else
                            $price = 0;

                        if (isset($xprice->week))
                            $Wprice = $xprice->week;
                        else
                            $Wprice = 0;

                        if (isset($xprice->month))
                            $Mprice = $xprice->month;
                        else
                            $Mprice = 0;

                        //check admin premium condition and apply so for
                        $query = $this->Common_model->getTableData('paymode', array('id' => 2));
                        $row = $query->row();

                        //Seasonal Price
                        //1. Store all the dates between checkin and checkout in an array		
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

                            if ($security != 0)
                            {
                                $total_price = $total_price + $security;
                            }

                            //Admin Commission
                            $data['commission'] = 0;
                            if ($row->is_premium == 1)
                            {
                                if ($row->is_fixed == 1)
                                {
                                    $fix = $row->fixed_amount;
                                    $amt = $total_price + $fix;
                                    $data['commission'] = $fix;
                                }
                                else
                                {
                                    $per = $row->percentage_amount;
                                    $camt = floatval(($total_price * $per) / 100);
                                    $amt = $total_price + $camt;
                                    $data['commission'] = $camt;
                                }
                            }
                        }
                        if ($is_seasonal == 1)
                        {
                            //Total days
                            $days = $total_nights;
                            //Final price	
                            $data['price'] = $total_price;
                        }
                        else
                        {
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

                                $price = $price * $days;

                                //Additional guests
                                if ($data['guests'] > $guests)
                                {
                                    $diff_days = $data['guests'] - $guests;
                                    $price = $price + ($days * $xprice->addguests * $diff_days);
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
                                $data['commission'] = 0;


                                if ($days >= 7 && $days < 30)
                                {
                                    if (!empty($Wprice))
                                    {
                                        $finalAmount = $Wprice;
                                        $differNights = $days - 7;
                                        $perDay = $Wprice / 7;
                                        $per_night = round($perDay, 2);
                                        if ($differNights > 0)
                                        {
                                            $addAmount = $differNights * $per_night;
                                            $finalAmount = $Wprice + $addAmount;
                                        }
                                        $price = $finalAmount;
                                        //Additional guests
                                        if ($data['guests'] > $guests)
                                        {
                                            $diff_days = $data['guests'] - $guests;
                                            $price = $price + ($days * $xprice->addguests * $diff_days);
                                        }
                                    }
                                }


                                if ($days >= 30)
                                {
                                    if (!empty($Mprice))
                                    {
                                        $finalAmount = $Mprice;
                                        $differNights = $days - 30;
                                        $perDay = $Mprice / 30;
                                        $per_night = round($perDay, 2);
                                        if ($differNights > 0)
                                        {
                                            $addAmount = $differNights * $per_night;
                                            $finalAmount = $Mprice + $addAmount;
                                        }
                                        $price = $finalAmount;
                                        //Additional guests
                                        if ($data['guests'] > $guests)
                                        {
                                            $diff_days = $data['guests'] - $guests;
                                            $price = $price + ($days * $xprice->addguests * $diff_days);
                                        }
                                    }
                                }

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



                                $xprice = $this->Common_model->getTableData('list', array('id' => $id))->row();


                                if ($cleaning != 0)
                                {
                                    $price = $price + $cleaning;
                                }

                                if ($security != 0)
                                {
                                    $price = $price + $security;
                                }

                                $data['price'] = $price;
                            }
                        }

                        //   $data['price'] = get_currency_value1($id,$data['price']);
                        //  	$data['commission'] = get_currency_value1($id,$data['commission']);

                        $list['price'] = $data['price'];
                        $list['admin_commission'] = $data['commission'];
                        $list['send_date'] = local_to_gmt();
                        $list['status'] = 1;
                        $query_list = $this->Common_model->getTableData('list', array('id' => $id))->row();
                        $list['userto'] = $query_list->user_id;
                        $list['userby'] = $this->dx_auth->get_user_id();
                        $key = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789', 5)), 0, 9);
                        $list['contact_key'] = $key;
                        $query_user = $this->Common_model->getTableData('users', array('id' => $list['userby']))->row();
                        $username = $query_user->username;
                        $this->Common_model->insertData('contacts', $list);
                        $contact_id = $this->db->insert_id();
                        $query_name = $this->Users_model->get_user_by_id($list['userby'])->row();
                        $buyer_name = $query_name->username;
                        $link = base_url() . 'contacts/request/' . $contact_id;
                        //Send Message Notification
                        $insertData = array(
                            'list_id' => $list['list_id'],
                            'contact_id' => $contact_id,
                            'userby' => $list['userby'],
                            'userto' => $list['userto'],
                            'message' => '<b>You have a new contact request from ' . ucfirst($username) . '</b><br><br>' . $message,
                            'created' => local_to_gmt(),
                            'message_type' => 7
                        );

                        $this->Message_model->sentMessage($insertData, ucfirst($buyer_name), ucfirst($username), $query_list->title, $contact_id);

                        //Send mail to host
                        $query = $this->Common_model->getTableData('list', array('id' => $id))->row();
                        $host_id = $query->user_id;
                        $list_email = $this->Common_model->getTableData('users', array('id' => $host_id))->row()->email;
                        $host_username = $this->Common_model->getTableData('users', array('id' => $host_id))->row()->username;
                        $query2 = $this->Common_model->getTableData('users', array('id' => $this->dx_auth->get_user_id()))->row();
                        $user_email = $query2->email;
                        $emailsubject = "Contact Request";

                        $this->load->library('email');
                        $config['mailtype'] = 'html';
                        $config['wordwrap'] = TRUE;
                        //$encrypted_user_email = $this->hide_email($user_email);
                        $this->email->from($user_email, $this->dx_auth->get_site_title());
                        $this->email->to($list_email);
                        $this->email->subject('Contact Request');
                        $slogan = $this->db->get_where('settings', array('code' => 'SITE_SLOGAN'))->row()->string_value;

                        $message = '<table cellspacing="0" cellpadding="0" width="678" style="border:1px solid #e6e6e6; background:#fff;  font-family:Arial, Helvetica, sans-serif; -moz-border-radius: 16px; -webkit-border-radius:16px; -khtml-border-radius: 16px; border-radius: 16px; -moz-box-shadow: 0 0 4px #888888; -webkit-box-shadow:0 0 4px #888888; box-shadow:0 0 4px #888888;">
		<tr>
		<td>
		<table background="' . base_url() . 'images/email/head_bg.png" width="676" height="156" cellspacing="0" cellpadding="0">
		<tr>
		<td style="vertical-align:top;">
		<img src="' . base_url() . 'logo/logo.png" alt="' . $this->dx_auth->get_site_title() . '" style=" margin:10px 0 0 20px;" />
		</td>
		<td style="text-transform:uppercase; font-weight:bold; color:#0271b8; width:290px; padding:0 10px 0 0; line-height:28px;">																																				
		<p style="margin:0 0 10px 0; color:#0271b8;">' . $slogan . '</p>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		<tr>
		<td style="padding:0 10px; font-size:14px;">
		
		Hi ' . $host_username . ',<br /><br />
		
		Please click on the following link to contact the user : ' . $link . '<br /><br />
				
		Room : ' . $query->title . '<br /><br />
		
		Checkin Date : ' . $checkin . '<br /><br />
		
		Checkout Date : ' . $checkout . '<br /><br />
		
		Guests : ' . $data['guests'] . '<br /><br />
		
		Message       : ' . $message . '<br /><br /></td>
		</tr>
		<tr>
        <td>
 		<p style="margin: 0 10px 0 0;">--</p>
		<p style="margin: 0 0 10px 0;">Thanks and Regards,</p>
		<p style="margin: 0 10px 0 0;">Admin</p>
		<p style="margin: 0px;">' . $this->dx_auth->get_site_title() . '</p>
		</td>
		</tr>
		<tr>
		<td>
		<table cellpadding="0" cellspacing="0" background="' . base_url() . 'images/email/footer.png" width="676" height="58" style="text-align:center;">
		<tr>
		<td style="font-size:13px; padding:6px 0 0 0; color:#333333;">Copyright 2013 - 2014 <span style="color:#0271b8;">' . $this->dx_auth->get_site_title() . '.</span> All Rights Reserved.</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>';

                        $this->email->message($message);
                        $this->email->set_mailtype("html");
                        $this->email->send();
                    }
                }
            }
            echo json_encode($data);
        }

        function hide_email($email)
        {
            $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
            $key = str_shuffle($character_set);
            $cipher_text = '';
            $id = 'e' . rand(1, 999999999);
            for ($i = 0; $i < strlen($email); $i+=1)
                $cipher_text.= $key[strpos($character_set, $email[$i])]; $script = 'var a="' . $key . '";var b=a.split("").sort().join("");var c="' . $cipher_text . '";var d="";';
            $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
            $script.= 'document.getElementById("' . $id . '").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
            $script = "eval(\"" . str_replace(array("\\", '"'), array("\\\\", '\"'), $script) . "\")";
            $script = '<script type="text/javascript">/*<![CDATA[*/' . $script . '/*]]>*/</script>';
            return '<span id="' . $id . '">[javascript protected email address]</span>' . $script;
        }

        function form($param = '')
        {
            if ($this->input->get('contact'))
            {
                $contact_key = $this->input->get('contact');
                $contact_result = $this->Common_model->getTableData('contacts', array('contact_key' => $contact_key))->row();

                if ($contact_result->status == 10)
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Sorry! Access denied.')));
                    redirect('rooms/' . $param, "refresh");
                }

                if ($contact_result->userby != $this->dx_auth->get_user_id())
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('You are not a valid user to use this link.')));
                    redirect('rooms/' . $param, "refresh");
                }

                $checkin = $contact_result->checkin;
                $checkout = $contact_result->checkout;
                $data['guests'] = $contact_result->no_quest;
                $data['contact_key'] = $contact_result->contact_key;
            }
            else if ($this->session->userdata('formCheckout'))
            {
                $checkin = $this->session->userdata('Lcheckin');
                $checkout = $this->session->userdata('Lcheckout');
                $data['guests'] = $this->session->userdata('number_of_guests');
            }
            else if ($this->input->get())
            {
                $checkin = $this->input->get('checkin');
                $checkout = $this->input->get('checkout');
                $data['guests'] = $this->input->get('guest');
            }
            else
            {
                $checkin = $this->input->post('checkin');
                $checkout = $this->input->post('checkout');
                $data['guests'] = $this->input->post('number_of_guests');
            }

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

            if (isset($xprice->security))
                $security = $xprice->security;
            else
                $security = 0;

            $data['cleaning'] = $cleaning;

            $data['security'] = $security;

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
            $data['manual'] = $list->house_rule;


            $diff = strtotime($ckout[2] . '-' . $ckout[0] . '-' . $ckout[1]) - strtotime($ckin[2] . '-' . $ckin[0] . '-' . $ckin[1]);
            $days = ceil($diff / (3600 * 24));

            /* $amt = $price * $days * $data['guests']; */
            if ($data['guests'] > $guests)
            {
                $diff_days = $data['guests'] - $guests;
                $amt = ($price * $days) + ($days * $xprice->addguests * $diff_days);
                $data['extra_guest_price'] = $xprice->addguests * $diff_days;
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
            else
            {
                $finalAmount = $amt;
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
            else
            {
                $finalAmount = $amt;
            }
            //Update the daily price
            $data['price'] = $xprice->night;

            //Cleaning fee
            if ($cleaning != 0)
            {
                $amt = $amt + $cleaning;
            }
            if ($security != 0)
            {
                $amt = $amt + $security;
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
            $this->session->set_userdata("total_price_'" . $id . "'_'" . $this->dx_auth->get_user_id() . "'", $amt);
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
                    $camt = floatval(($finalAmount * $per) / 100);
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
                if ($this->db->select('referral_amount')->where('id', $this->dx_auth->get_user_id())->get('users')->row()->referral_amount != 0)
                {
                    $data['amt'] = $amt;
                    $data['referral_amount'] = $this->db->select('referral_amount')->where('id', $this->dx_auth->get_user_id())->get('users')->row()->referral_amount;
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

            $data['result'] = $this->Common_model->getTableData('payments')->result();

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
                    $data['extra_guest_price'] = $xprice->addguests * $diff_guests;
                }
                //Cleaning
                if ($cleaning != 0)
                {
                    $total_price = $total_price + $cleaning;
                }

                if ($security != 0)
                {
                    $total_price = $total_price + $security;
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
                        $camt = floatval(($finalAmount * $per) / 100);
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

            //echo $data['price'];exit;

            $data['countries'] = $this->Common_model->getCountries()->result();
            $data['title'] = get_meta_details('Confirm_your_booking', 'title');
            $data["meta_keyword"] = get_meta_details('Confirm_your_booking', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Confirm_your_booking', 'meta_description');

            $data['message_element'] = "payments/view_booking";
            $this->load->view('template', $data);
        }

        public function payment($param = "", $env = "")
        {

            if ($this->input->post('agrees_to_terms') != 'on')
            {
                $newdata = array(
                    'list_id' => $param,
                    'Lcheckin' => $this->input->post('checkin'),
                    'Lcheckout' => $this->input->post('checkout'),
                    'number_of_guests' => $this->input->post('number_of_guests'),
                    'formCheckout' => TRUE
                );
                $this->session->set_userdata($newdata);
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('You must agree to the Cancellation Policy and House Rules!')));
                redirect('payments/index/' . $param, 'refresh');
            }

            $contact_key = $this->input->post('contact_key');
            $updateKey = array('contact_key' => $contact_key);
            $updateData = array();
            $updateData['status'] = 10;
            $this->Contacts_model->update_contact($updateKey, $updateData);

            /* 	if($this->session->userdata("total_price_'".$param."'_'".$this->dx_auth->get_user_id()."'") == "")
              {
              redirect('rooms/'.$param, "refresh");
              $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Please! Try Again'));

              } */
            if ($this->input->post('payment_method') == 'cc')
            {
                //$this->submissionCC($param);
            }
            else if ($this->input->post('payment_method') == 'paypal' || $env = "mobile")
            {

                $this->submission($param, $contact_key);
            }
            else if ($this->input->post('payment_method') == '2c')
            {
                //$this->submissionTwoc($param);	
            }
            else
            {
                redirect('info');
            }
        }

        function submission($param = '', $contact_key)
        {

            $checkin = $this->input->post('checkin');
            $checkout = $this->input->post('checkout');
            $number_of_guests = $this->input->post('number_of_guests');
            $ckin = explode('/', $checkin);
            $ckout = explode('/', $checkout);
            $pay = $this->Common_model->getTableData('paywhom', array('id' => 1));
            $paywhom = $pay->result();
            $paywhom = $paywhom[0]->whom;
            $id = $this->uri->segment(3);

            if ($ckin[0] == "mm")
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Sorry! Access denied.')));
                redirect('rooms/' . $id, "refresh");
            }
            if ($ckout[0] == "mm")
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Sorry! Access denied.')));
                redirect('rooms/' . $id, "refresh");
            }

            $xprice = $this->Common_model->getTableData('price', array('id' => $this->uri->segment(3)))->row();


            $price = $xprice->night;

            //$price      		 = $xprice->night;
            $placeid = $xprice->id;

            $guests = $xprice->guests;

            $extra_guest_price = $xprice->addguests;

            if (isset($xprice->cleaning))
                $cleaning = $xprice->cleaning;
            else
                $cleaning = 0;

            if (isset($xprice->security))
                $security = $xprice->security;
            else
                $security = 0;

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
            $q = $query->result();

            $diff = strtotime($ckout[2] . '-' . $ckout[0] . '-' . $ckout[1]) - strtotime($ckin[2] . '-' . $ckin[0] . '-' . $ckin[1]);
            $days = ceil($diff / (3600 * 24));

            $user_travel_cretids = 0;
            if ($this->session->userdata('travel_cretids'))
            {
                $amt = $this->session->userdata('travel_cretids');
                $user_travel_cretids = $this->session->userdata('user_travel_cretids');
                $is_travelCretids = md5('Yes Travel Cretids');
            }
            else
            {
                if ($number_of_guests > $guests)
                {
                    $diff_days = $number_of_guests - $guests;
                    $amt = ($price * $days) + ($days * $xprice->addguests * $diff_days);
                }
                else
                {
                    $amt = $price * $days;
                }


                if ($days >= 7 && $days < 30)
                {
                    if (!empty($Wprice))
                    {
                        $finalAmount = $Wprice;
                        $differNights = $days - 7;
                        $perDay = $Wprice / 7;
                        $per_night = round($perDay, 2);
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
                        $per_night = round($perDay, 2);
                        if ($differNights > 0)
                        {
                            $addAmount = $differNights * $per_night;
                            $finalAmount = $Mprice + $addAmount;
                        }
                        $amt = $finalAmount;
                    }
                }

                //Cleaning fee
                if ($cleaning != 0)
                {
                    $amt = $amt + $cleaning;
                }
                if ($security != 0)
                {
                    $amt = $amt + $cleaning;
                }
                else
                {
                    $amt = $amt;
                }


                $to_pay = 0;
                $admin_commission = 0;
                //Amount from session 
                //	$amt=$this->session->userdata("total_price_'".$id."'_'".$this->dx_auth->get_user_id()."'");
                //commission calculation
                $query = $this->Common_model->getTableData('paymode', array('id' => 2));
                $row = $query->row();
                if ($row->is_premium == 1)
                {
                    if ($row->is_fixed == 1)
                    {
                        $to_pay = $amt;
                        $fix = $row->fixed_amount;
                        $amt = $amt + $fix;
                        //$amt = $this->session->userdata('topay');
                        $admin_commission = $fix;
                    }
                    else
                    {
                        $to_pay = $amt;
                        $per = $row->percentage_amount;
                        $camt = floatval(($amt * $per) / 100);
                        $amt = $amt + $camt;
                        $amt = $amt;
                        $admin_commission = $camt;
                    }
                }
                else
                {
                    $amt = $amt;
                    $to_pay = $amt;
                }

                $is_travelCretids = md5('No Travel Cretids');
            }
            //echo $amt;exit;

            if ($contact_key != '')
            {
                $contact_result = $this->db->where('contact_key', $contact_key)->get('contacts')->row();
                $amt = $contact_result->price + $contact_result->admin_commission;
            }

            if ($amt > 110)
            {
                if ($this->db->select('referral_amount')->where('id', $this->dx_auth->get_user_id())->get('users')->row()->referral_amount != 0)
                {
                    $referral_amount = $this->db->select('referral_amount')->where('id', $this->dx_auth->get_user_id())->get('users')->row()->referral_amount;

                    if ($referral_amount > 100)
                    {
                        $final_amt = get_currency_value1($id, $amt) - get_currency_value(100);
                    }
                    else
                    {
                        $final_amt = $amt - $referral_amount;
                    }
                    $amt = $final_amt;
                }
                else
                {
                    $amt = $amt;
                }
            }
            else
            {
                $amt = $amt;
            }

            if ($contact_key == "")
                $contact_key = "None";
            //Entering it into data variables
            $row = $this->Common_model->getTableData('payment_details', array('code' => 'PAYPAL_ID'))->row();
            $paymode = $this->db->where('payment_name', 'Paypal')->get('payments')->row()->is_live;

            $custom = $id . '@' . $this->dx_auth->get_user_id() . '@' . get_gmt_time(strtotime($checkin)) . '@' . get_gmt_time(strtotime($checkout)) . '@' . $number_of_guests . '@' . $is_travelCretids . '@' . $user_travel_cretids . '@' . get_currency_value1($id, $to_pay) . '@' . get_currency_value1($id, $admin_commission) . '@' . $contact_key . '@' . get_currency_value1($id, $cleaning) . '@' . get_currency_value1($id, $security) . '@' . get_currency_value1($id, $extra_guest_price) . '@' . $guests;

            $this->session->set_userdata('custom', $custom);

            if ($this->session->userdata('final_amount') != '')
            {
                $amt = $this->session->userdata('final_amount');
                $this->session->unset_userdata('final_amount');
            }
            else
            {
                $amt = get_currency_value1($id, $amt);
            }

            $to_buy = array(
                'desc' => 'Purchase from ACME Store',
                'currency' => get_currency_code(),
                'type' => 'sale',
                'return_URL' => site_url('payments/paypal_success'),
// see below have a function for this -- function back()
// whatever you use, make sure the URL is live and can process
// the next steps
                'cancel_URL' => site_url('payments/paypal_cancel'), // this goes to this controllers index()
                'shipping_amount' => 0,
                'get_shipping' => false);
// I am just iterating through $this->product from defined
// above. In a live case, you could be iterating through
// the content of your shopping cart.
//foreach($this->product as $p) {
            $temp_product = array(
                'name' => $this->dx_auth->get_site_title() . ' Transaction',
                'number' => $placeid,
                'quantity' => 1, // simple example -- fixed to 1
                'amount' => $amt);

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

        function paypal_cancel()
        {
            $data['title'] = "Payment Failed";
            $data["meta_keyword"] = "";
            $data["meta_description"] = "";

            $data['message_element'] = "payments/paypal_cancel";
            $this->load->view('template', $data);
        }

        function paypal_success()
        {
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

                    $custom = $this->session->userdata('custom');
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

                    $amt = $do_ec_return['PAYMENTINFO_0_AMT'];

                    $list['price'] = $amt;
                    $currency = $do_ec_return['PAYMENTINFO_0_CURRENCYCODE'];

                    $list['payment_id'] = 2;
                    $list['credit_type'] = 1;
                    $list['transaction_id'] = 0;

                    $is_travelCretids = $data[5];
                    $user_travel_cretids = $data[6];

                    $list['topay'] = $amt - $data[8];
                    $list['currency'] = $query1->row()->currency;
                    $list['admin_commission'] = $data[8];
                    $list['cleaning'] = $data[10];
                    $list['security'] = $data[11];
                    $list['extra_guest_price'] = $data[12];
                    $list['guest_count'] = $data[13];

                    if ($contact_key != "None")
                    {
                        $list['status'] = 1;
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
                    }
                    else
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

                    $conversation_result = $this->db->select('conversation_id')->order_by('conversation_id', 'desc')->limit(1)->get('messages');

                    if ($conversation_result->num_rows() == 0)
                    {
                        $conversation_id = 1;
                    }
                    else
                    {
                        $conversation_id = $conversation_result->row()->conversation_id + 1;
                    }

                    //Send Message Notification
                    $insertData = array(
                        'list_id' => $list['list_id'],
                        'reservation_id' => $reservation_id,
                        'conversation_id' => $conversation_id,
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

                    $referral_amount = $this->db->where('id', $this->dx_auth->get_user_id())->get('users')->row()->referral_amount;
                    if ($referral_amount > 100)
                    {
                        $this->db->set('referral_amount', $referral_amount - 100)->where('id', $this->dx_auth->get_user_id())->update('users');
                    }
                    else
                    {
                        $this->db->set('referral_amount', 0)->where('id', $this->dx_auth->get_user_id())->update('users');
                    }

                    $data['title'] = "Payment Success !";
                    $data['message_element'] = "payments/paypal_success";
                    $this->load->view('template', $data);
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

        function paypal_ipn()
        {
            $logfile = 'ipnlog/' . uniqid() . '.html';
            $logdata = "<pre>\r\n" . print_r($_POST, true) . '</pre>';
            file_put_contents($logfile, $logdata);
        }

        function _error($ecd)
        {
            echo "<br>error at Express Checkout<br>";
            echo "<pre>" . print_r($ecd, true) . "</pre>";
            echo "<br>CURL error message<br>";
            echo 'Message:' . $this->session->userdata('curl_error_msg') . '<br>';
            echo 'Number:' . $this->session->userdata('curl_error_no') . '<br>';
        }

        //Date convert module
        public function dateconvert($date)
        {
            $ckout = explode('/', $date);
            $diff = $ckout[2] . '-' . $ckout[0] . '-' . $ckout[1];
            return $diff;
        }

    }

    /* End of file payments.php */
    /* Location: ./app/controllers/payments.php */
?>
