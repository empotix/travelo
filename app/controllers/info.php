<?php

    /**
     * DROPinn Info Controller Class
     *
     * helps to achieve common tasks related to the site like flash message formats,pagination variables.
     *
     * @package		Info
     * @subpackage	Controllers
     * @category	Info
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Info extends CI_Controller
    {

        public function Info()
        {
            parent::__construct();

            $this->load->helper('url');
            $this->load->helper('cookie');

            $this->load->model('Referrals_model');
            $this->facebook_lib->enable_debug(TRUE);
        }

        public function index()
        {
            $data['title'] = get_meta_details('Access_Deny', 'title');
            $data["meta_keyword"] = get_meta_details('Access_Deny', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Access_Deny', 'meta_description');

            $data['message_element'] = 'view_deny';
            $this->load->view('template', $data);
        }

        public function deny()
        {
            $data['title'] = get_meta_details('Access_Deny', 'title');
            $data["meta_keyword"] = get_meta_details('Access_Deny', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Access_Deny', 'meta_description');

            $data['message_element'] = 'view_deny';
            $this->load->view('template', $data);
        }

        public function how_it_works()
        {

            $data['display_type'] = $this->Common_model->getTableData('settings', array('code' => 'HOW_IT_WORKS'))->row()->int_value;
            $data['media'] = $this->Common_model->getTableData('settings', array('code' => 'HOW_IT_WORKS'))->row()->string_value;
            $data['embed_code'] = $this->Common_model->getTableData('settings', array('code' => 'HOW_IT_WORKS'))->row()->text_value;

            $data['title'] = 'How ' . $this->dx_auth->get_site_title() . ' Works';
            $data["meta_keyword"] = "";
            $data["meta_description"] = "";
            $data['message_element'] = 'view_howit';
            $this->load->view('template', $data);
        }

    }

    /* End of file info.php */
    /* Location: ./app/controllers/info.php */
?>
