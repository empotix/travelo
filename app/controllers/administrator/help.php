<?php

    /**
     * Dropinn help Class
     *
     * Permits admin to handle the static helps of the site
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Manage Static help 
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @created		December 22 2008
     * @link		http://www.cogzidel.com

     */
    class Help extends CI_Controller
    {

        /**
         * Constructor 
         *
         * Loads language files and models needed for this controller
         */
        public function help()
        {
            parent::__construct();

            //load validation library
            $this->load->library('form_validation');

            //Load Form Helper
            $this->load->helper('form');
            $this->load->helper('text');
            //load model
            $this->load->model('help_model');
            $this->dx_auth->check_uri_permissions();
            $this->path = realpath(APPPATH);
        }

//Controller End 

        /**
         * Loads Faqs settings help.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function addhelp()
        {
            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
            //Set rules

            if ($this->input->post('addhelp'))
            {
                $this->form_validation->set_rules('question', 'help title', 'required|trim|xss_clean');
                $this->form_validation->set_rules('page_refer', 'page refer', 'required|trim|xss_clean'); //|callback_helpUrlCheck|callback_helpUrlValid');
                $this->form_validation->set_rules('status', 'active', 'required|trim|xss_clean');
                $this->form_validation->set_rules('description', 'help content', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {

                    //prepare insert data
                    $insertData = array();
                    $insertData['question'] = $this->input->post('question');
                    $insertData['description'] = $this->input->post('description');
                    $insertData['page_refer'] = $this->input->post('page_refer');
                    $insertData['status'] = $this->input->post('status');
                    $insertData['created'] = local_to_gmt();

                    //Add Group

                    $this->help_model->addhelp($insertData);

                    //Notification message
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Help added successfully')));
                    redirect_admin('help/viewhelp');
                }
            }
            //If - Form Submission End
            //Get Faq Categories
            $data['addHelps'] = $this->help_model->gethelps();

            $data['message_element'] = "administrator/help/addhelp";
            $this->load->view('administrator/admin_template', $data);
        }

//Function addHelp End 

        /**
         * Loads Manage Static helps View.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function viewhelp()
        {
            //Get Groups
            $data['helps'] = $this->help_model->gethelps();

            //Load View	
            $data['message_element'] = "administrator/help/viewhelp";
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
        public function deletehelp()
        {
            $id = $this->uri->segment(4, 0);

            if ($id == 0)
            {
                $gethelps = $this->help_model->gethelps();
                $helplist = $this->input->post('helplist');
                if (!empty($helplist))
                {
                    foreach ($helplist as $res)
                    {
                        $condition = array('help.id' => $res);
                        $this->help_model->deletehelp(NULL, $condition);
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select help')));
                    redirect_admin('help/viewhelp');
                }
            }
            else
            {
                $condition = array('help.id' => $id);
                $this->help_model->deletehelp(NULL, $condition);
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Help deleted successfully')));
            redirect_admin('help/viewhelp');
        }

        //Function end

        /**
         * Loads Manage Static helps View.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function edithelp()
        {
            //Get id of the category	
            $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post('edithelp'))
            {
                //Set rules
                $this->form_validation->set_rules('question', 'help title', 'required|trim|xss_clean');
                $this->form_validation->set_rules('description', 'help content', 'required|trim|xss_clean');
                $this->form_validation->set_rules('page_refer', 'page refer', 'required|trim|xss_clean'); //|callback_helpUrlCheck|callback_helpUrlValid');
                $this->form_validation->set_rules('status', 'active', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    //prepare update data
                    $updateData = array();
                    $updateData['question'] = $this->input->post('question');
                    $updateData['description'] = $this->input->post('description');
                    $updateData['page_refer'] = $this->input->post('page_refer');
                    $updateData['status'] = $this->input->post('status');
                    $updateData['modified_date'] = local_to_gmt();

                    //Edit Faq Category
                    $updateKey = array('help.id' => $this->uri->segment(4));

                    $this->help_model->updatehelp($updateKey, $updateData);

                    //Notification message
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Help updated successfully')));
                    redirect_admin('help/viewhelp');
                }
            } //If - Form Submission End
            //Set Condition To Fetch The Faq Category
            $condition = array('help.id' => $id);

            //Get Groups
            $data['helps'] = $this->help_model->gethelps($condition);
            //Load View	
            $data['message_element'] = "administrator/help/edithelp";
            $this->load->view('administrator/admin_template', $data);
        }

//End of edithelp
        //start of hidehelp	
        public function hidehelp()
        {
            $row_hide = $this->input->post('stat');
            $row_id = $this->input->get('id');
            if ($row_hide == 1)
            {
                $data['status'] = 1;
                $condition = array("id" => $row_id);
                $this->Common_model->updateTableData('list', NULL, $condition, $data);
                redirect('administrator/help/viewhelp');
                echo "hi";
                exit;
            }
            else
            {
                $data['status'] = 1;
                $condition = array("id" => $row_id);
                $this->Common_model->updateTableData('list', NULL, $condition, $data);
                redirect('administrator/help/viewhelp');
            }
            $this->load->view('template', $data);
            $data['title'] = get_meta_details('Manage_help', 'title');
            $data["meta_keyword"] = get_meta_details('Manage_Listings', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Manage_Listings', 'meta_description');

            $data['message_element'] = "administrator/help/viewhelp";
            $this->load->view('template', $data);
            redirect('administrator/help/viewhelp');
        }

        /**
          helpNameCheck

         * checks whether help name already exists or not.
         *
         * @access	private
         * @param	string name of category
         * @return	bool true or false
         */
        public function helpNameCheck()
        {
            //Condition to check

            if ($this->input->post('help_operation') !== false and $this->input->post('help_operation') == 'edit')
                $condition = array('help.question' => $this->input->post('question'), 'help.page_refer' => $this->input->post('page_refer'));
            else
                $condition = array('help.question' => $this->input->post('question'));

            //Check with table
            $resulthelpName = $this->help_model->gethelps($condition);

            if ($resulthelpName->num_rows() > 0)
            {
                $this->form_validation->set_message('helpNameCheck', $this->lang->line('help_unique'));
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }

//End of helpNameCheck function

        /**
         * checks whether help url already exists or not.
         *
         * @access	private
         * @param	string name of category
         * @return	bool true or false
         */
        public function helpUrlCheck()
        {
            //Condition to check
            if ($this->input->post('help_operation') !== false and $this->input->post('help_operation') == 'edit')
                $condition = array('help.page_refer' => $this->input->post('page_refer'));
            else
                $condition = array('help.page_refer' => $this->input->post('page_refer'));

            //Check with table
            $resulthelpName = $this->help_model->gethelps($condition);

            if ($resulthelpName->num_rows() > 0)
            {
                $this->form_validation->set_message('helpUrlCheck', $this->lang->line('url_unique'));
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }

//End of helpUrlValid function

        /**
         * checks whether the url is in correct format or not.
         *
         * @access	private
         * @param	string name of category
         * @return	bool true or false
         */
        public function helpUrlValid()
        {
            //Condition to check the url
            if ($this->input->post('help_operation') !== false and $this->input->post('help_operation') == 'add')
            {
                $str = $this->input->post('page_refer');
                $pattern = '/^([-a-z0-9_])+$/i';
                if (!preg_match($pattern, $str))
                {
                    $this->form_validation->set_message('helpUrlValid', $this->lang->line('page_refer_check'));
                    return false;
                }
                else
                {
                    return TRUE;
                }
            }
        }

//End of helpUrlValid function
    }

//End  help Class

/* End of file help.php */ 
/* Location: ./app/controllers/admin/help.php */