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

    class Contacts extends CI_Controller
    {

        public function Contacts()
        {
            parent::__construct();
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->helper('cookie');

            $this->load->library('Form_validation');

            $this->load->model('Users_model');
            $this->load->model('Email_model');
            $this->load->model('Message_model');
            $this->load->model('Contacts_model');
        }

        public function request($param = '')
        {

            if (isset($param))
            {

                $contact_id = $param;

                $conditions = array('contacts.id' => $contact_id, 'contacts.userto' => $this->dx_auth->get_user_id());
                $result = $this->Contacts_model->get_contacts($conditions);

                if ($result->num_rows() == 0)
                {
                    redirect('info');
                }

                $data['result'] = $result->row();
                $list_id = $data['result']->list_id;
                $data['list'] = $this->Common_model->getTableData('list', array('id' => $list_id))->row()->title;
                $no_quest = $data['result']->no_quest;
                $data['no_quest'] = $no_quest;

                $x = $this->Common_model->getTableData('price', array('id' => $list_id));
                $data['per_night'] = $price = $x->row()->night;


                $checkin = $data['result']->checkin;
                $data['checkin'] = $checkin;
                $checkout = $data['result']->checkout;
                $data['checkout'] = $checkout;

                $diff = abs(strtotime($checkout) - strtotime($checkin));
                $data['nights'] = $days = floor($diff / (60 * 60 * 24));
                $amt = $data['subtotal'] = $result->row()->price;
                $data['message'] = $this->Common_model->getTableData('messages', array('contact_id' => $data['result']->id, 'message_type' => '7'))->row()->message;

                $data['commission'] = $result->admin_commission;
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
                //}	

                $data['totalprice'] = $result->row()->price;
                $data['subtotal'] = $result->row()->price;
                $data['per_night'] = $price = $result->row()->price / $days;

                $data['title'] = get_meta_details('Contact_Request', 'title');
                $data["meta_keyword"] = get_meta_details('Contact_Request', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Contact_Request', 'meta_description');
                $data['message_element'] = 'contacts/request';
                $this->load->view('template', $data);
            }
            else
            {
                redirect('info');
            }
        }

        public function response($param = '')
        {

            if (isset($param))
            {

                $contact_id = $param;

                $conditions = array('contacts.id' => $contact_id, 'contacts.userby' => $this->dx_auth->get_user_id());
                $result = $this->Contacts_model->get_contacts($conditions);

                if ($result->num_rows() == 0)
                {
                    redirect('info');
                }

                $data['result'] = $result->row();
                $list_id = $data['result']->list_id;
                $key = $data['result']->contact_key;
                $data['list'] = $this->Common_model->getTableData('list', array('id' => $list_id))->row()->title;
                $no_quest = $data['result']->no_quest;
                $data['no_quest'] = $no_quest;

                $x = $this->Common_model->getTableData('price', array('id' => $list_id));
                $data['per_night'] = $price = $x->row()->night;

                $data['cleaning'] = $result->row()->cleaning;
                $data['security'] = $result->row()->security;

                $checkin = $data['result']->checkin;
                $data['checkin'] = $checkin;
                $checkout = $data['result']->checkout;
                $data['checkout'] = $checkout;

                $diff = abs(strtotime($checkout) - strtotime($checkin));
                $data['nights'] = $days = floor($diff / (60 * 60 * 24));
                $data['subtotal'] = $result->row()->price;
                $data['status'] = $this->Common_model->getTableData('contacts', array('id' => $data['result']->id))->row()->status;
                if ($data['status'] == 4)
                    $data['message'] = $this->Common_model->getTableData('messages', array('contact_id' => $data['result']->id, 'message_type' => '8'))->row()->message;
                else
                    $data['message'] = $this->Common_model->getTableData('messages', array('contact_id' => $data['result']->id, 'message_type' => '8'))->row()->message;
                $data['url'] = base_url() . "payments/form/" . $list_id . "?contact=" . $key;
                $data['status'] = $this->Common_model->getTableData('contacts', array('id' => $data['result']->id))->row()->status;
                $data['commission'] = $result->row()->admin_commission;
                $data['total_payout'] = $amt;
                $data['totalprice'] = round($result->row()->price + $data['commission']);
                $data['title'] = get_meta_details('Contact_Request', 'title');
                $data["meta_keyword"] = get_meta_details('Contact_Request', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Contact_Request', 'meta_description');
                $data['message_element'] = 'contacts/response';
                $this->load->view('template', $data);
            }
            else
            {
                redirect('info');
            }
        }

        public function accept()
        {
            $contact_id = $this->input->post('contact_id');
            $message = $this->input->post('comment');
            //Update the status,price
            $updateKey = array('id' => $contact_id);
            $updateData = array();
            $updateData['status'] = 3;
            $updateData['price'] = $this->input->post('price');
            $this->Contacts_model->update_contact($updateKey, $updateData);


            //Email the confirmation link to the traveller	
            $result = $this->Common_model->getTableData('contacts', array('id' => $contact_id))->row();
            $traveller_id = $result->userby;
            $key = $result->contact_key;
            $list_id = $result->list_id;
            $title = $this->Common_model->getTableData('list', array('id' => $list_id))->row()->title;
            $host_email = $this->Common_model->getTableData('users', array('id' => $this->dx_auth->get_user_id()))->row()->email;
            $traveller_email = $this->Common_model->getTableData('users', array('id' => $traveller_id))->row()->email;

            //send message to traveller
            $host_id = $result->userto;
            $travellername = $this->Common_model->getTableData('users', array('id' => $traveller_id))->row()->username;
            $hostname = $this->Common_model->getTableData('users', array('id' => $this->dx_auth->get_user_id()))->row()->username;
            $list_title = $this->Common_model->getTableData('list', array('id' => $list_id))->row()->title;
            $insertData = array(
                'list_id' => $list_id,
                'contact_id' => $contact_id,
                'userby' => $host_id,
                'userto' => $traveller_id,
                'message' => '<b>Contact Request granted by ' . $hostname . '</b><br><br>' . $message,
                'created' => local_to_gmt(),
                'message_type' => 8
            );

            $this->Message_model->sentMessage($insertData, ucfirst($hostname), ucfirst($travellername), $list_title, $contact_id);
            //send mail to traveller
            $emailsubject = "Request Granted for Booking";
            $this->load->library('email');
            $config['mailtype'] = 'html';
            $config['wordwrap'] = TRUE;
            $this->email->from($host_email, $this->dx_auth->get_site_title());
            $this->email->to($traveller_email);
            $this->email->subject('Request Granted');
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
		
		Hi ' . $travellername . ',<br /><br />
		
		User Email : ' . $host_email . '<br /><br />
		
		Room 	: ' . $title . '<br /><br />
		
		Message 	: ' . $message . '<br /><br />
		
		Checkin Date : ' . $this->input->post('checkin') . '<br /><br />
		
		Checkout Date : ' . $this->input->post('checkout') . '<br /><br />
		
		URL for Booking       : ' . base_url() . 'payments/index/' . $list_id . '?contact=' . $key . '<br /><br /></td>
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

        public function discuss()
        {
            $contact_id = $this->input->post('contact_id');
            $message = $this->input->post('comment');
            //Email the confirmation link to the traveller	
            $result = $this->Common_model->getTableData('contacts', array('id' => $contact_id))->row();
            $traveller_id = $result->userby;
            $list_id = $result->list_id;
            $title = $this->Common_model->getTableData('list', array('id' => $list_id))->row()->title;
            $host_email = $this->Common_model->getTableData('users', array('id' => $this->dx_auth->get_user_id()))->row()->email;
            $traveller_email = $this->Common_model->getTableData('users', array('id' => $traveller_id))->row()->email;
            //send message to traveller
            $host_id = $result->userto;
            $travellername = $this->Common_model->getTableData('users', array('id' => $traveller_id))->row()->username;
            $hostname = $this->Common_model->getTableData('users', array('id' => $this->dx_auth->get_user_id()))->row()->username;
            $list_title = $this->Common_model->getTableData('list', array('id' => $list_id))->row()->title;
            $insertData = array(
                'list_id' => $list_id,
                'contact_id' => $contact_id,
                'userby' => $host_id,
                'userto' => $traveller_id,
                'message' => '<b>Contact Request Message from ' . $hostname . '</b><br><br>' . $message,
                'message_type' => 3
            );

            $this->Message_model->sentMessage($insertData, 1);
            //send mail to traveller
            $emailsubject = "Need to discuss";
            $this->load->library('email');
            $config['mailtype'] = 'html';
            $config['wordwrap'] = TRUE;
            $this->email->from($host_email, $this->dx_auth->get_site_title());
            $this->email->to($traveller_email);
            $this->email->subject('Need to discuss');
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
		
		User Email : ' . $host_email . '<br /><br />
		
		Room 	: ' . $title . '<br /><br />
		
		Checkin Date : ' . $this->input->post('checkin') . '<br /><br />
		
		Checkout Date : ' . $this->input->post('checkout') . '<br /><br /></td>
		
		</tr>
		<tr>
		<td style="padding:0 10px; font-size:14px;">
		
		Message : <br /><br />' . $message . '
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

        public function decline()
        {
            $contact_id = $this->input->post('contact_id');
            //Update the status,price
            $updateKey = array('id' => $contact_id);
            $updateData = array();
            $updateData['status'] = 4;
            $this->Contacts_model->update_contact($updateKey, $updateData);

            $message = $this->input->post('comment');
            //Email the confirmation link to the traveller	
            $result = $this->Common_model->getTableData('contacts', array('id' => $contact_id))->row();
            $traveller_id = $result->userby;
            $list_id = $result->list_id;
            //send message to traveller
            $host_id = $result->userto;
            $travellername = $this->Common_model->getTableData('users', array('id' => $traveller_id))->row()->username;
            $hostname = $this->Common_model->getTableData('users', array('id' => $this->dx_auth->get_user_id()))->row()->username;
            $insertData = array(
                'list_id' => $list_id,
                'contact_id' => $contact_id,
                'userby' => $host_id,
                'userto' => $traveller_id,
                'message' => '<b>Contact Request Declined by ' . $hostname . '</b><br><br>' . $message,
                'message_type' => 3
            );

            $this->Message_model->sentMessage($insertData, 1);
        }

    }

?>
