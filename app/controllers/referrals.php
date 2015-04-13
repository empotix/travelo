<?php

    /**
     * DROPinn Referrals Controller Class
     *
     * It helps to control the referral functionality
     *
     * @package		DROPinn
     * @subpackage	Controllers
     * @category	Referrals
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Referrals extends CI_Controller
    {

        public function Referrals()
        {
            parent::__construct();

            $this->load->helper('url');
            $this->load->helper('form');

            $this->load->library('Form_validation');
            $this->load->library('email');

            $this->load->model('Users_model');
            $this->load->model('Referrals_model');
            $this->load->model('Email_model');

            $this->facebook_lib->enable_debug(TRUE);
        }

        public function index()
        {
            if ((!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()))
            {
                redirect('users/signin', 'refresh');
            }

            $data['fp_appId'] = $this->Common_model->getTableData('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;

            $data['title'] = get_meta_details('Invite_Your_Friends', 'title');
            $data["meta_keyword"] = get_meta_details('Invite_Your_Friends', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Invite_Your_Friends', 'meta_description');
            $data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
            $data['fb_app_secret'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_SECRET'))->row()->string_value;

            $data['username'] = $this->db->where('id', $this->dx_auth->get_user_id())->get('users')->row()->username;

            $code = $this->db->where('id', $this->dx_auth->get_user_id())->get('users');

            if ($code->num_rows() != 0)
            {
                if (!$code->row()->referral_code)
                {
                    $code_data = md5($data['username']);

                    $this->db->set('referral_code', $code_data)->where('id', $this->dx_auth->get_user_id())->update('users');
                }
            }
            $data['referral_code'] = $this->db->where('id', $this->dx_auth->get_user_id())->get('users')->row()->referral_code;
            $data['message_element'] = 'home/referrals';

            $this->load->view('template', $data);
        }

        public function email()
        {
            if ((!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()))
            {
                redirect('users/signin', 'refresh');
            }


            $username = $this->dx_auth->get_username();
            $user_id = $this->dx_auth->get_user_id();
            $email_id = $this->dx_auth->get_emailId();
            $ref_id = $this->dx_auth->get_refId();

            if ($this->input->post())
            {
                $invite_list = $this->input->post('friends');
                $email_text = $this->input->post('email_text');

                $_POST['friend_email'] = implode(',', $invite_list);

                //Set rules
                $this->form_validation->set_rules('friend_email', 'Email', 'required|trim|xss_clean|valid_email');
                if ($this->form_validation->run() && $invite_list[0] != "")
                {
                    $email_name = 'refferal_invite';

                    $mailer_mode = $this->Common_model->getTableData('email_settings', array('code' => 'MAILER_MODE'))->row()->value;

                    if ($mailer_mode == 'html')
                        $anchor = anchor('tell_a_friend?ref=' . $ref_id . '', 'Click here');
                    else
                        $anchor = site_url('tell_a_friend?ref=' . $ref_id);

                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($username), "{dynamic_content}" => $email_text, "{click_here}" => $anchor);

                    $fromEmail = $this->dx_auth->get_site_sadmin();
                    $fromName = $this->dx_auth->get_site_title();

                    if (!empty($invite_list))
                    {
                        foreach ($invite_list as $email_to)
                        {
                            if ($this->email->valid_email($email_to))
                            {
                                //Send Mail
                                $this->Email_model->sendMail($email_to, $fromEmail, $fromName, $email_name, $splVars);
                            }
                            else
                            {
                                $data['email_status'][] = $email_to;
                            }
                        }
                    }
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Invitation sent successfully.')));
                    redirect('referrals/email');
                }
            }

            $data['title'] = get_meta_details('Invite_Your_Friends -Email', 'title');
            $data["meta_keyword"] = get_meta_details('Invite_Your_Friends -Email', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Invite_Your_Friends -Email', 'meta_description');

            $data['message_element'] = 'referrals/view_referrals_email';
            $this->load->view('template', $data);
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

        public function booking()
        {
            if ((!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()))
            {
                redirect('users/signin', 'refresh');
            }
            if ($this->session->userdata('full_cretids'))
            {
                $list = array();

                $list['list_id'] = $this->session->userdata('list_id');
                $list['userby'] = $this->dx_auth->get_user_id();

                $query1 = $this->Common_model->getTableData('list', array('id' => $list['list_id']));
                $buyer_id = $query1->row()->user_id;

                $list['userto'] = $buyer_id;
                $list['checkin'] = $this->session->userdata('checkin');
                $list['checkout'] = $this->session->userdata('checkout');
                $list['price'] = $this->session->userdata('price');
                $list['credit_type'] = 3; //full credit
                $list['status'] = 1;

                $admin_email = $this->dx_auth->get_site_sadmin();
                $admin_name = $this->dx_auth->get_site_title();

                $username = $this->dx_auth->get_username();
                $user_id = $this->dx_auth->get_user_id();
                $email_id = $this->dx_auth->get_emailId();

                $query3 = $this->db->get_where('list', array('id' => $list['userby']));
                $buyer_id = $query3->row()->user_id;

                $query4 = $this->users->get_user_by_id($buyer_id);
                $buyer_name = $query4->row()->username;
                $buyer_email = $query4->row()->email;

                $query5 = $this->Referrals_model->get_details_refamount($user_id);
                $amount = $query5->row()->amount;

                $updateKey = array('user_id ' => $user_id);
                $updateData = array();
                $updateData['amount'] = $amount - $list['price'];
                $this->Referrals_model->updateReferralsAmount($updateKey, $updateData);


                $list['ref_amount'] = $list['price'];
                $list['book_date'] = local_to_gmt();

                $this->Common_model->insertData('reservation', $list);

                $row = $query2->row();

                //sent mail to administrator
                $email_name = 'tc_book_to_admin';
                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($username), "{list_title}" => $row->title, "{book_date}" => date('m/d/Y'), "{book_date}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $list['checkin'], "{checkout}" => $list['checkout'], "{market_price}" => $list['price'], "{payed_amount}" => 'None', "{travel_credits}" => $list['price'], "{host_name}" => ucfirst($buyer_name), "{host_email_id}" => $buyer_email);
                //Send Mail
                $this->Email_model->sendMail($admin_email, $email_id, ucfirst($username), $email_name, $splVars);


                //sent mail to buyer
                $email_name = 'tc_book_to_host';
                $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($buyer_name), "{traveler_name}" => ucfirst($username), "{list_title}" => $row->title, "{book_date}" => date('m/d/Y'), "{book_date}" => date('g:i A'), "{traveler_email_id}" => $email_id, "{checkin}" => $list['checkin'], "{checkout}" => $list['checkout'], "{market_price}" => $list['price']);
                //Send Mail
                $this->Email_model->sendMail($buyer_email, $admin_email, ucfirst($admin_name), $email_name, $splVars);


                //unset the session
                $newdata = array(
                    'list_id' => '',
                    'checkin' => '',
                    'checkout' => '',
                    'price' => '',
                    'full_cretids' => ''
                );
                $this->session->unset_userdata($remove_items);
                redirect('referrals');
                //redirect('/func/editConfirm/'.$this->session->userdata('userby'), "refresh");
            }
            else
            {
                redirect('rooms/' . $this->session->userdata('list_id'), "refresh");
            }
        }

        public function tell_a_friend()
        {
            $ref_id = $this->input->get('ref', TRUE);

            $referData = array('ref_id' => $ref_id);
            $this->session->set_userdata($referData);

            $data['title'] = get_meta_details('Tell_A_Friend', 'title');
            $data["meta_keyword"] = get_meta_details('Tell_A_Friend', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Tell_A_Friend', 'meta_description');

            $data['message_element'] = 'referrals/view_tell_friend';
            $this->load->view('template', $data);
        }

        public function dateconvert($date)
        {
            $ckout = explode('/', $date);
            $diff = $ckout[2] . '-' . $ckout[0] . '-' . $ckout[1];
            return $diff;
        }

    }

    /* End of file referrals.php */
    /* Location: ./app/controllers/referrals.php */
?>
