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
    class Page extends CI_Controller
    {

        /**
         * Constructor 
         *
         * Loads language files and models needed for this controller
         */
        public function Page()
        {
            parent::__construct();

            //load validation library
            $this->load->library('form_validation');

            //Load Form Helper
            $this->load->helper('form');

            //load model
            $this->load->model('page_model');
            $this->dx_auth->check_uri_permissions();
            $this->path = realpath(APPPATH);
        }

//Controller End 

        /**
         * Loads Faqs settings page.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function addPage()
        {
            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post('addPage'))
            {
                //Set rules
                $this->form_validation->set_rules('page_title', 'Page Title', 'required|trim|xss_clean');
                $this->form_validation->set_rules('page_url', 'Page URL', 'required|trim|xss_clean|callback_pageUrlCheck|callback_pageUrlValid');
                $this->form_validation->set_rules('page_name', 'Page Name', 'required|trim|xss_clean|callback_pageNameCheck');
                $this->form_validation->set_rules('page_content', 'Page Content', 'required|trim|xss_clean');
                $this->form_validation->set_rules('is_footer', 'Is Footer', 'trim|xss_clean');
                $this->form_validation->set_rules('is_under', 'Is Under', 'trim|xss_clean');

                $page_url = str_replace('/', '', $this->input->post('page_url'));

                if ($this->form_validation->run())
                {
                    //prepare insert data
                    $insertData = array();
                    $insertData['page_name'] = $this->input->post('page_name');
                    $insertData['page_title'] = $this->input->post('page_title');
                    $insertData['page_url'] = $page_url;
                    $insertData['is_footer'] = $this->input->post('is_footer');
                    $insertData['is_under'] = $this->input->post('is_under');
                    $insertData['page_content'] = $this->input->post('page_content');
                    $insertData['created'] = time();
                    //Add Groups
                    $this->page_model->addpage($insertData);

                    //Notification message
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Page added successfully')));
                    redirect_admin('page/viewPages');
                }
            } //If - Form Submission End
            //Get Faq Categories
            $data['addPages'] = $this->page_model->getPages();

            $data['message_element'] = "administrator/page/addPage";
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
        public function viewPages()
        {
            //Get Groups
            $data['pages'] = $this->page_model->getPages();

            //Load View	
            $data['message_element'] = "administrator/page/viewPages";
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
        public function deletePage()
        {
            $id = $this->uri->segment(4, 0);

            if ($id == 0)
            {
                $getpages = $this->page_model->getPages();
                $pagelist = $this->input->post('pagelist');
                if (!empty($pagelist))
                {
                    foreach ($pagelist as $res)
                    {
                        $condition = array('page.id' => $res);
                        $this->page_model->deletePage(NULL, $condition);
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select page')));
                    redirect_admin('page/viewPages');
                }
            }
            else
            {
                $condition = array('page.id' => $id);
                $this->page_model->deletePage(NULL, $condition);
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Page deleted successfully')));
            redirect_admin('page/viewPages');
        }

        //Function end

        /**
         * Loads Manage Static Pages View.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function editPage()
        {
            //Get id of the category	
            $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post('editPage'))
            {
                //Set rules
                $this->form_validation->set_rules('page_title', 'Page Title', 'required|trim|xss_clean');
                $this->form_validation->set_rules('page_content', 'Page Content', 'required|trim|xss_clean');
                $this->form_validation->set_rules('page_name', 'Page Name', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    //prepare update data
                    $updateData = array();
                    $updateData['page_title'] = $this->input->post('page_title');
                    $updateData['page_name'] = $this->input->post('page_name');
                    $updateData['is_footer'] = $this->input->post('is_footer');
                    $updateData['is_under'] = $this->input->post('is_under');
                    $updateData['page_content'] = $this->input->post('page_content');

                    //Edit Faq Category
                    $updateKey = array('page.id' => $this->uri->segment(4));

                    $this->page_model->updatePage($updateKey, $updateData);

                    //Notification message
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Page updated successfully')));
                    redirect_admin('page/viewPages');
                }
            } //If - Form Submission End
            //Set Condition To Fetch The Faq Category
            $condition = array('page.id' => $id);

            //Get Groups
            $data['pages'] = $this->page_model->getPages($condition);
            if ($data['pages']->num_rows() == 0)
            {
                redirect('info');
            }
            //Load View	
            $data['message_element'] = "administrator/page/editPage";
            $this->load->view('administrator/admin_template', $data);
        }

//End of editPage

        /**
          pageNameCheck

         * checks whether page name already exists or not.
         *
         * @access	private
         * @param	string name of category
         * @return	bool true or false
         */
        public function pageNameCheck()
        {
            //Condition to check

            if ($this->input->post('page_operation') !== false and $this->input->post('page_operation') == 'edit')
                $condition = array('page.page_name' => $this->input->post('page_name'), 'page.page_url' => $this->input->post('page_url'));
            else
                $condition = array('page.page_name' => $this->input->post('page_name'));

            //Check with table
            $resultPageName = $this->page_model->getPages($condition);

            if ($resultPageName->num_rows() > 0)
            {
                $this->form_validation->set_message('pageNameCheck', 'This page name is already registered.');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }

//End of pageNameCheck function

        /**
         * checks whether page url already exists or not.
         *
         * @access	private
         * @param	string name of category
         * @return	bool true or false
         */
        public function pageUrlCheck()
        {
            //Condition to check
            if ($this->input->post('page_operation') !== false and $this->input->post('page_operation') == 'edit')
                $condition = array('page.page_url' => str_replace('/', '', $this->input->post('page_url')));
            else
                $condition = array('page.page_url' => str_replace('/', '', $this->input->post('page_url')));

            //Check with table
            $resultPageName = $this->page_model->getPages($condition);

            if ($resultPageName->num_rows() > 0)
            {
                $this->form_validation->set_message('pageUrlCheck', 'This page URL is already registered.');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }

//End of pageUrlValid function

        /**
         * checks whether the url is in correct format or not.
         *
         * @access	private
         * @param	string name of category
         * @return	bool true or false
         */
        public function pageUrlValid()
        {
            //Condition to check the url
            if ($this->input->post('page_operation') !== false and $this->input->post('page_operation') == 'add')
            {
                $str = str_replace('/', '', $this->input->post('page_url'));
                $pattern = '/^([-a-z0-9_])+$/i';
                if (!preg_match($pattern, $str))
                {
                    $this->form_validation->set_message('pageUrlValid', 'This URL is invalid.');
                    return FALSE;
                }
                else
                {
                    return TRUE;
                }
            }
        }

//End of pageUrlValid function
    }

//End  Page Class

/* End of file Page.php */ 
/* Location: ./app/controllers/admin/Page.php */