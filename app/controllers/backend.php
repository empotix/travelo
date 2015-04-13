<?php

    /**
     * DROPinn Backend Controller Class
     *
     * It helps to show the user account details
     *
     * @package     Dropinn
     * @subpackage  Controllers
     * @category    Backend
     * @author      Cogzidel Product Team
     * @version     Version 1.6
     * @link        http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Backend extends CI_Controller
    {

        public function Backend()
        {
            parent::__construct();
            $this->load->library("Table");
            $this->load->library("Pagination");
            $this->load->library("DX_Auth");
            $this->load->helper("form");
            $this->load->helper("url");
            $this->load->helper("file");
            $this->path = realpath(APPPATH . "../images");
            $this->load->model("Users_model");
            $this->dx_auth->check_uri_permissions();
        }

        public function index()
        {
            $today_user = array();
            $registered_user_today = "";
            $created_date = "";
            foreach ($user_date as $user)
            {
                if ($created_date == date("m-d-Y", $cur_date))
                {
                    $today_user[] = $user->id;
                }
            }
            $created_datelist = "";
            $today_userlist = array();
            foreach ($user_list as $list)
            {
                if ($created_datelist == date("m-d-Y", $cur_date))
                {
                    $today_userlist[] = $list->user_id;
                }
            }
            $data['today_userlist'] = count($today_userlist);
            $data['todayuser'] = count($today_user);
            $today_reservation = array();
            $created_datelist1 = "";
            foreach ($user_reservation as $reservation)
            {
                $reservation_list = $today_user;
                if ($reservation_list == date("m-d-Y", $cur_date))
                {
                    $today_reservation[] = $reservation->list_id;
                }
            }
            $data['today_reservation'] = count($today_reservation);
            $data['message_element'] = "administrator/view_home";
            $this->load->view("administrator/admin_template", $data);
        }

    }

?>