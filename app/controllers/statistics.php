<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Statistics extends CI_Controller
    {

        public function Statistics()
        {
            parent::__construct();

            $this->load->helper('url');
            $this->load->helper('cookie');

            $this->load->model('Users_model');
            $this->load->model('Trips_model');
            $this->load->model('Email_model');
            $this->load->model('Message_model');
            $this->facebook_lib->enable_debug(TRUE);
        }

        public function view_statistics_graph()
        {
            $room_id = $this->uri->segment(3);
            $conditions = array("list_id" => $room_id);
            $data['results'] = $this->Common_model->get_statistics($conditions);
            $check = $this->db->where('id', $room_id)->get('list');
            if ($check->num_rows() == 0)
            {
                redirect('info');
            }
            $data['title'] = get_meta_details('Statistics', 'title');
            $data["meta_keyword"] = get_meta_details('Statistics', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Statistics', 'meta_description');
            $data['message_element'] = 'statistics/view_statistics';
            $this->load->view('template', $data);
        }

    }

?>
