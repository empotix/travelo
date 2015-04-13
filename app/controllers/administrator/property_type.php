<?php

    /**
     * DROPinn Admin List Controller Class
     *
     * helps to achieve common tasks related to the site like flash message formats,pagination variables.
     *
     * @package		DROPinn
     * @subpackage	Controllers
     * @category	Admin property type
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com

     */
    class Property_type extends CI_Controller
    {

        function Property_type()
        {
            parent::__construct();

            $this->load->library('Table');
            $this->load->library('Pagination');
            $this->load->library('form_validation');

            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->helper('file');

            $this->load->model('Users_model');
            $this->load->model('Rooms_model');

            $this->path = realpath(APPPATH . '../images');

            // Protect entire controller so only admin, 
            // and users that have granted role in permissions table can access it.
            $this->dx_auth->check_uri_permissions();
        }

        function index()
        {
            $query = $this->db->get('list');

            // Get offset and limit for page viewing
            $start = (int) $this->uri->segment(4, 0);

            // Number of record showing per page
            $row_count = 10;

            if ($start > 0)
                $offset = ($start - 1) * $row_count;
            else
                $offset = $start * $row_count;


            // Get all users
            $data['users'] = $this->db->get('list', $row_count, $offset)->result();

            // Pagination config
            $p_config['base_url'] = admin_url('property_type/index');
            $p_config['uri_segment'] = 4;
            $p_config['num_links'] = 5;
            $p_config['total_rows'] = $query->num_rows();
            $p_config['per_page'] = $row_count;

            // Init pagination
            $this->pagination->initialize($p_config);
            // Create pagination links
            $data['pagination'] = $this->pagination->create_links2();


            $data['message_element'] = "administrator/view_lists";
            $this->load->view('administrator/admin_template', $data);
        }

        public function view_all_property()
        {
            //Get Groups
            $this->load->model('property_model');
            $data['property'] = $this->property_model->getproperty();

            //$data['area']   =   $this->place_model->getplace1();
            //Load View	
            $data['message_element'] = "administrator/property_type/view_property";
            $this->load->view('administrator/admin_template', $data);
        }

        function view_property()
        {


            $data['message_element'] = "administrator/view_add_property";
            $this->load->view('administrator/admin_template', $data);
        }

        public function editproperty()
        {

            $this->load->model('property_model');


            //Get id of the category	
            $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post('submit'))
            {
                //Set rules
                $this->form_validation->set_rules('type', 'Type', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    //prepare update data
                    $updateData = array();
                    $updateData['type'] = $this->input->post('type');


                    //Edit Faq Category
                    $updateKey = array('property_type.id' => $this->uri->segment(4));

                    $this->property_model->updateproperty($updateKey, $updateData);

                    //Notification message
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('property type updated successfully')));
                    redirect_admin('property_type/view_all_property');
                }
            } //If - Form Submission End
            //Set Condition To Fetch The Faq Category
            $condition = array('property_type.id' => $id);

            //Get Groups
            $data['property'] = $this->property_model->getproperty($condition);

            //Load View	
            $data['message_element'] = "administrator/property_type/edit_property";
            $this->load->view('administrator/admin_template', $data);
        }

        public function delete_property()
        {
            $this->load->model('property_model');
            $id = $this->uri->segment(4, 0);

            if ($id == 0)
            {
                $getproperty = $this->property_model->getproperty();
                $propertylist = $this->input->post('propertylist');
                if (!empty($propertylist))
                {
                    foreach ($propertylist as $res)
                    {
                        $condition = array('property_type.id' => $res);
                        $this->property_model->deleteproperty(NULL, $condition);
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select Property type')));
                    redirect_admin('property_type/view_all_property');
                }
            }
            else
            {
                $condition = array('property_type.id' => $id);
                $this->property_model->deleteproperty(NULL, $condition);
                redirect_admin('property_type/view_all_Property');
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Property deleted successfully')));
            redirect_admin('property_type/view_all_Property');
        }

        function addproperty()
        {
            $prop = $this->input->post('addproperty');
            $property = trim($prop);
            if (empty($property))
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Sorry, You have to select atleast one!')));
                redirect_admin('property_type');
            }
            else
            {
                $nul = "NULL";
                $data = array(
                    'id' => NULL,
                    'type' => $this->input->post('addproperty')
                );
                $this->Common_model->insertData('property_type', $data);

                echo "<p>Additional Property type added successfully</p>";
            }
        }

        function addproperties()
        {
            $prop = $this->input->post('addproperty');
            $property = trim($prop);
            if (empty($property))
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Sorry, You have to fill all fields!')));
                redirect_admin('property_type/view_property');
            }
            else
            {
                $nul = "NULL";
                $data = array(
                    'id' => NULL,
                    'type' => $this->input->post('addproperty')
                );
                $this->Common_model->insertData('property_type', $data);

                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Property type added successfully!')));
                redirect_admin('property_type/view_property');
            }
        }

    }

?>
