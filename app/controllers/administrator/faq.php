<?php

    /**
     * Dropinn system page Class
     *
     * Permits admin to handle the FAQ System of the site
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Skills 
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @created		December 22 2008
     * @link		http://www.cogzidel.com

     */
    class Faq extends CI_Controller
    {

        //Global variable  
        public $outputData;  //Holds the output data for each view

        /**
         * Constructor 
         *
         * Loads language files and models needed for this controller
         */

        function Faq()
        {

            parent::__construct();

            $this->load->library('Table');
            $this->load->library('Pagination');
            $this->load->library('DX_Auth');

            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->helper('file');
            //load validation library
            $this->load->library('form_validation');

            //load model
            $this->load->model('faq_model');

            $this->dx_auth->check_uri_permissions();
            $this->path = realpath(APPPATH);
        }

//Controller End 
        // --------------------------------------------------------------------

        /**
         * Loads Faqs settings page.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        function index()
        {
            $data["faqs"] = $this->faq_model->getFaqs();

            $data['message_element'] = 'administrator/faq/viewFaq';
            $this->load->view('administrator/admin_template', $data);
        }

        function addFaq()
        {

            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post('addFaq'))
            {
                //Set rules
                $this->form_validation->set_rules('question', 'Question', 'required|trim|xss_clean');

                $this->form_validation->set_rules('faq_content', 'Answer', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    //prepare insert data
                    $insertData = array();
                    $insertData['question'] = $this->input->post('question');
                    $insertData['faq_content'] = $this->input->post('faq_content');
                    $insertData['created'] = local_to_gmt();

                    //Add Groups
                    $this->faq_model->addfaq($insertData);

                    //Notification message
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('FAQ added successfully')));
                    redirect_admin('faq/viewFaqs');
                }
            } //If - Form Submission End
            //Load View
            $data['message_element'] = 'administrator/faq/addFaq';
            $this->load->view('administrator/admin_template', $data);
        }

//Function addPage End 

        function viewFaqs()
        {
            //Load View
            $data["faqs"] = $this->faq_model->getFaqs();

            $data['message_element'] = 'administrator/faq/viewFaq';
            $this->load->view('administrator/admin_template', $data);
        }

        /**
         * delete Faq.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        function deletePage()
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
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select Page')));
                    redirect_admin('page/viewPages');
                }
            }
            else
            {
                $condition = array('page.id' => $id);
                $this->page_model->deletePage(NULL, $condition);
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Deleted Successfully')));
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
        function editFaq($id)
        {

            //Get id of the category	
            $id = is_numeric($id) ? $id : 0;

            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post('editfaq'))
            {
                //Set rules
                $this->form_validation->set_rules('question', 'Question', 'required|trim|xss_clean');

                $this->form_validation->set_rules('faq_content', 'Answer', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    //prepare update data
                    $updateData = array();

                    $updateData['question'] = $this->input->post('question');

                    $updateData['faq_content'] = $this->input->post('faq_content');

                    //Edit Faq Category
                    $updateKey = array('id' => $id);

                    $this->faq_model->updateFaq($updateKey, $updateData);

                    //Notification message

                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Updated Successfully')));

                    redirect_admin('faq/viewFaqs');
                }
            } //If - Form Submission End
            //Set Condition To Fetch The Faq Category
            $condition = array('id' => $id);

            //Get Groups
            $data['faqs'] = $this->faq_model->getfaqs($condition);

            //Load View
            $data['message_element'] = 'administrator/faq/editFaq';
            $this->load->view('administrator/admin_template', $data);
        }

//End of editPage

        public function cge_status()
        {
            $table_name = $this->input->post("change_status");

            $current_id = $this->input->post("id");

            $status = $this->db->get_where('faq', "id =$current_id")->result();

            $current_status = $status[0]->status;

            $update_data = array();

            if ($current_status)
                $update_data["status"] = 0;
            else
                $update_data["status"] = 1;

            $data = array('status' => $update_data["status"]);
            $this->db->where('id', $current_id);
            $this->db->update('faq', $data);

            echo $update_data["status"];

            exit();
        }

        function delete_record()
        {
            $table_name = $this->input->post("delete_record");

            $current_id = $this->input->post("id");

            $result = $this->db->delete($table_name, array('id' => $current_id));

            if ($result)
                echo $current_id;
            else
                echo 0;
            exit;
        }

    }

//End  Page Class

/* End of file Page.php */ 
/* Location: ./app/controllers/admin/Page.php */
