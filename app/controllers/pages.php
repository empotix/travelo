<?php

    /**
     * DROPinn Pages Controller Class
     *
     * It helps shows the Static pages.
     *
     * @package		Users
     * @subpackage	Controllers
     * @category	Referrals
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com
     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Pages extends CI_Controller
    {

        //Constructor
        public function Pages()
        {
            parent::__construct();

            //Load Form Helper
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('Email_model');
            $this->load->library('Form_validation');

            $this->facebook_lib->enable_debug(TRUE);
        }

        public function contact()
        {
            $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');
            if ($this->input->post())
            {
                $name = $this->input->post('name');
                $email = $this->input->post('email');
                $message = $this->input->post('message');

                $admin_email = $this->dx_auth->get_site_sadmin();
                $admin_name = $this->dx_auth->get_site_title();

                $this->form_validation->set_rules('name', 'Name', 'required|alpha|trim|xss_clean');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim|xss_clean');
                $this->form_validation->set_rules('message', 'Message', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    $admin_email = $this->dx_auth->get_site_sadmin();
                    $admin_name = $this->dx_auth->get_site_title();

                    $date = date('Y-m-d');
                    $time = date('H:i:s');

                    $email_name = 'contact_form';
                    $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{email}" => $email, "{name}" => $name, "{message}" => $message, "{date}" => $date, "{time}" => $time);

                    $contact_email = $this->Common_model->getTableData('contact_info', array('id' => '1'))->row()->email;

                    //Send Mail
                    $this->Email_model->sendMail($contact_email, $email, ucfirst($name), $email_name, $splVars);

                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Thanks for being part of our community! We will contact you ASAP.')));
                    redirect('pages/contact');
                }
            }

            $data['row'] = $this->Common_model->getTableData('contact_info', array('id' => '1'))->row();

            $data['title'] = get_meta_details('Contact_Us', 'title');
            $data["meta_keyword"] = get_meta_details('Contact_Us', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Contact_Us', 'meta_description');

            $data['message_element'] = 'view_contact';
            $this->load->view('template', $data);
        }

        public function faq()
        {
            $this->load->model('faq_model');

            $data["faqs"] = $this->faq_model->getFaqs();

            $data['title'] = get_meta_details('FAQs', 'title');
            $data["meta_keyword"] = get_meta_details('FAQs', 'meta_keyword');
            $data["meta_description"] = get_meta_details('FAQs', 'meta_description');

            $data['message_element'] = 'view_faq';
            $this->load->view('template', $data);
        }

        public function cancellation_policy()
        {
            $data['title'] = get_meta_details('cancellation_policy', 'title');
            $data["meta_keyword"] = get_meta_details('cancellation_policy', 'meta_keyword');
            $data["meta_description"] = get_meta_details('cancellation_policy', 'meta_description');

            $data['message_element'] = 'view_cancellation_policy';
            $this->load->view('template', $data);
        }

        public function view($param = '')
        {
            if ($this->db->where('page_url', $param)->get('page')->num_rows() == 0)
            {
                redirect('info/deny');
            }
            $query = $this->Common_model->getTableData('page', array('page_url' => $param));
            if ($query->num_rows() < 0)
            {
                redirect('info');
            }
            else
            {
                $row = $query->row();

                $data['title'] = $row->page_title;
                $data['page_content'] = $row->page_content;
                $data['page_name'] = $row->page_name;
                $data['message_element'] = 'view_pages';
                $this->load->view('template', $data);
            }
        }

    }

    /* End of file pages.php */
    /* Location: ./app/controllers/pages.php */
?>