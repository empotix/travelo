<?php

    /**
     * Dropinn Admin_key Class
     *
     * Permits admin to handle the static Admin_ of the site
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Manage Static Admin_key 
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @created		December 22 2008
     * @link		http://www.cogzidel.com

     */
    class Admin_key extends CI_Controller
    {

        /**
         * Constructor 
         *
         * Loads language files and models needed for this controller
         */
        public function Admin_key()
        {
            parent::__construct();

            //load validation library
            $this->load->library('form_validation');

            //Load Form Helper
            $this->load->helper('form');

            //load model
            $this->load->model('Admin_key_model');
            $this->dx_auth->check_uri_permissions();
            $this->path = realpath(APPPATH);
        }

//Controller End 

        /**
         * Loads Faqs settings Admin_key.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function addAdmin_key()
        {
            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post('addAdmin_key'))
            {
                //Set rules
                $this->form_validation->set_rules('Admin_key', 'Page_key', 'required|trim|xss_clean');
                //$this->form_validation->set_rules('page_ref.','page_ref.','required|trim|xss_clean');
                if ($this->form_validation->run())
                {
                    //prepare insert data
                    $insertData = array();
                    $insertData['page_key'] = $this->input->post('Admin_key');
                    $insertData['page_refer'] = $this->input->post('page_ref');
                    $insertData['status'] = $this->input->post('is_footer');
                    $insertData['created'] = local_to_gmt();

                    if ($this->db->where('status', 0)->get('admin_key')->num_rows() == 0 && $insertData['status'] == 0)
                    {
                        //Add Groups
                        $this->Admin_key_model->addAdmin_key($insertData);

                        //Notification message
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Admin Key added successfully')));
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Sorry! Already another Admin Key in active state.')));
                    }
                    redirect_admin('admin_key/viewAdmin_key');
                }
            } //If - Form Submission End
            //Get Faq Categories
            $data['Admin_key'] = $this->Admin_key_model->getAdmin_keys();

            $data['message_element'] = "administrator/admin_key/addAdmin_key";
            $this->load->view('administrator/admin_template', $data);
        }

//Function addAdmin_key End 

        /**
         * Loads Manage Static Admin_keys View.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function viewAdmin_key()
        {
            //Get Groups
            $data['Admin_key'] = $this->Admin_key_model->getAdmin_keys();

            //Load View	
            $data['message_element'] = "administrator/admin_key/viewAdmin_key";
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
        public function deleteAdmin_key()
        {
            $id = $this->uri->segment(4, 0);

            if ($id == 0)
            {
                $getAdmin_keys = $this->Admin_key_model->getAdmin_keys();
                $Admin_keylist = $this->input->post('Admin_keylist');
                if (!empty($Admin_keylist))
                {
                    foreach ($Admin_keylist as $res)
                    {
                        $condition = array('admin_key.id' => $res);
                        $this->Admin_key_model->deleteAdmin_key(NULL, $condition);
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select Admin_key')));
                    redirect_admin('admin_key/viewAdmin_key');
                }
            }
            else
            {
                $condition = array('admin_key.id' => $id);
                $this->Admin_key_model->deleteAdmin_key(NULL, $condition);
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Admin key deleted successfully')));
            redirect_admin('admin_key/viewAdmin_key');
        }

        //Function end

        /**
         * Loads Manage Static Admin_keys View.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function editAdmin_key()
        {
            //Get id of the category	
            $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post('editAdmin_key'))
            {
                //Set rules
                $this->form_validation->set_rules('Admin_key', 'Page_key', 'required|trim|xss_clean');
                //$this->form_validation->set_rules('page_ref.','page_ref.','required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    //prepare update data
                    $updateData = array();
                    $updateData['page_key'] = $this->input->post('Admin_key');
                    $updateData['page_refer'] = $this->input->post('page_ref');
                    $updateData['status'] = $this->input->post('is_footer');

                    //Edit Faq Category
                    $updateKey = array('admin_key.id' => $this->uri->segment(4));
                    if ($this->db->where('status', 0)->get('admin_key')->num_rows() == 0 || $updateData['status'] == 1)
                    {
                        $this->Admin_key_model->updateAdmin_key($updateKey, $updateData);

                        //Notification message
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Admin_key updated successfully')));
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Sorry! Already another Admin Key in active state.')));
                    }
                    redirect_admin('admin_key/viewAdmin_key');
                }
            } //If - Form Submission End
            //Set Condition To Fetch The Faq Category
            $condition = array('admin_key.id' => $id);

            //Get Groups
            $data['Admin_key'] = $this->Admin_key_model->getAdmin_keys($condition);

            //Load View	
            $data['message_element'] = "administrator/admin_key/editAdmin_key";
            $this->load->view('administrator/admin_template', $data);
        }

//End of editAdmin_key
    }

//End  Admin_key Class

/* End of file Admin_key.php */ 
/* Location: ./app/controllers/admin/Admin_key.php */
