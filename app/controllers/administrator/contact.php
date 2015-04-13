<?php

    /**
     * DROPinn Admin Contact Controller Class
     *
     * helps to achieve common tasks related to the site like flash message formats,pagination variables.
     *
     * @package		DROPinn
     * @subpackage	Controllers
     * @category	Admin Contact
     * @author		Cogzidel Product Team
     * @version		Version 1.4
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Contact extends CI_Controller
    {

        public function Contact()
        {
            parent::__construct();

            $this->load->library('Table');
            $this->load->library('Pagination');
            $this->load->library('DX_Auth');

            $this->load->helper('form');
            $this->load->helper('url');

            //load validation library
            $this->load->library('form_validation');


            $this->load->model('Users_model');

            // Protect entire controller so only admin, 
            // and users that have granted role in permissions table can access it.
            $this->dx_auth->check_uri_permissions();
        }

        public function index()
        {
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post())
            {
                $this->form_validation->set_message('is_natural', 'You must enter a valid %s number');
                $this->form_validation->set_rules('phone', 'Phone', 'required|trim|xss|is_natural|xss_clean');
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
                $this->form_validation->set_rules('city', 'City', 'required|trim|xss_clean');
                $this->form_validation->set_rules('state', 'State', 'required|trim|xss_clean');
                $this->form_validation->set_rules('country', 'Country', 'required|trim|xss_clean');
                $this->form_validation->set_rules('pincode', 'Pincode to', 'required|trim|alpha_numeric|xss_clean');
                $this->form_validation->set_rules('street', 'Street', 'required|trim|xss_clean');
                $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    $data['phone'] = $this->input->post('phone');
                    $data['email'] = $this->input->post('email');
                    $data['name'] = $this->input->post('name');
                    $data['street'] = $this->input->post('street');
                    $data['city'] = $this->input->post('city');
                    $data['state'] = $this->input->post('state');
                    $data['country'] = $this->input->post('country');
                    $data['pincode'] = $this->input->post('pincode');

                    $rows = $this->db->get_where('contact_info', array('id' => '1'))->num_rows();
                    if ($rows > 0)
                    {
                        $this->db->where('id', 1);
                        $this->db->update('contact_info', $data);
                    }
                    else
                    {
                        $this->db->insert('contact_info', $data);
                    }
                    //echo '<p>Contact info updated successfully</p>';
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Contact info updated successfully')));
                    redirect_admin('contact');
                }
                else
                {
                    $data['row'] = $this->db->get_where('contact_info', array('id' => '1'))->row();
                    $data['message_element'] = "administrator/contact/view_contact_info";
                    $this->load->view('administrator/admin_template', $data);
                }
            }
            else
            {
                $data['row'] = $this->db->get_where('contact_info', array('id' => '1'))->row();
                $data['message_element'] = "administrator/contact/view_contact_info";
                $this->load->view('administrator/admin_template', $data);
            }
        }

    }

?>
