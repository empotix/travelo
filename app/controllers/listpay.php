<?php

    /**
     * DROPinn Payment List Controller Class
     *
     * helps to achieve payment functionality while adding the list.
     *
     * @package		DROPinn
     * @subpackage	Controllers
     * @category	Pay List
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Listpay extends CI_Controller
    {

        public function Listpay()
        {
            parent::__construct();

            $this->load->helper('url');
            $this->load->helper('form');


            $this->load->library('Form_validation');
            $this->load->library('email');
            $this->load->library('form_validation');
            $this->load->library('Twoco_Lib');

            $this->load->model('Users_model');

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

        public function index()
        {
            extract($this->input->get());
            $this->form_validation->set_error_delimiters('<p>', '</p>');

            if ($this->input->post('book_it_button'))
            {
                if ($this->input->post('payment_method') == 'cc')
                {
                    $this->submissionCC();
                }
                else if ($this->input->post('payment_method') == 'paypal')
                {
                    $this->submission($room_id);
                }
                else if ($this->input->post('payment_method') == '2c')
                {
                    $this->submissionTwoc();
                }
                else
                {
                    redirect('info');
                }
            }

            $data['id'] = $this->session->userdata('Lid');
            $data['amt'] = $this->session->userdata('amount');
            $data['full_cretids'] = 'off';

            $data['result'] = $this->Common_model->getTableData('payments')->result();

            $data['title'] = get_meta_details('Payment_Option', 'title');
            $data["meta_keyword"] = get_meta_details('Payment_Option', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Payment_Option', 'meta_description');

            $data['message_element'] = "payments/view_listPay";

            $this->load->view('template', $data);
        }

        public function submission($param)
        {
            $list_id = $param;
            $row = $this->Common_model->getTableData('payment_details', array('code' => 'PAYPAL_ID'))->row();
            $paymode = $this->db->where('payment_name', 'Paypal')->get('payments')->row()->is_live;

            if (get_currency_value($this->session->userdata('amount')) == 0)
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('You are not able to pay a amount for this list. Please contact the Admin.')));
                redirect('rooms/lys_next/edit/' . $list_id);
            }

            $to_buy = array(
                'desc' => 'Purchase from ACME Store',
                'currency' => get_currency_code(),
                'type' => 'sale',
                'return_URL' => site_url('listpay/list_success/' . $list_id),
// see below have a function for this -- function back()
// whatever you use, make sure the URL is live and can process
// the next steps
                'cancel_URL' => site_url('listpay/list_cancel'), // this goes to this controllers index()
                'shipping_amount' => 0,
                'get_shipping' => false);
// I am just iterating through $this->product from defined
// above. In a live case, you could be iterating through
// the content of your shopping cart.
//foreach($this->product as $p) {
            $temp_product = array(
                'name' => $this->dx_auth->get_site_title() . ' Transaction',
                'number' => $list_id,
                'quantity' => 1, // simple example -- fixed to 1
                'amount' => get_currency_value($this->session->userdata('amount')));

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

        public function list_cancel()
        {
            // redirect('home/addlist','refresh');
            redirect('rooms/new', 'refresh');
        }

        public function list_ipn()
        {
            if ($_REQUEST['payment_status'] == 'Completed')
            {

                $data = explode('@', $custom);
                $listId = $data[0];
                $data['status'] = 1;
                $this->db->where('id', $listId);
                $this->db->update('list', $data);
                $query = $this->Common_model->getTableData('list', array('id' => $listId))->row();
                $list_email = $query->email;
                $data['status'] = $list_email;
                $query2 = $this->Common_model->getTableData('users', array('id' => $this->dx_auth->get_user_id()))->row();
                $user_email = $query2->email;
                $data['status'] = $user_email;
                $emailsubject = "Host Listing Confirmation";
                $headers = "";
                $headers .= "From: Dropinn Host Listing <gokulnath@cogzidel.com>\r\n";
                $headers .= "MIME-Version: 1.0\n";
                $headers .= "Content-Type: multipart/related; type=\"multipart/alternative\"; boundary=\"----=MIME_BOUNDRY_main_message\"\n";
                $headers .= "X-Sender: from_name<" . $user_email . ">\n";
                $headers .= "X-Mailer: PHP4\n";
                $headers .= "X-Priority: 3\n"; //1 = Urgent, 3 = Normal
                $headers .= "Return-Path: <" . $user_email . ">\n";
                $emsg = 'You have finished the payment for your listing ';
                mail($list_email, $emailsubject, $emsg, $headers);
            }
        }

        public function payment($param)
        {

            if ($this->input->post('payment_method') == 'cc')
            {
                $this->submissionCC($param);
            }
            else if ($this->input->post('payment_method') == 'paypal')
            {

                $this->submission($param);
            }
            else if ($this->input->post('payment_method') == '2c')
            {
                $this->submissionTwoc($param);
            }
            else
            {
                redirect('info');
            }
        }

        public function list_success()
        {
            //echo $payment_status 	= $this->input->post('payment_status',true);
            //exit;
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

                    if ($this->input->post('payment_status', true) == 'Completed')
                    {
                        $listId = $this->input->post('custom', true);

                        $condition = array('id' => $listId);
                        $data['status'] = 1;
                        $data['list_pay'] = 1;
                        $data['is_enable'] = 1;
                        $this->Common_model->updateTableData('list', NULL, $condition, $data);
                        //redirect('rooms/edit/'.$listId, 'refresh');
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Rooms added successfully.')));
                        redirect('rooms/' . $listId, 'refresh');
                    }
                    else if ($this->input->post('payment_status', true) == '')
                    {
                        $listId = $this->uri->segment('3');

                        $condition = array('id' => $listId);
                        $data['status'] = 1;
                        $data['list_pay'] = 1;
                        $data['is_enable'] = 1;
                        $this->Common_model->updateTableData('list', NULL, $condition, $data);
                        //redirect('rooms/edit/'.$listId, 'refresh');
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Rooms added successfully.')));
                        redirect('rooms/' . $listId, 'refresh');
                    }
                    else
                    {
                        //echo $this->input->post('payment_status',true);
                        //exit;
                        //redirect('home/addlist','refresh');
                        redirect('rooms/new', 'refresh');
                    }
                }
                else
                {
                    $this->_error($do_ec_return);
                }
            }
            else
            {
                $this->_error($do_ec_return);
            }
        }

    }

    /* End of file listpay.php */
    /* Location: ./app/controllers/listpay.php */
?>
