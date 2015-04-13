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

    class Managemetas extends CI_Controller
    {

        public function Managemetas()
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

            $data['meta'] = $this->Common_model->getTableData('metas');
            //var_dump($data);exit;
            $data['message_element'] = "administrator/managemetas/view_managemetas";

            $this->load->view('administrator/admin_template', $data);
        }

        public function editmetas($param = '')
        {
            if ($this->input->post())
            {

                $this->form_validation->set_rules('url', 'URL', 'required|trim|xss_clean');
                $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');
                $this->form_validation->set_rules('description', 'Meta Description', 'required|trim|xss_clean');
                $this->form_validation->set_rules('keyword', 'Meta Keyword', 'required|trim|xss_clean');
                if ($this->form_validation->run())
                {

                    $id = $this->uri->segment(4, 0);

                    $updateData = array();
                    $updateData['url'] = $this->input->post('url');
                    $updateData['name'] = $this->input->post('name');
                    $updateData['title'] = $this->input->post('title');
                    $updateData['meta_description'] = $this->input->post('description');
                    $updateData['meta_keyword'] = $this->input->post('keyword');


                    $condition = array('id' => $id);
                    $this->Common_model->updateTableData('metas', $id, $condition, $updateData);

                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Location updated successfully.')));
                    redirect_admin('managemetas');
                }
            }
            else
            {
                echo 'error';
            }
            $conditions = array("metas.id" => $param);
            $data['metas'] = $this->Common_model->getTableData('metas', $conditions)->row();
            $conditions = array("metas.id" => $param);

            $data['message_element'] = "administrator/managemetas/editmanagemetas";

            $this->load->view('administrator/admin_template', $data);
        }

    }

?>