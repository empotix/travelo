<?php

    /**
     * DROPinn Account Controller Class
     *
     * It helps to show the user account details
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Account
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Account extends CI_Controller
    {

        //Constructor
        public function Account()
        {
            parent::__construct();

            $this->load->helper('url');
            $this->load->helper('cookie');
            $this->load->helper('form');

            $this->load->library('form_validation');
            $this->load->library('Pagination');


            $this->load->model('Users_model');
            $this->load->model('Trips_model');
            $this->load->model('Email_model');
            $this->load->model('Message_model');
            $this->facebook_lib->enable_debug(TRUE);
        }

        public function index()
        {
            //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $user_id = $this->dx_auth->get_user_id();

                $query = $this->Common_model->getTableData('user_notification', array("user_id" => $user_id));

                if ($this->input->post())
                {
                    if ($this->input->post('periodic_offers') == 1)
                        $data['periodic_offers'] = 1;
                    else
                        $data['periodic_offers'] = 0;

                    if ($this->input->post('company_news') == 1)
                        $data['company_news'] = 1;
                    else
                        $data['company_news'] = 0;

                    if ($this->input->post('upcoming_reservation') == 1)
                        $data['upcoming_reservation'] = 1;
                    else
                        $data['upcoming_reservation'] = 0;

                    if ($this->input->post('new_review') == 1)
                        $data['new_review'] = 1;
                    else
                        $data['new_review'] = 0;

                    if ($this->input->post('leave_review') == 1)
                        $data['leave_review'] = 1;
                    else
                        $data['leave_review'] = 0;

                    if ($this->input->post('standby_guests') == 1)
                        $data['standby_guests'] = 1;
                    else
                        $data['standby_guests'] = 0;

                    if ($this->input->post('rank_search') == 1)
                        $data['rank_search'] = 1;
                    else
                        $data['rank_search'] = 0;

                    //insert the data	
                    $data['user_id'] = $user_id;

                    if ($query->num_rows() > 0)
                    {
                        $condition = array("user_id" => $user_id);
                        $this->Common_model->updateTableData('user_notification', NULL, $condition, $data);
                        //echo $this->db->last_query();exit;
                    }
                    else
                    {
                        $this->Common_model->insertData('user_notification', $data);
                    }
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Settings updated successfully')));
                    redirect('account');
                }


                $row = $query->row();

                if ($query->num_rows() > 0)
                {
                    $data['periodic_offers'] = $row->periodic_offers;
                    $data['company_news'] = $row->company_news;
                    $data['upcoming_reservation'] = $row->upcoming_reservation;
                    $data['standby_guests'] = $row->standby_guests;
                    $data['new_review'] = $row->new_review;
                    $data['leave_review'] = $row->leave_review;
                    $data['rank_search'] = $row->rank_search;
                }

                $data['title'] = get_meta_details('Edit_account_details', 'title');
                $data["meta_keyword"] = get_meta_details('Edit_account_details', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Edit_account_details', 'meta_description');
                $data['message_element'] = "account/view_nodification";
                $this->load->view('template', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        //Payout Preferences
        public function payout()
        {
            //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()))
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                if ($this->input->post())
                {
                    $data['user_id'] = $this->dx_auth->get_user_id();
                    $data['country'] = $this->input->post('country');
                    $data['payout_type'] = $this->input->post('payout_type');
                    $data['email'] = $this->input->post('email');
                    $data['currency'] = $this->input->post('currency');
                    $check = $this->db->where('user_id', $data['user_id'])->where('email', $data['email'])->where('currency', $data['currency'])->get('payout_preferences');

                    if ($check->num_rows() == 0)
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Your payout preference set successfully.')));
                        $this->Common_model->insertData('payout_preferences', $data);
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('Your payout preference this data already inserted.')));
                    }
                }
                $data['result'] = $this->Common_model->getTableData('payout_preferences', array("user_id" => $this->dx_auth->get_user_id()));
                $data['defaults'] = $this->Common_model->getTableData('payout_preferences', array("user_id" => $this->dx_auth->get_user_id(), "is_default !=" => 1));
                $data['countries'] = $this->Common_model->getCountries()->result();

                $data['title'] = get_meta_details('Your_Payment_Method_details', 'title');
                $data["meta_keyword"] = get_meta_details('Your_Payment_Method_details', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Your_Payment_Method_details', 'meta_description');
                $data['message_element'] = "account/view_payout";
                $this->load->view('template', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        public function setDefault()
        {
            //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $user_id = $this->dx_auth->get_user_id();

                if ($this->input->post())
                {
                    //unset the previous default email
                    $condition = array("user_id" => $user_id);
                    $unset_default_email['is_default'] = 0;
                    $this->Common_model->updateTableData('payout_preferences', NULL, $condition, $unset_default_email);

                    //set new default email	 
                    $default_email = $this->input->post('default_email');
                    $data['is_default'] = 1;
                    $condition = array("id" => $default_email);
                    $this->Common_model->updateTableData('payout_preferences', NULL, $condition, $data);

                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Your default payout updated successfully.')));
                }

                redirect('account/payout');
            }
            else
            {
                redirect('users/signin');
            }
        }

        //Ajax page
        public function payoutMethod()
        {
            // if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $country = $this->input->post('country');

                $conditions = array("country_symbol" => $country);
                $query = $this->Common_model->getCountries($conditions);

                $data['result'] = $this->Common_model->getTableData('payments', array("is_payout" => 1));
                $data['country'] = $query->row()->country_name;
                $data['country_symbol'] = $query->row()->country_symbol;

                $this->load->view(THEME_FOLDER . '/account/view_payout_method', $data);
            }
            else
            {
                return false;
            }
        }

        //Ajax page
        public function paymentInfo()
        {
            //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()))
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $country = '';
                $payout_type = '';

                $country = $this->input->post('country');
                $payout_type = $this->input->post('payout_type');

                $data['user_id'] = $this->dx_auth->get_user_id();
                $data['payout_type'] = $payout_type;
                $data['payout_name'] = $this->Common_model->getTableData('payments', array("id" => $payout_type))->row()->payment_name;
                $data['country'] = $country;

                $this->load->view(THEME_FOLDER . '/account/view_payment_info', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        public function transaction()
        {
            //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) )
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $user_id = $this->dx_auth->get_user_id();
                $conditions = array("reservation.userto" => $user_id);
                $query = $this->Trips_model->get_reservation($conditions);

                // Get offset and limit for page viewing
                $start = (int) $this->uri->segment(3, 0);

                // Number of record showing per page
                $row_count = 20;

                if ($start > 0)
                    $offset = ($start - 1) * $row_count;
                else
                    $offset = $start * $row_count;

                $limit = array($row_count, $offset);
                // Get all transaction
                $data['result'] = $this->Trips_model->get_reservation($conditions, $limit);

                // Pagination config
                $config['base_url'] = site_url('account/transaction');
                $config['uri_segment'] = 3;
                $config['num_links'] = 5;
                $config['total_rows'] = $query->num_rows();
                $config['per_page'] = $row_count;

                // Init pagination
                $this->pagination->initialize($config);
                // Create pagination links
                $data['pagination'] = $this->pagination->create_links2();

                $data['title'] = get_meta_details('Your_Transaction_Details', 'title');
                $data["meta_keyword"] = get_meta_details('Your_Transaction_Details', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Your_Transaction_Details', 'meta_description');
                $data['message_element'] = "account/view_transaction";
                $this->load->view('template', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        public function mywishlist()
        {
            //echo "My Wishlist";

            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $list_id = $this->input->post('list_id');
                $user_id = $this->dx_auth->get_user_id();
                $shortlist = $this->Common_model->getTableData('users', array('id' => $this->dx_auth->get_user_id()))->row()->shortlist;
                //Remove the selected list from the All short lists
                $result = "";
                $my = explode(',', $shortlist);
                foreach ($my as $list)
                {
                    if ($list != $list_id)
                    {
                        $result .= $list . ",";
                    }
                }
                //Remove Comma from last character
                if ((substr($result, -1)) == ',')
                    $my_shortlist = substr_replace($result, "", -1);
                else
                    $my_shortlist = $result;

                $data = array('shortlist' => $my_shortlist);
                $this->db->where('id', $user_id);
                $this->db->update('users', $data);

                $data['title'] = get_meta_details('My Wishlist', 'title');
                $data["meta_keyword"] = get_meta_details('My Wishlist', 'meta_keyword');
                $data["meta_description"] = get_meta_details('My Wishlist', 'meta_description');
                $data['message_element'] = "account/view_wishlist";
                $this->load->view('template', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        public function remove_my_shortlist()
        {

            if ((!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()))
            {
                echo "error";
            }
            else
            {
                $list_id = $this->uri->segment(3, 0);
                $user_id = $this->dx_auth->get_user_id();
                $shortlist = $this->Common_model->getTableData('users', array('id' => $this->dx_auth->get_user_id()))->row()->shortlist;
                //Remove the selected list from the All short lists
                $result = "";
                $my = explode(',', $shortlist);
                foreach ($my as $list)
                {
                    if ($list != $list_id)
                    {
                        $result .= $list . ",";
                    }
                }
                //Remove Comma from last character
                if ((substr($result, -1)) == ',')
                    $my_shortlist = substr_replace($result, "", -1);
                else
                    $my_shortlist = $result;

                $data = array('shortlist' => $my_shortlist);
                $this->db->where('id', $user_id);
                $this->db->update('users', $data);
                redirect('account/mywishlist');
            }
        }

    }

    /* End of file account.php */
    /* Location: ./app/controllers/account.php */
?>