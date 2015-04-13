<?php

    /**
     * DROPinn Admin Social Controller Class
     *
     * helps to achieve common tasks related to the site like flash message formats,pagination variables.
     *
     * @package		DROPinn
     * @subpackage	Controllers
     * @category	Admin Social
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Social extends CI_Controller
    {

        public function Social()
        {
            parent::__construct();

            $this->load->library('Table');
            $this->load->library('Pagination');

            $this->load->helper('form');
            $this->load->helper('url');

            //load validation library
            $this->load->library('form_validation');


            $this->load->model('Users_model');

            // Protect entire controller so only admin, 
            // and users that have granted role in permissions table can access it.
            $this->dx_auth->check_uri_permissions();
        }

        public function fb_settings()
        {
            if ($this->input->post('update'))
            {
                $data1['string_value'] = $this->input->post('fb_api_id');
                $this->db->where('code', 'SITE_FB_API_ID');
                $this->db->update('settings', $data1);

                $data2['string_value'] = $this->input->post('fb_api_secret');
                $this->db->where('code', 'SITE_FB_API_SECRET');
                $this->db->update('settings', $data2);

                echo '<p>' . translate_admin('Settings updated successfully') . '</p>';
            }
            else
            {
                $data['fb_api_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
                $data['fb_api_secret'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_SECRET'))->row()->string_value;
                $data['message_element'] = "administrator/social/fb_settings";
                $this->load->view('administrator/admin_template', $data);
            }
        }

        public function twitter_settings()
        {
            if ($this->input->post('update'))
            {
                $data1['string_value'] = $this->input->post('twitter_api_id');
                $this->db->where('code', 'SITE_TWITTER_API_ID');
                $this->db->update('settings', $data1);

                $data2['string_value'] = $this->input->post('twitter_api_secret');
                $this->db->where('code', 'SITE_TWITTER_API_SECRET');
                $this->db->update('settings', $data2);

                echo '<p>' . translate_admin('Settings updated successfully') . '</p>';
            }
            else
            {
                $data['twitter_api_id'] = $this->db->get_where('settings', array('code' => 'SITE_TWITTER_API_ID'))->row()->string_value;
                $data['twitter_api_secret'] = $this->db->get_where('settings', array('code' => 'SITE_TWITTER_API_SECRET'))->row()->string_value;
                $data['message_element'] = "administrator/social/twitter_settings";
                $this->load->view('administrator/admin_template', $data);
            }
        }

        public function google_settings()
        {
            if ($this->input->post('update'))
            {
                $data['string_value'] = $this->input->post('gmap_api_key');
                $this->db->where('code', 'SITE_GMAP_API_KEY');
                $this->db->update('settings', $data);

                echo '<p>' . translate_admin('Settings updated successfully') . '</p>';
            }
            else
            {
                $data['gmap_api_key'] = $this->db->get_where('settings', array('code' => 'SITE_GMAP_API_KEY'))->row()->string_value;
                $data['message_element'] = "administrator/social/google_settings";
                $this->load->view('administrator/admin_template', $data);
            }
        }

        // News Letter //

        public function news_letter()
        {
            if ($this->input->post())
            {
                $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('from', 'From', 'required|trim|xss_clean');
                $this->form_validation->set_rules('to', 'To', 'required|trim|valid_email|xss_clean');
                $this->form_validation->set_rules('subject', 'Subject', 'required|trim|xss_clean');
                $this->form_validation->set_rules('message', 'Message', 'required|trim|xss_clean');
                if ($this->form_validation->run())
                {
                    $from = $this->input->post('from');
                    $to = $this->input->post('to');
                    $subject = $this->input->post('subject');
                    $messages = $this->input->post('message');

                    $this->load->library('email');
                    $config['mailtype'] = 'html';
                    $config['wordwrap'] = TRUE;
                    $this->email->from($from, $this->dx_auth->get_site_title());
                    $this->email->to($to);
                    $this->email->subject($subject);
                    $message = '<table cellspacing="0" cellpadding="0" width="678" style="border:1px solid #e6e6e6; background:#fff;  font-family:Arial, Helvetica, sans-serif; -moz-border-radius: 16px; -webkit-border-radius:16px; -khtml-border-radius: 16px; border-radius: 16px; -moz-box-shadow: 0 0 4px #888888; -webkit-box-shadow:0 0 4px #888888; box-shadow:0 0 4px #888888;">
						<tr>
																			<td>
																							<table background="' . base_url() . 'images/email/head_bg.png" width="676" height="156" cellspacing="0" cellpadding="0">
																											<tr>
																															<td style="vertical-align:top;">
																																			<img src="' . base_url() . 'logo/logo.png" alt="' . $this->dx_auth->get_site_title() . '" style=" margin:10px 0 0 20px;" />
																																		</td>
																																		<td style="text-transform:uppercase; font-weight:bold; color:#0271b8; width:290px; padding:0 10px 0 0; line-height:28px;">																																				
																																		</td>
																														</tr>
																										</table>
																						</td>
																		</tr>
																		<tr>
																			<td style="padding:0 10px; font-size:14px;">
																			' . $this->dx_auth->get_site_title() . '. New Letter.
		
		' . $messages . '</td>
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
                $data['message_element'] = "administrator/social/news_letter";
                $this->load->view('administrator/admin_template', $data);
            }
            else
            {

                $data['message_element'] = "administrator/social/news_letter";
                $this->load->view('administrator/admin_template', $data);
            }
        }

    }

?>
