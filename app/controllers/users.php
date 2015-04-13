<?php

    /**
     * DROPinn Users Controller Class
     *
     * It helps to control the users profile
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Users
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Users extends CI_Controller
    {

        // Used for registering and changing password form validation
        var $min_username = 4;
        var $max_username = 20;
        var $min_password = 4;
        var $max_password = 20;

        public function Users()
        {
            parent::__construct();
            $this->load->helper('url');
            $this->load->helper('cookie');
            $this->load->helper('form');
            $this->load->helper('file');

            $this->load->library('form_validation');
            $this->load->library('twconnect');
            //include_once APPPATH."libraries/linkedin_OAuth/linkedClass.php";
            // $this->load->library('linkedin_OAuth/OAuth_linkedin');
            //$this->load->library('gpluslibrary');

            $this->load->model('Users_model');
            $this->load->model('Trips_model');
            $this->load->model('Email_model');
            $this->load->model('Message_model');
            $this->load->model('Referrals_model');
            $this->facebook_lib->enable_debug(TRUE);
        }

        public function redirect()
        {

            $twitter_connection = $this->twconnect->twredirect('users/callback');


            if (!$twitter_connection)
            {
                echo 'Could not connect to Twitter. Refresh the page or try again later.';
                echo '<script> location.reload(); </script>';
            }
        }

        public function callback()
        {


            $twitter_connection = $this->twconnect->twprocess_callback();

            if ($twitter_connection)
            {
                redirect('users/success');
            }
            else
            {
                redirect('users/failure');
            }
        }

        public function success()
        {
            $twitter_id = $this->twconnect->tw_user_id;
            $username = $this->twconnect->tw_user_name;
            $this->twconnect->twaccount_verify_credentials();
            $checknew_user = $this->Users_model->getUserStatusByTwitterUid($twitter_id);
            $user_info = $this->twconnect->tw_user_info;
            if ($user_info == '')
            {
                redirect('users/signin');
            }
            $profile_url_large = str_replace("_normal", "", $user_info->profile_image_url);

            $profile = array('image_url' => $profile_url_large);
            $this->session->set_userdata($profile);
            if (!$checknew_user)
            {
                //$user_id = $this->Users_model->createTwitterUser($twitter_id,$username);


                $data['title'] = 'Twitter SignUp';
                $data['message_element'] = "users/view_popup";

                $this->load->view('template', $data);

                //echo "signup";
            }
            else
            {

                $user_info = $this->Users_model->getUserInfobyTwitterid($twitter_id);
                $this->Users_model->TwitterUserLast($twitter_id);
                $user = array(
                    'DX_user_id' => $user_info['id'],
                    'DX_username' => $user_info['username'],
                    'DX_emailId' => $user_info['email'],
                    'DX_role_id' => $user_info['role_id'],
                    'DX_logged_in' => TRUE
                );

                $this->session->set_userdata($user);
                $condition = array("id" => $this->dx_auth->get_user_id());
                $data['via_login'] = "twitter";
                $this->Common_model->updateTableData('users', NULL, $condition, $data);
                redirect('users/signup');
            }
        }

        public function failure()
        {
            //echo '<p>Twitter connect failed</p>';
            redirect('users/signin');
        }

        public function edit()
        {

            //	if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                if ($this->input->post())
                {
                    $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

                    $this->form_validation->set_rules('phnum', 'Phone', 'trim|xss_clean|callback__check_phone_no');
                    $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email');

                    $this->form_validation->set_rules('Fname', 'Fname', 'trim|xss_clean');
                    $this->form_validation->set_rules('Lname', 'Lname', 'trim|xss_clean');
                    $this->form_validation->set_rules('live', 'live', 'trim|xss_clean');
                    $this->form_validation->set_rules('work', 'work', 'trim|xss_clean');
                    $this->form_validation->set_rules('desc', 'desc', 'trim|xss_clean');

                    if ($this->form_validation->run())
                    {

                        $data = array(
                            'Fname' => $this->input->post('Fname'),
                            'Lname' => $this->input->post('Lname'),
                            'phnum' => $this->input->post('phnum'),
                            'live' => $this->input->post('live'),
                            'work' => $this->input->post('work'),
                            'describe' => $this->input->post('desc')
                        );

                        $param = $this->dx_auth->get_user_id();
                        $data2['photo_status'] = 1;
                        $this->db->where('id', $param);
                        $this->db->update('users', $data2);
                        $rows = $this->Common_model->getTableData('profiles', array('id' => $param))->num_rows();
                        if ($rows == 0)
                        {
                            $data['id'] = $param;
                            $this->Common_model->insertData('profiles', $data);
                        }
                        else
                        {
                            $this->db->where('id', $param);
                            $this->db->update('profiles', $data);
                        }
                        $email_check = $this->db->where('email', $this->input->post('email'))->where('id', $this->dx_auth->get_user_id())->from('users')->get();
                        if ($email_check->num_rows() != 1)
                        {
                            $all_email_check = $this->db->where('email', $this->input->post('email'))->from('users')->get();
                            if ($all_email_check->num_rows() != 1)
                            {
                                $this->edit_email_verify($this->input->post('email'));
                            }
                            else
                            {
                                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Your New Email Address Already Used.')));
                                redirect('users/edit');
                            }
                        }
                        $data2['email'] = $this->input->post('email');
                        $data2['timezone'] = $this->input->post('timezones');
                        $this->db->where('id', $param);
                        $this->db->update('users', $data2);

                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Information updated successfully.')));
                        redirect('users/edit');
                    }
                }

                $data['user_id'] = $this->dx_auth->get_user_id();
                $data['profile'] = $this->Common_model->getTableData('profiles', array('id' => $data['user_id']))->row();
                $data['email_id'] = $this->Common_model->getTableData('users', array('id' => $data['user_id']))->row()->email;
                $data['title'] = get_meta_details('Edit_your_Profile', 'title');
                $data["meta_keyword"] = get_meta_details('Edit_your_Profile', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Edit_your_Profile', 'meta_description');
                $data['message_element'] = "users/view_edit_profile";
                $this->load->view('template', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        function _check_phone_no($value)
        {
            $value = trim($value);
            if ($value == '')
            {
                return TRUE;
            }
            else
            {
                if (preg_match('/^\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}$/', $value))
                {
                    return preg_replace('/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/', '($1) $2-$3', $value);
                }
                else
                {
                    $this->form_validation->set_message('_check_phone_no', translate('Give a valid phone number'));
                    return FALSE;
                }
            }
        }

        public function photo($id = "")
        {
            $target_path = realpath(APPPATH . '../images/users');

            if (!is_writable(dirname($target_path)))
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Sorry! Destination folder is not writable.')));
                redirect('users/edit', 'refresh');
            }
            else
            {
                if (!is_dir(realpath(APPPATH . '../images/users') . '/' . $id))
                {
                    mkdir(realpath(APPPATH . '../images/users') . '/' . $id, 0777, true);
                }

                $target_path = $target_path . '/' . $id . '/userpic.jpg';



                if ($_FILES['upload123']['name'] != '')
                {
                    move_uploaded_file($_FILES['upload123']['tmp_name'], $target_path);
                    $thumb1 = realpath(APPPATH . '../images/users') . '/' . $id . '/userpic_thumb.jpg';
                    GenerateThumbFile($target_path, $thumb1, 107, 78);
                    $thumb2 = realpath(APPPATH . '../images/users') . '/' . $id . '/userpic_profile.jpg';
                    GenerateThumbFile($target_path, $thumb2, 209, 209);
                    $thumb3 = realpath(APPPATH . '../images/users') . '/' . $id . '/userpic_home.jpg';
                    GenerateThumbFile($target_path, $thumb3, 40, 40);
                    $this->db->query('update users set photo_status = 1 where id = ' . $id);
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Your profile photo updated successfully.')));
                    redirect('users/edit', 'refresh');
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Please browse your profile photo.')));
                    redirect('users/edit', 'refresh');
                }
            }
        }

        public function recommendation()
        {
            //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()))
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $this->load->library('email');

                $username = $this->dx_auth->get_username();
                $user_id = $this->dx_auth->get_user_id();

                if ($this->input->post())
                {

                    $share_url = $this->input->post('share_url');
                    $email = $this->input->post('email_to_friend');
                    $mail_list = explode(',', $email);

                    $admin_email = $this->dx_auth->get_site_sadmin();

                    $email_name = 'user_vouch';

                    $mailer_mode = $this->Common_model->getTableData('email_settings', array('code' => 'MAILER_MODE'))->row()->value;

                    if ($mailer_mode == 'html')
                        $anchor = anchor('users/vouch/' . $user_id, translate('Click here'));
                    else
                        $anchor = site_url('users/vouch/' . $user_id);

                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($username), "{click_here}" => $anchor);


                    if (!empty($mail_list))
                    {
                        foreach ($mail_list as $email_to)
                        {
                            if ($this->email->valid_email($email_to))
                            {
                                //Send Mail
                                $this->Email_model->sendMail($email_to, $admin_email, $this->dx_auth->get_site_title(), $email_name, $splVars);
                            }
                            else
                            {
                                $data['email_status'][] = $email_to;
                            }
                        }
                    }
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Mail sent successfully.')));
                }

                $data['title'] = get_meta_details('Your_recommendation_details', 'title');
                $data["meta_keyword"] = get_meta_details('Your_recommendation_details', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Your_recommendation_details', 'meta_description');

                $data['message_element'] = "users/view_recommendations";
                $this->load->view('template', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        public function reviews()
        {
            //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $conditionsFrom = array('userto' => $this->dx_auth->get_user_id());
                $conditionsBy = array('userby' => $this->dx_auth->get_user_id());

                $data['reviewfrom'] = $this->Trips_model->get_review($conditionsFrom);
                $data['recommendsfrom'] = $this->Common_model->getTableData('recommends', $conditionsFrom);

                $data['reviewby'] = $this->Trips_model->get_review($conditionsBy);
                $data['recommendsby'] = $this->Common_model->getTableData('recommends', $conditionsBy);

                $conditions = array('userto' => $this->dx_auth->get_user_id());
                $data['stars'] = $this->Trips_model->get_review_sum($conditions)->row();

                $data['title'] = get_meta_details('Your_Reviews_and_Recommendation', 'title');
                $data["meta_keyword"] = get_meta_details('Your_Reviews_and_Recommendation', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Your_Reviews_and_Recommendation', 'meta_description');

                $data['message_element'] = "users/view_reviews";
                $this->load->view('template', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        public function vouch()
        {
            $param = $this->uri->segment(3);

            if ($param == '')
            {
                redirect('info/deny');
            }
            //Insert the Recommendation detial
            if ($this->input->post())
            {
                if ($this->input->post('userby') == $this->input->post('userto'))
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Sorry! You cannot recommend yourself.')));
                    redirect('users/vouch/' . $param, 'refresh');
                }
                $this->form_validation->set_rules('message', 'Recomment', 'required|trim|xss_clean');
                if ($this->form_validation->run())
                {
                    $data['userby'] = $this->input->post('userby');
                    $data['userto'] = $this->input->post('userto');
                    $data['message'] = $this->input->post('message');
                    $data['created'] = local_to_gmt();

                    $this->Common_model->insertData('recommends', $data);
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Your recommend added successfully.')));
                    redirect('users/vouch/' . $param, 'refresh');
                }
            }
            $user_id = $param;
            $getUser = $this->Common_model->getTableData('users', array('id' => $user_id));
            $data['user'] = $getUser->row();

            if ($getUser->num_rows() <= 0)
            {
                redirect('info/deny');
            }

            $data['lists'] = $this->Common_model->getTableData('list', array('user_id' => $user_id, "status =" => 1, "is_enable" => 1));
            $data['recommends'] = $this->Common_model->getTableData('recommends', array('userto' => $user_id));

            $data['title'] = get_meta_details('Recommend_your_friends', 'title');
            $data["meta_keyword"] = get_meta_details('Recommend_your_friends', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Recommend_your_friends', 'meta_description');

            $data['message_element'] = "users/view_vouch";
            $this->load->view('template', $data);
        }

        public function popup()
        {
            $this->load->view('template', $data);
        }

        public function signup()
        {

            if ($this->dx_auth->is_logged_in())
            {

                if ($this->session->userdata('redirect_to') == FALSE)
                {
                    redirect('home/dashboard');
                }
                else
                {

                    redirect($this->session->userdata('redirect_to'));
                }
            } // End if
            if ($this->input->post())
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
                        /*
                          $email_name = 'User_join_to_Referal_mail';
                          $splVars    = array("{site_name}" => $this->dx_auth->get_site_title(),"{friend_name}" => $first_name);
                          //Send Mail to refeeral user
                          $this->Email_model->send_ref_user($email_user,$admin_email,ucfirst($admin_name),$email_name,$splVars); */


                        $this->session->unset_userdata('ref_id');
                    }
                    $referral_code['referral_code'] = $this->session->userdata('referral_code');
                    $this->db->set('trips_referral_code', $referral_code['referral_code'])->where('id', $this->dx_auth->get_user_id())->update('users');
                    $this->db->set('list_referral_code', $referral_code['referral_code'])->where('id', $this->dx_auth->get_user_id())->update('users');
                    $this->session->unset_userdata('referral_code');

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

                    $referralcode = $referral_code['referral_code'];


                    if ($referralcode != "")
                    {
                        $this->Referrals_model->get_user_refId($referralcode);
                        $user = $this->Referrals_model->get_user_refId($referralcode)->result();
                        foreach ($user as $us)
                        {
                            $email_user = $us->email;
                            $user_name = $us->username;
                        }
                        $admin_email = $this->dx_auth->get_site_sadmin();
                        $admin_name = $this->dx_auth->get_site_title();
                        $email_name = 'User_join_to_Referal_mail';
                        $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{friend_name}" => $first_name, "{user_name}" => $email_user, "{refer_name}" => $user_name);
                        $this->Email_model->sendMail($email_user, $admin_email, ucfirst($admin_name), $email_name, $splVars);
                    }
                    //End of adding it
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Logged in successfully.')));
                    redirect('home/dashboard', 'refresh');
                }
            }
            if ($this->input->get('airef'))
            {
                $check = $this->db->where('referral_code', $this->input->get('airef'))->get('users');
                $this->session->set_userdata('referral_code', $this->input->get('airef'));

                if ($check->num_rows() != 0)
                {
                    $this->session->set_userdata('referral_code', $this->input->get('airef'));
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Sorry Your Referral code is wrong.')));
                    redirect('users/signUp', 'refresh');
                }
            }

            $data["title"] = get_meta_details('Sign_Up_for_the_site', 'title');
            $data["meta_keyword"] = get_meta_details('Sign_Up_for_the_site', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Sign_Up_for_the_site', 'meta_description');
            $data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
            $data['message_element'] = "users/view_signUp";
            $this->load->view('template', $data);
        }

        function _check_user_name($username)
        {
            if ($this->dx_auth->is_username_available($username))
            {
                return true;
            }
            else
            {
                $this->form_validation->set_message('_check_user_name', translate('Sorry username is not available'));
                return false;
            }//If end 
        }

        function _check_user_email($email)
        {
            if ($this->dx_auth->is_email_available($email))
            {
                return true;
            }
            else
            {
                $this->form_validation->set_message('_check_user_email', translate('Sorry this email has already been registered'));
                return false;
            }//If end 
        }

        /* script for sign in
         * 
         */

        public function signin($param = '', $param1 = '')
        {

            if ($this->dx_auth->is_logged_in())
            {
                header("Cache-Control: no-cache, must-revalidate");
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                redirect('home/dashboard');
            }

            $this->session->unset_userdata('image_url');

            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post())
            {
                if (!$this->dx_auth->is_logged_in())
                {
                    // Set form validation rules
                    $this->form_validation->set_rules('username', 'Username or Email', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('password', 'password', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('remember', 'Remember me', 'integer');

                    if ($this->form_validation->run())
                    {
                        $username = $this->input->post("username");
                        $password = $this->input->post("password");

                        if ($this->dx_auth->login($username, $password, $this->form_validation->set_value('TRUE')))
                        {
                            // Redirect to homepage
                            $newdata = array(
                                'user' => $this->dx_auth->get_user_id(),
                                'username' => $this->dx_auth->get_username(),
                                'logged_in' => TRUE
                            );
                            $this->session->set_userdata($newdata);

                            if ($this->session->userdata('redirect_to'))
                            {
                                $redirect_to = $this->session->userdata('redirect_to');
                                $this->session->unset_userdata('redirect_to');
                                redirect($redirect_to, 'refresh');
                            }
                            else
                            {
                                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Logged in successfully.')));
                                redirect('home/dashboard/', 'refresh');
                            }
                        }
                        else
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Either the username or password is wrong. Please try again!')));
                            redirect('users/signin');
                        }
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('You are already logged in. Logout to login again!')));
                    redirect('home/index', 'refresh');
                }
            }

            if ($param == 'logout')
            {
                if ($param1 == 1)
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('You are Banned by admin.')));
                    redirect('users/signin');
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('You are logged out successfully.')));
                    redirect('users/signin');
                }
            }

            $data["title"] = get_meta_details('Sign_In / Sign_Up', 'title');
            $data["meta_keyword"] = get_meta_details('Sign_In / Sign_Up', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Sign_In / Sign_Up', 'meta_description');
            $data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
            $data['message_element'] = "users/view_signIn"; //from template
            $this->load->view('template', $data);
        }

        function login()
        {
            $data['title'] = get_meta_details('Sign_In / Sign_up', 'title');
            $data["meta_keyword"] = get_meta_details('Sign_In / Sign_up', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Sign_In / Sign_up', 'meta_description');
            $data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
            $data['message_element'] = "users/view_signIn";

            $this->load->view('template', $data);
        }

        function logout()
        {
            $data["title"] = get_meta_details('Logout_Shortly', 'title');
            $data["meta_keyword"] = get_meta_details('Logout_Shortly', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Logout_Shortly', 'meta_description');

            $is_banned = $this->db->where('id', $this->dx_auth->get_user_id())->where('banned', 1)->get('users')->num_rows();

            //$this->dx_auth->logout();  
            $user = array(
                'DX_user_id' => '',
                'DX_username' => '',
                'DX_emailId' => '',
                'DX_refId' => '',
                'DX_role_id' => '',
                'DX_role_name' => '',
                'DX_parent_roles_id' => '', // Array of parent role_id
                'DX_parent_roles_name' => '', // Array of parent role_name
                'DX_permission' => '',
                'DX_parent_permissions' => '',
                'DX_logged_in' => TRUE
            );
            $this->session->unset_userdata($user);
            $this->session->unset_userdata('image_url');

            if ($this->facebook_lib->logged_in())
            {
                $facebook->destroySession();
                $this->session->sess_destroy();
            }
            redirect('users/signin/logout/' . $is_banned);
        }

        function change_password()
        {
            // Check if user logged in or not
            // if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()))
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $val = $this->form_validation;
                // Set form validation
                $val->set_rules('old_password', 'Old Password', 'required|trim|xss_clean|min_length[' . $this->min_password . ']|max_length[' . $this->max_password . ']|callback__check_password');
                $val->set_rules('new_password', 'New Password', 'required|trim|xss_clean|min_length[' . $this->min_password . ']|max_length[' . $this->max_password . ']|matches[confirm_new_password]');
                $val->set_rules('confirm_new_password', 'Confirm new Password', 'required|trim|xss_clean');

                // Validate rules and change password
                /* 	if ($val->run() AND $this->dx_auth->change_password($val->set_value('old_password'), $val->set_value('new_password')))
                  {
                  $admin_email = $this->dx_auth->get_site_sadmin();
                  $admin_name  = $this->dx_auth->get_site_title();

                  $email_name  = 'reset_password';
                  $splVars     = array("{site_name}" => $this->dx_auth->get_site_title(), "{password}" => $val->set_value('new_password'));

                  //Send Mail
                  $this->Email_model->sendMail($this->dx_auth->get_emailId(), $admin_email, ucfirst($admin_name), $email_name, $splVars);

                  $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success','Your password has successfully been changed.'));
                  redirect('users/change_password/'.$this->dx_auth->get_user_id());
                  } */
                if ($val->run())
                {
                    $result = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get();

                    $old_pass = $this->_check_password($this->input->post('old_password'));

                    if ($result->num_rows() != 0)
                    {
                        if ($old_pass == 1)
                        {
                            $new_pass = crypt($this->dx_auth->_encode($this->input->post('new_password')));
                            $this->db->where('id', $this->dx_auth->get_user_id())->update('users', array('password' => $new_pass));

                            $admin_email = $this->dx_auth->get_site_sadmin();
                            $admin_name = $this->dx_auth->get_site_title();

                            $email_name = 'reset_password';
                            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{password}" => $val->set_value('new_password'));

                            //Send Mail
                            $this->Email_model->sendMail($this->dx_auth->get_emailId(), $admin_email, ucfirst($admin_name), $email_name, $splVars);

                            $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Your password has successfully been changed.')));
                            redirect('users/change_password/' . $this->dx_auth->get_user_id());
                        }
                    }
                }
                else
                {
                    $data['title'] = get_meta_details('Change_Password', 'title');
                    $data["meta_keyword"] = get_meta_details('Change_Password', 'meta_keyword');
                    $data["meta_description"] = get_meta_details('Change_Password', 'meta_description');

                    $data['message_element'] = 'users/view_change_password';
                    $this->load->view('template', $data);
                }
            }
            else
            {
                // Redirect to login page
                redirect('users/signin');
            }
        }

        function _check_password()
        {
            $password = $this->input->post('old_password');

            $user_id = $this->dx_auth->get_user_id();


            $stored_hash = get_user_by_id($user_id)->password;


            $password = $this->dx_auth->_encode($password);

            if (crypt($password, $stored_hash) === $stored_hash)
            {
                return true;
            }
            else
            {

                $this->form_validation->set_message('_check_password', translate('Your Old Password Is Wrong'));
                return false;
            }//If end
        }

        //Ajax Apge
        function forgot_password()
        {
            $val = $this->form_validation;
            $this->load->library(array('email', 'table'));
            // Set form validation rules
            if ($this->input->post("email"))
            {
                $val->set_rules('email', translate('Please Enter the Valid Email'), 'required|valid_email');

                if ($this->form_validation->run())
                {
                    extract($this->input->post());

                    $conditions["email"] = $email;

                    $conditions['banned'] = '0';

                    $members_query = $this->Common_model->getTableData("users", $conditions);

                    $members = $members_query->result();

                    if (count($members) == 0)
                    {
                        echo translate("This email address doesn't exist in our database");

                        exit;
                    }
                    else
                    {
                        $data['password'] = $this->dx_auth->_gen_pass();

                        // Encode & Crypt password
                        $encode = crypt($this->dx_auth->_encode($data['password']));

                        $user_detail = $this->Users_model->get_user_by_email($email)->result();

                        if (count($user_detail))
                        {
                            $this->Users_model->set_user($user_detail[0]->id, array("password" => $encode));

                            $admin_email = $this->dx_auth->get_site_sadmin();
                            $admin_name = $this->dx_auth->get_site_title();

                            $email_name = 'forgot_password';
                            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{email}" => $email, "{password}" => $data['password'], "{date}" => date('m/d/Y'), "{time}" => date('g:i A'));

                            //Send Mail
                            $this->Email_model->sendMail($email, $admin_email, ucfirst($admin_name), $email_name, $splVars);

                            echo translate("An email has been sent to your email with instructions with how to activate your new password.");
                            exit;
                        }
                    }
                    redirect('users/signin');
                }
                else
                {
                    echo translate("This is invalid email address");
                    exit;
                }
            }

            $this->load->view(THEME_FOLDER . '/users/view_forgot_password');
        }

        function reset_password()
        {
            // Get username and key
            $username = $this->uri->segment(3);
            $key = $this->uri->segment(4);

            // Reset password
            if ($this->dx_auth->reset_password($username, $key))
            {
                $data['auth_message'] = translate('You have successfully reset you password') . ', ' . anchor(site_url($this->dx_auth->login_uri), translate('Login'));
                $this->load->view($this->dx_auth->reset_password_success_view, $data);
            }
            else
            {
                $data['auth_message'] = translate('Reset failed. Your username and key are incorrect. Please check your email again and follow the instructions.');
                $this->load->view($this->dx_auth->reset_password_failed_view, $data);
            }
        }

        public function change_language()
        {
            $string_value = $this->input->post('lang_code');
            $rows = $this->Common_model->getTableData('language', array('code' => $string_value))->row();
            $this->session->set_userdata('language', $rows->name);
            $this->session->set_userdata('locale', $string_value);
        }

        public function change_currency()
        {
            $string_value = $this->input->post('currency_code');

            $this->session->set_userdata('locale_currency', $string_value);
        }

        public function Twitter_MailId_Popup()
        {
            $twitter_id = $this->twconnect->tw_user_id;
            //$user_id = $this->twitter->get_userId();
            $mailId = $this->input->post('email');

            $username = $this->input->post('username');

            $user_id = $this->Users_model->createTwitterUser($twitter_id, $username);

            $query_users = $this->db->query('UPDATE users SET email="' . $mailId . '" WHERE twitter_id="' . $twitter_id . '"');
            $query_profiles = $this->db->query('UPDATE profiles SET email="' . $mailId . '" WHERE id="' . $user_id . '"');

            $referral_code['referral_code'] = $this->session->userdata('referral_code');

            $this->db->set('trips_referral_code', $referral_code['referral_code'])->where('twitter_id', $twitter_id)->update('users');
            $this->db->set('list_referral_code', $referral_code['referral_code'])->where('twitter_id', $twitter_id)->update('users');

            $user = array(
                'DX_user_id' => $user_id,
                'DX_username' => $username,
                'DX_emailId' => $mailId,
                //	'DX_refId'				 => $data->ref_id,
                //	'DX_role_id'			 => $data->role_id,			
                //	'DX_role_name'			 => $role_data['role_name'],
                //	'DX_parent_roles_id'	 => $role_data['parent_roles_id'],	// Array of parent role_id
                ///	'DX_parent_roles_name'	 => $role_data['parent_roles_name'], // Array of parent role_name
                //	'DX_permission'			 => $role_data['permission'],
                //	'DX_parent_permissions'	 => $role_data['parent_permissions'],			
                'DX_logged_in' => TRUE
            );

            $this->session->set_userdata($user);
            $condition = array("id" => $this->dx_auth->get_user_id());
            $data['via_login'] = "twitter";
            $this->Common_model->updateTableData('users', NULL, $condition, $data);

            $referralcode = $this->session->userdata('referral_code');

            if ($referralcode != "")
            {
                $this->Referrals_model->get_user_refId($referralcode);
                $user = $this->Referrals_model->get_user_refId($referralcode)->result();
                foreach ($user as $us)
                {
                    $email_user = $us->email;
                    $user_name = $us->username;
                }

                $admin_name = $this->dx_auth->get_site_title();
                $email_name = 'User_join_to_Referal_mail';
                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{friend_name}" => $username, "{user_name}" => $email_user, "{refer_name}" => $user_name);
                $this->Email_model->sendMail($email_user, $admin_email, ucfirst($admin_name), $email_name, $splVars);
            }
            redirect('users/signup');
        }

        function check_username_twitter()
        {
            $username = $this->input->get('username');
            $data['checked'] = $this->Users_model->check_username_twitter($username);
            echo json_encode($data['checked']);
        }

        function check_email_twitter()
        {
            $email = $this->input->get('email');
            $data['checked'] = $this->Users_model->check_email_twitter($email);
            echo json_encode($data['checked']);
        }

        function canvas()
        {
            $path = base_url() . "users/signup";
            echo("<script> top.location.href='" . $path . "'</script>");
        }

        function google_signin()
        {
            require_once APPPATH . 'libraries/openid.php';

            $openid = new LightOpenID(base_url() . 'users/google_signin');

            if ($openid->mode)
            {
                if ($openid->mode == 'cancel')
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('User has canceled authentication !.')));
                    redirect('users/signin');
                }
                elseif ($openid->validate())
                {
                    $data = $openid->getAttributes();
                    $email = $data['contact/email'];
                    $first = $data['namePerson/first'];
                    $last = $data['namePerson/last'];
                    $explode = explode('@', $email);
                    $user = $explode[0];
                    if ($this->dx_auth->login($user, $email))
                    {
                        // Redirect to homepage
                        $newdata = array(
                            'user' => $this->dx_auth->get_user_id(),
                            'username' => $this->dx_auth->get_username(),
                            'logged_in' => TRUE
                        );
                        $this->session->set_userdata($newdata);

                        $condition = array("id" => $this->dx_auth->get_user_id());
                        $data_via['via_login'] = "google";
                        $this->Common_model->updateTableData('users', NULL, $condition, $data_via);

                        if ($this->session->userdata('redirect_to'))
                        {
                            $redirect_to = $this->session->userdata('redirect_to');
                            $this->session->unset_userdata('redirect_to');
                            redirect($redirect_to, 'refresh');
                        }
                        else
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Logged in successfully.')));
                            redirect('home/dashboard/', 'refresh');
                        }
                    }
                    else
                    {
                        if ($this->_check_user_name($user) && $this->_check_user_email($email))
                        {
                            $data = $this->dx_auth->register($user, $email, $email);
                            $this->dx_auth->login($user, $email, 'TRUE');

                            $condition = array("id" => $this->dx_auth->get_user_id());
                            $data_via['via_login'] = "google";
                            $this->Common_model->updateTableData('users', NULL, $condition, $data_via);

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


                            //referral amount 
                            $referral_code['referral_code'] = $this->session->userdata('referral_code');
                            $this->db->set('trips_referral_code', $referral_code['referral_code'])->where('id', $this->dx_auth->get_user_id())->update('users');
                            $this->db->set('list_referral_code', $referral_code['referral_code'])->where('id', $this->dx_auth->get_user_id())->update('users');
                            $this->session->unset_userdata('referral_code');

                            $notification = array();
                            $notification['user_id'] = $this->dx_auth->get_user_id();
                            $notification['new_review '] = 1;
                            $notification['leave_review'] = 1;
                            $this->Common_model->insertData('user_notification', $notification);

                            //Need to add this data to user profile too 
                            $add['Fname'] = $first;
                            $add['Lname'] = $last;
                            $add['id'] = $this->dx_auth->get_user_id();
                            $add['email'] = $email;
                            $this->Common_model->insertData('profiles', $add);

                            //mail send to referred 
                            $referralcode = $referral_code['referral_code'];


                            if ($referralcode != "")
                            {
                                $this->Referrals_model->get_user_refId($referralcode);
                                $user = $this->Referrals_model->get_user_refId($referralcode)->result();
                                foreach ($user as $us)
                                {
                                    $email_user = $us->email;
                                    $user_name = $us->username;
                                }
                                $admin_email = $this->dx_auth->get_site_sadmin();
                                $admin_name = $this->dx_auth->get_site_title();
                                $email_name = 'User_join_to_Referal_mail';
                                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{friend_name}" => $first, "{user_name}" => $email_user, "{refer_name}" => $user_name);
                                $this->Email_model->sendMail($email_user, $admin_email, ucfirst($admin_name), $email_name, $splVars);
                            }


                            //End of adding it
                            $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Logged in successfully.')));
                            redirect('home/dashboard', 'refresh');
                        }
                        else
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Your Username or Email Already Registered.')));
                            redirect('users/signin');
                        }
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Sorry! Connection Error.')));
                    redirect('users/signin');
                }
            }
            else
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Sorry! Connection Error.')));
                redirect('users/signin');
            }
        }

        public function verify()
        {
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                if ($this->input->get())
                {
                    $passkey = $this->input->get('passkey');
                    $result = $this->db->where('email_verification_code', $passkey)->where('id', $this->dx_auth->get_user_id())->select('*')->from('users')->get();
                    if ($result->num_rows() == 1)
                    {
                        $this->db->where('email_verification_code', $passkey)->update('users', array('email_verify' => 'yes'));
                        //  $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success','Email Address Successfully Verified.'));
                    }
                    else
                    {
                        $this->db->where('email_verification_code', $passkey)->update('users', array('email_verify' => 'no'));
                        //	 $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Email Address Not Verified.'));
                    }
                }
                $data['title'] = get_meta_details('Verification', 'title');
                $data["meta_keyword"] = get_meta_details('Verification', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Verification', 'meta_description');
                $data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
                $data['users'] = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get()->row();
                $data['profiles'] = $this->db->where('id', $this->dx_auth->get_user_id())->from('profiles')->get()->row();
                $data['message_element'] = "users/view_verify";
                $this->load->view('template', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        public function google_verify()
        {
            require_once APPPATH . 'libraries/openid.php';

            $openid = new LightOpenID(base_url() . 'users/google_signin');

            if ($openid->mode)
            {
                if ($openid->mode == 'cancel')
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('User has canceled authentication !.')));
                    redirect('users/signin');
                }
                elseif ($openid->validate())
                {
                    $data = $openid->getAttributes();
                    $email = $data['contact/email'];
                    $result = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get();
                    if ($result->num_rows() == 1)
                    {

                        $this->db->where('id', $this->dx_auth->get_user_id())->update('users', array('google_verify' => 'yes', 'google_email' => "$email"));
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Google Account Successfully Verified.')));
                        redirect('users/verify');
                    }
                    else
                    {
                        $this->db->where('id', $this->dx_auth->get_user_id())->update('users', array('google_verify' => 'no', 'google_email' => 0));
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Google Account Not Verified.')));
                        redirect('users/verify');
                    }
                }
                else
                {
                    $this->db->where('id', $this->dx_auth->get_user_id())->update('users', array('google_verify' => 'no', 'google_email' => 0));
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Google Account Not Verified.')));
                    redirect('users/verify');
                }
            }
        }

        public function facebook_verify()
        {
            $email = $this->input->post('email');
            $id = $this->input->post('id');
            $result = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get();
            if ($result->num_rows() == 1)
            {
                $id = $result->row()->id;
                $this->db->where('id', $id)->update('users', array('facebook_verify' => 'yes', 'facebook_email' => "$email"));
                echo 'verified';
            }
            else
            {
                $this->db->where('id', $this->dx_auth->get_user_id())->update('users', array('facebook_verify' => 'no'));
                echo 'Not Verified';
            }
        }

        public function email_verify()
        {

            $this->load->model('Email_model');

            $email = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get()->row()->email;
            $toEmail = $email;

            $admin_email = $this->dx_auth->get_site_sadmin();
            $fromEmail = $admin_email;
            $fromName = $this->dx_auth->get_site_title();

            $email_name = 'email_verification';

            $link = base_url() . 'users/email_confirmation?passkey=' . md5($toEmail);

            $this->db->where('email', "$toEmail")->update('users', array('email_verification_code' => md5($toEmail)));

            $username = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get()->row()->username;

            $splVars = array("{site_name}" => $fromName, "{click_here}" => $link, "{user_name}" => $username);

            $this->Email_model->sendMail($toEmail, $fromEmail, $fromName, $email_name, $splVars);
            if ($this->input->get('email') == 'verify')
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Email Verification Link Sent To Your Email Address.')));
                redirect('home/verify?email=verify');
            }
            else
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Email Verification Link Sent To Your Email Address.')));
                redirect('users/verify');
            }
        }

        public function email_confirmation()
        {
            $passkey = $this->input->get('passkey');
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {

                $result = $this->db->where('email_verification_code', $passkey)->where('id', $this->dx_auth->get_user_id())->select('*')->from('users')->get();
                if ($result->num_rows() == 1)
                {
                    $this->db->where('email_verification_code', $passkey)->update('users', array('email_verify' => 'yes'));
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Your Email Address Successfully Verified.')));
                    redirect('users/verify');
                }
                else
                {
                    $this->db->where('email_verification_code', $passkey)->update('users', array('email_verify' => 'no'));
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Your Email Address Not Verified.')));
                    redirect('users/verify');
                }
            }
            else
            {
                $this->session->set_userdata('redirect_to', 'users/verify?passkey=' . $passkey);
                redirect('users/signin', 'refresh');
            }
        }

        public function email_verify_disconnect()
        {
            $this->db->where('id', $this->dx_auth->get_user_id())->update('users', array('email_verify' => 'no'));
            $facebook_verify = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get()->row()->facebook_verify;
            $google_verify = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get()->row()->google_verify;
            echo json_encode(array('fb' => $facebook_verify, 'google' => $google_verify));
        }

        public function facebook_verify_disconnect()
        {
            $this->db->where('id', $this->dx_auth->get_user_id())->update('users', array('facebook_verify' => 'no', 'facebook_email' => 0));
            $google_verify = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get()->row()->google_verify;
            $email_verify = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get()->row()->email_verify;
            echo json_encode(array('google' => $google_verify, 'email' => $email_verify));
        }

        public function google_verify_disconnect()
        {
            $this->db->where('id', $this->dx_auth->get_user_id())->update('users', array('google_verify' => 'no', 'google_email' => 0));
            $facebook_verify = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get()->row()->facebook_verify;
            $email_verify = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get()->row()->email_verify;
            echo json_encode(array('fb' => $facebook_verify, 'email' => $email_verify));
        }

        public function google_verify_detail()
        {
            require_once APPPATH . 'libraries/openid.php';

            $openid = new LightOpenID(base_url() . 'users/google_signin');

            if ($openid->mode)
            {
                if ($openid->mode == 'cancel')
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('User has canceled authentication !.')));
                    redirect('users/signin');
                }
                elseif ($openid->validate())
                {
                    $data = $openid->getAttributes();
                    $email = $data['contact/email'];
                    $result = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get();
                    if ($result->num_rows() != 0)
                    {
                        $this->db->where('id', $this->dx_auth->get_user_id())->update('users', array('google_verify' => 'yes', 'google_email' => "$email"));
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Your Google Account Successfully Verified.')));
                        redirect('home/verify?google=verified');
                    }
                    else
                    {
                        $this->db->where('id', $this->dx_auth->get_user_id())->update('users', array('google_verify' => 'no', 'google_email' => 0));
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Your Google Account Not Verified.')));
                        redirect('home/verify?google=not_verified');
                    }
                }
                else
                {
                    $this->db->where('id', $this->dx_auth->get_user_id())->update('users', array('google_verify' => 'no', 'google_email' => 0));
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Your Google Account Not Verified.')));
                    redirect('home/verify?google=not_verified');
                }
            }
        }

        public function edit_email_verify($email)
        {
            $this->load->model('Email_model');

            //$email = $this->db->where('id',$this->dx_auth->get_user_id())->from('users')->get()->row()->email;
            $toEmail = $email;

            $admin_email = $this->dx_auth->get_site_sadmin();
            $fromEmail = $admin_email;
            $fromName = $this->dx_auth->get_site_title();

            $email_name = 'email_verification';

            $link = base_url() . 'users/edit_email_confirmation?passkey=' . md5($toEmail);

            $this->db->where('id', $this->dx_auth->get_user_id())->update('users', array('email_verification_code' => md5($toEmail)));

            $username = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get()->row()->username;

            $splVars = array("{site_name}" => $fromName, "{click_here}" => $link, "{user_name}" => $username);

            $this->session->set_userdata('email', $email);

            $this->Email_model->sendMail($toEmail, $fromEmail, $fromName, $email_name, $splVars);

            $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Email Verification Link Sent To Your Email Address.')));
            redirect('users/edit');
        }

        public function edit_email_confirmation()
        {
            $passkey = $this->input->get('passkey');
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $result = $this->db->where('email_verification_code', $passkey)->where('id', $this->dx_auth->get_user_id())->select('*')->from('users')->get();
                $email = $this->session->userdata('email');
                if ($result->num_rows() == 1)
                {
                    $this->db->where('email_verification_code', $passkey)->update('users', array('email' => "$email"));
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Your Email Address Successfully Verified.')));
                    redirect('users/edit');
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Your Email Address Not Verified.')));
                    redirect('users/edit');
                }
            }
            else
            {
                // $this->session->set_userdata('redirect_to', 'users/verify?passkey='.$passkey);
                redirect('users/signin', 'refresh');
            }
        }

    }

    /* End of file users.php */
    /* Location: ./app/controllers/users.php */
?>
