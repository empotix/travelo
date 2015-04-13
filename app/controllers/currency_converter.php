<?php

    /**
     * DROPinn Trips Controller Class
     *
     * Helps to control the trips functionality
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Trips
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com
     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Currency_converter extends CI_Controller
    {

        public function Currency_converter()
        {
            parent::__construct();

            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->helper('cookie');

            $this->load->library('Form_validation');
        }

        function index()
        {
            $url = file_get_contents("http://openexchangerates.org/api/latest.json?app_id=8d43d9c41911416bb8d838e179812889");
            $json_a = json_decode($url, true);
            $curr = $this->db->get('currency_converter');
            foreach ($curr->result() as $row)
            {
                $this->db->where('currency_code', $row->currency_code)->update('currency_converter', array('currency_value' => $json_a['rates']["$row->currency_code"]));
            }
            redirect('');
        }

    }

?>