<?php

    /**
     * DROPinn Admin Top Location Controller Class
     *
     * helps to achieve common tasks related to the site like flash message formats,pagination variables.
     *
     * @package		DROPinn
     * @subpackage	Controllers
     * @category	Admin Top Locations
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Toplocation extends CI_Controller
    {

        public function Toplocation()
        {
            parent::__construct();

            $this->load->helper('form');
            $this->load->helper('url');

            $this->load->library('Table');
            $this->load->library('Pagination');
            $this->load->library('form_validation');

            // Protect entire controller so only admin, 
            // and users that have granted role in permissions table can access it.
            $this->dx_auth->check_uri_permissions();
        }

        public function index()
        {
            //$data['message_element'] = "administrator/toplocation/view_location";

            $data['location'] = $this->Common_model->getToplocation();
            //var_dump($data);exit;
            $data['message_element'] = "administrator/toplocation/view_location";

            //$this->load->view('administrator/toplocation/view_location', $data);
            $this->load->view('administrator/admin_template', $data);
        }

        public function editlocation($param = '')
        {

            if ($this->input->post('Update'))
            {
                $id = $this->input->post('id');
                $updateData = array();
                $updateData['name'] = $this->input->post('city_name');
                $updateData['category_id'] = $this->input->post('category');

                $condition = array('id' => $id);
                $this->Common_model->updateTableData('toplocations', $id, $condition, $updateData);

                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Location updated successfully.')));
                redirect_admin('toplocation');
            }

            $data['categories'] = $this->Common_model->getTableData('toplocation_categories');

            $conditions = array("toplocations.id" => $param);
            $data['result'] = $this->Common_model->getToplocation($conditions)->row();

            $data['message_element'] = "administrator/toplocation/editlocation";

            $this->load->view('administrator/admin_template', $data);
        }

        public function addlocation($param = '')
        {
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post())
            {
                $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('location', 'Location', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    $updateData = array();
                    $insertData['name'] = $this->input->post('name');
                    $insertData['category_id'] = 1;
                    $insertData['search_code'] = $this->input->post('location');

                    $this->Common_model->inserTableData('toplocations', $insertData);

                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Location updated successfully.')));
                    redirect_admin('toplocation');
                }
            }
            $data['categories'] = $this->Common_model->getTableData('toplocation_categories');

            $conditions = array("toplocations.id" => $param);
            $data['result'] = $this->Common_model->getToplocation($conditions)->row();

            $data['message_element'] = "administrator/toplocation/add_location";

            $this->load->view('administrator/admin_template', $data);
        }

    }

?>