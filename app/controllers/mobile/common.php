<?php

    /**
     * DROPinn Common Controller Class
     *
     * helps to achieve common tasks related to the site for mobile app like android and iphone.
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Common
     * @author		Cogzidel Product Team
     * @version		Version 1.0
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Common extends CI_Controller
    {

        public function Common()
        {
            parent::__construct();

            $this->load->helper('url');

            $this->load->library('DX_Auth');

            $this->load->model('Users_model');
            $this->load->model('Gallery');

            $this->_table = 'users';
        }

        public function index()
        {
            
        }

        public function get_site_details()
        {
            $pre_logo = $this->db->get_where('settings', array('code' => 'SITE_LOGO'))->row()->string_value;

            $site_title = $this->dx_auth->get_site_title();

            $logo = base_url() . 'logo/' . $pre_logo;

            echo '[{"site_title":"' . $site_title . '","site_logo":"' . $logo . '"}]';
            exit;
        }

    }

?>