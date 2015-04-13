<?php

    /**
     * Dropinn page Class
     *
     * Permits admin to handle the static pages of the site
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Manage Static Page 
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @created		December 22 2008
     * @link		http://www.cogzidel.com

     */
    class Cancellation extends CI_Controller
    {

        /**
         * Constructor 
         *
         * Loads language files and models needed for this controller
         */
        public function Cancellation()
        {
            parent::__construct();

            //load validation library
            $this->load->library('form_validation');

            //Load Form Helper
            $this->load->helper('form');

            //load model
            $this->load->model('cancellation_model');
            $this->dx_auth->check_uri_permissions();
        }

//Controller End 

        /**
         * Loads Faqs settings page.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function addCancellation()
        {
            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post('addCancellation'))
            {
                //Set rules
                $this->form_validation->set_rules('site_name', 'Site Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('cancellation_title', 'Cancellation Title', 'required|trim|xss_clean|callback_pageNameCheck');
                $this->form_validation->set_rules('cancellation_content', 'Cancellation Content', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    //prepare insert data
                    $insertData = array();
                    $insertData['site_name'] = $this->input->post('site_name');
                    $insertData['cancellation_title'] = $this->input->post('cancellation_title');
                    $insertData['cancellation_content'] = $this->input->post('cancellation_content');

                    //Add Groups
                    $this->cancellation_model->addCancellation($insertData);

                    //Notification message
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Cancellation Policy added successfully')));
                    redirect_admin('cancellation/viewCancellation');
                }
            } //If - Form Submission End
            //Get Faq Categories
            $data['addPages'] = $this->page_model->getPages();

            $data['message_element'] = "administrator/cancellation_policy/addCancellation";
            $this->load->view('administrator/admin_template', $data);
        }

//Function addPage End 

        /**
         * Loads Manage Static Pages View.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function viewcancellation()
        {

            if ((!$this->dx_auth->is_logged_in()) || $this->dx_auth->get_user_id() != 1)
            {
                redirect('info/deny');
            }
            //Get Groups
            $data['cancellation'] = $this->cancellation_model->getcancellation();

            //Load View	
            $data['message_element'] = "administrator/cancellation_policy/viewCancellation";
            $this->load->view('administrator/admin_template', $data);
        }

//End of 	

        /**
         * Delete Faq.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function deletecancellation()
        {
            $id = $this->uri->segment(4, 0);

            if ($id == 0)
            {
                $getpages = $this->cancellation_model->getCancellation();
                $pagelist = $this->input->post('pagelist');
                if (!empty($pagelist))
                {
                    foreach ($pagelist as $res)
                    {
                        $condition = array('cancellation_policy.id' => $res);
                        $this->cancellation_model->deleteCancellation(NULL, $condition);
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select Cancellation Policy')));
                    redirect_admin('cancellation/viewCancellation');
                }
            }
            else
            {
                $condition = array('cancellation_policy.id' => $res);
                $this->cancellation_model->deleteCancellation(NULL, $condition);
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Cancellation Policy deleted successfully')));
            redirect_admin('cancellation/viewCancellation');
        }

        //Function end

        /**
         * Loads Manage Static Pages View.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function editCancellation()
        {
            //Get id of the category	
            $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post('editCancellation'))
            {
                //Set rules
                $this->form_validation->set_rules('site_name', 'Site Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('cancellation_title', 'Cancellation Title', 'required|trim|xss_clean|callback_pageNameCheck');
                $this->form_validation->set_rules('cancellation_content', 'Cancellation Content', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    //prepare update data
                    $updateData = array();
                    $updateData['site_name'] = $this->input->post('site_name');
                    $updateData['cancellation_title'] = $this->input->post('cancellation_title');
                    $updateData['cancellation_content'] = $this->input->post('cancellation_content');


                    $updateKey = array('cancellation_policy.id' => $this->uri->segment(4));

                    //Add Groups
                    $this->cancellation_model->updateCancellation($updateKey, $updateData);



                    //Notification message
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Cancellation Policy updated successfully')));
                    redirect_admin('cancellation/viewCancellation');
                }
            } //If - Form Submission End
            //Set Condition To Fetch The Faq Category
            $condition = array('cancellation_policy.id' => $id);

            //Get Groups
            $data['cancellations'] = $this->cancellation_model->getCancellation($condition);

            //Load View	
            $data['message_element'] = "administrator/cancellation_policy/editCancellation";
            $this->load->view('administrator/admin_template', $data);
        }

//End of editPage
    }

//End  Page Class

/* End of file Page.php */ 
/* Location: ./app/controllers/admin/Page.php */