<?php

    /**
     * DROPinn Users Controller Class
     *
     * It helps shows the home page with slider.
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

    class Wishlist extends CI_Controller
    {

        //Constructor
        public function Wishlist()
        {
            parent::__construct();

            $this->load->database();

            $this->config_data->db_config_fetch();

            //Manage site Status 
            if ($this->config->item('site_status') == 1)
            {
                redirect('maintenance');
            }

            $this->load->helper('url');
            $this->load->helper('form');
            $this->load->helper('cookie');
            $this->load->library('twconnect');

            $this->load->library('Form_validation');

            $this->load->model('Referrals_model');
            $this->load->model('Message_model');
            $this->load->model('Rooms_model');
            $this->load->model('Trips_model');

            $this->facebook_lib->enable_debug(TRUE);
        }

        public function index()
        {
            $data['title'] = get_meta_details('wish_list', 'title');
            $data["meta_keyword"] = $this->Common_model->getTableData('settings', array('code' => 'META_KEYWORD'))->row()->string_value;
            $data["meta_description"] = $this->Common_model->getTableData('settings', array('code' => 'META_DESCRIPTION'))->row()->string_value;
            $data['message_element'] = "wishlist/view_wishlist";
            $this->load->view('template', $data);
        }

        public function users_wishlist()
        {
            $data['title'] = get_meta_details('wish_list', 'title');
            $data["meta_keyword"] = $this->Common_model->getTableData('settings', array('code' => 'META_KEYWORD'))->row()->string_value;
            $data["meta_description"] = $this->Common_model->getTableData('settings', array('code' => 'META_DESCRIPTION'))->row()->string_value;
            $data['message_element'] = "wishlist/view_users_wishlist";
            $this->load->view('template', $data);
        }

    }
    